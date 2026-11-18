@extends('layouts.app')

@section('title', 'Detail Pengguna')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-person"></i> Detail Pengguna
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>Nama:</strong> {{ $user->name }}</p>
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                            <p><strong>No. Telepon:</strong> {{ $user->phone ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p>
                                <strong>Role:</strong>
                                <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'primary' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </p>
                            <p>
                                <strong>Status:</strong>
                                <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </p>
                            <p><strong>Terdaftar:</strong> {{ $user->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>

                    @if($user->address)
                        <div class="alert alert-info">
                            <strong>Alamat:</strong><br>
                            {{ $user->address }}
                        </div>
                    @endif

                    @if($user->isCustomer())
                        <div class="card mt-4">
                            <div class="card-header">
                                <i class="bi bi-calendar-check"></i> Statistik Reservasi
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <h4 class="text-primary">{{ $user->reservations()->count() }}</h4>
                                            <p class="text-muted">Total Reservasi</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <h4 class="text-success">{{ $user->reservations()->where('status', 'confirmed')->count() }}</h4>
                                            <p class="text-muted">Dikonfirmasi</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <h4 class="text-info">{{ $paidTransactionCount }}</h4>
                                            <p class="text-muted">Transaksi Sukses</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <h4 class="text-warning">Rp {{ number_format($paidTransactionRevenue, 0, ',', '.') }}</h4>
                                            <p class="text-muted">Total Bayar</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="d-grid gap-2 mt-4">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
