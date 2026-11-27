@extends('layouts.app')

@section('title', 'Login Admin - SM Sport Center')

@section('content')
<div class="container py-5 my-auto">
    <div class="row justify-content-center">
        <div class="col-11 col-sm-9 col-md-7 col-lg-5 col-xl-4">
            
            <!-- Tombol Kembali ke Beranda -->
            <div class="mb-3">
                <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1.5 shadow-none">
                    <i class="bi bi-arrow-left"></i>
                    <span>Kembali ke Beranda</span>
                </a>
            </div>

            <!-- Card Login Modern Centered -->
            <div class="card shadow-sm border bg-white rounded-3 p-4 p-sm-4">
                
                <!-- Header Card -->
                <div class="text-center mb-4">
                    <div class="brand-monogram mx-auto mb-2.5" style="width: 52px; height: 52px; font-size: 1.35rem; border-radius: 13px;">
                        SM
                    </div>
                    <h1 class="h5 fw-bold text-dark mb-1">Login Admin SM Sport Center</h1>
                    <p class="text-muted small mb-0">Masuk untuk mengelola jadwal, konfirmasi pembayaran, dan laporan keuangan.</p>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger py-2 px-3 small mb-3 border-0 shadow-sm d-flex align-items-center gap-2" style="background-color: #FEF2F2; border-left: 4px solid #EF4444 !important; color: #991B1B;" role="alert">
                        <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success py-2 px-3 small mb-3 border-0 shadow-sm d-flex align-items-center gap-2" style="background-color: #ECFDF5; border-left: 4px solid #059669 !important; color: #065F46;" role="alert">
                        <i class="bi bi-check-circle-fill flex-shrink-0"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" novalidate>
                    @csrf

                    <!-- Input Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label small fw-bold text-dark mb-1">
                            Alamat Email Admin
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-white text-muted border-end-0">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" 
                                   placeholder="admin@smsport.com" 
                                   required 
                                   autofocus>
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1 d-flex align-items-center gap-1">
                                <i class="bi bi-exclamation-circle-fill"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Input Password dengan Toggle Lihat/Sembunyikan -->
                    <div class="mb-3">
                        <label for="password" class="form-label small fw-bold text-dark mb-1">
                            Password
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-white text-muted border-end-0">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="form-control border-start-0 border-end-0 ps-0 @error('password') is-invalid @enderror"
                                   placeholder="••••••••" 
                                   required>
                            <button class="btn btn-outline-secondary border-start-0 bg-white text-muted px-2.5" 
                                    type="button" 
                                    id="togglePasswordBtn" 
                                    aria-label="Lihat / Sembunyikan Password"
                                    title="Tampilkan / Sembunyikan Password">
                                <i class="bi bi-eye" id="togglePasswordIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1 d-flex align-items-center gap-1">
                                <i class="bi bi-exclamation-circle-fill"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Ingat Saya -->
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="form-check">
                            <input type="checkbox" name="remember" id="remember" class="form-check-input" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label small text-muted user-select-none" for="remember">
                                Ingat sesi login saya
                            </label>
                        </div>
                    </div>

                    <!-- Tombol Masuk -->
                    <button type="submit" class="btn btn-primary w-100 py-2.5 fw-bold shadow-sm">
                        <i class="bi bi-box-arrow-in-right me-1.5"></i> Masuk ke Dashboard Admin
                    </button>
                </form>

                <!-- Footer Informasi Pengunjung -->
                <div class="mt-4 pt-3 border-top text-center">
                    <p class="text-muted small mb-0">
                        Bukan admin? <a href="{{ route('home') }}" class="text-success fw-bold text-decoration-none">Kembali ke Pemesanan Lapangan</a>.
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('togglePasswordBtn');
    const passwordInput = document.getElementById('password');
    const icon = document.getElementById('togglePasswordIcon');

    if (toggleBtn && passwordInput && icon) {
        toggleBtn.addEventListener('click', function () {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            if (isPassword) {
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    }
});
</script>
@endpush
