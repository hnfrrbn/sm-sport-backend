@extends('layouts.app')

@section('title', 'SM Sport Center - Booking Lapangan Futsal & Badminton')

@section('content')
<div class="container py-4 py-md-5">

    <!-- 1. HEADER VENUE LEGA & RAPI (TANPA IKON BOLA BASKET) -->
    <div class="card border bg-white shadow-sm p-4 p-sm-5 mb-4 mb-md-5 rounded-4" style="border-color: var(--border-subtle) !important;">
        <div class="d-flex flex-column gap-3">
            <div>
                <h1 class="h3 fw-bold text-dark mb-2 tracking-tight">Booking Lapangan SM Sport Center</h1>
                <div class="text-muted d-flex flex-wrap align-items-center gap-2" style="font-size: 0.9rem;">
                    <span>
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>Jati Padang, Pasar Minggu, Jakarta Selatan
                    </span>
                    <span class="text-secondary d-none d-sm-inline">•</span>
                    <span>
                        <i class="bi bi-clock-fill text-success me-1"></i>Buka Setiap Hari: <strong>08:00 - 23:00 WIB</strong>
                    </span>
                </div>
            </div>

            <!-- Pemisah & Info Fasilitas Lega -->
            <div class="pt-3 border-top d-flex flex-wrap align-items-center gap-2 text-muted" style="font-size: 0.825rem; border-color: #F1F5F9 !important;">
                <span class="fw-semibold text-dark"><i class="bi bi-check-circle-fill text-success me-1"></i>Fasilitas:</span>
                <span>Parkir Gratis</span>
                <span class="text-secondary">•</span>
                <span>Toilet & Ruang Ganti</span>
                <span class="text-secondary">•</span>
                <span>Mushola</span>
                <span class="text-secondary">•</span>
                <span>Kantin</span>
            </div>
        </div>
    </div>

    <!-- 2. TAB FILTER KATEGORI & TOMBOL AKSES CEPAT CEK PESANAN -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 pb-3 border-bottom" id="daftar-lapangan">
        <div>
            <h2 class="h5 fw-bold text-dark mb-1">Pilih Lapangan Olahraga</h2>
            <p class="text-muted small mb-0">Silakan pilih lapangan untuk mengecek ketersediaan jam bermain & booking online.</p>
        </div>
        
        <div class="d-flex flex-wrap align-items-center gap-2 mt-2 mt-md-0">
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

    <!-- 3. DAFTAR KARTU LAPANGAN (GRID 3 KOLOM DESKTOP, LEGA & PROPORSIAL) -->
    <div class="row g-4" id="fieldsContainer">
        @forelse($fields as $field)
            @php
                $isBadminton = strtolower($field->type) === 'badminton';
                $categoryName = $isBadminton ? 'Badminton' : 'Futsal';
                $specSummary = $isBadminton 
                    ? 'Karpet Vinyl Standar BWF • Ganda / Tunggal' 
                    : (str_contains($field->name, '1') ? 'Interlock Vinyl FIFA • 10 Pemain' : 'Rumput Sintetis Premium • 10 Pemain');
            @endphp
            <div class="col-md-6 col-lg-4 field-card-item" data-category="{{ strtolower($field->type) }}">
                <div class="card h-100 bg-white border shadow-sm rounded-3 d-flex flex-column justify-content-between hover-card" style="padding: 1.25rem !important;">
                    <div>
                        <!-- Baris Atas: Nama Lapangan & Badge Kategori -->
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h3 class="h6 fw-bold text-dark mb-0">{{ $field->name }}</h3>
                            <span class="badge {{ $isBadminton ? 'bg-primary-subtle text-primary border border-primary-subtle' : 'bg-success-subtle text-success border border-success-subtle' }} text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                {{ $categoryName }}
                            </span>
                        </div>

                        <!-- Baris Tengah: Ringkasan Spesifikasi 1 Baris -->
                        <div class="text-muted small mb-3" style="font-size: 0.8rem;">
                            <i class="bi bi-check2-circle text-success me-1"></i>{{ $specSummary }}
                        </div>
                    </div>

                    <!-- Baris Bawah: Tarif Sewa Sejajar dengan Tombol "Pilih Jadwal" -->
                    <div class="pt-3 border-top d-flex justify-content-between align-items-center gap-2">
                        <div>
                            <span class="text-muted d-block" style="font-size: 0.72rem; line-height: 1;">Tarif Sewa</span>
                            <div class="mt-0.5">
                                <span class="fs-5 fw-bolder text-success">Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}</span>
                                <span class="text-muted small" style="font-size: 0.75rem;">/ jam</span>
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
        transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
    }
    .hover-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
        border-color: var(--primary-emerald) !important;
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
