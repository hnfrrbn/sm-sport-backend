@extends('layouts.app')

@section('title', 'Daftar Reservasi')

@section('content')
<div class="container py-3">
    <!-- Header Halaman -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4 pb-2 border-bottom">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">
                <i class="bi bi-calendar-check text-success me-2"></i>Daftar Reservasi
            </h1>
            <p class="text-muted small mb-0">Kelola dan pantau seluruh pemesanan lapangan pelanggan SM Sport Center.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.manual_booking') }}" class="btn btn-primary btn-sm px-3 py-2 fw-semibold shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> + Input Booking Manual
            </a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm px-3 py-2">
                <i class="bi bi-arrow-left me-1"></i> Dashboard
            </a>
        </div>
    </div>

    <!-- Kotak Pencarian & Filter Cepat -->
    <div class="card bg-white border shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.reservations.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-12 col-md-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white text-muted border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" 
                               name="q" 
                               class="form-control border-start-0 ps-0" 
                               placeholder="Cari Nama Pelanggan, Kode Booking, atau No. WhatsApp..." 
                               value="{{ $search ?? '' }}">
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">-- Semua Status --</option>
                        <option value="confirmed" @if(($status ?? '') === 'confirmed') selected @endif>Lunas / Terkonfirmasi</option>
                        <option value="pending" @if(($status ?? '') === 'pending') selected @endif>Menunggu Pembayaran WA</option>
                        <option value="completed" @if(($status ?? '') === 'completed') selected @endif>Selesai</option>
                        <option value="cancelled" @if(($status ?? '') === 'cancelled') selected @endif>Dibatalkan</option>
                    </select>
                </div>
                <div class="col-6 col-md-auto d-flex gap-1.5">
                    <button type="submit" class="btn btn-sm btn-primary px-3 fw-semibold">
                        <i class="bi bi-filter me-1"></i> Filter
                    </button>
                    @if(!empty($search) || !empty($status))
                        <a href="{{ route('admin.reservations.index') }}" class="btn btn-sm btn-outline-secondary px-2.5" title="Reset Pencarian">
                            <i class="bi bi-x-circle"></i> Reset
                        </a>
                    @endif
                </div>
                @if(!empty($search) || !empty($status))
                    <div class="col-12 mt-2 pt-2 border-top small text-muted">
                        Menampilkan hasil untuk: 
                        @if(!empty($search))
                            kata kunci "<strong>{{ $search }}</strong>"
                        @endif
                        @if(!empty($status))
                            status: <span class="badge bg-secondary-subtle text-dark border">{{ ucfirst($status) }}</span>
                        @endif
                        ({{ $reservations->total() }} data ditemukan)
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Tabel Daftar Reservasi -->
    <div class="card bg-white border shadow-sm mb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small text-muted">
                    <tr>
                        <th style="width: 50px;">NO</th>
                        <th>KODE BOOKING</th>
                        <th>PELANGGAN & WHATSAPP</th>
                        <th>LAPANGAN</th>
                        <th>TANGGAL MAIN</th>
                        <th>JAM MAIN</th>
                        <th>TOTAL</th>
                        <th>STATUS</th>
                        <th class="text-end" style="min-width: 140px;">AKSI</th>
                    </tr>
                </thead>
                <tbody class="small">
                    @forelse($reservations as $key => $reservation)
                        @php
                            // Ambil nama pelanggan yang sebenarnya (bukan nama akun admin)
                            $cName = trim($reservation->customer_name ?? '');
                            if (empty($cName) || strcasecmp($cName, 'admin') === 0) {
                                if ($reservation->user && ! $reservation->user->isAdmin()) {
                                    $cName = $reservation->user->name;
                                } else {
                                    $cName = 'Pelanggan Tamu';
                                }
                            }
                            $cPhone = $reservation->customer_phone ?: ($reservation->user?->phone ?? '-');
                            $cleanPhone = preg_replace('/[^0-9]/', '', $cPhone);

                            $indoDaysShort = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
                            $indoMonthsShort = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                            $rDate = \Carbon\Carbon::parse($reservation->reservation_date);
                            $dateStr = $indoDaysShort[$rDate->dayOfWeek] . ', ' . $rDate->day . ' ' . $indoMonthsShort[$rDate->month] . ' ' . $rDate->year;
                            $timeStr = \Carbon\Carbon::parse($reservation->start_time)->format('H:i') . ' - ' . \Carbon\Carbon::parse($reservation->end_time)->format('H:i') . ' WIB';
                        @endphp
                        <tr>
                            <td class="text-muted">{{ ($reservations->currentPage() - 1) * $reservations->perPage() + $key + 1 }}</td>
                            <td>
                                <span class="fw-bold font-monospace text-dark fs-6">{{ $reservation->code }}</span>
                                @if($reservation->notes)
                                    <div class="text-muted" style="font-size: 0.72rem;" title="{{ $reservation->notes }}">
                                        <i class="bi bi-chat-left-text me-1"></i>{{ Str::limit($reservation->notes, 25) }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong class="text-dark d-block">{{ $cName }}</strong>
                                @if($cleanPhone && strlen($cleanPhone) >= 6)
                                    <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" class="text-success fw-semibold" style="font-size: 0.75rem;">
                                        <i class="bi bi-whatsapp me-0.5"></i>{{ $cPhone }}
                                    </a>
                                @else
                                    <span class="text-muted" style="font-size: 0.75rem;">{{ $cPhone }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="fw-semibold text-dark">{{ $reservation->field->name }}</span>
                                <div class="text-muted text-uppercase" style="font-size: 0.7rem;">{{ $reservation->field->type }}</div>
                            </td>
                            <td>
                                <span class="fw-semibold text-dark">{{ $dateStr }}</span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border font-monospace py-1 px-2 fw-semibold">
                                    {{ $timeStr }}
                                </span>
                            </td>
                            <td>
                                <strong class="text-dark">Rp {{ number_format($reservation->total_price, 0, ',', '.') }}</strong>
                            </td>
                            <td>
                                @if($reservation->status === 'confirmed')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle py-1 px-2">
                                        <i class="bi bi-patch-check-fill me-1"></i> Lunas
                                    </span>
                                @elseif($reservation->status === 'cancelled')
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle py-1 px-2">
                                        <i class="bi bi-x-circle me-1"></i> Batal
                                    </span>
                                @elseif($reservation->status === 'completed')
                                    <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle py-1 px-2">
                                        <i class="bi bi-check2-all me-1"></i> Selesai
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle py-1 px-2">
                                        <i class="bi bi-clock-history me-1"></i> Menunggu Bayar
                                    </span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1 align-items-center">
                                    @if($reservation->status === 'pending')
                                        <form action="{{ route('admin.reservations.status', $reservation->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Konfirmasi LUNAS pesanan {{ $reservation->code }}?')">
                                            @csrf
                                            <input type="hidden" name="status" value="confirmed">
                                            <button type="submit" class="btn btn-sm btn-success py-0.5 px-2 fw-semibold" title="Konfirmasi Lunas">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.reservations.status', $reservation->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Batalkan pesanan {{ $reservation->code }}?')">
                                            @csrf
                                            <input type="hidden" name="status" value="cancelled">
                                            <button type="submit" class="btn btn-sm btn-outline-danger py-0.5 px-1.5" title="Batalkan">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('reservations.ticket', $reservation->id) }}?code={{ $reservation->code }}" class="btn btn-sm btn-outline-secondary py-0.5 px-2" target="_blank" title="Cetak Tiket/Struk">
                                        <i class="bi bi-printer"></i>
                                    </a>
                                    <a href="{{ route('admin.reservations.show', $reservation->id) }}" class="btn btn-sm btn-outline-primary py-0.5 px-2" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.reservations.destroy', $reservation->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus reservasi {{ $reservation->code }} secara permanen?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger py-0.5 px-1.5" title="Hapus Permanen">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                @if(!empty($search))
                                    Tidak ada reservasi yang cocok dengan kata kunci "<strong>{{ $search }}</strong>".
                                @else
                                    Belum ada data reservasi tercatat.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Bersih & Rapi Bahasa Indonesia -->
        <div class="card-footer bg-white border-top py-2.5 px-3 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
            <span class="small text-muted">
                Menampilkan {{ $reservations->firstItem() ?? 0 }} - {{ $reservations->lastItem() ?? 0 }} dari {{ $reservations->total() }} data
            </span>
            <div>
                {{ $reservations->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
