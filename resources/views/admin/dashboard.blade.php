@extends('layouts.app')

@section('title', 'Dashboard Admin - SM Sport Center')

@section('content')
<div class="container py-3">

    <!-- 1. HEADER ATAS MINIMALIS -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4 pb-2 border-bottom">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">
                <i class="bi bi-speedometer2 text-success me-2"></i>Dashboard Admin
            </h1>
            <p class="text-muted small mb-0">
                <i class="bi bi-calendar3 text-success me-1"></i> {{ $todayDateIndo ?? date('d M Y') }} • SM Sport Center Pasar Minggu
            </p>
        </div>
        <div>
            <a href="{{ route('admin.manual_booking') }}" class="btn btn-primary px-3 py-2 fw-semibold shadow-sm">
                <i class="bi bi-plus-circle me-1.5"></i> + Input Booking Manual (Walk-in)
            </a>
        </div>
    </div>

    <!-- 2. RINGKASAN STATISTIK (5 KARTU SEJAJAR: PENDAPATAN HARI INI, BULANAN, TAHUNAN, JADWAL, PENDING) -->
    <div class="row g-2 g-md-3 mb-4">
        <!-- 1. Pendapatan Hari Ini / Tanggal Terpilih -->
        <div class="col-6 col-md-4 col-xl">
            <div class="card bg-white border shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted fw-semibold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                        {{ $isScheduleToday ? 'Pendapatan Hari Ini' : 'Pendapatan ' . $scheduleDateIndoShort }}
                    </span>
                    <div class="rounded-circle bg-success-subtle p-1.5 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <i class="bi bi-cash-stack text-success fs-6"></i>
                    </div>
                </div>
                <h4 class="fw-bolder text-dark mb-1 fs-5">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h4>
                <small class="text-muted" style="font-size: 0.72rem;">
                    <i class="bi bi-calendar-check text-success me-1"></i> {{ $scheduleDateIndoShort }}
                </small>
            </div>
        </div>

        <!-- 2. Pendapatan Bulanan -->
        <div class="col-6 col-md-4 col-xl">
            <div class="card bg-white border shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted fw-semibold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                        Pendapatan Bulanan
                    </span>
                    <div class="rounded-circle bg-info-subtle p-1.5 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <i class="bi bi-graph-up-arrow text-info fs-6"></i>
                    </div>
                </div>
                <h4 class="fw-bolder text-dark mb-1 fs-5">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</h4>
                <small class="text-muted" style="font-size: 0.72rem;">
                    <i class="bi bi-calendar-month text-info me-1"></i> {{ $selectedMonthLabel }}
                </small>
            </div>
        </div>

        <!-- 3. Pendapatan Tahunan -->
        <div class="col-6 col-md-4 col-xl">
            <div class="card bg-white border shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted fw-semibold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                        Pendapatan Tahunan
                    </span>
                    <div class="rounded-circle bg-primary-subtle p-1.5 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <i class="bi bi-wallet2 text-primary fs-6"></i>
                    </div>
                </div>
                <h4 class="fw-bolder text-dark mb-1 fs-5">Rp {{ number_format($yearlyRevenue, 0, ',', '.') }}</h4>
                <small class="text-muted" style="font-size: 0.72rem;">
                    <i class="bi bi-trophy text-primary me-1"></i> {{ $selectedYearLabel }}
                </small>
            </div>
        </div>

        <!-- 4. Jadwal Main Hari Ini / Tanggal Terpilih -->
        <div class="col-6 col-md-6 col-xl">
            <div class="card bg-white border shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted fw-semibold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                        {{ $isScheduleToday ? 'Jadwal Hari Ini' : 'Jadwal ' . $scheduleDateIndoShort }}
                    </span>
                    <div class="rounded-circle bg-primary-subtle p-1.5 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <i class="bi bi-calendar2-check text-primary fs-6"></i>
                    </div>
                </div>
                <h4 class="fw-bolder text-dark mb-1 fs-5">{{ $todayPlayingCount }} <span class="fs-6 fw-normal text-muted">Sesi</span></h4>
                <small class="text-muted" style="font-size: 0.72rem;">
                    <i class="bi bi-geo-alt text-primary me-1"></i> {{ $isScheduleToday ? 'Pemakaian hari ini' : 'Pemakaian tanggal ini' }}
                </small>
            </div>
        </div>

        <!-- 5. Menunggu Pembayaran -->
        <div class="col-12 col-md-6 col-xl">
            <div class="card bg-white border shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted fw-semibold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                        Menunggu Bayar
                    </span>
                    <div class="rounded-circle bg-warning-subtle p-1.5 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <i class="bi bi-whatsapp text-warning fs-6"></i>
                    </div>
                </div>
                <h4 class="fw-bolder mb-1 fs-5" style="color: #D97706 !important;">{{ $pendingReservations->count() }} <span class="fs-6 fw-normal text-muted">Pesanan</span></h4>
                <small class="text-muted" style="font-size: 0.72rem;">
                    <i class="bi bi-chat-dots text-warning me-1"></i> Butuh konfirmasi WA
                </small>
            </div>
        </div>
    </div>

    <!-- 3. BAGIAN 1: STATUS & JADWAL LAPANGAN (REAL-TIME & VISUAL) -->
    <div class="card bg-white border shadow-sm mb-4">
        <div class="card-header bg-white border-bottom py-3 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
            <div>
                <h2 class="h6 fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                    <i class="bi bi-grid-3x3-gap-fill text-success"></i> Status & Jadwal Lapangan
                </h2>
                <div class="d-flex align-items-center gap-2 small text-muted">
                    <span><i class="bi bi-calendar-check text-success me-1"></i>{{ $scheduleDateIndo }}</span>
                    @if($isScheduleToday)
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5" style="font-size: 0.7rem;">Hari Ini</span>
                    @endif
                </div>
            </div>

            <!-- Pemilih Tanggal Singkat -->
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('admin.dashboard', ['schedule_date' => $todayStr]) }}" class="btn {{ $isScheduleToday ? 'btn-success fw-bold text-white' : 'btn-outline-secondary' }}">
                        Hari Ini
                    </a>
                    <a href="{{ route('admin.dashboard', ['schedule_date' => $tomorrowStr]) }}" class="btn {{ $scheduleDate === $tomorrowStr ? 'btn-success fw-bold text-white' : 'btn-outline-secondary' }}">
                        Besok
                    </a>
                </div>
                <div class="input-group input-group-sm" style="width: auto;">
                    <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-calendar3"></i></span>
                    <input type="date" 
                           class="form-control form-control-sm border-start-0" 
                           value="{{ $scheduleDate }}" 
                           min="{{ date('Y-m-d') }}" 
                           onchange="window.location.href='{{ route('admin.dashboard') }}?schedule_date=' + this.value" 
                           title="Pilih Tanggal Kalender">
                </div>
            </div>
        </div>

        <!-- Panduan Kode Warna Slot -->
        <div class="px-3 py-2 bg-light border-bottom d-flex flex-wrap align-items-center justify-content-between gap-2 small">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="text-muted fw-semibold me-1" style="font-size: 0.75rem;">Status Slot:</span>
                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="font-size: 0.72rem;">
                    <i class="bi bi-plus-circle me-1"></i> Hijau: Tersedia (Klik untuk Booking)
                </span>
                <span class="badge bg-primary text-white px-2 py-1" style="font-size: 0.72rem; background-color: #2563EB !important;">
                    <i class="bi bi-play-circle-fill me-1"></i> Biru: Sedang Dimainkan
                </span>
                <span class="badge bg-danger text-white px-2 py-1" style="font-size: 0.72rem;">
                    <i class="bi bi-lock-fill me-1"></i> Merah: Terisi (Lunas)
                </span>
                <span class="badge bg-warning text-dark px-2 py-1" style="font-size: 0.72rem;">
                    <i class="bi bi-clock-history me-1"></i> Oranye: Menunggu Bayar WA
                </span>
                <span class="badge bg-secondary-subtle text-muted border px-2 py-1" style="font-size: 0.72rem;">
                    <i class="bi bi-dash-circle me-1"></i> Abu-abu: Jam Lewat
                </span>
            </div>
            <div class="text-muted" style="font-size: 0.72rem;">
                <i class="bi bi-info-circle me-1 text-primary"></i> Klik slot hijau kosong untuk langsung input booking kasir.
            </div>
        </div>

        <div class="card-body p-0">
            @foreach($courtMonitoring as $item)
                @php
                    $field = $item['field'];
                    $rt = $item['realtime'];
                    $slots = $item['slots'];
                @endphp
                <div class="p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <!-- Baris Atas: Info Lapangan & Badge Status Real-Time -->
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-2">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="fw-bold text-dark fs-6">{{ $field->name }}</span>
                            @if(strtolower($field->type) === 'futsal')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5" style="font-size: 0.7rem;">Futsal</span>
                            @else
                                <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2 py-0.5" style="font-size: 0.7rem;">Badminton</span>
                            @endif
                            <span class="text-muted small">Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}/jam</span>
                        </div>

                        <!-- Badge Status Lapangan Real-Time -->
                        <div>
                            @if($rt['state'] === 'maintenance')
                                <span class="badge bg-secondary text-white py-1 px-2.5">
                                    <i class="bi bi-slash-circle me-1"></i> Tutup / Maintenance
                                </span>
                            @elseif($rt['state'] === 'playing')
                                <span class="badge text-white border-0 fw-semibold py-1 px-2.5 shadow-sm" style="background-color: #2563EB !important;" title="Sedang dimainkan saat ini">
                                    <i class="bi bi-play-circle-fill me-1"></i> Sedang Dimainkan: <strong>{{ $rt['customer'] }}</strong> ({{ $rt['time'] }} WIB)
                                </span>
                            @elseif($rt['state'] === 'in_use')
                                <span class="badge bg-warning text-dark border border-warning fw-semibold py-1 px-2.5" title="Sesi booking sedang berlangsung (Menunggu Bayar)">
                                    <i class="bi bi-clock-history me-1"></i> Menunggu Bayar: <strong>{{ $rt['customer'] }}</strong> • {{ $rt['time'] }} WIB
                                </span>
                            @else
                                <span class="badge bg-success-subtle text-success border border-success-subtle fw-semibold py-1 px-2.5">
                                    <i class="bi bi-check-circle-fill me-1"></i> Tersedia / Kosong Sekarang
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Baris Bawah: Deretan Kotak Slot Jam (08:00 - 22:00) -->
                    <div class="d-flex flex-nowrap overflow-x-auto gap-1.5 pt-1 pb-1 schedule-slots-track" style="-webkit-overflow-scrolling: touch; scrollbar-width: thin;">
                        @foreach($slots as $slot)
                            @if($slot['status'] === 'in_play')
                                <button type="button" 
                                        class="slot-box slot-in-play border-0 d-flex flex-column align-items-center justify-content-center" 
                                        data-bs-toggle="tooltip" 
                                        data-bs-html="true" 
                                        title="<strong>{{ e($slot['customer_name']) }}</strong> (SEDANG DIMAINKAN)<br>Kode: {{ $slot['booking_code'] }}<br>Jam: {{ $slot['start'] }} - {{ $slot['end'] }} WIB<br><em>Klik untuk rincian</em>"
                                        onclick="showSlotDetail('{{ $slot['booking_code'] }}', '{{ addslashes($slot['customer_name']) }}', '{{ $slot['customer_phone'] }}', '{{ addslashes($field->name) }}', '{{ $scheduleDateIndo }}', '{{ $slot['start'] }} - {{ $slot['end'] }} WIB', 'Sedang Dimainkan (Lunas)', '{{ number_format($slot['total_price'], 0, ',', '.') }}', '{{ $slot['res_id'] }}', 'in_play', '{{ addslashes($slot['notes'] ?? '') }}')">
                                    <span class="slot-time fw-bold text-white">{{ $slot['start'] }}</span>
                                    <span class="slot-sub text-white"><i class="bi bi-play-circle-fill"></i></span>
                                </button>
                            @elseif($slot['status'] === 'available')
                                <a href="{{ route('admin.manual_booking', ['field_id' => $field->id, 'reservation_date' => $scheduleDate, 'start_time' => $slot['start'], 'end_time' => $slot['end']]) }}" 
                                   class="slot-box slot-available text-decoration-none d-flex flex-column align-items-center justify-content-center"
                                   data-bs-toggle="tooltip" 
                                   data-bs-placement="top" 
                                   title="Tersedia: {{ $slot['start'] }} - {{ $slot['end'] }} WIB. Klik untuk booking manual.">
                                    <span class="slot-time fw-bold">{{ $slot['start'] }}</span>
                                    <span class="slot-sub text-success small"><i class="bi bi-plus"></i></span>
                                </a>
                            @elseif($slot['status'] === 'booked')
                                @if($slot['res_status'] === 'confirmed')
                                    <button type="button" 
                                            class="slot-box slot-booked-confirmed border-0 d-flex flex-column align-items-center justify-content-center" 
                                            data-bs-toggle="tooltip" 
                                            data-bs-html="true" 
                                            title="<strong>{{ e($slot['customer_name']) }}</strong> (LUNAS)<br>Kode: {{ $slot['booking_code'] }}<br>Jam: {{ $slot['start'] }} - {{ $slot['end'] }} WIB<br><em>Klik untuk rincian</em>"
                                            onclick="showSlotDetail('{{ $slot['booking_code'] }}', '{{ addslashes($slot['customer_name']) }}', '{{ $slot['customer_phone'] }}', '{{ addslashes($field->name) }}', '{{ $scheduleDateIndo }}', '{{ $slot['start'] }} - {{ $slot['end'] }} WIB', 'Lunas / Terkonfirmasi', '{{ number_format($slot['total_price'], 0, ',', '.') }}', '{{ $slot['res_id'] }}', 'confirmed', '{{ addslashes($slot['notes'] ?? '') }}')">
                                        <span class="slot-time fw-bold text-white">{{ $slot['start'] }}</span>
                                        <span class="slot-sub text-white-50"><i class="bi bi-lock-fill"></i></span>
                                    </button>
                                @else
                                    <button type="button" 
                                            class="slot-box slot-booked-pending border-0 d-flex flex-column align-items-center justify-content-center" 
                                            data-bs-toggle="tooltip" 
                                            data-bs-html="true" 
                                            title="<strong>{{ e($slot['customer_name']) }}</strong> (MENUNGGU BAYAR)<br>Kode: {{ $slot['booking_code'] }}<br>Jam: {{ $slot['start'] }} - {{ $slot['end'] }} WIB<br><em>Klik untuk rincian</em>"
                                            onclick="showSlotDetail('{{ $slot['booking_code'] }}', '{{ addslashes($slot['customer_name']) }}', '{{ $slot['customer_phone'] }}', '{{ addslashes($field->name) }}', '{{ $scheduleDateIndo }}', '{{ $slot['start'] }} - {{ $slot['end'] }} WIB', 'Menunggu Pembayaran via WhatsApp', '{{ number_format($slot['total_price'], 0, ',', '.') }}', '{{ $slot['res_id'] }}', 'pending', '{{ addslashes($slot['notes'] ?? '') }}')">
                                        <span class="slot-time fw-bold text-dark">{{ $slot['start'] }}</span>
                                        <span class="slot-sub text-dark-50"><i class="bi bi-clock-history"></i></span>
                                    </button>
                                @endif
                            @else
                                <div class="slot-box slot-past d-flex flex-column align-items-center justify-content-center" 
                                     data-bs-toggle="tooltip" 
                                     data-bs-placement="top" 
                                     title="Jam telah lewat ({{ $slot['start'] }} - {{ $slot['end'] }} WIB)">
                                    <span class="slot-time text-muted">{{ $slot['start'] }}</span>
                                    <span class="slot-sub text-muted" style="font-size: 0.65rem;">Lewat</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- 4. BAGIAN 2: MENUNGGU KONFIRMASI PEMBAYARAN -->
    @if($pendingReservations->isNotEmpty())
        <div class="card bg-white border shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-warning text-dark px-2.5 py-1 fw-bold rounded-pill">{{ $pendingReservations->count() }}</span>
                    <h2 class="h6 fw-bold text-dark mb-0">
                        <i class="bi bi-whatsapp text-success me-1"></i> Menunggu Konfirmasi Pembayaran
                    </h2>
                </div>
                <small class="text-muted">Konfirmasi pesanan setelah pembayaran via WhatsApp diterima.</small>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small text-muted">
                        <tr>
                            <th>KODE BOOKING</th>
                            <th>PEMESAN & WHATSAPP</th>
                            <th>LAPANGAN</th>
                            <th>JADWAL MAIN</th>
                            <th>TOTAL TAGIHAN</th>
                            <th>WAKTU PESAN</th>
                            <th class="text-center" style="min-width: 200px;">AKSI CEPAT</th>
                        </tr>
                    </thead>
                    <tbody class="small">
                        @foreach($pendingReservations as $rsv)
                            @php
                                $cName = trim($rsv->customer_name ?? '');
                                if (empty($cName) || strcasecmp($cName, 'admin') === 0) {
                                    $cName = ($rsv->user && !$rsv->user->isAdmin() && strcasecmp($rsv->user->name, 'admin') !== 0) ? $rsv->user->name : 'Pelanggan Tamu';
                                }
                                $cPhone = trim($rsv->customer_phone ?? '');
                                if (empty($cPhone) || $cPhone === '-') {
                                    $cPhone = ($rsv->user && !$rsv->user->isAdmin()) ? ($rsv->user->phone ?? '-') : '-';
                                }
                                $cleanPhone = preg_replace('/[^0-9]/', '', $cPhone);

                                $indoDaysShort = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
                                $indoMonthsShort = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                                $rDate = \Carbon\Carbon::parse($rsv->reservation_date);
                                $dateStr = $indoDaysShort[$rDate->dayOfWeek] . ', ' . $rDate->day . ' ' . $indoMonthsShort[$rDate->month] . ' ' . $rDate->year;
                                $timeStr = \Carbon\Carbon::parse($rsv->start_time)->format('H:i') . ' - ' . \Carbon\Carbon::parse($rsv->end_time)->format('H:i') . ' WIB';
                            @endphp
                            <tr>
                                <td>
                                    <span class="fw-bold font-monospace text-dark fs-6">{{ $rsv->code }}</span>
                                    @if($rsv->notes)
                                        <div class="text-muted" style="font-size: 0.72rem;" title="{{ $rsv->notes }}">
                                            <i class="bi bi-chat-left-text me-1"></i>{{ Str::limit($rsv->notes, 25) }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong class="text-dark d-block">{{ $cName }}</strong>
                                    @if($cleanPhone)
                                        <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" rel="noopener noreferrer" class="text-success text-decoration-none fw-semibold" style="font-size: 0.78rem;">
                                            <i class="bi bi-whatsapp me-1"></i> {{ $cPhone }}
                                        </a>
                                    @else
                                        <span class="text-muted" style="font-size: 0.78rem;">-</span>
                                    @endif
                                </td>
                                <td>
                                    <strong class="text-dark">{{ $rsv->field->name }}</strong>
                                    <span class="badge bg-light text-dark border ms-1" style="font-size: 0.68rem;">{{ ucfirst($rsv->field->type) }}</span>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $dateStr }}</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">{{ $timeStr }}</div>
                                </td>
                                <td>
                                    <strong class="text-success fs-6">Rp {{ number_format($rsv->total_price, 0, ',', '.') }}</strong>
                                    <div class="text-muted" style="font-size: 0.72rem;">({{ $rsv->duration_hours }} Jam)</div>
                                </td>
                                <td class="text-muted" style="font-size: 0.75rem;">
                                    {{ $rsv->created_at->diffForHumans() }}
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center align-items-center gap-1.5">
                                        <!-- Tombol Konfirmasi Lunas -->
                                        <form action="{{ route('admin.reservations.status', $rsv->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Konfirmasi LUNAS pesanan {{ $rsv->code }} dan kunci jadwal?')">
                                            @csrf
                                            <input type="hidden" name="status" value="confirmed">
                                            <button type="submit" class="btn btn-sm btn-success fw-bold px-2.5 py-1 shadow-sm d-flex align-items-center gap-1" title="Konfirmasi Lunas">
                                                <i class="bi bi-check-circle-fill"></i> Lunas
                                            </button>
                                        </form>

                                        <!-- Tombol Batalkan -->
                                        <form action="{{ route('admin.reservations.status', $rsv->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Batalkan pesanan {{ $rsv->code }} dan buka slot jam?')">
                                            @csrf
                                            <input type="hidden" name="status" value="cancelled">
                                            <button type="submit" class="btn btn-sm btn-outline-danger fw-semibold px-2 py-1" title="Batalkan Pesanan">
                                                <i class="bi bi-x-circle"></i> Batal
                                            </button>
                                        </form>

                                        <!-- Tombol Edit / Reschedule -->
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-warning text-dark px-2 py-1" 
                                                title="Edit / Reschedule Jadwal"
                                                data-id="{{ $rsv->id }}"
                                                data-code="{{ $rsv->code }}"
                                                data-name="{{ $cName }}"
                                                data-phone="{{ $cPhone !== '-' ? $cPhone : '' }}"
                                                data-field-id="{{ $rsv->field_id }}"
                                                data-date="{{ \Carbon\Carbon::parse($rsv->reservation_date)->format('Y-m-d') }}"
                                                data-start="{{ \Carbon\Carbon::parse($rsv->start_time)->format('H:i') }}"
                                                data-duration="{{ $rsv->duration_hours ?: $rsv->calculateDuration() }}"
                                                data-status="{{ $rsv->status }}"
                                                data-total="{{ (int)$rsv->total_price }}"
                                                data-notes="{{ $rsv->notes ?? '' }}"
                                                onclick="openRescheduleModal(this)">
                                            <i class="bi bi-pencil-square text-warning-emphasis"></i>
                                        </button>

                                        <!-- Tombol Struk -->
                                        <a href="{{ route('reservations.ticket', $rsv->id) }}?code={{ $rsv->code }}" target="_blank" class="btn btn-sm btn-outline-secondary px-2 py-1" title="Cetak Struk">
                                            <i class="bi bi-printer"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <!-- Keadaan Kosong Ringkas (1 Baris Teks Kecil Saja) -->
        <div class="p-3 bg-white rounded-3 border shadow-sm text-muted small mb-4 d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill text-success fs-5"></i>
            <span>Tidak ada pesanan yang menunggu pembayaran saat ini. Semua reservasi telah terkonfirmasi lunas atau selesai.</span>
        </div>
    @endif

    <!-- 4. BAGIAN 2: DAFTAR RESERVASI & JADWAL MAIN -->
    <div class="card bg-white border shadow-sm">
        <div class="card-header bg-white border-bottom py-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
            <div>
                <h2 class="h6 fw-bold text-dark mb-0">
                    <i class="bi bi-calendar2-range text-success me-1.5"></i> Daftar Reservasi & Jadwal Main
                </h2>
                <small class="text-muted">Data reservasi terbaru yang tercatat di sistem.</small>
            </div>
            <!-- Filter Cepat: Semua / Hari Ini -->
            <div class="btn-group p-1 bg-light border rounded-3" role="group" aria-label="Filter Reservasi">
                <button type="button" class="btn btn-sm btn-outline-secondary active rsv-tab-btn" data-filter="all">
                    Semua ({{ $recentReservations->count() }})
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary rsv-tab-btn" data-filter="today">
                    Hari Ini ({{ $recentReservations->filter(fn($r) => \Carbon\Carbon::parse($r->reservation_date)->isToday())->count() }})
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="reservationsTable">
                <thead class="table-light small text-muted">
                    <tr>
                        <th>KODE</th>
                        <th>PEMESAN & WHATSAPP</th>
                        <th>LAPANGAN</th>
                        <th>TANGGAL & JAM MAIN</th>
                        <th>STATUS</th>
                        <th>TOTAL</th>
                        <th class="text-end" style="min-width: 140px;">AKSI</th>
                    </tr>
                </thead>
                <tbody class="small">
                    @forelse($recentReservations as $rsv)
                        @php
                            $cName = trim($rsv->customer_name ?? '');
                            if (empty($cName) || strcasecmp($cName, 'admin') === 0) {
                                $cName = ($rsv->user && !$rsv->user->isAdmin() && strcasecmp($rsv->user->name, 'admin') !== 0) ? $rsv->user->name : 'Pelanggan Tamu';
                            }
                            $cPhone = trim($rsv->customer_phone ?? '');
                            if (empty($cPhone) || $cPhone === '-') {
                                $cPhone = ($rsv->user && !$rsv->user->isAdmin()) ? ($rsv->user->phone ?? '-') : '-';
                            }
                            $cleanPhone = preg_replace('/[^0-9]/', '', $cPhone);

                            $indoDaysShort = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
                            $indoMonthsShort = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                            $rDate = \Carbon\Carbon::parse($rsv->reservation_date);
                            $isToday = $rDate->isToday();
                            $dateStr = $indoDaysShort[$rDate->dayOfWeek] . ', ' . $rDate->day . ' ' . $indoMonthsShort[$rDate->month] . ' ' . $rDate->year;
                            $timeStr = \Carbon\Carbon::parse($rsv->start_time)->format('H:i') . ' - ' . \Carbon\Carbon::parse($rsv->end_time)->format('H:i') . ' WIB';
                        @endphp
                        <tr class="rsv-row-item" data-today="{{ $isToday ? '1' : '0' }}">
                            <td>
                                <strong class="font-monospace text-dark">{{ $rsv->code }}</strong>
                            </td>
                            <td>
                                <strong class="text-dark d-block">{{ $cName }}</strong>
                                @if($cleanPhone)
                                    <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" rel="noopener noreferrer" class="text-success text-decoration-none fw-semibold" style="font-size: 0.78rem;">
                                        <i class="bi bi-whatsapp me-1"></i> {{ $cPhone }}
                                    </a>
                                @else
                                    <span class="text-muted" style="font-size: 0.78rem;">-</span>
                                @endif
                            </td>
                            <td>
                                <strong class="text-dark">{{ $rsv->field->name }}</strong>
                                <span class="badge bg-light text-dark border ms-1" style="font-size: 0.68rem;">{{ ucfirst($rsv->field->type) }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $dateStr }}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">{{ $timeStr }} ({{ $rsv->duration_hours }} Jam)</div>
                            </td>
                            <td>
                                @if($rsv->status === 'confirmed')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle py-1 px-2">
                                        <i class="bi bi-patch-check-fill me-1"></i> Lunas
                                    </span>
                                @elseif($rsv->status === 'cancelled')
                                    <span class="badge bg-danger-subtle text-danger border border-danger py-1 px-2">
                                        <i class="bi bi-x-circle me-1"></i> Batal
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle py-1 px-2">
                                        <i class="bi bi-clock-history me-1"></i> Menunggu
                                    </span>
                                @endif
                            </td>
                            <td>
                                <strong class="text-dark">Rp {{ number_format($rsv->total_price, 0, ',', '.') }}</strong>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1 align-items-center">
                                    @if($rsv->status === 'pending')
                                        <form action="{{ route('admin.reservations.status', $rsv->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Konfirmasi Lunas {{ $rsv->code }}?')">
                                            @csrf
                                            <input type="hidden" name="status" value="confirmed">
                                            <button type="submit" class="btn btn-sm btn-success py-0.5 px-2 fw-semibold" title="Konfirmasi Lunas">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.reservations.status', $rsv->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Batalkan pesanan {{ $rsv->code }}?')">
                                            @csrf
                                            <input type="hidden" name="status" value="cancelled">
                                            <button type="submit" class="btn btn-sm btn-outline-danger py-0.5 px-1.5" title="Batalkan">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-warning text-dark py-0.5 px-2" 
                                            title="Edit / Reschedule Jadwal"
                                            data-id="{{ $rsv->id }}"
                                            data-code="{{ $rsv->code }}"
                                            data-name="{{ $cName }}"
                                            data-phone="{{ $cPhone !== '-' ? $cPhone : '' }}"
                                            data-field-id="{{ $rsv->field_id }}"
                                            data-date="{{ \Carbon\Carbon::parse($rsv->reservation_date)->format('Y-m-d') }}"
                                            data-start="{{ \Carbon\Carbon::parse($rsv->start_time)->format('H:i') }}"
                                            data-duration="{{ $rsv->duration_hours ?: $rsv->calculateDuration() }}"
                                            data-status="{{ $rsv->status }}"
                                            data-total="{{ (int)$rsv->total_price }}"
                                            data-notes="{{ $rsv->notes ?? '' }}"
                                            onclick="openRescheduleModal(this)">
                                        <i class="bi bi-pencil-square text-warning-emphasis"></i>
                                    </button>
                                    <a href="{{ route('reservations.ticket', $rsv->id) }}?code={{ $rsv->code }}" class="btn btn-sm btn-outline-secondary py-0.5 px-2" target="_blank" title="Cetak Tiket/Struk">
                                        <i class="bi bi-printer"></i>
                                    </a>
                                    <a href="{{ route('admin.reservations.show', $rsv->id) }}" class="btn btn-sm btn-outline-primary py-0.5 px-2" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada data reservasi tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Rincian Booking Slot -->
    <div class="modal fade" id="slotDetailModal" tabindex="-1" aria-labelledby="slotDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <div class="modal-header border-bottom py-3 px-4">
                    <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="slotDetailModalLabel">
                        <i class="bi bi-ticket-detailed text-success"></i> Rincian Pesanan Slot
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem;">Kode Booking</small>
                            <span class="fs-5 fw-bold font-monospace text-dark" id="modalBookingCode">-</span>
                        </div>
                        <span id="modalStatusBadge" class="badge bg-success-subtle text-success border border-success-subtle py-1.5 px-2.5 fw-semibold">-</span>
                    </div>

                    <div class="row g-3 small">
                        <div class="col-6">
                            <span class="text-muted d-block">Nama Pemesan:</span>
                            <strong class="text-dark fs-6" id="modalCustomerName">-</strong>
                        </div>
                        <div class="col-6">
                            <span class="text-muted d-block">No. WhatsApp:</span>
                            <span id="modalCustomerPhone" class="fs-6">-</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted d-block">Lapangan:</span>
                            <strong class="text-dark" id="modalFieldName">-</strong>
                        </div>
                        <div class="col-6">
                            <span class="text-muted d-block">Total Tarif:</span>
                            <strong class="text-success fs-6" id="modalTotalPrice">-</strong>
                        </div>
                        <div class="col-12">
                            <span class="text-muted d-block">Jadwal Main:</span>
                            <strong class="text-dark" id="modalScheduleTime">-</strong>
                        </div>
                        <div class="col-12" id="modalNotesContainer" style="display: none;">
                            <span class="text-muted d-block mb-1">Catatan Pesanan:</span>
                            <div class="p-2.5 rounded bg-light border text-muted small" id="modalNotes">-</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-2.5 px-4 bg-light d-flex justify-content-between">
                    <a href="#" id="modalWaBtn" target="_blank" rel="noopener noreferrer" class="btn btn-outline-success btn-sm fw-semibold">
                        <i class="bi bi-whatsapp me-1"></i> Hubungi WhatsApp
                    </a>
                    <div class="d-flex gap-2" id="modalActionButtons">
                        <a href="#" id="modalTicketBtn" target="_blank" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-printer me-1"></i> Cetak Bukti
                        </a>
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit & Reschedule (Pindah Jadwal) Pesanan -->
    <div class="modal fade" id="rescheduleModal" tabindex="-1" aria-labelledby="rescheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <div class="modal-header bg-light border-bottom py-3 px-4">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle bg-warning-subtle text-warning-emphasis p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="bi bi-calendar-event fs-5"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold text-dark mb-0" id="rescheduleModalLabel">
                                Edit & Pindah Jadwal Pesanan
                            </h5>
                            <small class="text-muted">Kode Pesanan: <strong class="font-monospace text-primary" id="rescheduleModalCode">-</strong></small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                
                <form id="rescheduleForm" action="" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="modal-body p-4">
                        <div class="alert alert-info py-2.5 px-3 small d-flex align-items-center gap-2 mb-3 border-0" style="background-color: #EFF6FF; border-left: 4px solid #3B82F6 !important; color: #1E40AF;">
                            <i class="bi bi-info-circle-fill flex-shrink-0 fs-5"></i>
                            <div>
                                Anda dapat memperbarui data kontak pemesan, memindahkan lapangan, tanggal, jam main, serta durasi. Sistem otomatis memvalidasi bentrok jadwal dan menghitung ulang total tagihan.
                            </div>
                        </div>

                        <div class="row g-3">
                            <!-- Data Pemesan -->
                            <div class="col-md-6">
                                <label for="edit_customer_name" class="form-label small fw-bold text-dark mb-1">
                                    <i class="bi bi-person text-success me-1"></i> Nama Pemesan <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="customer_name" id="edit_customer_name" class="form-control" placeholder="Nama Lengkap" required>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_customer_phone" class="form-label small fw-bold text-dark mb-1">
                                    <i class="bi bi-whatsapp text-success me-1"></i> No. WhatsApp <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="customer_phone" id="edit_customer_phone" class="form-control" placeholder="081234567890" required>
                            </div>

                            <div class="col-12"><hr class="my-1 text-muted"></div>

                            <!-- Pilihan Lapangan & Tanggal -->
                            <div class="col-md-6">
                                <label for="edit_field_id" class="form-label small fw-bold text-dark mb-1">
                                    <i class="bi bi-geo-alt text-success me-1"></i> Pilihan Lapangan <span class="text-danger">*</span>
                                </label>
                                <select name="field_id" id="edit_field_id" class="form-select fw-semibold" required onchange="calculateRescheduleTotal()">
                                    @foreach($fieldsForSchedule ?? $availableFields as $f)
                                        <option value="{{ $f->id }}" data-price="{{ $f->price_per_hour }}">
                                            {{ $f->name }} ({{ ucfirst($f->type) }}) — Rp {{ number_format($f->price_per_hour, 0, ',', '.') }} / jam
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="edit_reservation_date" class="form-label small fw-bold text-dark mb-1">
                                    <i class="bi bi-calendar3 text-success me-1"></i> Tanggal Main <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="reservation_date" id="edit_reservation_date" class="form-control fw-semibold" required>
                            </div>

                            <!-- Jam Mulai & Durasi -->
                            <div class="col-md-6">
                                <label for="edit_start_time" class="form-label small fw-bold text-dark mb-1">
                                    <i class="bi bi-clock text-success me-1"></i> Jam Mulai Main <span class="text-danger">*</span>
                                </label>
                                <select name="start_time" id="edit_start_time" class="form-select fw-semibold" required onchange="calculateRescheduleTotal()">
                                    @for($hour = 8; $hour <= 22; $hour++)
                                        @php $hStr = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00'; @endphp
                                        <option value="{{ $hStr }}">{{ $hStr }} WIB</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="edit_duration_hours" class="form-label small fw-bold text-dark mb-1">
                                    <i class="bi bi-hourglass-split text-success me-1"></i> Durasi Jam Bermain <span class="text-danger">*</span>
                                </label>
                                <select name="duration_hours" id="edit_duration_hours" class="form-select fw-semibold" required onchange="calculateRescheduleTotal()">
                                    <option value="1">1 Jam</option>
                                    <option value="2">2 Jam</option>
                                    <option value="3">3 Jam</option>
                                    <option value="4">4 Jam</option>
                                    <option value="5">5 Jam</option>
                                </select>
                            </div>

                            <!-- Status Pembayaran & Estimasi Baru -->
                            <div class="col-md-6">
                                <label for="edit_status" class="form-label small fw-bold text-dark mb-1">
                                    <i class="bi bi-cash-coin text-success me-1"></i> Status Pembayaran <span class="text-danger">*</span>
                                </label>
                                <select name="status" id="edit_status" class="form-select fw-semibold" required>
                                    <option value="pending">Menunggu Pembayaran</option>
                                    <option value="confirmed">Lunas (Terkonfirmasi)</option>
                                    <option value="cancelled">Dibatalkan</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-dark mb-1">
                                    <i class="bi bi-calculator text-success me-1"></i> Estimasi Jam & Total Biaya Baru
                                </label>
                                <div class="p-2.5 rounded border bg-light d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="small text-muted" id="edit_time_range_label">-</div>
                                        <div class="fw-bold text-success fs-6" id="edit_total_price_label">Rp 0</div>
                                    </div>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle small">Otomatis Dihitung</span>
                                </div>
                            </div>

                            <!-- Catatan Tambahan -->
                            <div class="col-12">
                                <label for="edit_notes" class="form-label small fw-bold text-dark mb-1">
                                    <i class="bi bi-sticky text-muted me-1"></i> Catatan Tambahan / Alasan Reschedule
                                </label>
                                <textarea name="notes" id="edit_notes" class="form-control" rows="2" placeholder="Contoh: Permintaan pelanggan pindah dari Futsal 1 ke Futsal 2"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light border-top py-2.5 px-4 d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary btn-sm px-4 fw-bold shadow-sm d-flex align-items-center gap-1.5">
                            <i class="bi bi-check-circle-fill"></i> Simpan Perubahan Jadwal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<style>
.schedule-slots-track::-webkit-scrollbar {
    height: 4px;
}
.schedule-slots-track::-webkit-scrollbar-track {
    background: #F1F5F9;
}
.schedule-slots-track::-webkit-scrollbar-thumb {
    background: #CBD5E1;
    border-radius: 4px;
}
.slot-box {
    min-width: 54px;
    width: 54px;
    height: 44px;
    border-radius: 8px;
    padding: 3px 2px;
    text-align: center;
    transition: all 0.15s ease;
    flex-shrink: 0;
    user-select: none;
}
.slot-time {
    font-size: 0.72rem;
    line-height: 1.15;
    display: block;
}
.slot-sub {
    font-size: 0.65rem;
    line-height: 1;
    display: block;
}
.slot-in-play {
    background: #2563EB !important;
    border: 1px solid #1D4ED8 !important;
    color: #FFFFFF !important;
    box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.25);
    animation: pulsePlay 2s infinite ease-in-out;
}
.slot-in-play:hover {
    background: #1D4ED8 !important;
    transform: translateY(-2px);
    box-shadow: 0 3px 8px rgba(37, 99, 235, 0.4) !important;
}
@keyframes pulsePlay {
    0%, 100% {
        box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.5);
    }
    50% {
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0);
    }
}
.slot-available {
    background: #ECFDF5;
    border: 1px solid #A7F3D0;
    color: #065F46;
}
.slot-available:hover {
    background: #10B981;
    border-color: #059669;
    color: #FFFFFF !important;
    transform: translateY(-2px);
    box-shadow: 0 3px 6px rgba(16, 185, 129, 0.25);
}
.slot-available:hover .slot-sub {
    color: #FFFFFF !important;
}
.slot-booked-confirmed {
    background: #EF4444;
    border: 1px solid #DC2626;
    color: #FFFFFF;
}
.slot-booked-confirmed:hover {
    background: #DC2626;
    transform: translateY(-2px);
    box-shadow: 0 3px 6px rgba(239, 68, 68, 0.3);
}
.slot-booked-pending {
    background: #F59E0B;
    border: 1px solid #D97706;
    color: #111827;
}
.slot-booked-pending:hover {
    background: #D97706;
    color: #FFFFFF !important;
    transform: translateY(-2px);
    box-shadow: 0 3px 6px rgba(245, 158, 11, 0.3);
}
.slot-booked-pending:hover .slot-time,
.slot-booked-pending:hover .slot-sub {
    color: #FFFFFF !important;
}
.slot-past {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    color: #94A3B8;
    opacity: 0.75;
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Tab filter untuk daftar reservasi
    const filterButtons = document.querySelectorAll('.rsv-tab-btn');
    const tableRows = document.querySelectorAll('.rsv-row-item');

    filterButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            filterButtons.forEach(b => {
                b.classList.remove('active');
                b.classList.remove('btn-primary');
                b.classList.add('btn-outline-secondary');
            });
            this.classList.add('active');

            const filterType = this.dataset.filter;
            tableRows.forEach(row => {
                const isToday = row.dataset.today === '1';
                if (filterType === 'all' || (filterType === 'today' && isToday)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });

    // 2. Inisialisasi Tooltip Bootstrap untuk setiap slot
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// 3. Fungsi Tampilkan Modal Rincian Booking Slot
function showSlotDetail(code, customer, phone, field, dateStr, timeStr, status, price, resId, resStatus, notes) {
    document.getElementById('modalBookingCode').textContent = code;
    document.getElementById('modalCustomerName').textContent = customer;
    document.getElementById('modalFieldName').textContent = field;
    document.getElementById('modalScheduleTime').textContent = dateStr + ' • ' + timeStr;
    document.getElementById('modalTotalPrice').textContent = 'Rp ' + price;

    const cleanPhone = phone ? phone.replace(/[^0-9]/g, '') : '';
    const phoneEl = document.getElementById('modalCustomerPhone');
    const waBtn = document.getElementById('modalWaBtn');
    if (cleanPhone && cleanPhone.length >= 6) {
        phoneEl.innerHTML = '<a href="https://wa.me/' + cleanPhone + '" target="_blank" class="fw-semibold text-success"><i class="bi bi-whatsapp me-1"></i>' + phone + '</a>';
        waBtn.href = 'https://wa.me/' + cleanPhone + '?text=' + encodeURIComponent('Halo ' + customer + ', kami dari SM Sport Center ingin konfirmasi booking Anda dengan Kode ' + code + ' untuk lapangan ' + field + ' (' + timeStr + ').');
        waBtn.style.display = 'inline-flex';
    } else {
        phoneEl.textContent = phone || '-';
        waBtn.style.display = 'none';
    }

    const badge = document.getElementById('modalStatusBadge');
    if (resStatus === 'in_play') {
        badge.className = 'badge text-white py-1.5 px-2.5 fw-semibold border-0';
        badge.style.backgroundColor = '#2563EB';
        badge.innerHTML = '<i class="bi bi-play-circle-fill me-1"></i> Sedang Dimainkan (Lunas)';
    } else if (resStatus === 'confirmed') {
        badge.className = 'badge bg-success-subtle text-success border border-success-subtle py-1.5 px-2.5 fw-semibold';
        badge.style.backgroundColor = '';
        badge.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Lunas / Terkonfirmasi';
    } else {
        badge.className = 'badge bg-warning-subtle text-warning-emphasis border border-warning-subtle py-1.5 px-2.5 fw-semibold';
        badge.style.backgroundColor = '';
        badge.innerHTML = '<i class="bi bi-clock-history me-1"></i> Menunggu Bayar WA';
    }

    const notesContainer = document.getElementById('modalNotesContainer');
    const notesEl = document.getElementById('modalNotes');
    if (notes && notes.trim() !== '') {
        notesContainer.style.display = 'block';
        notesEl.textContent = notes;
    } else {
        notesContainer.style.display = 'none';
    }

    document.getElementById('modalTicketBtn').href = '/reservations/' + resId + '/ticket?code=' + code;

    var detailModal = new bootstrap.Modal(document.getElementById('slotDetailModal'));
    detailModal.show();
}

// 4. Fungsi Tampilkan Modal Edit & Reschedule Pesanan
function openRescheduleModal(btn) {
    const id = btn.getAttribute('data-id');
    const code = btn.getAttribute('data-code');
    const name = btn.getAttribute('data-name');
    const phone = btn.getAttribute('data-phone');
    const fieldId = btn.getAttribute('data-field-id');
    const date = btn.getAttribute('data-date');
    const start = btn.getAttribute('data-start');
    const duration = btn.getAttribute('data-duration');
    const status = btn.getAttribute('data-status');
    const notes = btn.getAttribute('data-notes');

    document.getElementById('rescheduleModalCode').textContent = code;
    document.getElementById('rescheduleForm').action = '/admin/reservations/' + id + '/reschedule';
    document.getElementById('edit_customer_name').value = name || '';
    document.getElementById('edit_customer_phone').value = phone || '';
    document.getElementById('edit_field_id').value = fieldId;
    document.getElementById('edit_reservation_date').value = date;
    
    // Normalisasi jam mulai ke format HH:00
    let startHour = '08:00';
    if (start) {
        const parts = start.split(':');
        const h = parseInt(parts[0], 10);
        startHour = (h < 10 ? '0' : '') + h + ':00';
    }
    document.getElementById('edit_start_time').value = startHour;
    document.getElementById('edit_duration_hours').value = duration || '1';
    document.getElementById('edit_status').value = status || 'confirmed';
    document.getElementById('edit_notes').value = notes || '';

    calculateRescheduleTotal();

    var rescheduleModalEl = document.getElementById('rescheduleModal');
    var modalInstance = bootstrap.Modal.getInstance(rescheduleModalEl) || new bootstrap.Modal(rescheduleModalEl);
    modalInstance.show();
}

// 5. Hitung Estimasi Rentang Jam & Total Biaya Baru
function calculateRescheduleTotal() {
    const fieldSelect = document.getElementById('edit_field_id');
    const selectedOption = fieldSelect ? fieldSelect.options[fieldSelect.selectedIndex] : null;
    const pricePerHour = selectedOption ? parseFloat(selectedOption.getAttribute('data-price') || 0) : 0;
    
    const durationSelect = document.getElementById('edit_duration_hours');
    const duration = parseInt(durationSelect ? durationSelect.value : 1, 10) || 1;

    const startTimeSelect = document.getElementById('edit_start_time');
    const startVal = startTimeSelect ? (startTimeSelect.value || '08:00') : '08:00';
    const startHourInt = parseInt(startVal.split(':')[0], 10) || 8;
    const endHourInt = startHourInt + duration;
    const endHourStr = (endHourInt < 10 ? '0' : '') + endHourInt + ':00';

    const total = pricePerHour * duration;

    const timeLabel = document.getElementById('edit_time_range_label');
    const priceLabel = document.getElementById('edit_total_price_label');
    if (timeLabel) {
        timeLabel.textContent = startVal + ' - ' + endHourStr + ' WIB (' + duration + ' Jam)';
    }
    if (priceLabel) {
        priceLabel.textContent = 'Rp ' + total.toLocaleString('id-ID');
    }
}
</script>
@endpush
@endsection
