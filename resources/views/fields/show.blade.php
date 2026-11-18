@extends('layouts.app')

@section('title', $field->name)

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-building"></i> {{ $field->name }}
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>Jenis:</strong> {{ ucfirst($field->type) }}</p>
                            <p><strong>Kapasitas:</strong> {{ $field->capacity }} orang</p>
                            <p>
                                <strong>Status:</strong> 
                                <span class="badge bg-{{ $field->status === 'available' ? 'success' : ($field->status === 'maintenance' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($field->status) }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Harga per Jam:</strong> Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}</p>
                            <p><strong>Total Reservasi:</strong> {{ $field->reservations()->count() }}</p>
                            <p><strong>Dibuat:</strong> {{ $field->created_at->format('d M Y') }}</p>
                        </div>
                    </div>

                    @if($field->description)
                        <div class="alert alert-info mb-4">
                            <strong>Deskripsi:</strong><br>
                            {{ $field->description }}
                        </div>
                    @endif

                    <div class="d-grid gap-2">
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.fields.edit', $field) }}" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                        @else
                            @if($field->status === 'available')
                                <a href="{{ route('reservations.create') }}?field_id={{ $field->id }}" class="btn btn-success">
                                    <i class="bi bi-plus"></i> Pesan Sekarang
                                </a>
                            @else
                                <button class="btn btn-secondary" disabled>
                                    Lapangan Tidak Tersedia
                                </button>
                            @endif
                        @endif
                        <a href="{{ route('fields.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>

            @if(auth()->user()->isAdmin())
                <div class="card mt-4">
                    <div class="card-header">
                        <i class="bi bi-calendar-check"></i> Reservasi Terbaru
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Pelanggan</th>
                                    <th>Tanggal</th>
                                    <th>Jam</th>
                                    <th>Status</th>
                                    <th>Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($field->reservations()->latest()->take(10)->get() as $reservation)
                                    <tr>
                                        <td>{{ $reservation->user->name }}</td>
                                        <td>{{ $reservation->reservation_date->format('d M Y') }}</td>
                                        <td>{{ $reservation->start_time }} - {{ $reservation->end_time }}</td>
                                        <td>
                                            <span class="badge bg-{{ $reservation->status === 'confirmed' ? 'success' : ($reservation->status === 'pending' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($reservation->status) }}
                                            </span>
                                        </td>
                                        <td>Rp {{ number_format($reservation->total_price, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Belum ada reservasi</td>
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
