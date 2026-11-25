@extends('layouts.app')

@section('title', 'Cek Status Pesanan - SM Sport Center')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9 col-xl-8">

            <!-- Navigasi Kembali -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
                </a>
                <a href="{{ route('home') }}#daftar-lapangan" class="btn btn-outline-success btn-sm">
                    <i class="bi bi-plus-circle me-1"></i> Booking Lapangan Baru
                </a>
            </div>

            <!-- Header Halaman -->
            <div class="text-center mb-4">
                <div class="brand-badge mx-auto mb-2" style="width: 44px; height: 44px; font-size: 1.35rem;">
                    <i class="bi bi-search text-success"></i>
                </div>
                <h1 class="h3 fw-bold text-dark mb-1">Cek Status Pesanan Lapangan</h1>
                <p class="text-muted small mx-auto" style="max-width: 520px;">
                    Cari reservasi Anda secara instan menggunakan <strong>Kode Booking, Nama Pemesan, atau No. WhatsApp</strong> yang digunakan saat pemesanan.
                </p>
            </div>

            <!-- Kartu Pencarian -->
            <div class="card shadow-sm border mb-4">
                <div class="card-body p-4">
                    <form action="{{ route('reservations.check') }}" method="GET">
                        <div class="row g-2 align-items-center">
                            <div class="col-12 col-sm">
                                <label for="searchQuery" class="visually-hidden">Kode Booking, Nama Pemesan, atau No. WhatsApp</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text" 
                                           name="q" 
                                           id="searchQuery" 
                                           class="form-control border-start-0 ps-0" 
                                           placeholder="Masukkan Kode Booking, Nama Pemesan, atau No. WhatsApp" 
                                           value="{{ $query ?? '' }}" 
                                           required>
                                </div>
                            </div>
                            <div class="col-12 col-sm-auto">
                                <button type="submit" class="btn btn-primary w-100 px-4 fw-semibold">
                                    <i class="bi bi-search me-1"></i> Cari Pesanan
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="d-flex flex-wrap justify-content-between align-items-center mt-3 pt-3 border-top small text-muted">
                        <span><i class="bi bi-info-circle text-success me-1"></i> Format: Kode Booking (<code>RSV-...</code>), Nama Pemesan (misal: Budi / Wilson), atau No. WhatsApp.</span>
                        <a href="{{ route('home') }}#daftar-lapangan" class="text-success fw-semibold">
                            <i class="bi bi-plus-circle me-1"></i> Booking Jadwal Baru
                        </a>
                    </div>
                </div>
            </div>

            <!-- Hasil Pencarian -->
            @if($searched)
                @if($reservations->isNotEmpty())
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-dark mb-0">
                            <i class="bi bi-check2-circle text-success me-1"></i> Hasil Pencarian: {{ $reservations->count() }} Pesanan Ditemukan
                        </h6>
                        <span class="text-muted small">Kata kunci: "<strong>{{ $query }}</strong>"</span>
                    </div>

                    <div class="d-flex flex-column gap-3 mb-4">
                        @foreach($reservations as $res)
                            @php 
                                $resTrx = $res->transaction; 
                                $isLunas = ($res->status === 'confirmed' || ($resTrx && in_array($resTrx->status, ['paid', 'completed'])));
                                $cName = trim($res->customer_name ?? '');
                                if (empty($cName) || strcasecmp($cName, 'admin') === 0) {
                                    if ($res->user && ! $res->user->isAdmin()) {
                                        $cName = $res->user->name;
                                    } else {
                                        $cName = 'Pelanggan';
                                    }
                                }
                                $cPhone = $res->customer_phone ?: ($res->user?->phone ?? '-');

                                $indoDaysCheck = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                $indoMonthsCheck = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                $cDate = \Carbon\Carbon::parse($res->reservation_date);
                                $waDateCheck = $indoDaysCheck[$cDate->dayOfWeek] . ', ' . $cDate->day . ' ' . $indoMonthsCheck[$cDate->month] . ' ' . $cDate->year;
                            @endphp
                            <div class="card shadow-sm {{ $isLunas ? 'border border-success' : 'border' }}">
                                <div class="card-header bg-white border-bottom py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <div>
                                            <small class="text-muted text-uppercase d-block" style="font-size: 0.72rem; letter-spacing: 0.5px;">KODE BOOKING</small>
                                            <span class="font-monospace fw-bold text-dark fs-6">{{ $res->code }}</span>
                                        </div>
                                        <button type="button" class="btn btn-outline-secondary btn-sm py-0.5 px-2 mt-2" onclick="copyBookingCode('{{ $res->code }}', this)" title="Salin Kode Booking">
                                            <i class="bi bi-clipboard"></i> <span style="font-size: 0.75rem;">Salin</span>
                                        </button>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2 align-items-center">
                                        @if($isLunas)
                                            <span class="badge bg-success text-white py-1.5 px-2.5">
                                                <i class="bi bi-patch-check-fill me-1"></i> LUNAS / TERKONFIRMASI
                                            </span>
                                        @elseif($res->status === 'cancelled')
                                            <span class="badge bg-danger-subtle text-danger border border-danger">
                                                <i class="bi bi-x-circle me-1"></i> Dibatalkan
                                            </span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">
                                                <i class="bi bi-clock-history me-1"></i> Menunggu Pembayaran via WhatsApp
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="card-body p-4 {{ $isLunas ? 'bg-light-subtle' : '' }}">
                                    <div class="row g-3 align-items-center">
                                        <div class="col-md-7">
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <h5 class="h6 fw-bold text-dark mb-0">{{ $res->field->name }}</h5>
                                                <span class="badge bg-light text-dark border text-uppercase" style="font-size: 0.7rem;">
                                                    {{ $res->field->type }}
                                                </span>
                                            </div>

                                            <div class="small text-muted mb-1">
                                                <i class="bi bi-calendar3 me-1 text-success"></i>
                                                {{ $waDateCheck }}
                                            </div>
                                            <div class="small text-muted mb-2">
                                                <i class="bi bi-clock me-1 text-success"></i>
                                                {{ \Carbon\Carbon::parse($res->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($res->end_time)->format('H:i') }} WIB
                                                <span class="text-dark fw-semibold">({{ $res->duration_hours }} Jam)</span>
                                            </div>
                                            <div class="small text-muted mb-1">
                                                <i class="bi bi-person me-1"></i>
                                                Pemesan: <strong class="text-dark">{{ $cName }}</strong>
                                                <span class="ms-1">({{ $cPhone }})</span>
                                            </div>
                                            <div class="small text-muted">
                                                <i class="bi bi-geo-alt me-1 text-danger"></i>
                                                SM Sport Center • Pasar Minggu, Jakarta Selatan
                                            </div>
                                        </div>

                                        <div class="col-md-5 text-md-end pt-3 pt-md-0 border-top border-top-md-0">
                                            <small class="text-muted d-block" style="font-size: 0.75rem;">{{ $isLunas ? 'TOTAL DIBAYARKAN (LUNAS)' : 'TOTAL TAGIHAN' }}</small>
                                            <span class="fs-5 fw-bold text-success d-block mb-3">
                                                Rp {{ number_format($res->total_price, 0, ',', '.') }}
                                            </span>

                                            <div class="d-flex flex-wrap justify-content-md-end gap-2">
                                                @if(!$isLunas && $res->status !== 'cancelled')
                                                    @php
                                                        $indoDaysCheck = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                                        $indoMonthsCheck = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                                        $cDate = \Carbon\Carbon::parse($res->reservation_date);
                                                        $waDateCheck = $indoDaysCheck[$cDate->dayOfWeek] . ', ' . $cDate->day . ' ' . $indoMonthsCheck[$cDate->month] . ' ' . $cDate->year;
                                                        $waTimeCheck = \Carbon\Carbon::parse($res->start_time)->format('H:i') . ' - ' . \Carbon\Carbon::parse($res->end_time)->format('H:i') . ' WIB';
                                                        $waPriceCheck = 'Rp ' . number_format($res->total_price, 0, ',', '.');
                                                        $waMsgCheck = "Halo Admin SM Sport Center, saya ingin melakukan pembayaran booking lapangan:\n"
                                                                    . "- Kode Booking: " . $res->code . "\n"
                                                                    . "- Nama: " . $cName . "\n"
                                                                    . "- Lapangan: " . $res->field->name . "\n"
                                                                    . "- Jadwal: " . $waDateCheck . ", " . $waTimeCheck . "\n"
                                                                    . "- Total Bayar: " . $waPriceCheck . "\n"
                                                                    . "Mohon info rekening / QRIS untuk pembayarannya. Terima kasih.";
                                                        $waUrlCheck = "https://wa.me/6281384665386?text=" . urlencode($waMsgCheck);
                                                    @endphp
                                                    <a href="{{ $waUrlCheck }}" target="_blank" rel="noopener noreferrer" class="btn btn-success btn-sm fw-semibold">
                                                        <i class="bi bi-whatsapp me-1"></i> Lanjut Bayar via WhatsApp Admin
                                                    </a>
                                                @endif
                                                <a href="{{ route('reservations.show', $res) }}?code={{ $res->code }}" class="btn {{ $isLunas ? 'btn-success' : 'btn-outline-primary' }} btn-sm">
                                                    <i class="bi bi-eye me-1"></i> {{ $isLunas ? 'Lihat Bukti Lunas' : 'Rincian Pesanan' }}
                                                </a>
                                                <a href="{{ route('reservations.ticket', $res) }}?code={{ $res->code }}" target="_blank" class="btn btn-outline-secondary btn-sm" title="Cetak Struk / Simpan Bukti">
                                                    <i class="bi bi-printer me-1"></i> Cetak
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Keadaan Kosong Ramah (No Results Box) -->
                    <div class="card shadow-sm border p-4 text-center mb-4 bg-white">
                        <div class="py-3">
                            <div class="brand-badge mx-auto mb-3" style="width: 48px; height: 48px; background: #F8FAFC; border: 1px solid #E2E8F0;">
                                <i class="bi bi-search text-muted fs-4"></i>
                            </div>
                            <h5 class="h6 fw-bold text-dark mb-1">Pesanan Tidak Ditemukan</h5>
                            <p class="text-muted small mb-3" style="max-width: 500px; margin: 0 auto; line-height: 1.6;">
                                Mohon maaf, tidak ditemukan data reservasi dengan kata kunci "<strong>{{ $query }}</strong>".
                            </p>
                            <div class="p-3 bg-light rounded border text-start mx-auto mb-3" style="max-width: 500px;">
                                <strong class="d-block small text-dark mb-1"><i class="bi bi-lightbulb text-warning me-1"></i> Tips Pencarian:</strong>
                                <ul class="small text-muted mb-0 ps-3">
                                    <li>Pastikan <strong>Kode Booking</strong> sesuai format (contoh: <code>RSV-261124-ABCD</code>).</li>
                                    <li>Jika menggunakan <strong>Nomor WhatsApp</strong>, gunakan nomor aktif yang dimasukkan saat pemesanan (contoh: <code>081234567890</code>).</li>
                                </ul>
                            </div>
                            <div class="d-flex flex-wrap justify-content-center gap-2">
                                <a href="{{ route('reservations.check') }}" class="btn btn-outline-secondary btn-sm px-3 py-2">
                                    <i class="bi bi-arrow-repeat me-1"></i> Cari Ulang
                                </a>
                                <a href="{{ route('home') }}#daftar-lapangan" class="btn btn-primary btn-sm px-3 py-2">
                                    <i class="bi bi-plus-circle me-1"></i> Booking Lapangan Baru
                                </a>
                                <a href="https://wa.me/6281384665386?text={{ urlencode('Halo Admin SM Sport Center Pasar Minggu, saya butuh bantuan cek status booking saya: ' . $query) }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-success btn-sm px-3 py-2">
                                    <i class="bi bi-whatsapp me-1"></i> Bantuan Admin
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <!-- Panduan Singkat sebelum mencari -->
                <div class="card shadow-sm border p-4 mb-4">
                    <h6 class="fw-bold text-dark mb-3">
                        <i class="bi bi-patch-question text-success me-1"></i> Panduan Pemesanan Tanpa Akun
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded border h-100">
                                <div class="fw-bold text-dark small mb-1">
                                    <span class="badge bg-success text-white me-1">1</span> Pilih & Pesan
                                </div>
                                <small class="text-muted">Pilih lapangan dan jam main dari beranda langsung tanpa perlu membuat akun atau login.</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded border h-100">
                                <div class="fw-bold text-dark small mb-1">
                                    <span class="badge bg-success text-white me-1">2</span> Kode Booking
                                </div>
                                <small class="text-muted">Kode Booking unik (misal: <code>RSV-261124-ABCD</code>) langsung terbit setelah Anda memesan.</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded border h-100">
                                <div class="fw-bold text-dark small mb-1">
                                    <span class="badge bg-success text-white me-1">3</span> Cek Status
                                </div>
                                <small class="text-muted">Kapan saja Anda dapat memantau konfirmasi admin dan mencetak bukti sewa melalui halaman ini.</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>

@push('scripts')
<script>
function copyBookingCode(code, btn) {
    if (!code) return;
    const originalHtml = btn ? btn.innerHTML : '';

    const onSuccess = () => {
        if (btn) {
            btn.innerHTML = '<i class="bi bi-check2 text-success"></i> <span style="font-size: 0.75rem;">Tersalin!</span>';
            setTimeout(() => { btn.innerHTML = originalHtml; }, 2000);
        }
    };

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(code).then(onSuccess).catch(() => fallbackCopy(code, onSuccess));
    } else {
        fallbackCopy(code, onSuccess);
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
