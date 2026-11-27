@extends('layouts.app')

@section('title', 'Dashboard Pelanggan')

@section('content')
<div class="container-fluid">
    <div class="modern-shell">
        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="premium-hero-card h-100 p-4 overflow-hidden" style="background: linear-gradient(135deg, rgba(6, 20, 45, 0.98), rgba(22, 163, 74, 0.28), rgba(255,255,255,0.08)); border: 1px solid rgba(22, 163, 74, 0.24); box-shadow: 0 30px 96px rgba(0, 0, 0, 0.34); border-radius: 28px;">
                    <div class="hero-badge" style="background: rgba(34,197,94,0.20); color: #d9f99d; border: 1px solid rgba(255,255,255,0.08);">Customer Dashboard</div>
                    <div class="d-flex flex-column gap-4 h-100">
                        <div>
                            <h1 class="display-6 fw-bolder mb-2">Halo, {{ auth()->user()->name }}</h1>
                            <p class="text-muted mb-4">Pantau semua reservasi dan transaksi Anda dalam satu dashboard yang modern, cepat, dan profesional.</p>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="dashboard-pill"><i class="bi bi-stars"></i> Reservasi paling baru</span>
                            <span class="dashboard-pill"><i class="bi bi-bell"></i> Status transaksi terpantau</span>
                            <span class="dashboard-pill"><i class="bi bi-lightning-charge"></i> Siap booking</span>
                        </div>
                        <div class="row row-cols-1 row-cols-md-3 g-3">
                            <div class="col">
                                <div class="hero-card-stat">
                                    <div class="hero-card-stat-title">Total Reservasi</div>
                                    <div class="hero-card-stat-value">{{ auth()->user()->reservations()->count() }}</div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="hero-card-stat">
                                    <div class="hero-card-stat-title">Dikonfirmasi</div>
                                    <div class="hero-card-stat-value">{{ auth()->user()->reservations()->where('status', 'confirmed')->count() }}</div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="hero-card-stat">
                                    <div class="hero-card-stat-title">Pendapatan</div>
                                    <div class="hero-card-stat-value">Rp {{ number_format(auth()->user()->transactions()->whereIn('status', ['completed', 'paid'])->sum('amount'), 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="glass-card h-100 p-4" style="background: rgba(8, 14, 32, 0.96); border: 1px solid rgba(255,255,255,0.10); box-shadow: 0 20px 60px rgba(0,0,0,0.18);">
                    <div class="hero-card-top mb-3">
                        <span class="dashboard-pill"><i class="bi bi-calendar-check text-primary"></i> Siap reservasi</span>
                    </div>
                    <h4 class="fw-bold mb-2">Reservasi lapangan favorit Anda</h4>
                    <p class="text-muted mb-4">Pilih slot paling nyaman, lihat estimasi biaya, dan lanjutkan pembayaran tanpa kerepotan.</p>
                    <a href="{{ route('reservations.create') }}" class="btn btn-primary w-100">Buat Reservasi Baru</a>
                    <div class="legend-row mt-4">
                        <div class="legend-chip green"><i class="bi bi-check-circle"></i> Cepat & terpercaya</div>
                        <div class="legend-chip gray"><i class="bi bi-clock"></i> Jadwal jelas</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="glass-stat-card skeleton-target">
                    <div class="stat-icon text-primary"><i class="bi bi-calendar-check"></i></div>
                    <div class="stat-value">{{ auth()->user()->reservations()->count() }}</div>
                    <div class="stat-label">Total Reservasi</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-stat-card skeleton-target">
                    <div class="stat-icon text-success"><i class="bi bi-check-circle"></i></div>
                    <div class="stat-value">{{ auth()->user()->reservations()->where('status', 'confirmed')->count() }}</div>
                    <div class="stat-label">Dikonfirmasi</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-stat-card skeleton-target">
                    <div class="stat-icon text-warning"><i class="bi bi-clock-history"></i></div>
                    <div class="stat-value">{{ auth()->user()->reservations()->where('status', 'pending')->count() }}</div>
                    <div class="stat-label">Pending</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-stat-card skeleton-target">
                    <div class="stat-icon text-info"><i class="bi bi-credit-card"></i></div>
                    <div class="stat-value">Rp {{ number_format(auth()->user()->transactions()->whereIn('status', ['completed', 'paid'])->sum('amount'), 0, ',', '.') }}</div>
                    <div class="stat-label">Total Pembayaran</div>
                </div>
            </div>
        </div>

        @if(isset($availableFields) && $availableFields->isNotEmpty())
            <div class="row g-4 mb-4">
                <div class="col-12">
                    <div class="section-header-row mb-2">
                        <h3 class="mb-0 text-white"><i class="bi bi-building"></i> Lapangan Tersedia</h3>
                        <a href="{{ route('fields.index') }}" class="text-link">Lihat semua lapangan</a>
                    </div>
                    <!-- Filter Lapangan: Semua / Futsal / Badminton -->
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                        <span class="text-white-50 small me-2"><i class="bi bi-funnel-fill text-success"></i> Filter:</span>
                        <button type="button" class="btn btn-sm px-3 py-1 rounded-pill field-filter-btn active" data-filter="all">
                            Semua Lapangan ({{ $availableFields->count() }})
                        </button>
                        <button type="button" class="btn btn-sm px-3 py-1 rounded-pill field-filter-btn" data-filter="futsal">
                            Futsal ({{ $availableFields->where('type', 'futsal')->count() }})
                        </button>
                        <button type="button" class="btn btn-sm px-3 py-1 rounded-pill field-filter-btn" data-filter="badminton">
                            Badminton ({{ $availableFields->where('type', 'badminton')->count() }})
                        </button>
                    </div>
                </div>
                @foreach($availableFields as $field)
                    <div class="col-md-6 col-lg-4 field-item-col" data-field-type="{{ strtolower($field->type) }}">
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
                                        <i class="bi {{ strtolower($field->type) === 'badminton' ? 'bi-feather text-info' : 'bi-trophy text-success' }} me-1"></i>
                                        {{ $field->type }}
                                    </span>
                                </div>
                                <div class="position-absolute top-0 end-0 m-3">
                                    <span class="badge text-uppercase fw-bold px-3 py-2 shadow-sm rounded-pill" 
                                          style="backdrop-filter: blur(8px); background: rgba(0, 0, 0, 0.4); color: #4ade80; border: 1px solid rgba(74, 222, 128, 0.3);">
                                        <i class="bi bi-check-circle-fill me-1"></i> Tersedia
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

                                <a href="{{ route('reservations.create') }}?field_id={{ $field->id }}" 
                                   class="btn btn-primary btn-lg w-100 fw-bold py-3 shadow-lg d-flex align-items-center justify-content-center gap-2"
                                   style="font-size: 1.15rem; border-radius: 14px; background: linear-gradient(135deg, #16a34a, #22c55e); border: none;">
                                    <i class="bi bi-calendar2-check-fill fs-5"></i>
                                    <span>Pesan Sekarang</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="glass-card h-100">
                    <div class="section-header-row">
                        <h3 class="mb-0"><i class="bi bi-calendar2-week"></i> Reservasi Terbaru</h3>
                        <a href="{{ route('customer.reservations') }}" class="text-link">Lihat semua</a>
                    </div>
                    <div class="skeleton-loader-wrapper d-none">
                        <div class="skeleton-line w-100 mb-2"></div>
                        <div class="skeleton-line w-75 mb-2"></div>
                        <div class="skeleton-line w-100 mb-2"></div>
                        <div class="skeleton-line w-50"></div>
                    </div>
                    <div class="list-stack">
                        @forelse(auth()->user()->reservations()->latest()->take(5)->get() as $reservation)
                            <div class="reservation-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $reservation->field->name }}</strong>
                                    <p class="text-muted mb-1">{{ $reservation->reservation_date->format('d M Y') }} • {{ $reservation->start_time }} - {{ $reservation->end_time }}</p>
                                    <div class="legend-row gap-2">
                                        <span class="legend-chip gray">Kode: {{ $reservation->code ?? '-' }}</span>
                                        <span class="legend-chip green">{{ ucfirst($reservation->field->type ?? 'Lapangan') }}</span>
                                        <a href="{{ route('reservations.ticket', $reservation->id) }}" class="badge bg-success text-decoration-none" target="_blank">
                                            <i class="bi bi-printer"></i> Tiket
                                        </a>
                                    </div>
                                </div>
                                <span class="status-badge {{ $reservation->status === 'confirmed' ? 'status-confirmed' : ($reservation->status === 'pending' ? 'status-pending' : 'status-cancelled') }}">
                                    {{ ucfirst($reservation->status) }}
                                </span>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">Belum ada reservasi.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="glass-card h-100">
                    <div class="section-header-row">
                        <h3 class="mb-0"><i class="bi bi-buildings"></i> Lapangan Populer</h3>
                    </div>
                    <canvas id="fieldChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const fieldChart = new Chart(document.getElementById('fieldChart'), {
        type: 'doughnut',
        data: {
            labels: ['Futsal 1', 'Futsal 2', 'Badminton 1', 'Badminton 2', 'Badminton 3'],
            datasets: [{
                data: [5, 4, 3, 6, 2],
                backgroundColor: ['#16a34a', '#22c55e', '#4ade80', '#86efac', '#bbf7d0']
            }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });

    const skeletonTargets = document.querySelectorAll('.skeleton-target');
    setTimeout(() => {
        document.querySelector('.skeleton-loader-wrapper')?.classList.add('d-none');
        skeletonTargets.forEach(el => el.classList.add('is-visible'));
    }, 700);

    // Field Filter
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
