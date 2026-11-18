<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Field;
use App\Models\Reservation;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Show admin dashboard with today's revenue, bookings, and pending payment verifications
     */
    public function dashboard(): View
    {
        $user = auth()->user();
        if (! $user || ! $user->isAdmin()) {
            abort(403, 'Hanya admin yang dapat mengakses halaman ini');
        }

        $totalUsers = User::where('role', 'customer')->where('status', 'active')->count();
        $totalFields = Field::count();
        $totalReservations = Reservation::count();

        $paidTransactions = Transaction::whereIn('status', ['completed', 'paid']);

        // Revenue calculations
        $totalRevenue = (clone $paidTransactions)->sum('amount');
        $todayRevenue = (clone $paidTransactions)
            ->where(function ($query) {
                $query->whereDate('payment_date', Carbon::today())
                    ->orWhere(function ($innerQuery) {
                        $innerQuery->whereNull('payment_date')
                            ->whereDate('created_at', Carbon::today());
                    });
            })
            ->sum('amount');

        $monthlyRevenue = (clone $paidTransactions)
            ->where(function ($query) {
                $query->whereBetween('payment_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
                    ->orWhere(function ($innerQuery) {
                        $innerQuery->whereNull('payment_date')
                            ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
                    });
            })
            ->sum('amount');

        // Today's bookings metrics
        $todayBookingsCount = Reservation::whereDate('created_at', Carbon::today())->count();
        $todayPlayingCount = Reservation::whereDate('reservation_date', Carbon::today())
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        // Pending payment proofs awaiting verification
        $pendingVerifications = Transaction::whereNotNull('payment_proof')
            ->where('status', 'pending')
            ->with(['reservation.field', 'user'])
            ->latest('payment_proof_uploaded_at')
            ->get();

        // Recent reservations
        $recentReservations = Reservation::with(['user', 'field', 'transaction'])
            ->latest()
            ->take(8)
            ->get();

        // Available fields for quick walk-in manual booking modal
        $availableFields = Field::where('status', 'available')->get();

        // Get reservations for the last 7 days
        $reservationData = Reservation::where('created_at', '>=', Carbon::now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get revenue for the last 7 days
        $revenueData = (clone $paidTransactions)
            ->where(function ($query) {
                $query->where('payment_date', '>=', Carbon::now()->subDays(7))
                    ->orWhere(function ($innerQuery) {
                        $innerQuery->whereNull('payment_date')
                            ->where('created_at', '>=', Carbon::now()->subDays(7));
                    });
            })
            ->selectRaw('DATE(COALESCE(payment_date, created_at)) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Field popularity
        $fieldPopularity = Reservation::selectRaw('field_id, fields.name, COUNT(*) as total')
            ->join('fields', 'reservations.field_id', '=', 'fields.id')
            ->groupBy('field_id', 'fields.name')
            ->orderBy('total', 'desc')
            ->get();

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalFields' => $totalFields,
            'totalReservations' => $totalReservations,
            'totalRevenue' => $totalRevenue,
            'todayRevenue' => $todayRevenue,
            'monthlyRevenue' => $monthlyRevenue,
            'todayBookingsCount' => $todayBookingsCount,
            'todayPlayingCount' => $todayPlayingCount,
            'pendingVerifications' => $pendingVerifications,
            'recentReservations' => $recentReservations,
            'availableFields' => $availableFields,
            'reservationData' => $reservationData,
            'revenueData' => $revenueData,
            'fieldPopularity' => $fieldPopularity,
        ]);
    }

    /**
     * 1-Click Verification (Terima / Tolak) for payment proof
     */
    public function verifyPayment(Request $request, Transaction $transaction)
    {
        $user = auth()->user();
        if (! $user || ! $user->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'action' => ['required', 'in:approve,reject'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $reservation = $transaction->reservation;

        if ($validated['action'] === 'approve') {
            $transaction->status = 'paid';
            $transaction->payment_date = now();
            $transaction->save();

            if ($reservation) {
                $reservation->status = 'confirmed';
                $reservation->save();
            }

            ActivityLog::log(
                $user,
                'approve',
                'Transaction',
                $transaction->id,
                'Approved payment proof for transaction ' . $transaction->transaction_code
            );

            return back()->with('success', 'Pembayaran berhasil DITERIMA! Status reservasi #' . ($reservation?->code ?? $transaction->transaction_code) . ' kini telah Terkonfirmasi.');
        } else {
            $transaction->status = 'cancelled';
            $transaction->save();

            if ($reservation) {
                $reservation->status = 'cancelled';
                $reservation->cancellation_reason = $validated['notes'] ?: 'Bukti pembayaran ditolak oleh admin.';
                $reservation->cancelled_at = now();
                $reservation->save();
            }

            ActivityLog::log(
                $user,
                'reject',
                'Transaction',
                $transaction->id,
                'Rejected payment proof for transaction ' . $transaction->transaction_code
            );

            return back()->with('success', 'Pembayaran berhasil DITOLAK. Reservasi telah dibatalkan.');
        }
    }

    /**
     * Show manual booking form for walk-in customers
     */
    public function manualBookingForm(): View
    {
        $user = auth()->user();
        if (! $user || ! $user->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $fields = Field::where('status', 'available')->get();
        return view('admin.manual-booking', compact('fields'));
    }

    /**
     * Store manual booking for walk-in customer
     */
    public function storeManualBooking(Request $request)
    {
        $admin = auth()->user();
        if (! $admin || ! $admin->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'field_id' => ['required', 'exists:fields,id'],
            'reservation_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required'],
            'end_time' => ['required'],
            'payment_status' => ['required', 'in:paid,pending'],
            'payment_method' => ['required', 'string'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $startTime = Carbon::createFromFormat('H:i', $validated['start_time']);
        $endTime = Carbon::createFromFormat('H:i', $validated['end_time']);

        if ($endTime->lessThanOrEqualTo($startTime)) {
            return back()->withErrors(['time_range' => 'Jam selesai harus lebih besar dari jam mulai.'])->withInput();
        }

        $field = Field::findOrFail($validated['field_id']);

        // Check for conflicting reservations
        $conflict = Reservation::where('field_id', $field->id)
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
            ->exists();

        if ($conflict) {
            return back()->withErrors(['time_conflict' => 'Slot waktu tersebut sudah dipesan. Silakan pilih jam lain.'])->withInput();
        }

        // Find or create customer account
        $cleanPhone = preg_replace('/[^0-9]/', '', $validated['customer_phone']);
        $customerUser = User::where('phone', $cleanPhone)->orWhere('phone', $validated['customer_phone'])->first();

        if (! $customerUser) {
            $customerUser = User::create([
                'name' => $validated['customer_name'],
                'email' => 'walkin_' . time() . '_' . rand(100, 999) . '@smsport.local',
                'password' => bcrypt('walkin' . rand(1000, 9999)),
                'phone' => $validated['customer_phone'],
                'role' => 'customer',
                'status' => 'active',
            ]);
        }

        // Calculate duration and price
        $durationHours = max(1, (int) round($startTime->diffInMinutes($endTime) / 60));
        $totalPrice = $durationHours * $field->price_per_hour;

        $bookingCode = 'WLK-' . date('ymd') . '-' . strtoupper(Str::random(4));

        $reservation = Reservation::create([
            'user_id' => $customerUser->id,
            'field_id' => $field->id,
            'reservation_date' => $validated['reservation_date'],
            'start_time' => $startTime->format('H:i:s'),
            'end_time' => $endTime->format('H:i:s'),
            'duration_hours' => $durationHours,
            'total_price' => $totalPrice,
            'status' => $validated['payment_status'] === 'paid' ? 'confirmed' : 'pending',
            'booking_code' => $bookingCode,
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'notes' => $validated['notes'],
        ]);

        $transaction = Transaction::create([
            'user_id' => $customerUser->id,
            'reservation_id' => $reservation->id,
            'transaction_code' => 'TRX-' . strtoupper(Str::random(8)),
            'amount' => $totalPrice,
            'payment_method' => $validated['payment_method'],
            'status' => $validated['payment_status'] === 'paid' ? 'paid' : 'pending',
            'payment_date' => $validated['payment_status'] === 'paid' ? now() : null,
            'description' => 'Booking manual kasir (walk-in) untuk ' . $field->name . ' - ' . $validated['customer_name'],
        ]);

        ActivityLog::log(
            $admin,
            'create',
            'Reservation',
            $reservation->id,
            "Created manual walk-in reservation {$bookingCode} for {$validated['customer_name']}"
        );

        return redirect()->route('reservations.ticket', $reservation->id)->with([
            'success' => "Booking manual atas nama {$validated['customer_name']} berhasil dibuat! Tiket/struk siap dicetak.",
        ]);
    }
}
