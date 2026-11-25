@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm border p-4 p-md-5">
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success-subtle text-success p-3 mb-3">
                        <i class="bi bi-person-circle fs-2"></i>
                    </div>
                    <h1 class="h3 fw-bold text-dark mb-1">Masuk ke Akun</h1>
                    <p class="text-muted small mb-0">Masuk untuk mengelola data reservasi atau akses panel admin.</p>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger py-2 small mb-3">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label small fw-bold text-dark">Alamat Email</label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" placeholder="nama@email.com" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label small fw-bold text-dark">Password</label>
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror"
                               placeholder="••••••••" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="form-check">
                            <input type="checkbox" name="remember" id="remember" class="form-check-input">
                            <label class="form-check-label small text-muted" for="remember">Ingat saya</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2.5">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Sekarang
                    </button>
                </form>

                <div class="mt-4 pt-3 border-top text-center">
                    <p class="text-muted small mb-2">
                        Pelanggan baru? Booking langsung tanpa login di <a href="{{ route('reservations.create') }}" class="text-success fw-bold">Pesan Lapangan</a>.
                    </p>
                    <p class="text-muted small mb-0">
                        Belum punya akun? <a href="{{ route('register') }}" class="text-success fw-bold">Daftar di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
