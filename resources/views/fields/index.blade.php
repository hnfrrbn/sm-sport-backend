@extends('layouts.app')

@section('title', 'Daftar Lapangan Olahraga')

@section('content')
<div class="container py-3">
    <!-- Header Halaman -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3 pb-2 border-bottom">
        <div>
            <h1 class="h4 fw-bold text-dark mb-0">Daftar Lapangan Olahraga</h1>
            <p class="text-muted small mb-0">SM Sport Center • 08:00 - 23:00 WIB • Jati Padang, Pasar Minggu</p>
        </div>
        <div>
            @if(auth()->check() && auth()->user()->isAdmin())
                <a href="{{ route('admin.fields.create') }}" class="btn btn-primary btn-sm px-3 shadow-sm">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Lapangan
                </a>
            @endif
        </div>
    </div>

    <!-- Filter Kategori Lapangan -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <div class="btn-group p-1 bg-white border rounded-3 shadow-sm" role="group" aria-label="Filter Lapangan">
            <button type="button" class="btn btn-sm btn-filter px-3 active" data-filter="all">Semua Lapangan</button>
            <button type="button" class="btn btn-sm btn-filter px-3" data-filter="futsal">Futsal</button>
            <button type="button" class="btn btn-sm btn-filter px-3" data-filter="badminton">Badminton</button>
        </div>
        <div class="small text-muted">
            Jam Operasional: <strong class="text-dark">08:00 - 23:00 WIB</strong>
        </div>
    </div>

    <!-- Grid Lapangan Murni Teks & Info -->
    <div class="row g-3" id="fieldsListContainer">
        @forelse($fields as $field)
            @php
                $isBadminton = strtolower($field->type) === 'badminton';
                $categoryName = $isBadminton ? 'Badminton' : 'Futsal';
                $floorType = $isBadminton 
                    ? 'Karpet Vinyl Standar BWF' 
                    : (str_contains($field->name, '1') ? 'Interlock Vinyl FIFA' : 'Rumput Sintetis Premium');
                $playerCap = $isBadminton ? '4 Pemain (Ganda / Tunggal)' : '10 Pemain (5 vs 5)';
            @endphp
            <div class="col-md-6 col-lg-4 field-col-item" data-category="{{ strtolower($field->type) }}">
                <div class="card h-100 bg-white border shadow-sm p-3 d-flex flex-column justify-content-between">
                    <div>
                        <!-- Header Kartu: Badge Kategori & Jam Operasional -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge {{ $isBadminton ? 'bg-primary-subtle text-primary border border-primary-subtle' : 'bg-success-subtle text-success border border-success-subtle' }} text-uppercase fw-bold" style="font-size: 0.72rem;">
                                {{ $categoryName }}
                            </span>
                            <span class="small text-muted" style="font-size: 0.75rem;">
                                <i class="bi bi-clock text-success me-1"></i> 08:00 - 23:00 WIB
                            </span>
                        </div>

                        <!-- Info Lapangan -->
                        <h2 class="h5 fw-bold text-dark mb-2">{{ $field->name }}</h2>
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

                    <!-- Harga & Tombol Aksi -->
                    <div class="pt-3 border-top mt-2">
                        <div class="d-flex align-items-baseline justify-content-between mb-3">
                            <span class="small text-muted">Tarif Sewa:</span>
                            <div>
                                <span class="fs-4 fw-bolder text-success">Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}</span>
                                <span class="small text-muted">/ jam</span>
                            </div>
                        </div>

                        @if(auth()->check() && auth()->user()->isAdmin())
                            <div class="d-flex gap-2">
                                <a href="{{ route('fields.show', $field) }}" class="btn btn-outline-secondary btn-sm flex-grow-1">
                                    <i class="bi bi-eye me-1"></i> Detail
                                </a>
                                <a href="{{ route('admin.fields.edit', $field) }}" class="btn btn-outline-warning btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.fields.destroy', $field) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus lapangan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="d-flex gap-2">
                                <a href="{{ route('fields.show', $field) }}" class="btn btn-outline-secondary btn-sm px-3">
                                    Detail
                                </a>
                                @if($field->status === 'available')
                                    <a href="{{ route('reservations.create') }}?field_id={{ $field->id }}" class="btn btn-primary btn-sm flex-grow-1 py-2 fw-bold">
                                        Pilih Jadwal
                                    </a>
                                @else
                                    <button class="btn btn-secondary btn-sm flex-grow-1" disabled>
                                        Tidak Tersedia
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">Tidak ada lapangan olahraga yang ditemukan.</p>
            </div>
        @endforelse
    </div>

    @if($fields instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="d-flex justify-content-center mt-4">
            {{ $fields->links() }}
        </div>
    @endif
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
    document.querySelectorAll('.btn-filter').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.btn-filter').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const filter = this.dataset.filter;
            document.querySelectorAll('.field-col-item').forEach(col => {
                if (filter === 'all' || col.dataset.category === filter) {
                    col.style.display = 'block';
                } else {
                    col.style.display = 'none';
                }
            });
        });
    });
</script>
@endpush
@endsection
