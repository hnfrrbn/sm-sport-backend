@extends('layouts.app')

@section('title', $field->name . ' - Detail Lapangan')

@section('content')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Navigasi Balik -->
            <div class="mb-3">
                <a href="{{ route('fields.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Lapangan
                </a>
            </div>

            <!-- Card Detail Lapangan Murni Teks & Info (Tanpa Gambar/Kotak Dekoratif) -->
            <div class="card shadow-sm border bg-white p-4 mb-4">
                @php
                    $isBadminton = strtolower($field->type) === 'badminton';
                    $categoryName = $isBadminton ? 'Badminton' : 'Futsal';
                    $floorType = $isBadminton 
                        ? 'Karpet Vinyl Standar BWF' 
                        : (str_contains($field->name, '1') ? 'Interlock Vinyl FIFA' : 'Rumput Sintetis Premium');
                    $playerCap = $isBadminton ? '4 Pemain (Ganda / Tunggal)' : '10 Pemain (5 vs 5)';
                @endphp

                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge {{ $isBadminton ? 'bg-primary-subtle text-primary border border-primary-subtle' : 'bg-success-subtle text-success border border-success-subtle' }} text-uppercase fw-bold">
                            {{ $categoryName }}
                        </span>
                        <span class="badge {{ $field->status === 'available' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} border">
                            <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i>
                            {{ $field->status === 'available' ? 'Tersedia' : 'Dalam Perawatan' }}
                        </span>
                    </div>
                    <div class="small text-muted">
                        <i class="bi bi-clock text-success me-1"></i> 08:00 - 23:00 WIB
                    </div>
                </div>

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4">
                    <div>
                        <h1 class="h3 fw-bold text-dark mb-1">{{ $field->name }}</h1>
                        <p class="text-muted small mb-0">
                            <i class="bi bi-geo-alt-fill text-danger me-1"></i> SM Sport Center - Jati Padang, Pasar Minggu, Jakarta Selatan
                        </p>
                    </div>
                    <div class="text-md-end">
                        <div class="small text-muted">Tarif Sewa:</div>
                        <span class="fs-3 fw-bolder text-success">Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}</span>
                        <span class="small text-muted">/ jam</span>
                    </div>
                </div>

                <!-- Rincian Spesifikasi Lapangan -->
                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <small class="text-muted d-block mb-1">Jenis Lantai / Permukaan:</small>
                            <strong class="text-dark">{{ $floorType }}</strong>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <small class="text-muted d-block mb-1">Kapasitas Maksimal:</small>
                            <strong class="text-dark">{{ $playerCap }}</strong>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <small class="text-muted d-block mb-1">Jam Operasional:</small>
                            <strong class="text-dark">08:00 - 23:00 WIB</strong>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <small class="text-muted d-block mb-1">Fasilitas Termasuk:</small>
                            <strong class="text-dark">Lampu Lapangan, Ruang Ganti, Parkir</strong>
                        </div>
                    </div>
                </div>

                @if($field->description)
                    <div class="alert alert-light border mb-4">
                        <strong class="text-dark d-block mb-1">Informasi Lapangan:</strong>
                        <p class="text-muted small mb-0">{{ $field->description }}</p>
                    </div>
                @endif

                <!-- Tombol Aksi -->
                <div class="d-flex flex-column flex-sm-row gap-2">
                    @if($field->status === 'available')
                        <a href="{{ route('reservations.create') }}?field_id={{ $field->id }}" class="btn btn-primary flex-grow-1 py-2.5 fw-bold">
                            Pilih Jadwal Lapangan Ini
                        </a>
                    @else
                        <button class="btn btn-secondary flex-grow-1 py-2.5" disabled>
                            Lapangan Sedang Tidak Tersedia
                        </button>
                    @endif

                    @if(auth()->check() && auth()->user()->isAdmin())
                        <a href="{{ route('admin.fields.edit', $field) }}" class="btn btn-warning py-2.5 px-4">
                            <i class="bi bi-pencil me-1"></i> Edit Lapangan
                        </a>
                    @endif
                </div>
            </div>

            <!-- Bagian Admin: Reservasi Lapangan -->
            @if(auth()->check() && auth()->user()->isAdmin())
                <div class="card shadow-sm border mt-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h2 class="h6 fw-bold text-dark mb-0">
                            Riwayat Reservasi Lapangan (Panel Admin)
                        </h2>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light small text-muted">
                                <tr>
                                    <th>Pelanggan</th>
                                    <th>Tanggal</th>
                                    <th>Jam Main</th>
                                    <th>Status</th>
                                    <th>Total Biaya</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                                @forelse($field->reservations()->latest()->take(10)->get() as $res)
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $res->user ? $res->user->name : ($res->customer_name ?? 'Tamu') }}</div>
                                            <div class="text-muted" style="font-size: 0.75rem;">{{ $res->user ? $res->user->phone : ($res->customer_phone ?? '-') }}</div>
                                        </td>
                                        <td>{{ $res->reservation_date ? \Carbon\Carbon::parse($res->reservation_date)->format('d M Y') : '-' }}</td>
                                        <td>{{ substr($res->start_time, 0, 5) }} - {{ substr($res->end_time, 0, 5) }} WIB</td>
                                        <td>
                                            @if($res->status === 'confirmed')
                                                <span class="badge bg-success-subtle text-success border border-success-subtle">Terkonfirmasi</span>
                                            @elseif($res->status === 'pending')
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle">Menunggu</span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">{{ ucfirst($res->status) }}</span>
                                            @endif
                                        </td>
                                        <td class="fw-bold text-dark">Rp {{ number_format($res->total_price, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">Belum ada reservasi untuk lapangan ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
