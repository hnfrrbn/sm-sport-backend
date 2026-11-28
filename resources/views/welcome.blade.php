@extends('layouts.app')

@section('title', 'SM Sport Center - Booking Lapangan Futsal & Badminton')

@section('content')
<div class="container py-4 py-md-5">

    <!-- 1. HERO BANNER MODERN DENGAN GRADASI EMERALD ELEGAN -->
    <div class="hero-banner p-4 p-sm-5 mb-4 rounded-4 shadow-sm text-white position-relative overflow-hidden" 
         style="background: linear-gradient(135deg, #059669 0%, #047857 55%, #0f766e 100%);">
        
        <!-- Ornamen Visual CSS Halus (Tanpa Gambar/File Eksternal) -->
        <div class="position-absolute" style="top: -40px; right: -40px; width: 180px; height: 180px; background: rgba(255, 255, 255, 0.08); border-radius: 50%; pointer-events: none;"></div>
        <div class="position-absolute" style="bottom: -50px; right: 120px; width: 140px; height: 140px; background: rgba(255, 255, 255, 0.05); border-radius: 50%; pointer-events: none;"></div>

        <div class="position-relative z-1 d-flex flex-column gap-3">
            <div>
                <div class="d-inline-flex align-items-center gap-2 px-2.5 py-1 rounded-pill mb-2.5" 
                     style="background: rgba(255, 255, 255, 0.18); backdrop-filter: blur(6px); border: 1px solid rgba(255, 255, 255, 0.25); font-size: 0.75rem;">
                    <i class="bi bi-patch-check-fill text-warning"></i>
                    <span class="fw-semibold">Venue Resmi Olahraga Pasar Minggu</span>
                </div>
                <h1 class="h3 fw-bold text-white mb-2 tracking-tight">Booking Lapangan SM Sport Center</h1>
                <div class="d-flex flex-wrap align-items-center gap-2 text-white" style="font-size: 0.9rem;">
                    <span>
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>Jati Padang, Pasar Minggu, Jakarta Selatan
                    </span>
                    <span class="text-white-50 d-none d-sm-inline">•</span>
                    <span>
                        <i class="bi bi-clock-fill text-warning me-1"></i>Buka Setiap Hari: <strong>08:00 - 23:00 WIB</strong>
                    </span>
                </div>
            </div>

            <!-- Badge Fasilitas dalam Pill Transparan Putih (bg-white/15) -->
            <div class="pt-3 mt-1 d-flex flex-wrap align-items-center gap-2 border-top" style="border-color: rgba(255, 255, 255, 0.2) !important;">
                <span class="small fw-semibold text-white-50 me-1" style="font-size: 0.8rem;">Fasilitas Venue:</span>
                <span class="badge rounded-pill px-3 py-1.5 fw-normal" 
                      style="background: rgba(255, 255, 255, 0.16); backdrop-filter: blur(4px); border: 1px solid rgba(255, 255, 255, 0.25); color: #fff; font-size: 0.8rem;">
                    <i class="bi bi-p-circle me-1 text-white"></i> Parkir Gratis
                </span>
                <span class="badge rounded-pill px-3 py-1.5 fw-normal" 
                      style="background: rgba(255, 255, 255, 0.16); backdrop-filter: blur(4px); border: 1px solid rgba(255, 255, 255, 0.25); color: #fff; font-size: 0.8rem;">
                    <i class="bi bi-droplet me-1 text-white"></i> Toilet & Ruang Ganti
                </span>
                <span class="badge rounded-pill px-3 py-1.5 fw-normal" 
                      style="background: rgba(255, 255, 255, 0.16); backdrop-filter: blur(4px); border: 1px solid rgba(255, 255, 255, 0.25); color: #fff; font-size: 0.8rem;">
                    <i class="bi bi-moon-stars me-1 text-white"></i> Mushola
                </span>
                <span class="badge rounded-pill px-3 py-1.5 fw-normal" 
                      style="background: rgba(255, 255, 255, 0.16); backdrop-filter: blur(4px); border: 1px solid rgba(255, 255, 255, 0.25); color: #fff; font-size: 0.8rem;">
                    <i class="bi bi-cup-hot me-1 text-white"></i> Kantin
                </span>
            </div>
        </div>
    </div>

    <!-- 2. STRIP CARA BOOKING CEPAT (3 LANGKAH RINGKAS) -->
    <div class="card border bg-white shadow-sm p-6 sm:p-8 mt-6 mb-8 rounded-2xl step-guide-wrapper" style="border-color: #E2E8F0 !important;">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-10 md:divide-x md:divide-slate-200 align-items-center step-guide-grid">
            <!-- Langkah 1 -->
            <div class="step-guide-item">
                <div class="d-flex align-items-center gap-4">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-success flex-shrink-0" 
                         style="width: 44px; height: 44px; background-color: #ECFDF5; border: 1px solid #A7F3D0;">
                        <i class="bi bi-calendar2-check-fill fs-5"></i>
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="fw-bold text-dark" style="font-size: 0.95rem; line-height: 1.3;">1. Pilih Lapangan & Jam</div>
                        <div class="text-muted" style="font-size: 0.8rem; line-height: 1.4;">Lihat slot jam & tarif sewa</div>
                    </div>
                </div>
            </div>

            <!-- Langkah 2 -->
            <div class="step-guide-item md:pl-8">
                <div class="d-flex align-items-center gap-4">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-primary flex-shrink-0" 
                         style="width: 44px; height: 44px; background-color: #EFF6FF; border: 1px solid #BFDBFE;">
                        <i class="bi bi-person-lines-fill fs-5"></i>
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="fw-bold text-dark" style="font-size: 0.95rem; line-height: 1.3;">2. Isi Data Pemesan</div>
                        <div class="text-muted" style="font-size: 0.8rem; line-height: 1.4;">Cukup nama & no. WhatsApp</div>
                    </div>
                </div>
            </div>

            <!-- Langkah 3 -->
            <div class="step-guide-item md:pl-8">
                <div class="d-flex align-items-center gap-4">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-success flex-shrink-0" 
                         style="width: 44px; height: 44px; background-color: #ECFDF5; border: 1px solid #A7F3D0;">
                        <i class="bi bi-whatsapp fs-5"></i>
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="fw-bold text-dark" style="font-size: 0.95rem; line-height: 1.3;">3. Konfirmasi Bayar WA</div>
                        <div class="text-muted" style="font-size: 0.8rem; line-height: 1.4;">Dapatkan struk & kode booking</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. TAB FILTER KATEGORI & TOMBOL AKSES CEPAT CEK PESANAN -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 pb-2 border-bottom" id="daftar-lapangan">
        <div>
            <h2 class="h5 fw-bold text-dark mb-1">Pilih Lapangan Olahraga</h2>
            <p class="text-muted small mb-0">Tersedia 5 lapangan standar resmi dengan jadwal ketersediaan jam ter-update.</p>
        </div>
        
        <div class="d-flex flex-wrap align-items-center gap-2 mt-1 mt-md-0">
            <!-- Filter Tabs Kategori -->
            <div class="btn-group p-1 bg-white border rounded-3 shadow-sm" role="group" aria-label="Filter Lapangan">
                <button type="button" class="btn btn-sm btn-filter px-3 py-1.5 active" data-filter="all">
                    Semua ({{ $fields->count() }})
                </button>
                <button type="button" class="btn btn-sm btn-filter px-3 py-1.5" data-filter="futsal">
                    Futsal ({{ $fields->filter(fn($f) => strtolower($f->type) === 'futsal')->count() }})
                </button>
                <button type="button" class="btn btn-sm btn-filter px-3 py-1.5" data-filter="badminton">
                    Badminton ({{ $fields->filter(fn($f) => strtolower($f->type) === 'badminton')->count() }})
                </button>
            </div>

            <!-- Tombol Ringan Cek Status Pesanan -->
            <a href="{{ route('reservations.check') }}" class="btn btn-sm btn-outline-secondary px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5 shadow-sm" title="Cek Status Pesanan Anda">
                <i class="bi bi-search text-success"></i>
                <span>Cek Status Pesanan</span>
            </a>
        </div>
    </div>

    <!-- 4. DAFTAR KARTU LAPANGAN (GRID 3 KOLOM DESKTOP, LEGA & PROPORSIAL DENGAN AKSEN BORDER) -->
    <div class="row g-4" id="fieldsContainer">
        @forelse($fields as $field)
            @php
                $isBadminton = strtolower($field->type) === 'badminton';
                $categoryName = $isBadminton ? 'Badminton' : 'Futsal';
                $accentColor = $isBadminton ? '#0EA5E9' : '#10B981'; // sky-500 vs emerald-500
                $specSummary = $isBadminton 
                    ? 'Karpet Vinyl Standar BWF • Ganda / Tunggal' 
                    : (str_contains($field->name, '1') ? 'Interlock Vinyl FIFA • 10 Pemain' : 'Rumput Sintetis Premium • 10 Pemain');
                $todaySlots = $field->today_available_slots ?? 8;
            @endphp
            <div class="col-md-6 col-lg-4 field-card-item" data-category="{{ strtolower($field->type) }}">
                <div class="card h-100 bg-white border shadow-sm rounded-3 d-flex flex-column justify-content-between hover-card position-relative overflow-hidden" 
                     style="border-top: 4px solid {{ $accentColor }} !important; padding: 1.25rem !important;">
                    <div>
                        <!-- Baris Atas: Nama Lapangan & Badge Kategori -->
                        <div class="d-flex justify-content-between align-items-center mb-1.5">
                            <h3 class="h6 fw-bold text-dark mb-0 fs-6">{{ $field->name }}</h3>
                            <span class="badge {{ $isBadminton ? 'bg-info-subtle text-info-emphasis border border-info-subtle' : 'bg-success-subtle text-success border border-success-subtle' }} text-uppercase fw-bold" style="font-size: 0.68rem; letter-spacing: 0.5px;">
                                {{ $categoryName }}
                            </span>
                        </div>

                        <!-- Baris Tengah: Ringkasan Spesifikasi 1 Baris -->
                        <div class="text-muted small mb-3" style="font-size: 0.78rem;">
                            <i class="bi bi-check2-circle text-success me-1"></i>{{ $specSummary }}
                        </div>
                    </div>

                    <div>
                        <!-- Info Slot Tersedia Hari Ini (WIB) di Atas Garis Pemisah -->
                        <div class="mb-2 d-flex align-items-center justify-content-between">
                            @if($todaySlots > 0)
                                <span class="badge bg-success-subtle text-success border border-success-subtle fw-semibold px-2 py-0.5 d-inline-flex align-items-center gap-1.5" style="font-size: 0.72rem;">
                                    <span class="pulse-dot"></span>
                                    <span>Tersedia {{ $todaySlots }} slot hari ini</span>
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-muted border fw-semibold px-2 py-0.5 d-inline-flex align-items-center gap-1" style="font-size: 0.72rem;">
                                    <i class="bi bi-clock-history"></i>
                                    <span>Slot hari ini penuh</span>
                                </span>
                            @endif
                            <small class="text-muted" style="font-size: 0.7rem;">08:00 - 23:00</small>
                        </div>

                        <!-- Baris Bawah: Tarif Sewa Sejajar dengan Tombol "Pilih Jadwal" -->
                        <div class="pt-2.5 border-top d-flex justify-content-between align-items-center gap-2">
                            <div>
                                <span class="text-muted d-block" style="font-size: 0.7rem; line-height: 1;">Tarif Sewa</span>
                                <div class="mt-0.5">
                                    <span class="fs-5 fw-bolder text-success">Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}</span>
                                    <span class="text-muted small" style="font-size: 0.72rem;">/ jam</span>
                                </div>
                            </div>

                            @if($field->status === 'available')
                                <a href="{{ route('reservations.create', ['field_id' => $field->id]) }}" class="btn btn-primary btn-sm px-3 py-1.5 fw-bold shadow-sm d-inline-flex align-items-center gap-1">
                                    <span>Pilih Jadwal</span>
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            @else
                                <button class="btn btn-secondary btn-sm px-3 py-1.5 fw-semibold" disabled>
                                    Tidak Tersedia
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card p-4 text-center border">
                    <p class="text-muted mb-0">Belum ada lapangan olahraga yang aktif saat ini.</p>
                </div>
            </div>
        @endforelse
    </div>

</div>

@push('styles')
<style>
    .btn-filter {
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.82rem;
        color: var(--text-muted);
        border: none;
        background: transparent;
        transition: all 0.15s ease;
    }
    .btn-filter:hover {
        color: var(--text-heading);
        background: #F1F5F9;
    }
    .btn-filter.active {
        background: var(--primary-emerald) !important;
        color: #FFFFFF !important;
    }
    .hover-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 22px rgba(0, 0, 0, 0.08) !important;
    }
    .pulse-dot {
        width: 6px;
        height: 6px;
        background-color: #059669;
        border-radius: 50%;
        display: inline-block;
        animation: pulseGreen 1.8s infinite ease-in-out;
    }
    @keyframes pulseGreen {
        0%, 100% {
            opacity: 1;
            transform: scale(1);
        }
        50% {
            opacity: 0.4;
            transform: scale(0.85);
        }
    }

    /* 3-Step Guide Responsive Spacing & Dividers */
    .step-guide-wrapper {
        padding: 1.5rem !important; /* p-6 */
        margin-top: 1.5rem !important; /* mt-6 */
        margin-bottom: 2rem !important; /* mb-8 */
        border-radius: 1rem !important; /* rounded-2xl */
    }
    @media (min-width: 640px) {
        .step-guide-wrapper {
            padding: 2rem !important; /* sm:p-8 */
        }
    }
    .step-guide-grid {
        display: grid !important;
        grid-template-columns: 1fr;
        gap: 1.5rem; /* gap-6 */
    }
    @media (min-width: 768px) {
        .step-guide-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
        }
        .step-guide-grid.md\:divide-x > * + * {
            border-left: 1px solid #E2E8F0 !important;
        }
        .md\:pl-8 {
            padding-left: 2rem !important;
        }
    }
    @media (min-width: 1024px) {
        .step-guide-grid {
            gap: 2.5rem !important; /* lg:gap-10 */
        }
    }
    .gap-4 {
        gap: 1rem !important;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Filter Kategori (Semua / Futsal / Badminton)
    const filterBtns = document.querySelectorAll('.btn-filter');
    const fieldItems = document.querySelectorAll('.field-card-item');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const filterValue = this.dataset.filter;
            fieldItems.forEach(item => {
                const category = item.dataset.category;
                if (filterValue === 'all' || category === filterValue) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
});
</script>
@endpush
@endsection
