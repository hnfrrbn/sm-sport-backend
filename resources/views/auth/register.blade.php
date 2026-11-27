@extends('layouts.app')

@section('title', 'Daftar Akun')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card shadow-sm border p-4 p-md-5">
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success-subtle text-success p-3 mb-3">
                        <i class="bi bi-person-plus-fill fs-2"></i>
                    </div>
                    <h1 class="h3 fw-bold text-dark mb-1">Daftar Akun Baru</h1>
                    <p class="text-muted small mb-0">Buat akun untuk riwayat booking yang lebih terorganisir.</p>
                </div>

                <form action="{{ route('register') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <div class="col-12">
                            <label for="name" class="form-label small fw-bold text-dark">Nama Lengkap</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name') }}" placeholder="Contoh: Budi Santoso" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label small fw-bold text-dark">Email</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email') }}" placeholder="nama@email.com" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label small fw-bold text-dark">No. WhatsApp / HP</label>
                            <input type="tel" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" 
                                   value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password" class="form-label small fw-bold text-dark">Password</label>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" 
                                   placeholder="Minimal 8 karakter" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label small fw-bold text-dark">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" 
                                   placeholder="Ulangi password" required>
                        </div>

                        <div class="col-12">
                            <label for="address" class="form-label small fw-bold text-dark">Alamat (Opsional)</label>
                            <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" 
                                      rows="2" placeholder="Alamat singkat...">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2.5 mt-4">
                        <i class="bi bi-check-circle me-1"></i> Selesaikan Pendaftaran
                    </button>
                </form>

                <div class="mt-4 pt-3 border-top text-center">
                    <p class="text-muted small mb-0">
                        Sudah punya akun? <a href="{{ route('login') }}" class="text-success fw-bold">Masuk di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
