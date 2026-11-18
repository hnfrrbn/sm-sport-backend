@extends('layouts.app')

@section('title', 'Tiket Booking - ' . $reservation->code)

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <!-- Action Bar (Hidden when printed) -->
            <div class="d-flex justify-content-between align-items-center mb-4 no-print">
                <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('customer.reservations') }}" class="btn btn-outline-light btn-sm px-3 py-2">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
                <button type="button" onclick="window.print()" class="btn btn-success fw-bold px-4 py-2 shadow d-flex align-items-center gap-2">
                    <i class="bi bi-printer-fill fs-5"></i> Cetak Struk / Tiket
                </button>
            </div>

            <!-- Tiket / Struk Booking Card -->
            <div class="ticket-card p-4 p-md-5 rounded-4 shadow-lg text-white" style="background: #0f1d36; border: 2px dashed rgba(34, 197, 94, 0.4); position: relative;">
                
                <!-- Header Struk -->
                <div class="text-center pb-3 border-bottom border-secondary border-opacity-50 mb-4">
                    <div class="brand-mark mx-auto mb-2" style="width: 44px; height: 44px;">
                        <img src="{{ asset('images/sm-sport-logo.svg') }}" alt="Logo" style="width: 28px; height: 28px;">
                    </div>
                    <h3 class="fw-bold mb-1 letter-spacing-1 text-white">SM SPORT CENTER</h3>
                    <p class="text-white-50 small mb-0">Jl. Raya Sport Center No. 12, Bandung • WA: 0813-8466-5386</p>
                    <div class="badge bg-secondary bg-opacity-50 text-white mt-2 px-3 py-1">STRUK & BUKTI RESERVASI</div>
                </div>

                <!-- Status Badge & Kode Tiket -->
                <div class="d-flex justify-content-between align-items-center p-3 rounded-3 mb-4" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1);">
                    <div>
                        <small class="text-white-50 d-block text-uppercase fw-semibold" style="font-size: 0.75rem;">KODE BOOKING</small>
                        <span class="fs-4 fw-bolder font-monospace" style="color: #4ade80;">{{ $reservation->code }}</span>
                    </div>
                    <div class="text-end">
                        <small class="text-white-50 d-block text-uppercase fw-semibold" style="font-size: 0.75rem;">STATUS</small>
                        @if($reservation->status === 'confirmed' || ($reservation->transaction && $reservation->transaction->status === 'paid'))
                            <span class="badge bg-success text-uppercase px-3 py-2 fw-bold">
                                <i class="bi bi-check-circle-fill me-1"></i> LUNAS / TERKONFIRMASI
                            </span>
                        @elseif($reservation->status === 'cancelled')
                            <span class="badge bg-danger text-uppercase px-3 py-2 fw-bold">
                                <i class="bi bi-x-circle-fill me-1"></i> DIBATALKAN
                            </span>
                        @else
                            <span class="badge bg-warning text-dark text-uppercase px-3 py-2 fw-bold">
                                <i class="bi bi-clock-history me-1"></i> MENUNGGU PEMBAYARAN
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Rincian Booking -->
                <div class="mb-4">
                    <h6 class="text-success text-uppercase fw-bold small letter-spacing-1 mb-3">
                        <i class="bi bi-calendar-event me-1"></i> DETAIL JADWAL & LAPANGAN
                    </h6>
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex justify-content-between py-1 border-bottom border-secondary border-opacity-25">
                            <span class="text-white-50">Nama Lapangan:</span>
                            <strong class="text-white">{{ $reservation->field->name }} ({{ ucfirst($reservation->field->type) }})</strong>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom border-secondary border-opacity-25">
                            <span class="text-white-50">Tanggal Bermain:</span>
                            <strong class="text-white">{{ $reservation->reservation_date->format('d F Y') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom border-secondary border-opacity-25">
                            <span class="text-white-50">Jam Main:</span>
                            <strong class="text-white">{{ substr($reservation->start_time, 0, 5) }} - {{ substr($reservation->end_time, 0, 5) }} WIB</strong>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom border-secondary border-opacity-25">
                            <span class="text-white-50">Durasi:</span>
                            <strong class="text-white">{{ $reservation->duration_hours }} Jam</strong>
                        </div>
                    </div>
                </div>

                <!-- Data Pemesan -->
                <div class="mb-4">
                    <h6 class="text-info text-uppercase fw-bold small letter-spacing-1 mb-3">
                        <i class="bi bi-person-fill me-1"></i> DATA PEMESAN
                    </h6>
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex justify-content-between py-1 border-bottom border-secondary border-opacity-25">
                            <span class="text-white-50">Nama:</span>
                            <strong class="text-white">{{ $reservation->customer_name ?: $reservation->user->name }}</strong>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom border-secondary border-opacity-25">
                            <span class="text-white-50">No. WhatsApp / HP:</span>
                            <strong class="text-white">{{ $reservation->customer_phone ?: ($reservation->user->phone ?: '-') }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Rincian Pembayaran -->
                <div class="p-3 rounded-3 mb-4" style="background: rgba(34, 197, 94, 0.12); border: 1px solid rgba(34, 197, 94, 0.3);">
                    <div class="d-flex justify-content-between py-1">
                        <span class="text-white-50">Tarif per Jam:</span>
                        <span class="text-white">Rp {{ number_format($reservation->field->price_per_hour, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-1">
                        <span class="text-white-50">Metode Bayar:</span>
                        <span class="text-white fw-semibold">{{ strtoupper($reservation->transaction->payment_method ?? 'Cash/Transfer') }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-top border-success border-opacity-25 mt-2 align-items-baseline">
                        <strong class="text-white fs-5">TOTAL BAYAR:</strong>
                        <strong class="fs-4" style="color: #4ade80;">Rp {{ number_format($reservation->total_price, 0, ',', '.') }}</strong>
                    </div>
                </div>

                <!-- Representasi Barcode / QR Visual -->
                <div class="text-center py-2 mb-4">
                    <div class="d-inline-block px-4 py-2 rounded bg-white text-dark font-monospace fw-bold tracking-widest letter-spacing-2" style="letter-spacing: 4px; font-size: 1.1rem;">
                        ||| | |||| | ||| |||| | |||
                    </div>
                    <small class="text-white-50 d-block mt-1 font-monospace">{{ $reservation->code }}</small>
                </div>

                <!-- Catatan Kaki / Rules -->
                <div class="p-3 rounded-3 text-white-50 small" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.06); font-size: 0.8rem; line-height: 1.6;">
                    <strong class="text-white d-block mb-1">Ketentuan Lapangan:</strong>
                    <ol class="ps-3 mb-0">
                        <li>Harap hadir 10 menit sebelum jam bermain dimulai.</li>
                        <li>Wajib menggunakan sepatu olahraga yang sesuai (futsal / badminton).</li>
                        <li>Tunjukkan struk/tiket ini kepada petugas di kasir SM Sport Center.</li>
                    </ol>
                </div>

                <div class="text-center text-white-50 small mt-4 pt-2 border-top border-secondary border-opacity-25">
                    Dicetak otomatis pada: {{ date('d M Y H:i') }} WIB • SM Sport Center
                </div>
            </div>

            <!-- Tombol Cetak Bawah -->
            <div class="text-center mt-4 no-print">
                <button type="button" onclick="window.print()" class="btn btn-primary btn-lg px-5 py-3 fw-bold shadow-lg" style="border-radius: 14px;">
                    <i class="bi bi-printer-fill me-2"></i> Cetak Struk Sekarang
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        body {
            background: #ffffff !important;
            color: #000000 !important;
        }
        .navbar, .sidebar, .luxury-footer, .no-print, .floating-cta {
            display: none !important;
        }
        main {
            margin: 0 !important;
            padding: 0 !important;
        }
        .ticket-card {
            background: #ffffff !important;
            color: #111827 !important;
            border: 2px solid #374151 !important;
            box-shadow: none !important;
            max-width: 600px;
            margin: 0 auto;
        }
        .ticket-card h3, .ticket-card strong, .ticket-card span, .ticket-card p {
            color: #111827 !important;
        }
        .ticket-card .text-white-50 {
            color: #4b5563 !important;
        }
        .ticket-card [style*="color: #4ade80"] {
            color: #15803d !important;
        }
        .badge.bg-success {
            background: #16a34a !important;
            color: #ffffff !important;
        }
    }
</style>
@endpush
@endsection
