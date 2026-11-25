@extends('layouts.app')

@section('title', 'Pilih Jadwal & Pesan Lapangan - SM Sport Center')

@section('content')
<div class="container py-3">

    <!-- Header & Tombol Kembali -->
    <div class="mb-3">
        <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm mb-1.5">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
        </a>
        <h1 class="h4 fw-bold text-dark mb-0">Pilih Jadwal & Pesan Lapangan</h1>
        <p class="text-muted small mb-0">Pemesanan instan tanpa perlu mendaftar akun.</p>
    </div>

    <!-- Stepper 3 Langkah Bersih -->
    <div class="card border-0 mb-4 shadow-sm" style="background: #F0FDF4; border: 1px solid #DCFCE7 !important;">
        <div class="card-body py-2.5 px-3 px-sm-4">
            <div class="row text-center g-2 align-items-center">
                <div class="col-4">
                    <div class="d-flex align-items-center justify-content-center gap-1.5">
                        <span class="badge bg-success text-white rounded-circle p-1 d-inline-flex align-items-center justify-content-center" style="width: 22px; height: 22px; font-size: 0.72rem;">1</span>
                        <strong class="text-success small">1. Jadwal</strong>
                    </div>
                </div>
                <div class="col-4 border-start border-end">
                    <div class="d-flex align-items-center justify-content-center gap-1.5">
                        <span class="badge bg-secondary-subtle text-muted rounded-circle p-1 d-inline-flex align-items-center justify-content-center border" style="width: 22px; height: 22px; font-size: 0.72rem;">2</span>
                        <span class="text-muted small">2. Data Diri</span>
                    </div>
                </div>
                <div class="col-4">
                    <div class="d-flex align-items-center justify-content-center gap-1.5 text-muted">
                        <span class="badge bg-secondary-subtle text-muted rounded-circle p-1 d-inline-flex align-items-center justify-content-center border" style="width: 22px; height: 22px; font-size: 0.72rem;">3</span>
                        <span class="small">3. Bayar via WA</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger p-3 mb-4 small border-0 shadow-sm" style="background-color: #FEF2F2; border-left: 4px solid #EF4444 !important; color: #991B1B;">
            <div class="d-flex align-items-center gap-2 mb-1">
                <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
                <strong>Perhatian:</strong>
            </div>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('reservations.store') }}" method="POST" id="bookingForm">
        @csrf
        <input type="hidden" name="start_time" id="hiddenStartTime" value="{{ old('start_time') }}">
        <input type="hidden" name="end_time" id="hiddenEndTime" value="{{ old('end_time') }}">

        <div class="row g-4">
            <!-- Kolom Kiri: Langkah 1 & Langkah 2 -->
            <div class="col-lg-7 col-xl-8">

                <!-- LANGKAH 1: PILIH LAPANGAN & TANGGAL HORIZONTAL ALA AYO -->
                <div class="card mb-4 shadow-sm border bg-white" id="stepPilihJadwal">
                    <div class="card-header bg-white border-bottom py-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 22px; height: 22px; font-size: 0.72rem;">1</span>
                            <h2 class="h6 fw-bold mb-0 text-dark">Langkah 1: Tentukan Lapangan, Tanggal & Jam Main</h2>
                        </div>
                    </div>
                    <div class="card-body p-3 p-md-4">
                        <!-- 1.1 Dropdown Pemilihan Lapangan -->
                        <div class="mb-3">
                            <label for="fieldSelect" class="form-label small fw-bold text-dark mb-1">
                                <i class="bi bi-geo-alt text-success me-1"></i> Pilih Lapangan:
                            </label>
                            <select name="field_id" id="fieldSelect" class="form-select fw-semibold @error('field_id') is-invalid @enderror" required>
                                @foreach($fields as $f)
                                    <option value="{{ $f->id }}" 
                                            data-name="{{ $f->name }}" 
                                            data-type="{{ ucfirst($f->type) }}" 
                                            data-price="{{ $f->price_per_hour }}"
                                            @if(old('field_id', request('field_id')) == $f->id || ($loop->first && !request('field_id') && !old('field_id'))) selected @endif>
                                        {{ $f->name }} ({{ ucfirst($f->type) }}) — Rp {{ number_format($f->price_per_hour, 0, ',', '.') }} / jam
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- 1.2 Barisan Kotak Tanggal Horizontal ala AYO -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label small fw-bold text-dark mb-0">
                                    <i class="bi bi-calendar3 text-success me-1"></i> Pilih Tanggal Bermain:
                                </label>
                                <button type="button" class="btn btn-link btn-sm text-decoration-none text-success p-0 small fw-semibold" id="btnToggleDatePicker">
                                    <i class="bi bi-calendar-event me-1"></i> Kalender Lengkap
                                </button>
                            </div>

                            <!-- Horizontal Scroll Bar Tanggal -->
                            @php
                                $indoDays = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
                                $indoMonths = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                                $initialDate = old('reservation_date', request('date', date('Y-m-d')));
                            @endphp
                            <div class="date-scroll-container pb-2" id="dateScrollContainer">
                                @for($i = 0; $i < 14; $i++)
                                    @php
                                        $d = \Carbon\Carbon::today()->addDays($i);
                                        $dVal = $d->format('Y-m-d');
                                        $dayOfWeek = $indoDays[$d->dayOfWeek];
                                        $dayNum = $d->format('d');
                                        $monthName = $indoMonths[$d->month];
                                        $labelTop = ($i === 0) ? 'Hari Ini' : (($i === 1) ? 'Besok' : $dayOfWeek);
                                        $isActive = ($dVal === $initialDate);
                                    @endphp
                                    <button type="button" 
                                            class="date-card-btn {{ $isActive ? 'active' : '' }}" 
                                            data-date="{{ $dVal }}">
                                        <span class="date-day">{{ $labelTop }}</span>
                                        <span class="date-num">{{ $dayNum }}</span>
                                        <span class="date-month">{{ $monthName }}</span>
                                    </button>
                                @endfor
                            </div>

                            <!-- Input Date Fallback / Custom Date Picker -->
                            <div id="customDatePickerWrapper" class="mt-2 d-none">
                                <div class="input-group input-group-sm" style="max-width: 260px;">
                                    <span class="input-group-text bg-white"><i class="bi bi-calendar"></i></span>
                                    <input type="date" 
                                           name="reservation_date" 
                                           id="dateInput" 
                                           class="form-control" 
                                           value="{{ $initialDate }}" 
                                           min="{{ date('Y-m-d') }}" 
                                           required>
                                </div>
                            </div>
                            <input type="hidden" id="hiddenDateVal" value="{{ $initialDate }}">
                        </div>

                        <!-- 1.3 Slot Jam Interaktif Ala AYO (08:00 - 23:00 WIB) -->
                        <div class="mb-2">
                            <div class="d-flex flex-wrap justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                <div>
                                    <span class="fw-bold small text-dark d-block">Slot Jam (08:00 - 23:00 WIB)</span>
                                    <small class="text-muted">Pilih 1 atau beberapa jam berurutan.</small>
                                </div>
                                <div class="d-flex align-items-center gap-2 mt-1 mt-sm-0">
                                    <button type="button" id="btnResetSlots" class="btn btn-outline-danger btn-sm py-0 px-2 d-none" style="font-size: 0.75rem;">
                                        Reset Jam
                                    </button>
                                    <div class="d-flex align-items-center gap-2 small text-muted" style="font-size: 0.75rem;">
                                        <span class="d-inline-flex align-items-center gap-1">
                                            <span style="width: 10px; height: 10px; background: #FFFFFF; border: 1px solid #CBD5E1; border-radius: 2px;"></span> Tersedia
                                        </span>
                                        <span class="d-inline-flex align-items-center gap-1">
                                            <span style="width: 10px; height: 10px; background: #059669; border-radius: 2px;"></span> Terpilih
                                        </span>
                                        <span class="d-inline-flex align-items-center gap-1">
                                            <span style="width: 10px; height: 10px; background: #FEF2F2; border: 1px solid #FECACA; border-radius: 2px;"></span> Terisi
                                        </span>
                                        <span class="d-inline-flex align-items-center gap-1">
                                            <span style="width: 10px; height: 10px; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 2px;"></span> Lewat
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Grid Kotak Kartu Kecil Slot Jam Ala AYO -->
                            <div id="slotGridContainer" class="row row-cols-2 row-cols-sm-3 row-cols-md-4 g-2.5 mb-2">
                                <div class="col-12 text-center py-4 text-muted small">
                                    <div class="spinner-border spinner-border-sm text-success me-2" role="status"></div>
                                    Memuat ketersediaan slot jam...
                                </div>
                            </div>

                            <!-- Alert Box Validasi Pemilihan Jam -->
                            <div id="slotAlertBox" class="alert alert-warning py-2 px-3 small d-none mt-2 mb-0"></div>
                        </div>
                    </div>
                </div>

                <!-- LANGKAH 2: DATA PEMESAN (GUEST TANPA LOGIN) -->
                <div class="card mb-4 shadow-sm border bg-white" id="stepDataPemesan">
                    <div class="card-header bg-white border-bottom py-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 22px; height: 22px; font-size: 0.72rem;">2</span>
                            <h2 class="h6 fw-bold mb-0 text-dark">Langkah 2: Data Pemesan (Tanpa Login)</h2>
                        </div>
                    </div>
                    <div class="card-body p-3 p-md-4">
                        @if(!auth()->check() || auth()->user()->isAdmin())
                            @if(auth()->check() && auth()->user()->isAdmin())
                                <div class="alert alert-info py-2 px-3 small mb-3 border-0 bg-info bg-opacity-10 text-info-emphasis d-flex align-items-center gap-2">
                                    <i class="bi bi-shield-check fs-5"></i>
                                    <div>
                                        <strong>Mode Booking Admin:</strong> Masukkan nama dan nomor WhatsApp pelanggan yang akan bermain di lapangan.
                                    </div>
                                </div>
                            @endif
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="customer_name" class="form-label small fw-bold text-dark mb-1">
                                        Nama Lengkap Pelanggan <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           name="customer_name" 
                                           id="customer_name" 
                                           class="form-control @error('customer_name') is-invalid @enderror" 
                                           placeholder="Contoh: Budi Santoso" 
                                           value="{{ old('customer_name') }}" 
                                           required>
                                    <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">Nama penanggung jawab saat main di lapangan.</small>
                                </div>

                                <div class="col-md-6">
                                    <label for="customer_phone" class="form-label small fw-bold text-dark mb-1">
                                        Nomor WhatsApp Aktif <span class="text-danger">*</span>
                                    </label>
                                    <input type="tel" 
                                           name="customer_phone" 
                                           id="customer_phone" 
                                           class="form-control @error('customer_phone') is-invalid @enderror" 
                                           placeholder="Contoh: 081234567890" 
                                           value="{{ old('customer_phone') }}" 
                                           required>
                                    <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">Digunakan untuk konfirmasi jadwal & cek pesanan.</small>
                                </div>

                                <div class="col-12">
                                    <label for="notes" class="form-label small fw-bold text-dark mb-1">
                                        Catatan Tambahan (Opsional)
                                    </label>
                                    <input type="text" 
                                           name="notes" 
                                           id="notes" 
                                           class="form-control" 
                                           placeholder="Contoh: Sewa bola / rompi tim" 
                                           value="{{ old('notes') }}">
                                </div>
                            </div>
                        @else
                            <div class="p-3 bg-light rounded border mb-3 small">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-person-check-fill text-success fs-5"></i>
                                    <div>
                                        <div class="text-dark fw-bold">Memesan sebagai Akun Terdaftar:</div>
                                        <div class="text-muted">{{ auth()->user()->name }} ({{ auth()->user()->phone ?? auth()->user()->email }})</div>
                                    </div>
                                </div>
                                <input type="hidden" name="customer_name" value="{{ auth()->user()->name }}">
                                <input type="hidden" name="customer_phone" value="{{ auth()->user()->phone ?? '' }}">
                            </div>

                            <div class="mb-0">
                                <label for="notes_auth" class="form-label small fw-bold text-dark mb-1">
                                    Catatan Tambahan (Opsional)
                                </label>
                                <input type="text" 
                                       name="notes" 
                                       id="notes_auth" 
                                       class="form-control" 
                                       placeholder="Contoh: Sewa bola / rompi tim" 
                                       value="{{ old('notes') }}">
                            </div>
                        @endif

                        <!-- Informasi Alur Pembayaran WhatsApp -->
                        <div class="mt-4 pt-3 border-top">
                            <input type="hidden" name="payment_method" value="whatsapp">
                            <div class="p-3 rounded border bg-light d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-success text-white p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                                    <i class="bi bi-whatsapp fs-5"></i>
                                </div>
                                <div>
                                    <strong class="text-dark small d-block">Pembayaran Langsung via WhatsApp Admin</strong>
                                    <span class="text-muted" style="font-size: 0.75rem;">
                                        Setelah formulir ini dikirim, Anda langsung mendapatkan Kode Booking resmi dan diarahkan ke WhatsApp Admin (0813-8466-5386) untuk info rekening / QRIS dan konfirmasi jadwal.
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Kolom Kanan: Ringkasan Booking Desktop -->
            <div class="col-lg-5 col-xl-4">
                <div class="card shadow-sm border bg-white sticky-top" style="top: 80px; z-index: 1020;">
                    <div class="card-header bg-white border-bottom py-3">
                        <h2 class="h6 fw-bold mb-0 text-dark">
                            <i class="bi bi-receipt text-success me-1"></i> Ringkasan Pesanan
                        </h2>
                    </div>
                    <div class="card-body p-3 p-md-4">
                        <div class="mb-2 pb-2 border-bottom">
                            <small class="text-muted d-block">Lapangan Dipilih:</small>
                            <strong class="text-dark fs-6" id="summaryFieldName">-</strong>
                            <span class="badge bg-light text-dark border ms-1" id="summaryFieldType">-</span>
                        </div>

                        <div class="mb-2 pb-2 border-bottom">
                            <small class="text-muted d-block">Tanggal Bermain:</small>
                            <strong class="text-dark" id="summaryDate">-</strong>
                        </div>

                        <div class="mb-3 pb-2 border-bottom">
                            <small class="text-muted d-block">Jam Main:</small>
                            <div id="summaryHours" class="text-dark fw-bold">
                                <span class="text-muted fw-normal fst-italic">Pilih slot jam di samping</span>
                            </div>
                        </div>

                        <!-- Perhitungan Biaya -->
                        <div class="p-2.5 rounded border mb-3 bg-light">
                            <div class="d-flex justify-content-between align-items-center mb-1 small">
                                <span class="text-muted">Tarif Lapangan:</span>
                                <span class="text-dark fw-semibold" id="summaryRate">Rp 0 / jam</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-1 small">
                                <span class="text-muted">Durasi Bermain:</span>
                                <span class="text-dark fw-semibold" id="summaryDurationText">0 Jam</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center pt-1.5 border-top small">
                                <span class="text-muted">Subtotal:</span>
                                <span class="font-monospace text-dark fw-bold" id="summaryCalculationText">0 Jam × Rp 0</span>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-baseline mb-4">
                            <span class="fw-bold text-dark fs-6">Total Biaya:</span>
                            <span class="fw-bold fs-4 text-success" id="summaryTotalPrice">Rp 0</span>
                        </div>

                        <!-- Tombol Submit Utama di Desktop -->
                        <button type="submit" id="btnSubmitBooking" class="btn btn-primary w-100 py-2.5 fw-bold shadow-sm" disabled>
                            Pilih Slot Jam Dahulu
                        </button>

                        <div class="p-2.5 rounded border mt-3 small" style="background-color: #ECFDF5; border-color: #A7F3D0 !important;">
                            <span class="text-muted" style="font-size: 0.75rem; line-height: 1.45;">
                                <i class="bi bi-shield-check text-success me-1"></i> Kode Booking resmi langsung diterbitkan setelah formulir dikirim.
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. STICKY BOTTOM BAR ALA AYO (OTOMATIS MUNCUL SAAT USER MEMILIH 1 ATAU LEBIH JAM) -->
        <div id="stickyBottomBar" class="sticky-bottom-bar d-none">
            <div class="container">
                <div class="d-flex align-items-center justify-content-between gap-3">
                    <div>
                        <div class="small text-muted" id="stickyHourLabel">
                            <i class="bi bi-clock text-success me-1"></i> <span id="stickyHourText">0 Jam Terpilih</span>
                        </div>
                        <div class="d-flex align-items-baseline gap-1">
                            <span class="small text-muted d-none d-sm-inline">Total:</span>
                            <span class="fs-5 fw-bolder text-success" id="stickyTotalPrice">Rp 0</span>
                        </div>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary px-4 py-2.5 fw-bold shadow-sm" id="btnStickySubmit">
                            Lanjut Pemesanan <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>

@push('styles')
<style>
    /* Barisan Tanggal Horizontal ala AYO */
    .date-scroll-container {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
        scrollbar-color: #CBD5E1 transparent;
    }
    .date-scroll-container::-webkit-scrollbar {
        height: 6px;
    }
    .date-scroll-container::-webkit-scrollbar-thumb {
        background-color: #CBD5E1;
        border-radius: 999px;
    }

    .date-card-btn {
        flex: 0 0 76px;
        border: 1px solid #E2E8F0;
        border-radius: 10px;
        background: #FFFFFF;
        padding: 8px 6px;
        text-align: center;
        cursor: pointer;
        transition: all 0.15s ease;
        color: #334155;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        touch-action: manipulation;
    }
    .date-card-btn:hover {
        border-color: #059669;
        background: #F0FDF4;
        color: #059669;
    }
    .date-card-btn.active {
        background: #059669 !important;
        border-color: #059669 !important;
        color: #FFFFFF !important;
        box-shadow: 0 2px 8px rgba(5, 150, 105, 0.35);
    }
    .date-card-btn .date-day {
        font-size: 0.72rem;
        font-weight: 500;
        text-transform: capitalize;
        line-height: 1.2;
    }
    .date-card-btn .date-num {
        font-size: 1.25rem;
        font-weight: 700;
        line-height: 1.2;
        margin: 2px 0;
    }
    .date-card-btn .date-month {
        font-size: 0.72rem;
        font-weight: 500;
        line-height: 1.2;
    }
    .date-card-btn.active .date-day,
    .date-card-btn.active .date-num,
    .date-card-btn.active .date-month {
        color: #FFFFFF !important;
    }

    /* Slot Jam Grid ala AYO */
    .slot-ayo-card {
        border: 1px solid #CBD5E1;
        border-radius: 10px;
        background: #FFFFFF;
        padding: 10px 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.15s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 64px;
        width: 100%;
        touch-action: manipulation;
        position: relative;
    }
    .slot-ayo-card:hover:not(:disabled) {
        border-color: #059669;
        background: #F0FDF4;
    }
    .slot-ayo-card .slot-time {
        font-size: 0.85rem;
        font-weight: 700;
        color: #0F172A;
        line-height: 1.2;
    }
    .slot-ayo-card .slot-price {
        font-size: 0.75rem;
        font-weight: 600;
        color: #059669;
        margin-top: 2px;
    }
    .slot-ayo-card.slot-selected {
        background: #059669 !important;
        border-color: #059669 !important;
        box-shadow: 0 2px 8px rgba(5, 150, 105, 0.35);
    }
    .slot-ayo-card.slot-selected .slot-time,
    .slot-ayo-card.slot-selected .slot-price {
        color: #FFFFFF !important;
    }
    .slot-ayo-card.slot-selected::after {
        content: '✓ Terpilih';
        position: absolute;
        bottom: -6px;
        background: #047857;
        color: #FFFFFF;
        font-size: 0.62rem;
        font-weight: 700;
        padding: 1px 6px;
        border-radius: 999px;
    }
    .slot-ayo-card.slot-past {
        background: #F8FAFC !important;
        border-color: #E2E8F0 !important;
        color: #94A3B8 !important;
        cursor: not-allowed;
        opacity: 0.65;
    }
    .slot-ayo-card.slot-past .slot-time {
        color: #94A3B8 !important;
        text-decoration: line-through;
    }
    .slot-ayo-card.slot-past .slot-price {
        color: #94A3B8 !important;
        font-weight: 500;
    }
    .slot-ayo-card.slot-booked {
        background: #FEF2F2 !important;
        border-color: #FECACA !important;
        color: #DC2626 !important;
        cursor: not-allowed;
        opacity: 0.9;
    }
    .slot-ayo-card.slot-booked .slot-time {
        color: #991B1B !important;
        text-decoration: line-through;
    }
    .slot-ayo-card.slot-booked .slot-price {
        color: #DC2626 !important;
        font-weight: 700;
    }

    /* Sticky Bottom Bar Ala AYO */
    .sticky-bottom-bar {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 1040;
        background: #FFFFFF;
        border-top: 1px solid #E2E8F0;
        box-shadow: 0 -4px 18px rgba(0, 0, 0, 0.08);
        padding: 12px 0;
        animation: slideUpBar 0.25s ease-out;
    }
    @keyframes slideUpBar {
        from { transform: translateY(100%); }
        to { transform: translateY(0); }
    }

    /* Hindari tabrakan dengan floating WhatsApp saat sticky bar aktif */
    body.has-sticky-bar .floating-whatsapp {
        bottom: 84px !important;
        transition: bottom 0.25s ease;
    }
    body.has-sticky-bar main {
        padding-bottom: 110px !important;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fieldSelect = document.getElementById('fieldSelect');
    const dateInput = document.getElementById('dateInput');
    const hiddenDateVal = document.getElementById('hiddenDateVal');
    const dateButtons = document.querySelectorAll('.date-card-btn');
    const btnToggleDatePicker = document.getElementById('btnToggleDatePicker');
    const customDatePickerWrapper = document.getElementById('customDatePickerWrapper');
    const slotGrid = document.getElementById('slotGridContainer');
    const slotAlertBox = document.getElementById('slotAlertBox');

    // Summary Elements (Desktop)
    const summaryFieldName = document.getElementById('summaryFieldName');
    const summaryFieldType = document.getElementById('summaryFieldType');
    const summaryDate = document.getElementById('summaryDate');
    const summaryHours = document.getElementById('summaryHours');
    const summaryDurationText = document.getElementById('summaryDurationText');
    const summaryCalculationText = document.getElementById('summaryCalculationText');
    const summaryRate = document.getElementById('summaryRate');
    const summaryTotalPrice = document.getElementById('summaryTotalPrice');

    // Sticky Bottom Bar Elements
    const stickyBottomBar = document.getElementById('stickyBottomBar');
    const stickyHourText = document.getElementById('stickyHourText');
    const stickyTotalPrice = document.getElementById('stickyTotalPrice');
    const btnStickySubmit = document.getElementById('btnStickySubmit');

    // Form inputs & button
    const bookingForm = document.getElementById('bookingForm');
    const hiddenStartTime = document.getElementById('hiddenStartTime');
    const hiddenEndTime = document.getElementById('hiddenEndTime');
    const btnSubmitBooking = document.getElementById('btnSubmitBooking');
    const btnResetSlots = document.getElementById('btnResetSlots');

    let availableSlots = [];
    let selectedIndices = []; // array of indices e.g. [0, 1]

    const formatRp = (val) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(val);

    // Date Card Button Click
    dateButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            dateButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const pickedDate = this.dataset.date;
            dateInput.value = pickedDate;
            hiddenDateVal.value = pickedDate;
            loadSchedule();
        });
    });

    // Toggle Kalender Lengkap
    if (btnToggleDatePicker && customDatePickerWrapper) {
        btnToggleDatePicker.addEventListener('click', function () {
            customDatePickerWrapper.classList.toggle('d-none');
        });
    }

    if (dateInput) {
        dateInput.addEventListener('change', function () {
            hiddenDateVal.value = this.value;
            // Sinkronkan tombol horizontal jika tanggal ada dalam 14 hari
            dateButtons.forEach(b => {
                if (b.dataset.date === this.value) {
                    b.classList.add('active');
                } else {
                    b.classList.remove('active');
                }
            });
            loadSchedule();
        });
    }

    fieldSelect.addEventListener('change', loadSchedule);

    function getDefaultSlots() {
        const slots = [];
        const dateVal = hiddenDateVal.value || (dateInput ? dateInput.value : '');
        const todayStr = new Date().toLocaleDateString('en-CA');
        const isToday = (dateVal === todayStr);
        const isPastDate = (dateVal < todayStr);
        const now = new Date();
        const currentHour = now.getHours();

        for (let h = 8; h < 23; h++) {
            const startStr = String(h).padStart(2, '0') + ':00';
            const endStr = String(h + 1).padStart(2, '0') + ':00';
            let status = 'available';
            if (isPastDate || (isToday && h <= currentHour)) {
                status = 'past';
            }
            slots.push({
                start: startStr,
                end: endStr,
                status: status
            });
        }
        return slots;
    }

    function loadSchedule() {
        selectedIndices = [];
        updateSummary();

        const fieldId = fieldSelect ? fieldSelect.value : '';
        const date = hiddenDateVal ? hiddenDateVal.value : (dateInput ? dateInput.value : '');

        if (!fieldId || !date) return;

        slotGrid.innerHTML = `
            <div class="col-12 text-center py-4 text-muted small">
                <div class="spinner-border spinner-border-sm text-success me-2" role="status"></div>
                Memuat ketersediaan slot jam...
            </div>
        `;

        fetch(`{{ route('reservations.schedule') }}?field_id=${encodeURIComponent(fieldId)}&date=${encodeURIComponent(date)}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(res => res.json())
            .then(data => {
                if (data && data.slots && Array.isArray(data.slots) && data.slots.length > 0) {
                    availableSlots = data.slots;
                } else {
                    availableSlots = getDefaultSlots();
                }
                renderSlots();
            })
            .catch(err => {
                console.error('Error fetching schedule slots:', err);
                availableSlots = getDefaultSlots();
                renderSlots();
            });
    }

    function renderSlots() {
        slotGrid.innerHTML = '';
        slotAlertBox.classList.add('d-none');

        if (availableSlots.length === 0) {
            slotGrid.innerHTML = `<div class="col-12 text-muted small py-3">Tidak ada slot jam operasional.</div>`;
            return;
        }

        const opt = fieldSelect.options[fieldSelect.selectedIndex];
        const price = opt ? Number(opt.dataset.price || 0) : 0;
        const formattedPrice = formatRp(price);

        availableSlots.forEach((slot, idx) => {
            const isPast = (slot.status === 'past');
            const isBooked = (slot.status === 'booked');
            const isMaintenance = (slot.status === 'maintenance');
            const isUnavailable = isPast || isBooked || isMaintenance;
            const isSelected = selectedIndices.includes(idx);

            const col = document.createElement('div');
            col.className = 'col';

            const card = document.createElement('button');
            card.type = 'button';

            let cardClass = 'slot-ayo-card';
            let labelText = formattedPrice;

            if (isPast) {
                cardClass += ' slot-past';
                labelText = 'Lewat';
            } else if (isBooked) {
                cardClass += ' slot-booked';
                labelText = 'Terisi';
            } else if (isMaintenance) {
                cardClass += ' slot-booked';
                labelText = 'Tutup';
            } else if (isSelected) {
                cardClass += ' slot-selected';
            } else {
                cardClass += ' slot-available';
            }

            card.className = cardClass;
            card.disabled = isUnavailable;

            card.innerHTML = `
                <div class="slot-time">${slot.start} - ${slot.end}</div>
                <div class="slot-price">${labelText}</div>
            `;

            card.addEventListener('click', () => toggleSlot(idx));
            col.appendChild(card);
            slotGrid.appendChild(col);
        });
    }

    if (btnResetSlots) {
        btnResetSlots.addEventListener('click', function () {
            selectedIndices = [];
            renderSlots();
            updateSummary();
        });
    }

    function toggleSlot(idx) {
        slotAlertBox.classList.add('d-none');

        if (!availableSlots[idx] || availableSlots[idx].status !== 'available') {
            return;
        }

        if (selectedIndices.length === 0) {
            // Belum ada pilihan: jadikan slot ini sebagai pilihan awal
            selectedIndices = [idx];
        } else if (selectedIndices.length === 1 && selectedIndices[0] === idx) {
            // Klik ulang slot tunggal: batalkan pilihan
            selectedIndices = [];
        } else if (idx === selectedIndices[selectedIndices.length - 1]) {
            // Klik slot terakhir dalam rangkaian berurutan: lepas slot terakhir
            selectedIndices.pop();
        } else if (idx === selectedIndices[0]) {
            // Klik slot pertama dalam rangkaian berurutan: lepas slot pertama
            selectedIndices.shift();
        } else if (idx === selectedIndices[selectedIndices.length - 1] + 1) {
            // Jam berurutan langsung di sebelah kanan
            selectedIndices.push(idx);
        } else if (idx === selectedIndices[0] - 1) {
            // Jam berurutan langsung di sebelah kiri
            selectedIndices.unshift(idx);
        } else {
            // Klik jam yang tidak berurutan / terloncat:
            // Otomatis jadikan jam yang baru diklik sebagai pilihan awal yang baru
            selectedIndices = [idx];
            slotAlertBox.innerHTML = '<i class="bi bi-info-circle me-1"></i> Jam yang dipilih tidak berurutan. Pilihan otomatis diatur ke jam <strong>' + availableSlots[idx].start + ' - ' + availableSlots[idx].end + ' WIB</strong>.';
            slotAlertBox.classList.remove('d-none');
        }

        renderSlots();
        updateSummary();
    }

    function updateSummary() {
        const opt = fieldSelect.options[fieldSelect.selectedIndex];
        const price = opt ? Number(opt.dataset.price || 0) : 0;
        const fieldName = opt ? opt.dataset.name : '-';
        const fieldType = opt ? opt.dataset.type : '-';
        const dateVal = hiddenDateVal.value || dateInput.value;

        summaryFieldName.textContent = fieldName;
        summaryFieldType.textContent = fieldType.toUpperCase();
        summaryDate.textContent = dateVal ? new Date(dateVal + 'T00:00:00').toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) : '-';
        summaryRate.textContent = `${formatRp(price)} / jam`;

        if (selectedIndices.length > 0) {
            const firstSlot = availableSlots[selectedIndices[0]];
            const lastSlot = availableSlots[selectedIndices[selectedIndices.length - 1]];
            const duration = selectedIndices.length;
            const total = duration * price;

            // Update Desktop Summary
            summaryHours.textContent = `${firstSlot.start} - ${lastSlot.end} WIB`;
            summaryDurationText.textContent = `${duration} Jam Bermain`;
            summaryCalculationText.textContent = `${duration} Jam × ${formatRp(price)}`;
            summaryTotalPrice.textContent = formatRp(total);

            hiddenStartTime.value = firstSlot.start;
            hiddenEndTime.value = lastSlot.end;

            btnSubmitBooking.disabled = false;
            btnSubmitBooking.innerHTML = 'Lanjutkan ke Pembayaran';
            btnResetSlots.classList.remove('d-none');

            // Update & Show Sticky Bottom Bar Ala AYO
            stickyHourText.textContent = `${duration} Jam (${firstSlot.start} - ${lastSlot.end} WIB)`;
            stickyTotalPrice.textContent = formatRp(total);
            stickyBottomBar.classList.remove('d-none');
            document.body.classList.add('has-sticky-bar');
        } else {
            // Empty State Desktop
            summaryHours.innerHTML = '<span class="text-muted fw-normal fst-italic">Pilih slot jam di samping</span>';
            summaryDurationText.textContent = '0 Jam';
            summaryCalculationText.textContent = `0 Jam × ${formatRp(price)}`;
            summaryTotalPrice.textContent = 'Rp 0';

            hiddenStartTime.value = '';
            hiddenEndTime.value = '';

            btnSubmitBooking.disabled = true;
            btnSubmitBooking.innerHTML = 'Pilih Slot Jam Dahulu';
            btnResetSlots.classList.add('d-none');

            // Hide Sticky Bottom Bar
            stickyBottomBar.classList.add('d-none');
            document.body.classList.remove('has-sticky-bar');
        }
    }

    // Aksi Klik Tombol "Lanjut Pemesanan" pada Sticky Bottom Bar
    if (btnStickySubmit) {
        btnStickySubmit.addEventListener('click', function () {
            const nameInput = document.getElementById('customer_name');
            const phoneInput = document.getElementById('customer_phone');

            if (nameInput && (!nameInput.value || !phoneInput.value)) {
                // Scroll halus ke formulir Data Pemesan
                const step2 = document.getElementById('stepDataPemesan');
                if (step2) {
                    step2.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    if (!nameInput.value) {
                        nameInput.focus();
                    } else if (phoneInput && !phoneInput.value) {
                        phoneInput.focus();
                    }
                }
            } else {
                // Jika data sudah terisi, submit form
                bookingForm.submit();
            }
        });
    }

    // Initial load
    loadSchedule();
});
</script>
@endpush
@endsection
