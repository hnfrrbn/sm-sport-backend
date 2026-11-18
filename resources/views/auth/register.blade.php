@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center align-items-center min-vh-75">
        <div class="col-xl-10">
            <div class="row g-4 align-items-stretch">
                <div class="col-lg-5">
                    <div class="glass-card premium-auth-shell h-100 p-4 p-lg-5">
                        <div class="hero-badge d-inline-flex mb-3">Buat Akun Premium</div>
                        <h1 class="h2 fw-bolder mb-3 text-primary">
                            <i class="bi bi-person-plus"></i> Daftar untuk mulai booking
                        </h1>
                        <p class="text-muted mb-4">Nikmati pengalaman reservasi yang lebih cepat, aman, dan terorganisir dari satu akun yang sama.</p>
                        <div class="d-grid gap-2 mb-4">
                            <div class="feature-bullet">
                                <i class="bi bi-check-circle-fill text-primary"></i>
                                <span>Booking lapangan dalam beberapa klik</span>
                            </div>
                            <div class="feature-bullet">
                                <i class="bi bi-check-circle-fill text-primary"></i>
                                <span>Pantau transaksi dan invoice otomatis</span>
                            </div>
                            <div class="feature-bullet">
                                <i class="bi bi-check-circle-fill text-primary"></i>
                                <span>Akses dashboard pengguna yang modern</span>
                            </div>
                        </div>
                        <div class="auth-highlight-card">
                            <div class="small text-muted mb-1">Fast Setup</div>
                            <div class="fw-bold text-primary">Daftar dalam hitungan menit dan langsung mulai reservasi</div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="glass-card p-4 p-lg-5 h-100">
                        <div class="text-center mb-4">
                            <h2 class="h3 fw-bolder mb-2">Informasi Akun</h2>
                            <p class="text-muted mb-0">Isi data berikut untuk membuat akun baru Anda.</p>
                        </div>

                        <form action="{{ route('register') }}" method="POST">
                            @csrf

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Nama Lengkap</label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="phone" class="form-label">No. Telepon</label>
                                    <input type="tel" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="08xxx" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="address" class="form-label">Alamat</label>
                                    <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" rows="3">{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 btn-lg mt-4">
                                <i class="bi bi-check-circle"></i> Daftar Sekarang
                            </button>
                        </form>

                        <p class="text-center mb-0 mt-4">
                            Sudah punya akun? <a href="{{ route('login') }}" class="text-primary fw-bold">Login di sini</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
