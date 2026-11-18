<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Models\ActivityLog;
use App\Models\Field;
use App\Models\Reservation;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReservationController extends Controller
{
    /**
     * Display a listing of reservations (for admin)
     */
    public function index(): View
    {
        $reservations = Reservation::with(['user', 'field'])->paginate(15);
        return view('reservations.index', compact('reservations'));
    }

    /**
     * Show available fields for customer
     */
    public function create(): View
    {
        $fields = Field::where('status', 'available')->get();
        return view('reservations.create', compact('fields'));
    }

    /**
     * Return schedule slots for interactive field booking UI.
     */
    public function schedule(Request $request): JsonResponse
    {
        $field = Field::findOrFail($request->field_id);
        $date = Carbon::parse($request->date);

        $slots = [];
        $startHour = 8;
        $endHour = 23;

        for ($hour = $startHour; $hour < $endHour; $hour++) {
            $slotStart = Carbon::parse($date->toDateString())->setTime($hour, 0, 0);
            $slotEnd = $slotStart->copy()->addHour();

            $status = 'available';

            $overlappingReservations = Reservation::where('field_id', $field->id)
                ->whereDate('reservation_date', $date->toDateString())
                ->whereIn('status', ['pending', 'confirmed'])
                ->where(function ($query) use ($slotStart, $slotEnd) {
                    $query->where('start_time', '<', $slotEnd->format('H:i:s'))
                        ->where('end_time', '>', $slotStart->format('H:i:s'));
                })
                ->exists();

            if ($overlappingReservations) {
                $status = 'booked';
            }

            if ($field->status !== 'available') {
                $status = 'maintenance';
            }

            $slots[] = [
                'start' => $slotStart->format('H:i'),
                'end' => $slotEnd->format('H:i'),
                'status' => $status,
            ];
        }

        return response()->json([
            'slots' => $slots,
            'field' => $field->name,
            'date' => $date->toDateString(),
        ]);
    }

    /**
     * Store a new reservation with double booking validation
     */
    public function store(StoreReservationRequest $request)
    {
        $validated = $request->validated();
        $userId = Auth::id();

        if (! $userId) {
            return back()->withErrors(['auth' => 'Silakan login terlebih dahulu.'])->withInput();
        }

        $startTime = Carbon::createFromFormat('H:i', $validated['start_time']);
        $endTime = Carbon::createFromFormat('H:i', $validated['end_time']);

        if ($endTime->lessThanOrEqualTo($startTime)) {
            return back()->withErrors(['time_range' => 'Jam selesai harus lebih besar dari jam mulai.'])->withInput();
        }

        $field = Field::find($validated['field_id']);
        if (! $field) {
            return back()->withErrors(['field_id' => 'Lapangan yang dipilih tidak tersedia.'])->withInput();
        }

        $conflictingReservation = Reservation::where('field_id', $validated['field_id'])
            ->whereDate('reservation_date', $validated['reservation_date'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($q) use ($startTime, $endTime) {
                $q->whereBetween('start_time', [$startTime->format('H:i:s'), $endTime->format('H:i:s')])
                    ->orWhereBetween('end_time', [$startTime->format('H:i:s'), $endTime->format('H:i:s')])
                    ->orWhere(function ($q2) use ($startTime, $endTime) {
                        $q2->where('start_time', '<=', $startTime->format('H:i:s'))
                            ->where('end_time', '>=', $endTime->format('H:i:s'));
                    });
            })
            ->first();

        if ($conflictingReservation) {
            return back()->withErrors([
                'time_conflict' => 'Waktu reservasi bentrok dengan reservasi lain. Silakan pilih waktu lain.',
            ])->withInput();
        }

        $validated['user_id'] = $userId;
        $validated['start_time'] = $startTime->format('H:i:s');
        $validated['end_time'] = $endTime->format('H:i:s');

        $reservation = new Reservation($validated);
        $reservation->duration_hours = $reservation->calculateDuration();
        $reservation->total_price = $reservation->calculateTotalPrice();
        $reservation->save();

        $driver = DB::connection()->getDriverName();
        $paymentMethod = $driver === 'sqlite' ? 'transfer' : 'QRIS';

        $transaction = Transaction::where('reservation_id', $reservation->id)->first();
        if (! $transaction) {
            $transaction = Transaction::create([
                'user_id' => $userId,
                'reservation_id' => $reservation->id,
                'amount' => $reservation->total_price,
                'payment_method' => $paymentMethod,
                'status' => 'pending',
                'description' => 'Pembayaran reservasi untuk ' . ($field->name ?? 'lapangan'),
            ]);
        }

        $user = Auth::user();

        ActivityLog::log(
            $user,
            'create',
            'Reservation',
            $reservation->id,
            'Created reservation for field: ' . $field->name
        );

        return redirect()->route('reservations.show', $reservation)->with([
            'success' => 'Reservasi berhasil dibuat! Silakan lanjutkan ke pembayaran jika siap.',
            'show_payment_modal' => $transaction->id,
        ]);
    }

    /**
     * Show customer reservations
     */
    public function myReservations(): View
    {
        $user = Auth::user();

        if (! $user instanceof \App\Models\User) {
            abort(403, 'Unauthorized');
        }

        $reservations = $user->reservations()->with('field')->paginate(10);
        return view('reservations.my-reservations', compact('reservations'));
    }

    /**
     * Show reservation details
     */
    public function show(Reservation $reservation): View
    {
        if (! Auth::check()) {
            abort(403, 'Unauthorized');
        }

        $user = Auth::user();

        if ($user->role !== 'admin' && Auth::id() !== $reservation->user_id) {
            abort(403, 'Unauthorized');
        }

        $reservation->load(['field', 'user', 'transaction']);

        return view('reservations.show', compact('reservation'));
    }

    /**
     * Create a pending transaction for a reservation (AJAX)
     */
    public function createTransaction(Request $request, Reservation $reservation)
    {
        $user = Auth::user();

        if (! $user instanceof \App\Models\User) {
            return response()->json(['ok' => false, 'message' => 'Unauthorized'], 403);
        }

        // allow owner or admin
        if ($user->role !== 'admin' && $user->id !== $reservation->user_id) {
            return response()->json(['ok' => false, 'message' => 'Unauthorized'], 403);
        }

        if ($reservation->transaction) {
            return response()->json(['ok' => true, 'transaction_id' => $reservation->transaction->id]);
        }

        $driver = DB::connection()->getDriverName();
        $paymentMethod = $driver === 'sqlite' ? 'transfer' : 'QRIS';

        // Support deposit (DP) or full payment
        $type = $request->input('type', 'full');
        $depositAmount = null;
        $amount = $reservation->total_price;

        if ($type === 'deposit') {
            $depositAmount = $request->input('deposit_amount');
            // basic validation: numeric and positive and less than total
            if (! is_numeric($depositAmount) || $depositAmount <= 0 || $depositAmount > $reservation->total_price) {
                return response()->json(['ok' => false, 'message' => 'Nilai DP tidak valid'], 422);
            }
            $amount = (float) $depositAmount;
        }

        $transaction = Transaction::create([
            'user_id' => $reservation->user_id,
            'reservation_id' => $reservation->id,
            'amount' => $amount,
            'payment_method' => $paymentMethod,
            'status' => 'pending',
            'is_deposit' => $type === 'deposit',
            'deposit_amount' => $depositAmount,
            'description' => 'Pembayaran reservasi untuk ' . ($reservation->field->name ?? 'lapangan'),
        ]);

        return response()->json(['ok' => true, 'transaction_id' => $transaction->id]);
    }

    /**
     * Update reservation status (admin only)
     */
    public function updateStatus(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,completed,cancelled'],
        ]);

        $oldStatus = $reservation->status;
        $reservation->update($validated);

        $user = Auth::user();

        ActivityLog::log(
            $user,
            'update',
            'Reservation',
            $reservation->id,
            "Changed status from {$oldStatus} to {$validated['status']}"
        );

        return back()->with('success', 'Status reservasi berhasil diubah');
    }

    /**
     * Cancel reservation (customer or admin)
     */
    public function cancel(Request $request, Reservation $reservation)
    {
        $user = Auth::user();

        if (! $user instanceof \App\Models\User) {
            abort(403, 'Unauthorized');
        }

        if (Auth::id() !== $reservation->user_id && $user->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'cancellation_reason' => ['required', 'string', 'max:1000'],
        ]);

        $reason = $validated['cancellation_reason'];

        $reservation->status = 'cancelled';
        $reservation->cancellation_reason = $reason;
        $reservation->cancelled_at = now();
        $reservation->save();

        // If there is an associated transaction that is still pending, cancel it as well
        if ($reservation->transaction && ! in_array($reservation->transaction->status, ['paid', 'completed'])) {
            $reservation->transaction->status = 'cancelled';
            $reservation->transaction->payment_date = null;
            $reservation->transaction->save();
        }

        ActivityLog::log(
            $user,
            'update',
            'Reservation',
            $reservation->id,
            'Cancelled reservation. Reason: ' . substr($reason, 0, 200)
        );

        return back()->with('success', 'Reservasi berhasil dibatalkan');
    }

    /**
     * Delete reservation (admin only)
     */
    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        $user = Auth::user();

        ActivityLog::log(
            $user,
            'delete',
            'Reservation',
            $reservation->id,
            'Deleted reservation'
        );

        return redirect()->route('admin.reservations.index')->with('success', 'Reservasi berhasil dihapus');
    }

    /**
     * Upload payment proof for a reservation
     */
    public function uploadProof(Request $request, Reservation $reservation)
    {
        $user = Auth::user();
        if (! $user) {
            abort(403, 'Unauthorized');
        }

        if ($user->role !== 'admin' && $user->id !== $reservation->user_id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'payment_proof' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
        ]);

        $file = $request->file('payment_proof');
        $filename = 'proof_' . $reservation->id . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $targetDir = public_path('uploads/payment_proofs');
        if (! file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        $file->move($targetDir, $filename);
        $relativePath = 'uploads/payment_proofs/' . $filename;

        $transaction = $reservation->transaction;
        if (! $transaction) {
            $transaction = Transaction::create([
                'user_id' => $reservation->user_id,
                'reservation_id' => $reservation->id,
                'transaction_code' => 'TRX-' . strtoupper(uniqid()),
                'amount' => $reservation->total_price,
                'payment_method' => 'transfer',
                'status' => 'pending',
                'payment_proof' => $relativePath,
                'payment_proof_uploaded_at' => now(),
                'description' => 'Pembayaran reservasi ' . ($reservation->field->name ?? 'lapangan'),
            ]);
        } else {
            $transaction->payment_proof = $relativePath;
            $transaction->payment_proof_uploaded_at = now();
            if ($transaction->status !== 'paid') {
                $transaction->status = 'pending';
            }
            $transaction->save();
        }

        ActivityLog::log(
            $user,
            'upload',
            'Transaction',
            $transaction->id,
            'Uploaded payment proof for reservation: ' . $reservation->code
        );

        return back()->with('success', 'Bukti pembayaran berhasil diunggah! Mohon tunggu verifikasi admin.');
    }

    /**
     * Print / view booking ticket
     */
    public function ticket(Reservation $reservation): View
    {
        $user = Auth::user();
        if (! $user) {
            abort(403, 'Unauthorized');
        }

        if ($user->role !== 'admin' && $user->id !== $reservation->user_id) {
            abort(403, 'Unauthorized');
        }

        $reservation->load(['field', 'user', 'transaction']);

        return view('reservations.ticket', compact('reservation'));
    }
}
