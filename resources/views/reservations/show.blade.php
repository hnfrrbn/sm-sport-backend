@extends('layouts.app')

@section('title', 'Rincian & Pembayaran Booking #' . $reservation->code)

@section('content')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">

            <!-- Navigasi Atas Sederhana (Hanya Kembali ke Beranda) -->
            <div class="mb-3">
                <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
                </a>
            </div>

            <!-- Stepper Ringkas (Langkah 3: Bayar via WhatsApp) -->
            <div class="card border-0 mb-4 shadow-sm" style="background: #F0FDF4; border: 1px solid #DCFCE7 !important;">
                <div class="card-body py-2.5 px-4">
                    <div class="row text-center g-2 align-items-center">
                        <div class="col-4 text-muted small">
                            <i class="bi bi-check-circle-fill text-success me-1"></i> 1. Jadwal
                        </div>
                        <div class="col-4 text-muted small border-start border-end">
                            <i class="bi bi-check-circle-fill text-success me-1"></i> 2. Data Diri
                        </div>
                        <div class="col-4">
                            @if(in_array(strtolower($reservation->status), ['paid', 'lunas', 'confirmed', 'dp']) || ($reservation->transaction && in_array(strtolower($reservation->transaction->status), ['paid', 'completed'])))
                                <strong class="text-success small"><i class="bi bi-check-circle-fill me-1"></i> 3. Lunas &amp; Terkonfirmasi</strong>
                            @else
                                <strong class="text-success small"><i class="bi bi-record-circle me-1"></i> 3. Bayar via WA</strong>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @php
                $transaction = $reservation->transaction;
                $isPaid = (in_array(strtolower($reservation->status), ['paid', 'lunas', 'confirmed', 'dp']) || ($transaction && in_array(strtolower($transaction->status), ['paid', 'completed'])));
                $customerName = $reservation->customer_name ?: ($reservation->user->name ?? 'Pelanggan');
                $customerPhone = $reservation->customer_phone ?: ($reservation->user->phone ?? '-');

                // Format Tanggal Bahasa Indonesia (contoh: "Rabu, 25 November 2026")
                $indoDays = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                $indoMonths = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                $carbonDate = \Carbon\Carbon::parse($reservation->reservation_date);
                $indoDateFormatted = $indoDays[$carbonDate->dayOfWeek] . ', ' . $carbonDate->day . ' ' . $indoMonths[$carbonDate->month] . ' ' . $carbonDate->year;

                $jamFormatted = \Carbon\Carbon::parse($reservation->start_time)->format('H:i') . ' - ' . \Carbon\Carbon::parse($reservation->end_time)->format('H:i') . ' WIB';
                $totalFormatted = 'Rp ' . number_format($reservation->total_price, 0, ',', '.');

                // Susunan Teks Otomatis WhatsApp Resmi Admin
                if (!$isPaid && $reservation->status !== 'cancelled') {
                    $waText = "Halo Admin SM Sport Center, saya ingin melakukan pembayaran booking lapangan:\n"
                            . "- Kode Booking: " . $reservation->code . "\n"
                            . "- Nama: " . $customerName . "\n"
                            . "- Lapangan: " . $reservation->field->name . "\n"
                            . "- Jadwal: " . $indoDateFormatted . ", " . $jamFormatted . "\n"
                            . "- Total Bayar: " . $totalFormatted . "\n"
                            . "Mohon info rekening / QRIS untuk pembayarannya. Terima kasih.";
                } else {
                    $waText = "Halo Admin SM Sport Center, saya ingin konfirmasi pesanan lapangan saya:\n"
                            . "- Kode Booking: " . $reservation->code . "\n"
                            . "- Nama: " . $customerName . "\n"
                            . "- Lapangan: " . $reservation->field->name . "\n"
                            . "- Jadwal: " . $indoDateFormatted . ", " . $jamFormatted . "\n"
                            . "- Status: " . ($isPaid ? 'LUNAS / TERKONFIRMASI' : ucfirst($reservation->status)) . "\n"
                            . "Terima kasih.";
                }

                $waUrl = "https://wa.me/6281384665386?text=" . urlencode($waText);
            @endphp

            <!-- KARTU UTAMA: RINGKASAN PESANAN BERSIH & JELAS -->
            <div class="card mb-3 shadow-sm border bg-white">
                <div class="card-header bg-white border-bottom py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <div>
                            <small class="text-muted text-uppercase d-block" style="font-size: 0.72rem; letter-spacing: 0.5px;">KODE BOOKING</small>
                            <span class="fs-5 fw-bold font-monospace text-dark" id="bookingCodeVal">{{ $reservation->code }}</span>
                        </div>
                        <button type="button" class="btn btn-outline-secondary btn-sm py-0.5 px-2 mt-2" onclick="copyBookingCode()" title="Salin Kode Booking">
                            <i class="bi bi-clipboard" id="copyIcon"></i> <span id="copyLabel" style="font-size: 0.75rem;">Salin</span>
                        </button>
                    </div>

                    <div>
                        @if($isPaid)
                            <span class="badge bg-success text-white py-1.5 px-3 fs-6">
                                <i class="bi bi-patch-check-fill me-1"></i> Lunas / Terkonfirmasi
                            </span>
                        @elseif($reservation->status === 'cancelled')
                            <span class="badge bg-danger-subtle text-danger border border-danger py-1.5 px-2.5">
                                <i class="bi bi-x-circle me-1"></i> Dibatalkan
                            </span>
                        @else
                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle py-1.5 px-2.5">
                                <i class="bi bi-clock-history me-1"></i> Menunggu Pembayaran via WhatsApp
                            </span>
                        @endif
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row g-3 pb-3 mb-3 border-bottom">
                        <div class="col-sm-6">
                            <small class="text-muted d-block mb-0.5">Nama Lapangan</small>
                            <strong class="text-dark fs-6">{{ $reservation->field->name }}</strong>
                            <span class="badge bg-light text-dark border ms-1">{{ ucfirst($reservation->field->type) }}</span>
                        </div>
                        <div class="col-sm-6">
                            <small class="text-muted d-block mb-0.5">Nama Pemesan</small>
                            <strong class="text-dark">{{ $customerName }}</strong>
                            <div class="small text-muted">{{ $customerPhone }}</div>
                        </div>
                        <div class="col-sm-6">
                            <small class="text-muted d-block mb-0.5">Tanggal Bermain</small>
                            <strong class="text-dark">{{ $indoDateFormatted }}</strong>
                        </div>
                        <div class="col-sm-6">
                            <small class="text-muted d-block mb-0.5">Waktu & Durasi</small>
                            <strong class="text-dark">{{ $jamFormatted }}</strong>
                            <span class="text-muted small">({{ $reservation->duration_hours }} Jam)</span>
                        </div>
                    </div>

                    @if($reservation->notes)
                        <div class="p-2.5 bg-light rounded border mb-3 small">
                            <strong class="text-dark"><i class="bi bi-chat-left-text text-muted me-1"></i> Catatan:</strong>
                            <span class="text-muted ms-1">{{ $reservation->notes }}</span>
                        </div>
                    @endif

                    <!-- Kotak Total Biaya -->
                    <div class="p-3 rounded border mb-0 d-flex justify-content-between align-items-center" style="background-color: #F0FDF4; border-color: #BBF7D0 !important;">
                        <div>
                            <span class="text-muted small d-block">Tarif: Rp {{ number_format($reservation->field->price_per_hour, 0, ',', '.') }} × {{ $reservation->duration_hours }} Jam</span>
                            <strong class="text-dark fs-6">{{ $isPaid ? 'Total Dibayarkan (Lunas):' : 'Total yang Harus Dibayar:' }}</strong>
                        </div>
                        <span class="fs-4 fw-bold text-success">{{ $totalFormatted }}</span>
                    </div>
                </div>
            </div>

            @if(!$isPaid && $reservation->status !== 'cancelled')
                <!-- KOTAK PANDUAN SINGKAT PEMBAYARAN VIA WHATSAPP -->
                <div class="card border-0 mb-4 shadow-sm" style="background: #F0FDF4; border: 1px solid #BBF7D0 !important;">
                    <div class="card-body p-3.5">
                        <div class="d-flex align-items-start gap-2.5">
                            <i class="bi bi-info-circle-fill text-success fs-5 mt-0.5"></i>
                            <div>
                                <strong class="text-dark small d-block mb-1">Cara Pembayaran:</strong>
                                <p class="text-muted small mb-0" style="line-height: 1.55;">
                                    Klik tombol hijau di bawah untuk mengirim rincian booking ke WhatsApp Admin. Admin akan mengirimkan nomor rekening / QRIS dan mengonfirmasi jadwal Anda setelah pembayaran diterima.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- TOMBOL UTAMA KIRIM KE WHATSAPP ADMIN (AUTO-TEXT) -->
            <div class="d-flex flex-column gap-2 mb-4">
                <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" 
                   class="btn btn-success w-100 py-3 fw-bold shadow d-flex align-items-center justify-content-center gap-2" 
                   style="font-size: 1.05rem; border-radius: 10px;">
                    <i class="bi bi-whatsapp fs-4"></i>
                    <span>{{ $isPaid ? 'Hubungi Admin via WhatsApp (0813-8466-5386)' : 'Kirim & Bayar via WhatsApp Admin (0813-8466-5386)' }}</span>
                </a>

                @if($isPaid)
                    <!-- Tombol Aksi Tambahan Bersih di Bawah (Hanya Tampil Setelah Disetujui / Lunas oleh Admin) -->
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        <a href="{{ route('reservations.ticket', $reservation->id) }}?code={{ $reservation->code }}" target="_blank" class="btn btn-outline-secondary flex-grow-1 py-2">
                            <i class="bi bi-printer me-1"></i> Cetak / Simpan Bukti
                        </a>
                        <a href="{{ route('reservations.check') }}" class="btn btn-outline-secondary flex-grow-1 py-2">
                            <i class="bi bi-search me-1"></i> Cek Status Pesanan
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
function copyBookingCode() {
    const codeEl = document.getElementById('bookingCodeVal');
    if (!codeEl) return;
    const code = codeEl.innerText.trim();
    copyText(code, document.getElementById('copyLabel'));
}

function copyText(text, btnEl) {
    const onSuccess = () => {
        if (btnEl) {
            const originalText = btnEl.innerText;
            btnEl.innerText = 'Tersalin!';
            setTimeout(() => { btnEl.innerText = originalText; }, 2000);
        }
    };

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text).then(onSuccess).catch(() => fallbackCopy(text, onSuccess));
    } else {
        fallbackCopy(text, onSuccess);
    }
}

function fallbackCopy(text, callback) {
    const textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.position = "fixed";
    textArea.style.opacity = "0";
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    try {
        document.execCommand('copy');
        if (callback) callback();
    } catch (err) {
        console.error('Fallback copy failed', err);
    }
    document.body.removeChild(textArea);
}
</script>
@endpush
@endsection
