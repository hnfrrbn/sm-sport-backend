@extends('layouts.app')

@section('title', 'Daftar Lapangan')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-8">
            <h1 class="h2 text-white">
                <i class="bi bi-building"></i> Daftar Lapangan
            </h1>
        </div>
        <div class="col-md-4 text-end">
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.fields.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah Lapangan
                </a>
            @endif
        </div>
    </div>

    <!-- Filter Lapangan: Semua / Futsal / Badminton -->
    <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
        <span class="text-white-50 small me-2"><i class="bi bi-funnel-fill text-success"></i> Filter Kategori:</span>
        <button type="button" class="btn btn-sm px-3 py-2 rounded-pill field-filter-btn active" data-filter="all">
            Semua Lapangan ({{ $fields->total() ?? $fields->count() }})
        </button>
        <button type="button" class="btn btn-sm px-3 py-2 rounded-pill field-filter-btn" data-filter="futsal">
            Futsal
        </button>
        <button type="button" class="btn btn-sm px-3 py-2 rounded-pill field-filter-btn" data-filter="badminton">
            Badminton
        </button>
    </div>

    <div class="row">
        @forelse($fields as $field)
            <div class="col-md-6 col-lg-4 mb-4 field-item-col" data-field-type="{{ strtolower($field->type) }}">
                <div class="field-card card h-100 overflow-hidden shadow-lg" style="background: #0f1d36; border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 20px;">
                    <!-- Card Header: Background Gradasi Elegan & Ilustrasi Lapangan Rapi di Tengah -->
                    <div class="position-relative overflow-hidden d-flex align-items-center justify-content-center p-3" 
                         style="height: 200px; background: {{ $field->gradient_style }};">
                        <img src="{{ asset($field->image_url) }}" 
                             alt="{{ $field->name }}" 
                             class="field-card-vector img-fluid"
                             style="max-height: 145px; max-width: 88%; object-fit: contain; filter: drop-shadow(0 10px 18px rgba(0, 0, 0, 0.45)); border-radius: 12px;">
                        <div class="position-absolute top-0 start-0 m-3 d-flex gap-2">
                            <span class="badge text-uppercase fw-bold px-3 py-2 shadow-sm rounded-pill" 
                                  style="backdrop-filter: blur(8px); background: rgba(0, 0, 0, 0.4); color: #fff; border: 1px solid rgba(255, 255, 255, 0.2);">
                                <i class="bi {{ strtolower($field->type) === 'badminton' ? 'bi-feather text-info' : 'bi-dribbble text-success' }} me-1"></i>
                                {{ $field->type }}
                            </span>
                        </div>
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge bg-{{ $field->status === 'available' ? 'success' : ($field->status === 'maintenance' ? 'warning' : 'danger') }} text-capitalize fw-bold px-3 py-2 shadow-sm rounded-pill" style="backdrop-filter: blur(8px);">
                                <i class="bi bi-circle-fill me-1" style="font-size: 0.6rem;"></i>
                                {{ $field->status === 'available' ? 'Tersedia' : ucfirst($field->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-4 d-flex flex-column" style="background: #0f1d36;">
                        <h4 class="card-title text-white fw-bold mb-2" style="font-size: 1.35rem;">{{ $field->name }}</h4>
                        <p class="card-text text-white-50 small mb-3">
                            {{ $field->description ?: 'Lapangan berkualitas dengan fasilitas lengkap untuk latihan dan pertandingan.' }}
                        </p>

                        <div class="p-3 rounded-3 mb-3" style="background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(255, 255, 255, 0.08);">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-people-fill text-info fs-5"></i>
                                <div>
                                    <small class="text-white-50 d-block" style="font-size: 0.72rem; text-transform: uppercase;">Kapasitas</small>
                                    <strong class="text-white fs-6">{{ $field->capacity }} Orang</strong>
                                </div>
                            </div>
                        </div>

                        <div class="p-3 rounded-3 mb-4 text-center mt-auto" style="background: linear-gradient(135deg, rgba(22, 163, 74, 0.20), rgba(15, 29, 54, 0.95)); border: 1px solid rgba(34, 197, 94, 0.35);">
                            <small class="text-white-50 d-block text-uppercase fw-semibold mb-1">Tarif Sewa</small>
                            <div class="d-flex align-items-baseline justify-content-center gap-1">
                                <span class="fs-2 fw-bolder" style="color: #4ade80;">Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}</span>
                                <span class="text-white small">/ jam</span>
                            </div>
                        </div>

                        <div class="d-flex flex-column gap-2">
                            @if(auth()->user()->isAdmin())
                                <div class="d-flex gap-2">
                                    <a href="{{ route('fields.show', $field) }}" class="btn btn-outline-light flex-grow-1 fw-semibold py-2">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                    <a href="{{ route('admin.fields.edit', $field) }}" class="btn btn-warning fw-semibold py-2">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.fields.destroy', $field) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger fw-semibold py-2" onclick="return confirm('Hapus lapangan ini?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="d-flex gap-2">
                                    <a href="{{ route('fields.show', $field) }}" class="btn btn-outline-light fw-semibold py-2 px-3">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                    @if($field->status === 'available')
                                        <a href="{{ route('reservations.create') }}?field_id={{ $field->id }}" 
                                           class="btn btn-primary flex-grow-1 fw-bold py-2 shadow d-flex align-items-center justify-content-center gap-2"
                                           style="background: linear-gradient(135deg, #16a34a, #22c55e); border: none; border-radius: 12px;">
                                            <i class="bi bi-calendar2-check-fill"></i> Pesan Sekarang
                                        </a>
                                    @else
                                        <button class="btn btn-secondary flex-grow-1 fw-semibold py-2" disabled>
                                            Tidak Tersedia
                                        </button>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-md-12">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Tidak ada lapangan
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $fields->links() }}
    </div>
</div>

@push('scripts')
<script>
    document.querySelectorAll('.field-filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.field-filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const filter = this.dataset.filter;
            document.querySelectorAll('.field-item-col').forEach(col => {
                if (filter === 'all' || col.dataset.fieldType === filter) {
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
