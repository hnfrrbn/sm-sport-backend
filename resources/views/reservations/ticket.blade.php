@extends('layouts.app')

@section('title', 'Tiket Booking #' . $reservation->code)

@section('content')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">

            <!-- Action Bar (Hidden when printed) -->
            <div class="d-flex justify-content-between align-items-center mb-3 d-print-none">
                <a href="{{ route('reservations.show', $reservation->id) }}?code={{ $reservation->code }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Rincian
                </a>
                <button type="button" onclick="window.print()" class="btn btn-primary btn-sm">
                    <i class="bi bi-printer me-1"></i> Cetak Struk / Tiket
                </button>
            </div>

            <!-- Tiket / Struk Card (Clean Light Mode) -->
            <div class="card shadow-sm border p-4 p-md-5 bg-white">
                <!-- Header Struk -->
                <div class="text-center pb-3 border-bottom mb-4">
                    <div class="brand-monogram mx-auto mb-2" style="width: 44px; height: 44px; font-size: 1.15rem; border-radius: 11px;">
                        SM
                    </div>
                    <h4 class="fw-bold mb-1 text-dark">SM SPORT CENTER</h4>
                    <p class="text-muted small mb-1">Jati Padang, RT/RW 006/003, Pasar Minggu, Jakarta Selatan • WA: 0813-8466-5386</p>
                    <span class="badge bg-light text-dark border">STRUK & BUKTI RESERVASI</span>
                </div>

                <!-- Status & Kode Booking -->
                @php 
                    $tTrx = $reservation->transaction; 
                    $isLunas = (in_array(strtolower($reservation->status), ['paid', 'lunas', 'confirmed', 'dp']) || ($tTrx && in_array(strtolower($tTrx->status), ['paid', 'completed'])));
                    $custName = $reservation->customer_name ?: ($reservation->user->name ?? 'Pelanggan');
                    $custPhone = $reservation->customer_phone ?: ($reservation->user->phone ?? '-');
                @endphp

                <div class="p-3 bg-light rounded border mb-4 d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div>
                        <small class="text-muted d-block text-uppercase" style="font-size: 0.72rem;">KODE BOOKING</small>
                        <span class="fs-5 fw-bold font-monospace text-dark">{{ $reservation->code }}</span>
                    </div>
                    <div>
                        @if($isLunas)
                            <span class="badge bg-success text-white py-1 px-2.5">
                                <i class="bi bi-patch-check-fill me-1"></i> LUNAS / TERKONFIRMASI
                            </span>
                        @elseif($reservation->status === 'cancelled')
                            <span class="badge bg-danger-subtle text-danger border">Dibatalkan</span>
                        @else
                            <span class="badge bg-warning-subtle text-warning-emphasis border">Menunggu Pembayaran</span>
                        @endif
                    </div>
                </div>

                <!-- Rincian Pesanan -->
                <div class="mb-4">
                    <h6 class="text-dark fw-bold small text-uppercase mb-2 pb-1 border-bottom">
                        <i class="bi bi-calendar-event text-success me-1"></i> Rincian Jadwal & Lapangan
                    </h6>
                    <table class="table table-sm table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td class="text-muted py-1" style="width: 40%;">Lapangan</td>
                                <td class="text-dark fw-semibold py-1">: {{ $reservation->field->name }} ({{ ucfirst($reservation->field->type) }})</td>
                            </tr>
                            <tr>
                                <td class="text-muted py-1">Tanggal</td>
                                <td class="text-dark fw-semibold py-1">: {{ $reservation->reservation_date->format('l, d F Y') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted py-1">Jam Bermain</td>
                                <td class="text-dark fw-semibold py-1">: {{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }} WIB ({{ $reservation->duration_hours }} Jam)</td>
                            </tr>
                            <tr>
                                <td class="text-muted py-1">Tarif Sewa</td>
                                <td class="text-dark py-1">: Rp {{ number_format($reservation->field->price_per_hour, 0, ',', '.') }} / jam</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Data Pemesan -->
                <div class="mb-4">
                    <h6 class="text-dark fw-bold small text-uppercase mb-2 pb-1 border-bottom">
                        <i class="bi bi-person text-success me-1"></i> Data Pemesan
                    </h6>
                    <table class="table table-sm table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td class="text-muted py-1" style="width: 40%;">Nama</td>
                                <td class="text-dark fw-semibold py-1">: {{ $custName }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted py-1">No. WhatsApp</td>
                                <td class="text-dark py-1">: {{ $custPhone }}</td>
                            </tr>
                            @if($reservation->notes)
                                <tr>
                                    <td class="text-muted py-1">Catatan</td>
                                    <td class="text-dark py-1">: {{ $reservation->notes }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Total Pembayaran -->
                <div class="p-3 bg-light rounded border mb-4 text-center">
                    <span class="text-muted small d-block">{{ $isLunas ? 'Total Dibayarkan (Lunas):' : 'Total Tagihan:' }}</span>
                    <strong class="fs-4 text-success font-monospace">Rp {{ number_format($reservation->total_price, 0, ',', '.') }}</strong>
                    @if($tTrx)
                        <small class="text-muted d-block mt-1">Metode: {{ ucfirst($tTrx->payment_method ?? 'Transfer') }} • No. Transaksi: {{ $tTrx->transaction_code }}</small>
                    @endif
                </div>

                <!-- QR Code Validasi Struk -->
                <div class="text-center pt-2">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=110x110&data=SM-SPORT-{{ $reservation->code }}" 
                         alt="QR Booking" 
                         class="p-1.5 bg-white border rounded shadow-sm mb-2" 
                         style="width: 100px; height: 100px;">
                    <p class="text-muted small mb-0" style="font-size: 0.72rem;">
                        Tunjukkan struk ini kepada petugas SM Sport Center saat tiba di lapangan.
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>

@push('styles')
<style>
@media print {
    .site-navbar, .site-footer, .floating-whatsapp, .d-print-none {
        display: none !important;
    }
    body, main {
        background: #FFFFFF !important;
        padding: 0 !important;
    }
    .card {
        border: 1px solid #CBD5E1 !important;
        box-shadow: none !important;
    }
}
</style>
@endpush
@endsection
