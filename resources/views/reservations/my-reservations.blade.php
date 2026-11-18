@extends('layouts.app')

@section('title', 'Reservasi Saya')

@section('content')
<div class="container-fluid">
    <div class="modern-shell">
        <div class="row align-items-center justify-content-between mb-4">
            <div class="col-lg-8">
                <div class="hero-badge">Reservasi Saya</div>
                <h1 class="display-6 fw-bolder mb-1">Kelola semua booking Anda dengan lebih nyaman</h1>
                <p class="text-muted mb-0">Pantau status, cek detail, dan batalkan reservasi kapan saja dari satu tempat.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('reservations.create') }}" class="btn btn-primary btn-lg shadow-soft">
                    <i class="bi bi-plus-circle"></i> Buat Reservasi Baru
                </a>
            </div>
        </div>

        <div class="glass-card">
            <div class="section-header-row">
                <h3 class="mb-0"><i class="bi bi-calendar2-week"></i> Riwayat Reservasi</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Lapangan</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Durasi</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservations as $key => $reservation)
                            <tr>
                                <td>{{ ($reservations->currentPage() - 1) * $reservations->perPage() + $key + 1 }}</td>
                                <td><strong>{{ $reservation->field->name }}</strong></td>
                                <td>{{ $reservation->reservation_date->format('d M Y') }}</td>
                                <td>{{ $reservation->start_time }} - {{ $reservation->end_time }}</td>
                                <td>{{ $reservation->duration_hours }} jam</td>
                                <td>Rp {{ number_format($reservation->total_price, 0, ',', '.') }}</td>
                                <td>
                                    <span class="status-badge {{ $reservation->status === 'confirmed' ? 'status-confirmed' : ($reservation->status === 'pending' ? 'status-pending' : 'status-cancelled') }}">
                                        {{ ucfirst($reservation->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-2">
                                        <a href="{{ route('reservations.show', $reservation) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                        <a href="{{ route('reservations.ticket', $reservation) }}" class="btn btn-sm btn-outline-success" target="_blank">
                                            <i class="bi bi-printer"></i> Cetak Tiket
                                        </a>
                                        @if($reservation->status === 'pending' || $reservation->status === 'confirmed')
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-open-cancel-modal" 
                                                data-reservation-id="{{ $reservation->id }}"
                                                data-field-name="{{ $reservation->field->name }}"
                                                data-date="{{ $reservation->reservation_date->format('d M Y') }}"
                                                data-time="{{ $reservation->start_time }} - {{ $reservation->end_time }}">
                                                <i class="bi bi-x-circle"></i> Batalkan
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Belum ada reservasi yang dibuat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    <!-- Cancellation modal -->
    <div class="modal fade" id="cancelReservationModal" tabindex="-1" aria-labelledby="cancelReservationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="cancelReservationForm" method="POST" action="">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="cancelReservationModalLabel">Batalkan Reservasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-danger"><strong>Peringatan DP:</strong> Pembatalan reservasi akan melepaskan slot jam ini. Kebijakan DP sesuai aturan pengelola.</p>
                        <p><strong>Reservasi:</strong> <span id="cancel-reservation-info"></span></p>
                        <div class="mb-3">
                            <label for="cancellation_reason" class="form-label">Alasan Pembatalan <span class="text-danger">*</span></label>
                            <textarea id="cancellation_reason" name="cancellation_reason" rows="4" class="form-control" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-danger">Konfirmasi Batalkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-open-cancel-modal');
            if (!btn) return;
            const id = btn.dataset.reservationId;
            const info = btn.dataset.fieldName + ' — ' + btn.dataset.date + ' / ' + btn.dataset.time;
            document.getElementById('cancel-reservation-info').textContent = info;
            const form = document.getElementById('cancelReservationForm');
            form.action = '/reservations/' + id + '/cancel';
            const modal = new bootstrap.Modal(document.getElementById('cancelReservationModal'));
            modal.show();
    });
    </script>
    @endpush
        <div class="d-flex justify-content-center mt-4">
            {{ $reservations->links() }}
        </div>
    </div>
</div>
@endsection
