@extends('layouts.app')

@section('title', 'Daftar Reservasi')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h2">
                <i class="bi bi-calendar-check"></i> Daftar Reservasi
            </h1>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Pelanggan</th>
                        <th>Lapangan</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $key => $reservation)
                        <tr>
                            <td>{{ ($reservations->currentPage() - 1) * $reservations->perPage() + $key + 1 }}</td>
                            <td>{{ $reservation->user->name }}</td>
                            <td>{{ $reservation->field->name }}</td>
                            <td>{{ $reservation->reservation_date->format('d M Y') }}</td>
                            <td>{{ $reservation->start_time }} - {{ $reservation->end_time }}</td>
                            <td>Rp {{ number_format($reservation->total_price, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-{{ $reservation->status === 'confirmed' ? 'success' : ($reservation->status === 'pending' ? 'warning' : ($reservation->status === 'completed' ? 'info' : 'danger')) }}">
                                    {{ ucfirst($reservation->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.reservations.show', $reservation) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                                <div class="btn-group mt-2" role="group">
                                    <form action="{{ route('admin.reservations.status', $reservation) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="confirmed">
                                        <button type="submit" class="btn btn-sm btn-success" title="Konfirmasi">
                                            <i class="bi bi-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.reservations.destroy', $reservation) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Belum ada reservasi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $reservations->links() }}
    </div>
</div>
@endsection
