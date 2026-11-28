<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Models\ActivityLog;
use App\Models\Field;
use App\Models\Reservation;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ReservationController extends Controller
{
    /**
     * Display a listing of reservations (for admin) with search & status filter
     */
    public function index(Request $request): View
    {
        $search = trim($request->query('q', ''));
        $status = $request->query('status');

        $query = Reservation::with(['user', 'field', 'transaction']);

        if ($search !== '') {
            $cleanPhone = preg_replace('/[^0-9]/', '', $search);
            $query->where(function ($q) use ($search, $cleanPhone) {
                $q->where('booking_code', 'LIKE', "%{$search}%")
                  ->orWhere('customer_name', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'LIKE', "%{$search}%");
                  });

                if (! empty($cleanPhone) && strlen($cleanPhone) >= 4) {
                    $q->orWhere('customer_phone', 'LIKE', "%{$cleanPhone}%")
                      ->orWhere('customer_phone', 'LIKE', "%{$search}%")
                      ->orWhereHas('user', function ($uq) use ($cleanPhone) {
                          $uq->where('phone', 'LIKE', "%{$cleanPhone}%");
                      });
                }
            });
        }

        if ($status && in_array($status, ['pending', 'confirmed', 'completed', 'cancelled'])) {
            $query->where('status', $status);
        }

        $reservations = $query->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('reservations.index', compact('reservations', 'search', 'status'));
    }

    /**
     * Show available fields for booking (Alur Booking 3 Langkah)
     */
    public function create(): View
    {
        $fields = Field::orderBy('id', 'asc')->get();
        return view('reservations.create', compact('fields'));
    }

    /**
     * Return schedule slots for interactive field booking UI.
     * Always returns HTTP 200 with 08:00 - 23:00 WIB slots.
     */
    public function schedule(Request $request): JsonResponse
    {
        try {
            $fieldId = $request->query('field_id', $request->input('field_id'));
            $dateParam = $request->query('date', $request->input('date'));

            $nowWib = Carbon::now('Asia/Jakarta');
            $todayWibStr = $nowWib->toDateString();

            $dateString = $todayWibStr;
            if ($dateParam) {
                try {
                    $dateString = Carbon::parse($dateParam, 'Asia/Jakarta')->toDateString();
                } catch (\Throwable $e) {
                    $dateString = $todayWibStr;
                }
            }

            $field = null;
            if ($fieldId) {
                $field = Field::find($fieldId);
            }
            if (! $field) {
                $field = Field::orderBy('id', 'asc')->first();
            }

            $slots = [];
            $startHour = 8;
            $endHour = 23;
            $isToday = ($dateString === $todayWibStr);
            $isPastDate = ($dateString < $todayWibStr);
            $currentTimeStr = $nowWib->format('H:i');

            // Retrieve all active approved / pending reservations for this field and date
            $activeReservations = collect();
            if ($field) {
                $activeReservations = Reservation::where('field_id', $field->id)
                    ->whereDate('reservation_date', $dateString)
                    ->whereIn('status', ['paid', 'lunas', 'confirmed', 'dp', 'pending'])
                    ->get(['id', 'start_time', 'end_time', 'status']);
            }

            for ($hour = $startHour; $hour < $endHour; $hour++) {
                $slotStartStr = sprintf('%02d:00', $hour);
                $slotEndStr = sprintf('%02d:00', $hour + 1);
                $slotStart = Carbon::parse($dateString . ' ' . $slotStartStr, 'Asia/Jakarta');
                $slotEnd = Carbon::parse($dateString . ' ' . $slotEndStr, 'Asia/Jakarta');

                $confirmedRes = $activeReservations->first(function ($r) use ($slotStartStr, $slotEndStr) {
                    $rStart = Carbon::parse($r->start_time)->format('H:i');
                    $rEnd = Carbon::parse($r->end_time)->format('H:i');
                    $isConfirmed = in_array(strtolower($r->status), ['paid', 'lunas', 'confirmed', 'dp']);
                    return $isConfirmed && $rStart < $slotEndStr && $rEnd > $slotStartStr;
                });

                $pendingRes = null;
                if (! $confirmedRes) {
                    $pendingRes = $activeReservations->first(function ($r) use ($slotStartStr, $slotEndStr) {
                        $rStart = Carbon::parse($r->start_time)->format('H:i');
                        $rEnd = Carbon::parse($r->end_time)->format('H:i');
                        return strtolower($r->status) === 'pending' && $rStart < $slotEndStr && $rEnd > $slotStartStr;
                    });
                }

                $hasActiveRes = ($confirmedRes || $pendingRes);

                // PRIORITAS 1: Abu-abu (Lewat)
                // Jika tanggal sebelum hari ini, ATAU jika hari ini dan waktu slot sudah lewat:
                // - Jika slot kosong: sudah lewat begitu $nowWib->gte($slotStart) (misal pukul 18:19 WIB, maka 18:00 ke bawah sudah lewat)
                // - Jika slot terisi (Lunas / Pending): sudah lewat begitu melewati jam selesai bermain $nowWib->gte($slotEnd) (misal booking pukul 08:00 - 15:00, maka setelah pukul 15:00 WIB otomatis berubah menjadi Abu-abu / Lewat)
                $isPastSlot = false;
                if ($isPastDate) {
                    $isPastSlot = true;
                } elseif ($isToday) {
                    if ($hasActiveRes) {
                        $isPastSlot = $nowWib->gte($slotEnd);
                    } else {
                        $isPastSlot = $nowWib->gte($slotStart);
                    }
                }

                if ($field && $field->status !== 'available') {
                    $status = 'maintenance';
                } elseif ($isPastSlot) {
                    // 1. Abu-abu: Lewat
                    $status = 'past';
                } elseif ($isToday && $nowWib->gte($slotStart) && $nowWib->lt($slotEnd) && $confirmedRes) {
                    // 2. Biru: Sedang Dimainkan
                    $status = 'in_play';
                } elseif ($confirmedRes) {
                    // 3. Merah: Terisi / Lunas
                    $status = 'booked';
                } elseif ($pendingRes) {
                    // 4. Oranye: Menunggu Bayar WA
                    $status = 'pending';
                } else {
                    // 5. Hijau: Tersedia
                    $status = 'available';
                }

                $slots[] = [
                    'start' => $slotStartStr,
                    'end' => $slotEndStr,
                    'status' => $status,
                ];
            }

            return response()->json([
                'success' => true,
                'slots' => $slots,
                'field' => $field ? $field->name : '-',
                'field_id' => $field ? $field->id : null,
                'date' => $dateString,
            ], 200);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('ReservationController::schedule error: ' . $e->getMessage());

            $fallbackSlots = [];
            $fbNowWib = Carbon::now('Asia/Jakarta');
            $fbTodayStr = $fbNowWib->toDateString();
            $fbIsToday = ($dateString === $fbTodayStr);
            $fbIsPastDate = ($dateString < $fbTodayStr);
            $fbNow = $fbNowWib->format('H:i');

            for ($hour = 8; $hour < 23; $hour++) {
                $sStr = sprintf('%02d:00', $hour);
                $eStr = sprintf('%02d:00', $hour + 1);
                $fbStatus = ($fbIsPastDate || ($fbIsToday && $sStr < $fbNow)) ? 'past' : 'available';
                $fallbackSlots[] = [
                    'start' => $sStr,
                    'end' => $eStr,
                    'status' => $fbStatus,
                ];
            }

            return response()->json([
                'success' => true,
                'slots' => $fallbackSlots,
                'field' => '-',
                'field_id' => null,
                'date' => date('Y-m-d'),
                'debug' => config('app.debug') ? $e->getMessage() : null,
            ], 200);
        }
    }

    /**
     * Store a new reservation with double booking validation & guest booking support
     */
    public function store(StoreReservationRequest $request)
    {
        $validated = $request->validated();
        $userId = Auth::id();

        $customerName = trim($validated['customer_name'] ?? '');
        $customerPhone = trim($validated['customer_phone'] ?? '');

        if (! $userId) {
            if (empty($customerName)) {
                return back()->withErrors(['customer_name' => 'Nama lengkap wajib diisi untuk pemesanan instan.'])->withInput();
            }
            if (empty($customerPhone)) {
                return back()->withErrors(['customer_phone' => 'Nomor WhatsApp aktif wajib diisi untuk konfirmasi jadwal.'])->withInput();
            }
        } else {
            if (Auth::user()->isAdmin() && empty($customerName)) {
                return back()->withErrors(['customer_name' => 'Nama lengkap pelanggan wajib diisi saat admin melakukan booking.'])->withInput();
            }
            $customerName = $customerName ?: Auth::user()->name;
            $customerPhone = $customerPhone ?: (Auth::user()->phone ?? '-');
        }

        $startTime = Carbon::createFromFormat('H:i', $validated['start_time']);
        $endTime = Carbon::createFromFormat('H:i', $validated['end_time']);

        if ($endTime->lessThanOrEqualTo($startTime)) {
            return back()->withErrors(['time_range' => 'Jam selesai harus lebih besar dari jam mulai.'])->withInput();
        }

        $field = Field::find($validated['field_id']);
        if (! $field) {
            return back()->withErrors(['field_id' => 'Lapangan yang dipilih tidak ditemukan.'])->withInput();
        }

        // Anti Double Booking Validation:
        // HANYA berbenturan jika sudah ada reservasi yang disetujui / lunas oleh admin
        $conflictingReservation = Reservation::where('field_id', $field->id)
            ->whereDate('reservation_date', $validated['reservation_date'])
            ->whereIn('status', ['paid', 'lunas', 'confirmed', 'dp'])
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where(function ($sub) use ($startTime, $endTime) {
                    $sub->where('start_time', '<', $endTime->format('H:i:s'))
                        ->where('end_time', '>', $startTime->format('H:i:s'));
                });
            })
            ->first();

        if ($conflictingReservation) {
            return back()->withErrors([
                'time_conflict' => 'Slot jam yang Anda pilih sudah terisi atau berbenturan dengan jadwal lain yang telah disetujui. Silakan pilih slot jam lain yang masih kosong.',
            ])->withInput();
        }

        // Generate unique booking code e.g. RSV-261124-ABCD
        $bookingCode = 'RSV-' . date('ymd') . '-' . strtoupper(Str::random(4));

        $duration = max(1, $endTime->diffInHours($startTime));
        $totalPrice = $duration * $field->price_per_hour;

        $reservation = new Reservation([
            'user_id' => $userId,
            'field_id' => $field->id,
            'reservation_date' => $validated['reservation_date'],
            'start_time' => $startTime->format('H:i:s'),
            'end_time' => $endTime->format('H:i:s'),
            'duration_hours' => $duration,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'booking_code' => $bookingCode,
            'customer_name' => $customerName,
            'customer_phone' => $customerPhone,
            'notes' => $validated['notes'] ?? null,
        ]);
        $reservation->save();

        $paymentMethod = $validated['payment_method'] ?? 'transfer';

        // Auto create pending transaction
        $transaction = Transaction::create([
            'user_id' => $userId,
            'reservation_id' => $reservation->id,
            'transaction_code' => 'TRX-' . date('ymd') . '-' . strtoupper(Str::random(5)),
            'amount' => $totalPrice,
            'payment_method' => $paymentMethod,
            'status' => 'pending',
            'description' => 'Sewa ' . $field->name . ' (' . $duration . ' Jam)',
        ]);

        if (Auth::check()) {
            ActivityLog::log(
                Auth::user(),
                'create',
                'Reservation',
                $reservation->id,
                'Created reservation #' . $bookingCode . ' for field: ' . $field->name
            );
        }

        session(['last_booking_code' => $bookingCode]);

        return redirect()->route('reservations.show', ['reservation' => $reservation->id, 'code' => $bookingCode])
            ->with('success', 'Booking berhasil dibuat! Kode Booking Anda: ' . $bookingCode . '. Silakan selesaikan pembayaran.');
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

        $reservations = $user->reservations()->with('field')->orderBy('id', 'desc')->paginate(10);
        return view('reservations.my-reservations', compact('reservations'));
    }

    /**
     * Show reservation details (Langkah 3: Pembayaran & Bukti)
     * Accessible for Admin, Account Owner, or Guest with Booking Code / Session
     */
    public function show(Reservation $reservation, Request $request)
    {
        $user = Auth::user();
        $codeParam = $request->query('code');
        $sessionCode = session('last_booking_code');

        $isAuthorized = false;
        if ($user && ($user->isAdmin() || $user->id === $reservation->user_id)) {
            $isAuthorized = true;
        } elseif ($codeParam && (strtoupper($codeParam) === strtoupper($reservation->booking_code))) {
            $isAuthorized = true;
        } elseif ($sessionCode && (strtoupper($sessionCode) === strtoupper($reservation->booking_code))) {
            $isAuthorized = true;
        }

        if (! $isAuthorized) {
            return redirect()->route('reservations.check')->with('error', 'Akses ditolak. Silakan masukkan Kode Booking atau Nomor WhatsApp yang sesuai untuk melihat rincian pesanan.');
        }

        $reservation->load(['field', 'user', 'transaction']);

        return view('reservations.show', compact('reservation'));
    }

    /**
     * Create a pending transaction for a reservation (AJAX)
     */
    public function createTransaction(Request $request, Reservation $reservation)
    {
        if ($reservation->transaction) {
            return response()->json(['ok' => true, 'transaction_id' => $reservation->transaction->id]);
        }

        $paymentMethod = $request->input('payment_method', 'transfer');

        $transaction = Transaction::create([
            'user_id' => $reservation->user_id,
            'reservation_id' => $reservation->id,
            'transaction_code' => 'TRX-' . date('ymd') . '-' . strtoupper(Str::random(5)),
            'amount' => $reservation->total_price,
            'payment_method' => $paymentMethod,
            'status' => 'pending',
            'description' => 'Pembayaran sewa ' . ($reservation->field->name ?? 'lapangan'),
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
            'cancellation_reason' => ['nullable', 'string', 'max:500'],
        ]);

        $oldStatus = $reservation->status;
        $newStatus = $validated['status'];

        // Cek jika status disetujui (confirmed), pastikan tidak ada bentrok dengan reservasi lain yang sudah disetujui
        if ($newStatus === 'confirmed') {
            $conflict = Reservation::where('field_id', $reservation->field_id)
                ->where('id', '!=', $reservation->id)
                ->whereDate('reservation_date', $reservation->reservation_date)
                ->whereIn('status', ['paid', 'lunas', 'confirmed', 'dp'])
                ->where(function ($q) use ($reservation) {
                    $q->where('start_time', '<', $reservation->end_time)
                        ->where('end_time', '>', $reservation->start_time);
                })
                ->first();

            if ($conflict) {
                return back()->with('error', 'Gagal menyetujui: Slot jam ' . substr($reservation->start_time, 0, 5) . ' - ' . substr($reservation->end_time, 0, 5) . ' sudah terisi oleh reservasi lunas lain (#' . $conflict->code . ').');
            }
        }

        $updateData = ['status' => $newStatus];
        if ($newStatus === 'cancelled') {
            $updateData['cancelled_at'] = now();
            $updateData['cancellation_reason'] = $request->input('cancellation_reason') ?: 'Dibatalkan oleh Admin.';
        }

        $reservation->update($updateData);

        if (in_array($newStatus, ['confirmed', 'completed']) && $reservation->transaction) {
            $reservation->transaction->update([
                'status' => 'paid',
                'payment_date' => now(),
            ]);
        } elseif ($newStatus === 'cancelled' && $reservation->transaction) {
            $reservation->transaction->update([
                'status' => 'cancelled',
            ]);
        }

        $user = Auth::user();
        if ($user) {
            ActivityLog::log(
                $user,
                'update',
                'Reservation',
                $reservation->id,
                "Changed status from {$oldStatus} to {$newStatus}"
            );
        }

        $msg = $newStatus === 'confirmed'
            ? 'Reservasi #' . $reservation->code . ' berhasil dikonfirmasi LUNAS dan jadwal telah dikunci.'
            : ($newStatus === 'cancelled'
                ? 'Reservasi #' . $reservation->code . ' telah dibatalkan dan slot jam dibuka kembali.'
                : 'Status reservasi berhasil diubah.');

        return back()->with('success', $msg);
    }

    /**
     * Cancel reservation (customer, guest with code, or admin)
     */
    public function cancel(Request $request, Reservation $reservation)
    {
        $user = Auth::user();
        $codeParam = $request->input('code', $request->query('code'));
        $sessionCode = session('last_booking_code');

        $canCancel = false;
        if ($user && ($user->isAdmin() || $user->id === $reservation->user_id)) {
            $canCancel = true;
        } elseif ($codeParam && (strtoupper($codeParam) === strtoupper($reservation->booking_code))) {
            $canCancel = true;
        } elseif ($sessionCode && (strtoupper($sessionCode) === strtoupper($reservation->booking_code))) {
            $canCancel = true;
        }

        if (! $canCancel) {
            abort(403, 'Akses tidak diizinkan untuk membatalkan pesanan ini.');
        }

        $validated = $request->validate([
            'cancellation_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $reason = $validated['cancellation_reason'] ?? 'Dibatalkan oleh pemesan.';

        $reservation->status = 'cancelled';
        $reservation->cancellation_reason = $reason;
        $reservation->cancelled_at = now();
        $reservation->save();

        if ($reservation->transaction && ! in_array($reservation->transaction->status, ['paid', 'completed'])) {
            $reservation->transaction->status = 'cancelled';
            $reservation->transaction->payment_date = null;
            $reservation->transaction->save();
        }

        if ($user) {
            ActivityLog::log(
                $user,
                'update',
                'Reservation',
                $reservation->id,
                'Cancelled reservation. Reason: ' . substr($reason, 0, 200)
            );
        }

        return back()->with('success', 'Reservasi berhasil dibatalkan dan slot jam telah dibuka kembali.');
    }

    /**
     * Delete reservation (admin only)
     */
    public function destroy(Reservation $reservation)
    {
        $user = Auth::user();
        if (! $user || ! $user->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $reservation->delete();

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
     * Upload payment proof for a reservation (Langkah 3)
     */
    public function uploadProof(Request $request, Reservation $reservation)
    {
        $request->validate([
            'payment_proof' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
        ]);

        $file = $request->file('payment_proof');
        $extension = strtolower($file->getClientOriginalExtension());
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];

        if (! in_array($extension, $allowedExtensions)) {
            return back()->withErrors(['payment_proof' => 'Format file tidak diizinkan. Gunakan JPG, PNG, WEBP, atau PDF.']);
        }

        $filename = 'proof_' . $reservation->id . '_' . time() . '_' . Str::random(10) . '.' . $extension;
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
                'transaction_code' => 'TRX-' . date('ymd') . '-' . strtoupper(Str::random(5)),
                'amount' => $reservation->total_price,
                'payment_method' => 'transfer',
                'status' => 'pending',
                'payment_proof' => $relativePath,
                'payment_proof_uploaded_at' => now(),
                'description' => 'Pembayaran sewa ' . ($reservation->field->name ?? 'lapangan'),
            ]);
        } else {
            $transaction->payment_proof = $relativePath;
            $transaction->payment_proof_uploaded_at = now();
            if ($transaction->status !== 'paid') {
                $transaction->status = 'pending';
            }
            $transaction->save();
        }

        if (Auth::check()) {
            ActivityLog::log(
                Auth::user(),
                'upload',
                'Transaction',
                $transaction->id,
                'Uploaded payment proof for reservation: ' . $reservation->code
            );
        }

        return redirect()->route('reservations.show', ['reservation' => $reservation->id, 'code' => $reservation->booking_code])
            ->with('success', 'Bukti pembayaran berhasil diunggah! Mohon tunggu verifikasi admin.');
    }

    /**
     * Print / view booking ticket
     */
    public function ticket(Reservation $reservation, Request $request): View|\Illuminate\Http\RedirectResponse
    {
        $user = Auth::user();
        $codeParam = $request->query('code');
        $sessionCode = session('last_booking_code');

        $isAuthorized = false;
        if ($user && ($user->isAdmin() || $user->id === $reservation->user_id)) {
            $isAuthorized = true;
        } elseif ($codeParam && (strtoupper($codeParam) === strtoupper($reservation->booking_code))) {
            $isAuthorized = true;
        } elseif ($sessionCode && (strtoupper($sessionCode) === strtoupper($reservation->booking_code))) {
            $isAuthorized = true;
        }

        if (! $isAuthorized) {
            return redirect()->route('reservations.check')->with('error', 'Akses ditolak. Silakan masukkan Kode Booking untuk melihat struk/tiket.');
        }

        // Cek apakah reservasi sudah disetujui / lunas oleh admin sebelum cetak tiket
        $isPaid = (in_array(strtolower($reservation->status), ['paid', 'lunas', 'confirmed', 'dp']) || ($reservation->transaction && in_array(strtolower($reservation->transaction->status), ['paid', 'completed'])));
        if (! $isPaid && (! $user || ! $user->isAdmin())) {
            return redirect()->route('reservations.show', ['reservation' => $reservation->id, 'code' => $reservation->booking_code])
                ->with('warning', 'Bukti / tiket reservasi baru dapat dicetak setelah pembayaran disetujui oleh admin.');
        }

        $reservation->load(['field', 'user', 'transaction']);

        return view('reservations.ticket', compact('reservation'));
    }

    /**
     * Fitur Cek Status Pesanan (via Kode Booking, Nama Pemesan, atau WhatsApp)
     */
    public function checkOrder(Request $request): View
    {
        $query = trim($request->input('q', $request->query('q', '')));
        $reservations = collect();
        $searched = false;

        if (! empty($query)) {
            $searched = true;
            $cleanPhone = preg_replace('/[^0-9]/', '', $query);

            $reservations = Reservation::with(['field', 'transaction', 'user'])
                ->where(function ($q) use ($query, $cleanPhone) {
                    $q->where('booking_code', 'LIKE', "%{$query}%")
                      ->orWhere('customer_name', 'LIKE', "%{$query}%")
                      ->orWhereHas('user', function ($uq) use ($query) {
                          $uq->where('name', 'LIKE', "%{$query}%");
                      });

                    if (! empty($cleanPhone) && strlen($cleanPhone) >= 4) {
                        $q->orWhere('customer_phone', 'LIKE', "%{$cleanPhone}%")
                          ->orWhere('customer_phone', 'LIKE', "%{$query}%")
                          ->orWhereHas('user', function ($uq) use ($cleanPhone) {
                              $uq->where('phone', 'LIKE', "%{$cleanPhone}%");
                          });
                    }
                })
                ->orderBy('id', 'desc')
                ->take(20)
                ->get();
        }

        return view('reservations.check', compact('reservations', 'query', 'searched'));
    }
}
