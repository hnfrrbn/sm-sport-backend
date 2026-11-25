@extends('layouts.app')

@section('title', 'SM Sport Center - Booking Lapangan Futsal & Badminton')

@section('content')
<div class="container py-3">

    <!-- 1. HEADER PROFIL VENUE AYO (RINGKAS & PADAT TANPA GAMBAR/BANNER BESAR) -->
    <div class="card border bg-white shadow-sm p-3 p-md-4 mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div>
                <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                    <h1 class="h3 fw-bold text-dark mb-0">SM Sport Center</h1>
                    <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.72rem;">
                        <i class="bi bi-patch-check-fill me-1"></i> Venue Resmi
                    </span>
                </div>
                
                <div class="text-muted small mb-2 d-flex flex-wrap align-items-center gap-2">
                    <span>
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i> Jati Padang, RT/RW 006/003, Pasar Minggu, Jakarta Selatan
                    </span>
                    <span class="text-secondary d-none d-sm-inline">•</span>
                    <span>
                        <i class="bi bi-clock-fill text-success me-1"></i> Buka Setiap Hari: <strong>08:00 - 23:00 WIB</strong>
                    </span>
                </div>

                <!-- Badge Fasilitas Venue Sederhana -->
                <div class="d-flex flex-wrap gap-1.5 align-items-center">
                    <span class="badge bg-light text-secondary border fw-normal py-1 px-2">
                        <i class="bi bi-p-circle text-primary me-1"></i> Parkir Gratis
                    </span>
                    <span class="badge bg-light text-secondary border fw-normal py-1 px-2">
                        <i class="bi bi-droplet-fill text-info me-1"></i> Toilet & Ruang Ganti
                    </span>
                    <span class="badge bg-light text-secondary border fw-normal py-1 px-2">
                        <i class="bi bi-moon-stars-fill text-success me-1"></i> Mushola
                    </span>
                    <span class="badge bg-light text-secondary border fw-normal py-1 px-2">
                        <i class="bi bi-cup-hot-fill text-warning me-1"></i> Kantin & Air Minum
                    </span>
                </div>
            </div>

            <!-- Tombol Aksi Cepat Header -->
            <div class="w-100 w-md-auto mt-2 mt-md-0">
                <a href="https://wa.me/6281384665386?text={{ urlencode('Halo Admin SM Sport Center Pasar Minggu, saya ingin tanya info ketersediaan lapangan.') }}" 
                   target="_blank" rel="noopener noreferrer" class="btn btn-outline-success btn-sm px-3 w-100 w-md-auto">
                    <i class="bi bi-whatsapp me-1"></i> Hubungi Admin
                </a>
            </div>
        </div>
    </div>

    <!-- 2. TAB FILTER KATEGORI LAPANGAN ALA AYO -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-3 pb-2 border-bottom">
        <div>
            <h2 class="h5 fw-bold text-dark mb-0">Daftar Lapangan Olahraga</h2>
            <small class="text-muted">Pilih lapangan untuk melihat jadwal jam yang tersedia</small>
        </div>
        <!-- Filter Tabs Sederhana -->
        <div class="btn-group p-1 bg-white border rounded-3 shadow-sm" role="group" aria-label="Filter Lapangan">
            <button type="button" class="btn btn-sm btn-filter px-3 active" data-filter="all">
                Semua ({{ $fields->count() }})
            </button>
            <button type="button" class="btn btn-sm btn-filter px-3" data-filter="futsal">
                Futsal ({{ $fields->filter(fn($f) => strtolower($f->type) === 'futsal')->count() }})
            </button>
            <button type="button" class="btn btn-sm btn-filter px-3" data-filter="badminton">
                Badminton ({{ $fields->filter(fn($f) => strtolower($f->type) === 'badminton')->count() }})
            </button>
        </div>
    </div>

    <!-- 3. DAFTAR KARTU LAPANGAN (MURNI BERBASIS TEKS & INFORMASI TANPA GAMBAR DEKORATIF) -->
    <div class="row g-3" id="fieldsContainer">
        @forelse($fields as $field)
            @php
                $isBadminton = strtolower($field->type) === 'badminton';
                $categoryName = $isBadminton ? 'Badminton' : 'Futsal';
                $floorType = $isBadminton 
                    ? 'Karpet Vinyl Standar BWF' 
                    : (str_contains($field->name, '1') ? 'Interlock Vinyl FIFA' : 'Rumput Sintetis Premium');
                $playerCap = $isBadminton ? '4 Pemain (Ganda / Tunggal)' : '10 Pemain (5 vs 5)';
            @endphp
            <div class="col-md-6 col-lg-4 field-card-item" data-category="{{ strtolower($field->type) }}">
                <div class="card h-100 bg-white border shadow-sm p-3 d-flex flex-column justify-content-between">
                    <div>
                        <!-- Bagian Atas Kartu: Badge Kategori di Kiri & Jam Operasional di Kanan -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge {{ $isBadminton ? 'bg-primary-subtle text-primary border border-primary-subtle' : 'bg-success-subtle text-success border border-success-subtle' }} text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                                {{ $categoryName }}
                            </span>
                            <span class="small text-muted" style="font-size: 0.75rem;">
                                <i class="bi bi-clock text-success me-1"></i> 08:00 - 23:00 WIB
                            </span>
                        </div>

                        <!-- Bagian Tengah Kartu: Nama Lapangan Dicetak Tebal Besar, Jenis Lantai, Kapasitas -->
                        <h3 class="h5 fw-bold text-dark mb-2">{{ $field->name }}</h3>
                        
                        <div class="small text-muted mb-3 d-flex flex-column gap-1.5">
                            <div class="d-flex align-items-center">
                                <span class="text-secondary" style="width: 80px;">Lantai:</span>
                                <strong class="text-dark">{{ $floorType }}</strong>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="text-secondary" style="width: 80px;">Kapasitas:</span>
                                <strong class="text-dark">{{ $playerCap }}</strong>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="text-secondary" style="width: 80px;">Status:</span>
                                <span class="badge {{ $field->status === 'available' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} py-0.5 px-1.5 fw-semibold" style="font-size: 0.72rem;">
                                    <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i>
                                    {{ $field->status === 'available' ? 'Tersedia' : 'Maintenance' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian Bawah Kartu: Tarif Sewa Tebal & Tombol Penuh "Pilih Jadwal" -->
                    <div class="pt-3 border-top mt-2">
                        <div class="d-flex justify-content-between align-items-baseline mb-3">
                            <span class="text-muted small">Tarif Sewa:</span>
                            <div>
                                <span class="fs-4 fw-bolder text-success">Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}</span>
                                <span class="text-muted small">/ jam</span>
                            </div>
                        </div>

                        @if($field->status === 'available')
                            <a href="{{ route('reservations.create', ['field_id' => $field->id]) }}" class="btn btn-primary w-100 py-2.5 fw-bold shadow-sm">
                                Pilih Jadwal
                            </a>
                        @else
                            <button class="btn btn-secondary w-100 py-2.5 fw-semibold" disabled>
                                Tidak Tersedia
                            </button>
                        @endif
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
