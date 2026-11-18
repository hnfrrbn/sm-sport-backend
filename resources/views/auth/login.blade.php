@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center align-items-center min-vh-75">
        <div class="col-xl-10">
            <div class="row g-4 align-items-stretch">
                <div class="col-lg-5">
                    <div class="glass-card premium-auth-shell h-100 p-4 p-lg-5">
                        <div class="hero-badge d-inline-flex mb-3">Selamat Datang</div>
                        <h1 class="h2 fw-bolder mb-3 text-primary">
                            <i class="bi bi-box-arrow-in-right"></i> Masuk ke akun Anda
                        </h1>
                        <p class="text-muted mb-4">Lanjutkan reservasi, cek pembayaran, dan lihat invoice dari satu dashboard yang nyaman.</p>
                        <div class="d-grid gap-2 mb-4">
                            <div class="feature-bullet">
                                <i class="bi bi-check-circle-fill text-primary"></i>
                                <span>Akses cepat ke riwayat reservasi</span>
                            </div>
                            <div class="feature-bullet">
                                <i class="bi bi-check-circle-fill text-primary"></i>
                                <span>Pantau status transaksi real-time</span>
                            </div>
                            <div class="feature-bullet">
                                <i class="bi bi-check-circle-fill text-primary"></i>
                                <span>Kelola pembayaran dengan lebih tenang</span>
                            </div>
                        </div>
                        <div class="auth-highlight-card">
                            <div class="small text-muted mb-1">Experience</div>
                            <div class="fw-bold text-primary">Booking yang terasa premium, cepat, dan aman</div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="glass-card p-4 p-lg-5 h-100">
                        <div class="text-center mb-4">
                            <h2 class="h3 fw-bolder mb-2">Login</h2>
                            <p class="text-muted mb-0">Silakan masukkan email dan password Anda.</p>
                        </div>

                        <form action="{{ route('login') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}" required autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" name="remember" id="remember" class="form-check-input">
                                <label class="form-check-label" for="remember">Ingat saya</label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 btn-lg">
                                <i class="bi bi-check-circle"></i> Masuk
                            </button>
                        </form>

                        <p class="text-center mt-4 mb-0">
                            Belum punya akun? <a href="{{ route('register') }}" class="text-primary fw-bold">Daftar di sini</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
