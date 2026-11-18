@extends('layouts.app')

@section('title', 'Admin Dashboard - SM Sport Center')

@section('content')
<div class="container-fluid">
    <!-- Hero Header & Quick Action Input Booking Manual -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card p-4 shadow-lg d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3" style="background: linear-gradient(135deg, #091325, #0f1d36); border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 24px;">
                <div>
                    <span class="badge bg-success bg-opacity-25 text-success px-3 py-1 rounded-pill fw-semibold mb-2">
                        <i class="bi bi-shield-lock-fill me-1"></i> Admin Control Center
                    </span>
                    <h1 class="h2 fw-bold text-white mb-1">Dashboard Admin SM Sport Center</h1>
                    <p class="text-white-50 mb-0">Pantau pendapatan harian, verifikasi bukti pembayaran, dan kelola booking kasir.</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.manual_booking') }}" class="btn btn-success btn-lg fw-bold px-4 py-3 shadow d-flex align-items-center gap-2" style="border-radius: 14px; background: linear-gradient(135deg, #16a34a, #22c55e); border: none;">
                        <i class="bi bi-plus-circle-fill fs-5"></i>
                        <span>+ Input Booking Manual (Walk-in)</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Ringkasan Hari Ini (Prioritas Utama Admin) -->
    <div class="row g-3 mb-4">
        <!-- Pendapatan Hari Ini -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-3 h-100 shadow-sm" style="background: #0f1d36; border: 1px solid rgba(34, 197, 94, 0.35); border-radius: 18px;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-white-50 small fw-semibold text-uppercase">Pendapatan Hari Ini</span>
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="background: rgba(34, 197, 94, 0.15); width: 42px; height: 42px;">
                        <i class="bi bi-cash-stack text-success fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1" style="color: #4ade80;">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h3>
                <small class="text-white-50"><i class="bi bi-calendar-check text-success"></i> Transaksi lunas hari ini</small>
            </div>
        </div>

        <!-- Booking Dibuat Hari Ini -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-3 h-100 shadow-sm" style="background: #0f1d36; border: 1px solid rgba(59, 130, 246, 0.35); border-radius: 18px;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-white-50 small fw-semibold text-uppercase">Booking Hari Ini</span>
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="background: rgba(59, 130, 246, 0.15); width: 42px; height: 42px;">
                        <i class="bi bi-calendar-plus text-primary fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-white mb-1">{{ $todayBookingsCount }} <span class="fs-6 fw-normal text-white-50">Booking</span></h3>
                <small class="text-white-50"><i class="bi bi-clock-history text-info"></i> Dibuat pada tanggal {{ date('d M Y') }}</small>
            </div>
        </div>

        <!-- Jadwal Main Hari Ini -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-3 h-100 shadow-sm" style="background: #0f1d36; border: 1px solid rgba(245, 158, 11, 0.35); border-radius: 18px;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-white-50 small fw-semibold text-uppercase">Jadwal Main Hari Ini</span>
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="background: rgba(245, 158, 11, 0.15); width: 42px; height: 42px;">
                        <i class="bi bi-dribbble text-warning fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-white mb-1">{{ $todayPlayingCount }} <span class="fs-6 fw-normal text-white-50">Sesi Main</span></h3>
                <small class="text-white-50"><i class="bi bi-geo-alt text-warning"></i> Pemakaian lapangan hari ini</small>
            </div>
        </div>

        <!-- Menunggu Verifikasi Bukti Bayar -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-3 h-100 shadow-sm" style="background: #0f1d36; border: 1px solid rgba(239, 68, 68, 0.35); border-radius: 18px;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-white-50 small fw-semibold text-uppercase">Perlu Verifikasi</span>
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="background: rgba(239, 68, 68, 0.15); width: 42px; height: 42px;">
                        <i class="bi bi-receipt-cutoff text-danger fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1" style="color: #f87171;">{{ $pendingVerifications->count() }} <span class="fs-6 fw-normal text-white-50">Menunggu</span></h3>
                <small class="text-white-50"><i class="bi bi-exclamation-circle text-danger"></i> Bukti transfer butuh approval</small>
            </div>
        </div>
    </div>

    <!-- Section 1-Klik Verifikasi Bukti Pembayaran -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-lg p-4" style="background: #0f1d36; border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 20px;">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom border-secondary border-opacity-25">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-danger rounded-pill px-2 py-1 small">{{ $pendingVerifications->count() }}</span>
                        <h4 class="h5 fw-bold text-white mb-0">
                            <i class="bi bi-check2-circle text-success me-1"></i> Verifikasi Bukti Pembayaran (1-Klik)
                        </h4>
                    </div>
                    <span class="text-white-50 small">Persetujuan instan mengubah status menjadi Lunas & Dikonfirmasi</span>
                </div>

                @if($pendingVerifications->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="color: #f1f5f9;">
                            <thead>
                                <tr class="text-white-50 small border-secondary border-opacity-25">
                                    <th>KODE BOOKING</th>
                                    <th>PELANGGAN & KONTAK</th>
                                    <th>LAPANGAN & JADWAL</th>
                                    <th>TOTAL TAGIHAN</th>
                                    <th>BUKTI BAYAR</th>
                                    <th>WAKTU UPLOAD</th>
                                    <th class="text-center" style="min-width: 170px;">AKSI 1-KLIK</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingVerifications as $trx)
                                    @php
                                        $rsv = $trx->reservation;
                                        $custName = $rsv?->customer_name ?: $trx->user?->name;
                                        $custPhone = $rsv?->customer_phone ?: $trx->user?->phone;
                                    @endphp
                                    <tr class="border-secondary border-opacity-25">
                                        <td>
                                            <span class="fw-bold font-monospace text-success">{{ $rsv?->code ?? $trx->transaction_code }}</span>
                                            <div class="text-white-50 small font-monospace">{{ $trx->transaction_code }}</div>
                                        </td>
                                        <td>
                                            <strong class="text-white">{{ $custName }}</strong>
                                            @if($custPhone)
                                                <div class="small">
                                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $custPhone) }}" target="_blank" rel="noopener" class="text-decoration-none text-info">
                                                        <i class="bi bi-whatsapp text-success me-1"></i> {{ $custPhone }}
                                                    </a>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary bg-opacity-50 text-white">{{ $rsv?->field?->name ?? 'Lapangan' }}</span>
                                            <div class="small text-white-50 mt-1">
                                                {{ $rsv?->reservation_date?->format('d M Y') }} • {{ substr($rsv?->start_time, 0, 5) }} - {{ substr($rsv?->end_time, 0, 5) }}
                                            </div>
                                        </td>
                                        <td>
                                            <strong class="fs-6" style="color: #4ade80;">Rp {{ number_format($trx->amount, 0, ',', '.') }}</strong>
                                            <div class="small text-white-50 text-uppercase">{{ $trx->payment_method }}</div>
                                        </td>
                                        <td>
                                            @if($trx->payment_proof)
                                                <button type="button" class="btn btn-sm btn-outline-info d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#proofModal{{ $trx->id }}">
                                                    <i class="bi bi-image"></i> Lihat Bukti
                                                </button>

                                                <!-- Modal Preview Bukti Bayar -->
                                                <div class="modal fade" id="proofModal{{ $trx->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                                        <div class="modal-content text-white" style="background: #0f1d36; border: 1px solid rgba(255, 255, 255, 0.15);">
                                                            <div class="modal-header border-secondary border-opacity-25">
                                                                <h5 class="modal-title">Bukti Pembayaran: {{ $rsv?->code ?? $trx->transaction_code }}</h5>
                                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body text-center p-3">
                                                                <img src="{{ asset($trx->payment_proof) }}" alt="Bukti Pembayaran" class="img-fluid rounded-3 shadow-lg" style="max-height: 520px; object-fit: contain;">
                                                            </div>
                                                            <div class="modal-footer border-secondary border-opacity-25 justify-content-between">
                                                                <span class="text-white-50 small">Pelanggan: {{ $custName }} (Rp {{ number_format($trx->amount, 0, ',', '.') }})</span>
                                                                <a href="{{ asset($trx->payment_proof) }}" target="_blank" class="btn btn-sm btn-outline-light">
                                                                    <i class="bi bi-box-arrow-up-right me-1"></i> Buka Ukuran Penuh
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-white-50 small">Belum diupload</span>
                                            @endif
                                        </td>
                                        <td class="small text-white-50">
                                            {{ $trx->payment_proof_uploaded_at?->diffForHumans() ?? $trx->updated_at->diffForHumans() }}
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <!-- Tombol Terima (Approve) 1-Klik -->
                                                <form action="{{ route('admin.transactions.verify', $trx->id) }}" method="POST" onsubmit="return confirm('Terima pembayaran ini dan konfirmasi jadwal reservasi?')">
                                                    @csrf
                                                    <input type="hidden" name="action" value="approve">
                                                    <button type="submit" class="btn btn-sm btn-success fw-bold px-3 py-1 shadow-sm d-flex align-items-center gap-1">
                                                        <i class="bi bi-check-lg fs-6"></i> Terima
                                                    </button>
                                                </form>

                                                <!-- Tombol Tolak (Reject) 1-Klik -->
                                                <form action="{{ route('admin.transactions.verify', $trx->id) }}" method="POST" onsubmit="return confirm('Tolak pembayaran ini dan batalkan jadwal reservasi?')">
                                                    @csrf
                                                    <input type="hidden" name="action" value="reject">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger fw-semibold px-2 py-1">
                                                        <i class="bi bi-x-lg"></i> Tolak
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-check-circle-fill text-success fs-1 d-block mb-2"></i>
                        <h6 class="text-white fw-bold mb-1">Semua Bukti Pembayaran Telah Diverifikasi</h6>
                        <p class="text-white-50 small mb-0">Tidak ada bukti transfer yang tertunda saat ini. Booking baru akan otomatis muncul di sini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Ringkasan Statistik Global & Grafik -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card p-3" style="background: #0f1d36; border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 18px;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-white-50 small text-uppercase fw-semibold d-block">Total Pelanggan</span>
                        <h4 class="fw-bold text-white mb-0">{{ $totalUsers }}</h4>
                    </div>
                    <div class="p-2 rounded-circle bg-primary bg-opacity-20 text-primary">
                        <i class="bi bi-people fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card p-3" style="background: #0f1d36; border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 18px;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-white-50 small text-uppercase fw-semibold d-block">Total Lapangan</span>
                        <h4 class="fw-bold text-white mb-0">{{ $totalFields }} Lapangan</h4>
                    </div>
                    <div class="p-2 rounded-circle bg-info bg-opacity-20 text-info">
                        <i class="bi bi-building fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card p-3" style="background: #0f1d36; border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 18px;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-white-50 small text-uppercase fw-semibold d-block">Total Seluruh Reservasi</span>
                        <h4 class="fw-bold text-white mb-0">{{ $totalReservations }}</h4>
                    </div>
                    <div class="p-2 rounded-circle bg-warning bg-opacity-20 text-warning">
                        <i class="bi bi-calendar-check fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card p-3" style="background: #0f1d36; border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 18px;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-white-50 small text-uppercase fw-semibold d-block">Total Revenue</span>
                        <h4 class="fw-bold text-white mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h4>
                    </div>
                    <div class="p-2 rounded-circle bg-success bg-opacity-20 text-success">
                        <i class="bi bi-credit-card fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Reservasi & Revenue 7 Hari Terakhir -->
    <div class="row g-4 mb-4">
        <div class="col-xl-6">
            <div class="card p-4 h-100 shadow-sm" style="background: #0f1d36; border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 20px;">
                <h5 class="card-title text-white fw-bold mb-3">
                    <i class="bi bi-bar-chart text-primary me-2"></i> Grafik Reservasi (7 Hari Terakhir)
                </h5>
                <div class="chart-panel" style="min-height: 280px;">
                    <canvas id="reservationChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card p-4 h-100 shadow-sm" style="background: #0f1d36; border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 20px;">
                <h5 class="card-title text-white fw-bold mb-3">
                    <i class="bi bi-graph-up text-success me-2"></i> Grafik Pendapatan (7 Hari Terakhir)
                </h5>
                <div class="chart-panel" style="min-height: 280px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Reservasi Terbaru -->
    <div class="row">
        <div class="col-12">
            <div class="card p-4 shadow-sm" style="background: #0f1d36; border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 20px;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title text-white fw-bold mb-0">
                        <i class="bi bi-calendar2-range text-info me-2"></i> Reservasi Terbaru
                    </h5>
                    <a href="{{ route('admin.reservations.index') }}" class="btn btn-sm btn-outline-light">
                        Lihat Semua Reservasi
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="color: #f1f5f9;">
                        <thead>
                            <tr class="text-white-50 small border-secondary border-opacity-25">
                                <th>KODE</th>
                                <th>PELANGGAN</th>
                                <th>LAPANGAN</th>
                                <th>TANGGAL & JAM</th>
                                <th>STATUS</th>
                                <th>TOTAL</th>
                                <th class="text-end">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentReservations as $rsv)
                                <tr class="border-secondary border-opacity-25">
                                    <td><strong class="text-success font-monospace">{{ $rsv->code }}</strong></td>
                                    <td>
                                        <div class="text-white fw-semibold">{{ $rsv->customer_name ?: $rsv->user->name }}</div>
                                        <div class="text-white-50 small">{{ $rsv->customer_phone ?: $rsv->user->phone }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary bg-opacity-50 text-white">{{ $rsv->field->name }}</span>
                                    </td>
                                    <td class="small">
                                        <div class="text-white">{{ $rsv->reservation_date->format('d M Y') }}</div>
                                        <div class="text-white-50">{{ substr($rsv->start_time, 0, 5) }} - {{ substr($rsv->end_time, 0, 5) }} WIB</div>
                                    </td>
                                    <td>
                                        @if($rsv->status === 'confirmed')
                                            <span class="badge bg-success">Terkonfirmasi</span>
                                        @elseif($rsv->status === 'cancelled')
                                            <span class="badge bg-danger">Dibatalkan</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong style="color: #4ade80;">Rp {{ number_format($rsv->total_price, 0, ',', '.') }}</strong>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-1">
                                            <a href="{{ route('reservations.ticket', $rsv->id) }}" class="btn btn-sm btn-outline-success" target="_blank" title="Cetak Tiket/Struk">
                                                <i class="bi bi-printer"></i>
                                            </a>
                                            <a href="{{ route('admin.reservations.show', $rsv->id) }}" class="btn btn-sm btn-outline-light" title="Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-white-50 py-3">Belum ada data reservasi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Reservation Chart
    const resCtx = document.getElementById('reservationChart')?.getContext('2d');
    if (resCtx) {
        new Chart(resCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($reservationData->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))) !!},
                datasets: [{
                    label: 'Reservasi',
                    data: {!! json_encode($reservationData->pluck('count')) !!},
                    borderColor: '#22c55e',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { ticks: { color: '#94a3b8' }, grid: { color: 'rgba(255,255,255,0.05)' } },
                    y: { ticks: { color: '#94a3b8', stepSize: 1 }, grid: { color: 'rgba(255,255,255,0.05)' } }
                }
            }
        });
    }

    // Revenue Chart
    const revCtx = document.getElementById('revenueChart')?.getContext('2d');
    if (revCtx) {
        new Chart(revCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($revenueData->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))) !!},
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: {!! json_encode($revenueData->pluck('total')) !!},
                    backgroundColor: '#16a34a',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { ticks: { color: '#94a3b8' }, grid: { color: 'rgba(255,255,255,0.05)' } },
                    y: { ticks: { color: '#94a3b8' }, grid: { color: 'rgba(255,255,255,0.05)' } }
                }
            }
        });
    }
</script>
@endpush
@endsection
