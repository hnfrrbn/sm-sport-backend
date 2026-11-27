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

        $indoDaysFull = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $indoMonthsFull = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        // Pemantauan Ketersediaan Lapangan Secara Real-time & Tanggal Terpilih
        $currentNow = Carbon::now('Asia/Jakarta');
        $todayStr = $currentNow->format('Y-m-d');
        $tomorrowStr = $currentNow->copy()->addDay()->format('Y-m-d');

        $scheduleDateParam = request('schedule_date');
        if ($scheduleDateParam && preg_match('/^\d{4}-\d{2}-\d{2}$/', $scheduleDateParam)) {
            $scheduleDate = $scheduleDateParam;
        } else {
            $scheduleDate = $todayStr;
        }

        $parsedScheduleDate = Carbon::parse($scheduleDate);
        $scheduleDateIndo = $indoDaysFull[$parsedScheduleDate->dayOfWeek] . ', ' . $parsedScheduleDate->day . ' ' . $indoMonthsFull[$parsedScheduleDate->month] . ' ' . $parsedScheduleDate->year;
        $scheduleDateIndoShort = $parsedScheduleDate->day . ' ' . substr($indoMonthsFull[$parsedScheduleDate->month], 0, 3);
        $isScheduleToday = ($scheduleDate === $todayStr);
        $currentTime = $currentNow->format('H:i:s');

        $totalUsers = User::where('role', 'customer')->where('status', 'active')->count();
        $totalFields = Field::count();
        $totalReservations = Reservation::count();

        $paidTransactions = Transaction::whereIn('status', ['completed', 'paid']);
        $totalRevenue = (clone $paidTransactions)->sum('amount');

        // PENDAPATAN HARI INI / TANGGAL TERPILIH:
        // Hitung jumlah (SUM) kolom total_price dari seluruh reservasi yang berstatus confirmed / lunas pada tanggal yang sedang dipilih
        $todayRevenue = (float) Reservation::whereDate('reservation_date', $scheduleDate)
            ->where('status', 'confirmed')
            ->sum('total_price');

        // PENDAPATAN BULANAN & TAHUNAN (Sesuai Bulan & Tahun dari Tanggal Terpilih):
        $selectedYear = (int) $parsedScheduleDate->year;
        $selectedMonth = (int) $parsedScheduleDate->month;
        $selectedMonthName = $indoMonthsFull[$selectedMonth];
        $selectedMonthLabel = 'Bulan ' . $selectedMonthName . ' ' . $selectedYear;
        $selectedYearLabel = 'Tahun ' . $selectedYear;

        $monthlyRevenue = (float) Reservation::whereYear('reservation_date', $selectedYear)
            ->whereMonth('reservation_date', $selectedMonth)
            ->where('status', 'confirmed')
            ->sum('total_price');

        $yearlyRevenue = (float) Reservation::whereYear('reservation_date', $selectedYear)
            ->where('status', 'confirmed')
            ->sum('total_price');

        // Jumlah sesi main aktif (pending & confirmed) pada tanggal yang sedang dipilih
        $todayPlayingCount = Reservation::whereDate('reservation_date', $scheduleDate)
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        // Today's bookings created on selected date
        $todayBookingsCount = Reservation::whereDate('created_at', $scheduleDate)->count();

        // Pending reservations awaiting WhatsApp payment confirmation
        $pendingReservations = Reservation::where('status', 'pending')
            ->with(['field', 'user', 'transaction'])
            ->latest()
            ->get();

        // Reservations for table (up to 50 latest, sorted by date & time)
        $recentReservations = Reservation::with(['user', 'field', 'transaction'])
            ->orderBy('reservation_date', 'desc')
            ->orderBy('start_time', 'asc')
            ->take(50)
            ->get();

        $now = Carbon::today();
        $todayDateIndo = $indoDaysFull[$now->dayOfWeek] . ', ' . $now->day . ' ' . $indoMonthsFull[$now->month] . ' ' . $now->year;

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

        // All fields for court schedule
        $fieldsForSchedule = Field::orderBy('id', 'asc')->get();

        // 1. Sedang dipakai saat ini (Real-time untuk hari ini dengan format jam string yang presisi)
        $ongoingReservations = Reservation::with(['user', 'field'])
            ->whereDate('reservation_date', $todayStr)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get()
            ->filter(function ($r) use ($currentTime) {
                $rStart = is_object($r->start_time) ? $r->start_time->format('H:i:s') : (strlen($r->start_time) == 5 ? $r->start_time . ':00' : $r->start_time);
                $rEnd = is_object($r->end_time) ? $r->end_time->format('H:i:s') : (strlen($r->end_time) == 5 ? $r->end_time . ':00' : $r->end_time);
                return $rStart <= $currentTime && $rEnd > $currentTime;
            })
            ->keyBy('field_id');

        // 2. Semua reservasi pada tanggal yang dipilih ($scheduleDate) dengan format jam string bersih (H:i)
        $selectedDateReservations = Reservation::with(['user', 'field'])
            ->whereDate('reservation_date', $scheduleDate)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get()
            ->map(function ($r) {
                $r->start_str = is_object($r->start_time) ? $r->start_time->format('H:i') : substr($r->start_time, 0, 5);
                $r->end_str = is_object($r->end_time) ? $r->end_time->format('H:i') : substr($r->end_time, 0, 5);
                return $r;
            });

        // 3. Time slots: 08:00 - 22:00 (mencakup jam main sampai 23:00)
        $timeSlots = [];
        for ($h = 8; $h <= 22; $h++) {
            $sH = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00';
            $eH = str_pad($h + 1, 2, '0', STR_PAD_LEFT) . ':00';
            $timeSlots[] = [
                'start' => $sH,
                'end' => $eH,
                'hour' => $h,
            ];
        }

        // Helper resolusi nama pelanggan sebenarnya (bukan nama akun admin)
        $resolveCustomerName = function ($reservation) {
            $name = trim($reservation->customer_name ?? '');
            if (!empty($name) && strcasecmp($name, 'admin') !== 0) {
                return $name;
            }
            if ($reservation->user && ! $reservation->user->isAdmin() && strcasecmp($reservation->user->name, 'admin') !== 0) {
                return $reservation->user->name;
            }
            return 'Pelanggan Tamu';
        };

        // 4. Susun ringkasan ketersediaan per lapangan dengan prioritas warna yang tepat
        $courtMonitoring = [];
        foreach ($fieldsForSchedule as $field) {
            // Real-time status badge
            if ($field->status !== 'available') {
                $realtimeBadge = [
                    'state' => 'maintenance',
                    'class' => 'bg-secondary text-white',
                    'label' => 'Tutup / Maintenance',
                    'detail' => 'Lapangan dinonaktifkan',
                ];
            } elseif (isset($ongoingReservations[$field->id])) {
                $onRes = $ongoingReservations[$field->id];
                $onName = $resolveCustomerName($onRes);
                $onTime = Carbon::parse($onRes->start_time)->format('H:i') . ' - ' . Carbon::parse($onRes->end_time)->format('H:i');
                if ($onRes->status === 'confirmed') {
                    $realtimeBadge = [
                        'state' => 'playing',
                        'class' => 'bg-primary text-white border border-primary',
                        'label' => 'Sedang Dimainkan: ' . $onName . ' (' . $onTime . ' WIB)',
                        'detail' => 'Sedang berlangsung: ' . $onName . ' • ' . $onTime,
                        'customer' => $onName,
                        'time' => $onTime,
                    ];
                } else {
                    $realtimeBadge = [
                        'state' => 'in_use',
                        'class' => 'bg-warning text-dark border border-warning',
                        'label' => 'Menunggu Pembayaran: ' . $onName . ' (' . $onTime . ' WIB)',
                        'detail' => 'Menunggu bayar: ' . $onName . ' • ' . $onTime,
                        'customer' => $onName,
                        'time' => $onTime,
                    ];
                }
            } else {
                $realtimeBadge = [
                    'state' => 'available',
                    'class' => 'bg-success-subtle text-success border border-success-subtle',
                    'label' => 'Tersedia / Kosong Sekarang',
                    'detail' => 'Tidak ada sesi main aktif saat ini',
                ];
            }

            // Slot array for this field
            $fieldSlots = [];
            foreach ($timeSlots as $slot) {
                // PRIORITAS 1: Cek apakah slot jam ini masuk dalam reservasi berstatus Lunas / Terkonfirmasi
                $confirmedRes = $selectedDateReservations->first(function ($r) use ($field, $slot) {
                    return $r->field_id == $field->id
                        && $r->status === 'confirmed'
                        && $r->start_str < $slot['end']
                        && $r->end_str > $slot['start'];
                });

                // PRIORITAS 2: Cek apakah slot jam masuk dalam reservasi berstatus Menunggu Pembayaran
                $pendingRes = null;
                if (! $confirmedRes) {
                    $pendingRes = $selectedDateReservations->first(function ($r) use ($field, $slot) {
                        return $r->field_id == $field->id
                            && $r->status === 'pending'
                            && $r->start_str < $slot['end']
                            && $r->end_str > $slot['start'];
                    });
                }

                $isCurrentSlot = $isScheduleToday && (substr($currentTime, 0, 5) >= $slot['start'] && substr($currentTime, 0, 5) < $slot['end']);
                $isPastSlot = $isScheduleToday && ($slot['end'] <= substr($currentTime, 0, 5));

                if ($confirmedRes) {
                    $cName = $resolveCustomerName($confirmedRes);
                    $cPhone = $confirmedRes->customer_phone ?: ($confirmedRes->user?->phone ?? '-');
                    
                    if ($isCurrentSlot) {
                        // PRIORITAS 1: Biru (Sedang Dimainkan) - Tanggal hari ini + jam sekarang berada di dalam slot + Lunas
                        $fieldSlots[] = [
                            'start' => $slot['start'],
                            'end' => $slot['end'],
                            'status' => 'in_play',
                            'res_status' => 'confirmed',
                            'res_id' => $confirmedRes->id,
                            'booking_code' => $confirmedRes->code,
                            'customer_name' => $cName,
                            'customer_phone' => $cPhone,
                            'total_price' => $confirmedRes->total_price,
                            'notes' => $confirmedRes->notes,
                        ];
                    } else {
                        // PRIORITAS 2: Merah (Terisi / Lunas)
                        $fieldSlots[] = [
                            'start' => $slot['start'],
                            'end' => $slot['end'],
                            'status' => 'booked',
                            'res_status' => 'confirmed',
                            'res_id' => $confirmedRes->id,
                            'booking_code' => $confirmedRes->code,
                            'customer_name' => $cName,
                            'customer_phone' => $cPhone,
                            'total_price' => $confirmedRes->total_price,
                            'notes' => $confirmedRes->notes,
                        ];
                    }
                } elseif ($pendingRes) {
                    $cName = $resolveCustomerName($pendingRes);
                    $cPhone = $pendingRes->customer_phone ?: ($pendingRes->user?->phone ?? '-');
                    if ($isPastSlot) {
                        $fieldSlots[] = [
                            'start' => $slot['start'],
                            'end' => $slot['end'],
                            'status' => 'past',
                        ];
                    } else {
                        // PRIORITAS 3: Oranye (Menunggu Bayar WA)
                        $fieldSlots[] = [
                            'start' => $slot['start'],
                            'end' => $slot['end'],
                            'status' => 'booked',
                            'res_status' => 'pending',
                            'res_id' => $pendingRes->id,
                            'booking_code' => $pendingRes->code,
                            'customer_name' => $cName,
                            'customer_phone' => $cPhone,
                            'total_price' => $pendingRes->total_price,
                            'notes' => $pendingRes->notes,
                        ];
                    }
                } elseif ($isPastSlot) {
                    // PRIORITAS 4: Abu-abu (Jam Lewat)
                    $fieldSlots[] = [
                        'start' => $slot['start'],
                        'end' => $slot['end'],
                        'status' => 'past',
                    ];
                } else {
                    // PRIORITAS 5: Hijau (Tersedia)
                    $fieldSlots[] = [
                        'start' => $slot['start'],
                        'end' => $slot['end'],
                        'status' => 'available',
                    ];
                }
            }

            $courtMonitoring[] = [
                'field' => $field,
                'realtime' => $realtimeBadge,
                'slots' => $fieldSlots,
            ];
        }

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalFields' => $totalFields,
            'totalReservations' => $totalReservations,
            'totalRevenue' => $totalRevenue,
            'todayRevenue' => $todayRevenue,
            'monthlyRevenue' => $monthlyRevenue,
            'yearlyRevenue' => $yearlyRevenue,
            'selectedMonthLabel' => $selectedMonthLabel,
            'selectedYearLabel' => $selectedYearLabel,
            'todayBookingsCount' => $todayBookingsCount,
            'todayPlayingCount' => $todayPlayingCount,
            'todayDateIndo' => $todayDateIndo,
            'pendingReservations' => $pendingReservations,
            'pendingVerifications' => $pendingReservations,
            'recentReservations' => $recentReservations,
            'availableFields' => $availableFields,
            'reservationData' => $reservationData,
            'revenueData' => $revenueData,
            'fieldPopularity' => $fieldPopularity,
            'courtMonitoring' => $courtMonitoring,
            'scheduleDate' => $scheduleDate,
            'scheduleDateIndo' => $scheduleDateIndo,
            'scheduleDateIndoShort' => $scheduleDateIndoShort,
            'isScheduleToday' => $isScheduleToday,
            'todayStr' => $todayStr,
            'tomorrowStr' => $tomorrowStr,
            'timeSlots' => $timeSlots,
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
            if ($reservation) {
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

                $reservation->status = 'confirmed';
                $reservation->save();
            }

            $transaction->status = 'paid';
            $transaction->payment_date = now();
            $transaction->save();

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

        // Check for conflicting reservations that are already approved
        $conflict = Reservation::where('field_id', $field->id)
            ->whereDate('reservation_date', $validated['reservation_date'])
            ->whereIn('status', ['paid', 'lunas', 'confirmed', 'dp'])
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

    /**
     * Reschedule / Edit an existing reservation
     */
    public function rescheduleReservation(Request $request, Reservation $reservation)
    {
        $user = auth()->user();
        if (! $user || ! $user->isAdmin()) {
            abort(403, 'Hanya admin yang dapat mengubah jadwal reservasi');
        }

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'field_id' => ['required', 'exists:fields,id'],
            'reservation_date' => ['required', 'date'],
            'start_time' => ['required', 'string'],
            'duration_hours' => ['required', 'integer', 'min:1', 'max:12'],
            'status' => ['required', 'in:pending,confirmed,cancelled'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'customer_name.required' => 'Nama pemesan wajib diisi.',
            'customer_phone.required' => 'Nomor WhatsApp wajib diisi.',
            'field_id.required' => 'Pilihan lapangan wajib ditentukan.',
            'reservation_date.required' => 'Tanggal bermain wajib diisi.',
            'start_time.required' => 'Jam mulai main wajib dipilih.',
            'duration_hours.required' => 'Durasi bermain wajib ditentukan.',
            'status.required' => 'Status pembayaran wajib dipilih.',
        ]);

        $field = Field::findOrFail($validated['field_id']);
        
        // Parse waktu & hitung jam selesai
        $startCarbon = Carbon::parse($validated['start_time']);
        $startTimeStr = $startCarbon->format('H:i:s');
        $durationHours = (int) $validated['duration_hours'];
        $endCarbon = $startCarbon->copy()->addHours($durationHours);
        $endTimeStr = $endCarbon->format('H:i:s');

        // VALIDASI ANTI-BENTROK:
        // Cek apakah lapangan dan jam tujuan bentrok dengan pesanan lain yang berstatus Lunas (confirmed / paid)
        if ($validated['status'] !== 'cancelled') {
            $clash = Reservation::where('id', '!=', $reservation->id)
                ->where('field_id', $field->id)
                ->whereDate('reservation_date', $validated['reservation_date'])
                ->whereIn('status', ['confirmed', 'paid'])
                ->where(function ($query) use ($startTimeStr, $endTimeStr) {
                    $query->where('start_time', '<', $endTimeStr)
                          ->where('end_time', '>', $startTimeStr);
                })
                ->first();

            if ($clash) {
                $clashStart = Carbon::parse($clash->start_time)->format('H:i');
                $clashEnd = Carbon::parse($clash->end_time)->format('H:i');
                $clashName = $clash->customer_name ?: ($clash->user?->name ?? 'Pelanggan');
                return back()->withInput()->with('error', "Jadwal bentrok! Lapangan {$field->name} pada tanggal {$validated['reservation_date']} jam {$clashStart} - {$clashEnd} WIB sudah dipesan dan berstatus Lunas oleh {$clashName} ({$clash->code}). Silakan pilih lapangan, tanggal, atau jam main lain.");
            }
        }

        // Hitung ulang total tagihan otomatis berdasarkan tarif per jam lapangan dan durasi terbaru
        $newTotalPrice = (float) ($field->price_per_hour * $durationHours);

        $oldDate = Carbon::parse($reservation->reservation_date)->format('Y-m-d');
        $oldField = $reservation->field?->name ?? 'Lapangan';
        $oldStartTimeStr = is_object($reservation->start_time) ? $reservation->start_time->format('H:i') : substr($reservation->start_time, 0, 5);
        $oldEndTimeStr = is_object($reservation->end_time) ? $reservation->end_time->format('H:i') : substr($reservation->end_time, 0, 5);
        $oldTime = $oldStartTimeStr . '-' . $oldEndTimeStr;

        // Update data reservasi
        $reservation->customer_name = $validated['customer_name'];
        $reservation->customer_phone = $validated['customer_phone'];
        $reservation->field_id = $field->id;
        $reservation->reservation_date = $validated['reservation_date'];
        $reservation->start_time = $startTimeStr;
        $reservation->end_time = $endTimeStr;
        $reservation->duration_hours = $durationHours;
        $reservation->total_price = $newTotalPrice;
        $reservation->status = $validated['status'];
        $reservation->notes = $validated['notes'];

        if ($validated['status'] === 'confirmed') {
            $reservation->amount_paid = $newTotalPrice;
            $reservation->remaining_amount = 0;
            $reservation->paid_at = $reservation->paid_at ?: Carbon::now('Asia/Jakarta');
        } elseif ($validated['status'] === 'cancelled') {
            $reservation->cancelled_at = Carbon::now('Asia/Jakarta');
            $reservation->cancellation_reason = 'Dibatalkan oleh Admin via Edit/Reschedule';
        }

        $reservation->save();

        // Sinkronisasi ke Transaction terkait jika ada
        if ($reservation->transaction) {
            $trx = $reservation->transaction;
            $trx->amount = $newTotalPrice;
            if ($validated['status'] === 'confirmed') {
                $trx->amount_paid = $newTotalPrice;
                $trx->remaining_amount = 0;
                $trx->status = 'completed';
                $trx->paid_at = $trx->paid_at ?: Carbon::now('Asia/Jakarta');
            } elseif ($validated['status'] === 'cancelled') {
                $trx->status = 'cancelled';
            } elseif ($validated['status'] === 'pending') {
                $trx->status = 'pending';
            }
            $trx->save();
        }

        ActivityLog::log(
            $user, 
            'update', 
            'Reservation', 
            $reservation->id, 
            "Rescheduled pesanan {$reservation->code} dari {$oldField} ({$oldDate} {$oldTime}) ke {$field->name} ({$validated['reservation_date']} {$startCarbon->format('H:i')}-{$endCarbon->format('H:i')})"
        );

        return back()->with('success', "Berhasil mengubah jadwal / data pesanan {$reservation->code}! Jadwal kini dipindahkan ke {$field->name} ({$validated['reservation_date']} {$startCarbon->format('H:i')} - {$endCarbon->format('H:i')} WIB) dengan total tagihan Rp " . number_format($newTotalPrice, 0, ',', '.') . ".");
    }
}
