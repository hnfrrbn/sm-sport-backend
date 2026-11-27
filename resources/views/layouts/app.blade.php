<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SM Sport Center - Booking Lapangan Futsal & Badminton Mudah, Cepat, dan Instan Tanpa Ribet.">
    <meta name="theme-color" content="#059669">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/sm-sport-logo.svg') }}">
    <title>@yield('title', 'Booking Lapangan Futsal & Badminton') - SM Sport Center</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --bg-page: #F8FAFC;
            --bg-card: #FFFFFF;
            --text-heading: #0F172A;
            --text-body: #334155;
            --text-muted: #64748B;
            --primary-emerald: #059669;
            --primary-emerald-hover: #047857;
            --primary-emerald-light: #ECFDF5;
            --primary-emerald-border: #A7F3D0;
            --border-subtle: #E2E8F0;
            --font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        html, body {
            max-width: 100vw;
            overflow-x: hidden;
        }

        body {
            background-color: var(--bg-page) !important;
            color: var(--text-body) !important;
            font-family: var(--font-family) !important;
            font-size: 0.935rem;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        h1, h2, h3, h4, h5, h6,
        .h1, .h2, .h3, .h4, .h5, .h6 {
            color: var(--text-heading);
            font-weight: 700;
            letter-spacing: -0.015em;
        }

        a {
            color: var(--primary-emerald);
            text-decoration: none;
        }

        a:hover {
            color: var(--primary-emerald-hover);
        }

        /* Navbar: Bersih & Minimalis */
        .site-navbar {
            background: #FFFFFF;
            border-bottom: 1px solid var(--border-subtle);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.15rem;
            color: var(--text-heading) !important;
            display: flex;
            align-items: center;
            gap: 0.65rem;
        }

        .brand-monogram {
            width: 36px;
            height: 36px;
            background: var(--primary-emerald);
            color: #FFFFFF !important;
            font-weight: 800;
            font-size: 0.92rem;
            letter-spacing: -0.04em;
            border-radius: 9px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 4px rgba(5, 150, 105, 0.25);
            flex-shrink: 0;
            line-height: 1;
            user-select: none;
        }

        .brand-badge {
            width: 36px;
            height: 36px;
            background: var(--primary-emerald-light);
            border: 1px solid var(--primary-emerald-border);
            border-radius: 9px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-emerald);
            font-size: 1.15rem;
        }

        .site-navbar .nav-link {
            color: var(--text-body) !important;
            font-weight: 500;
            font-size: 0.92rem;
            padding: 0.5rem 0.85rem !important;
            border-radius: 7px;
            transition: all 0.15s ease;
        }

        .site-navbar .nav-link:hover {
            color: var(--primary-emerald) !important;
            background-color: var(--primary-emerald-light);
        }

        /* Buttons & Touch Targets */
        .btn {
            font-weight: 600;
            border-radius: 8px;
            touch-action: manipulation;
            transition: all 0.15s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
        }

        .btn-primary {
            background-color: var(--primary-emerald) !important;
            border-color: var(--primary-emerald) !important;
            color: #FFFFFF !important;
        }

        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--primary-emerald-hover) !important;
            border-color: var(--primary-emerald-hover) !important;
            transform: translateY(-1px);
        }

        .btn-outline-primary {
            color: var(--primary-emerald) !important;
            border-color: var(--primary-emerald) !important;
            background-color: transparent !important;
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-emerald) !important;
            color: #FFFFFF !important;
        }

        .btn-outline-secondary {
            color: var(--text-body) !important;
            border-color: #CBD5E1 !important;
            background-color: #FFFFFF !important;
        }

        .btn-outline-secondary:hover {
            background-color: #F1F5F9 !important;
            border-color: #94A3B8 !important;
            color: var(--text-heading) !important;
        }

        /* Cards */
        .card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
        }

        /* Slot Jam Interaktif */
        .slot-btn {
            border-radius: 8px;
            padding: 8px 10px;
            font-size: 0.825rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            min-height: 44px;
            touch-action: manipulation;
            transition: all 0.15s ease;
            border: 1px solid var(--border-subtle);
            text-align: left;
            background-color: #FFFFFF;
            color: var(--text-heading);
        }

        .slot-btn.slot-available {
            border-color: #CBD5E1;
            cursor: pointer;
        }

        .slot-btn.slot-available:hover {
            background-color: var(--primary-emerald-light);
            border-color: var(--primary-emerald);
            color: var(--primary-emerald);
        }

        .slot-btn.slot-selected {
            background-color: var(--primary-emerald) !important;
            border-color: var(--primary-emerald) !important;
            color: #FFFFFF !important;
            box-shadow: 0 2px 6px rgba(5, 150, 105, 0.35);
        }

        .slot-btn.slot-selected .slot-badge {
            background: rgba(255, 255, 255, 0.25) !important;
            color: #FFFFFF !important;
        }

        .slot-btn.slot-booked {
            background-color: #F8FAFC !important;
            border-color: #E2E8F0 !important;
            color: #94A3B8 !important;
            cursor: not-allowed;
            opacity: 0.75;
        }

        /* Floating WhatsApp Button */
        .floating-whatsapp {
            position: fixed;
            bottom: 22px;
            right: 22px;
            z-index: 1045;
            background: #059669;
            color: #FFFFFF !important;
            border-radius: 999px;
            padding: 10px 18px;
            font-weight: 600;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 16px rgba(5, 150, 105, 0.4);
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .floating-whatsapp:hover {
            background: #047857;
            color: #FFFFFF !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(5, 150, 105, 0.5);
        }

        /* Safe padding bottom for mobile & desktop */
        main {
            padding-bottom: 96px !important;
            flex: 1 0 auto;
        }

        .site-footer {
            background: #FFFFFF;
            border-top: 1px solid var(--border-subtle);
            padding: 2.5rem 0 96px 0 !important;
            color: var(--text-muted);
            font-size: 0.875rem;
        }

        /* Mobile navbar explicit collapse */
        @media (max-width: 991.98px) {
            .site-navbar .navbar-collapse:not(.show) {
                display: none !important;
            }
        }

        /* If sticky bottom bar is present & active on mobile, lift floating WhatsApp */
        body:has(#stickyBottomBar:not(.d-none)) .floating-whatsapp {
            bottom: 84px !important;
        }

        @media (max-width: 767.98px) {
            .floating-whatsapp {
                bottom: 18px;
                right: 16px;
                padding: 8px 14px;
                font-size: 0.8rem;
            }
            main {
                padding-bottom: 105px !important;
            }
            .site-footer {
                padding-bottom: 110px !important;
            }
            .btn {
                min-height: 42px;
            }
            .btn-sm {
                min-height: 38px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- NAVBAR SEDERHANA: Logo, Pesan Lapangan, Cek Status, Login Admin -->
    <nav class="navbar navbar-expand-lg site-navbar py-2.5">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <span class="brand-monogram">SM</span>
                <span>SM Sport Center</span>
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list fs-3 text-dark"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-1 gap-lg-2 mt-2 mt-lg-0">
                    <li class="nav-item">
                        <a class="nav-link fw-semibold {{ request()->routeIs('home') ? 'active text-success' : '' }}" href="{{ route('home') }}">
                            <i class="bi bi-house-door me-1"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold {{ request()->routeIs('reservations.create') ? 'active text-success' : '' }}" href="{{ route('reservations.create') }}">
                            <i class="bi bi-calendar-check me-1"></i> Pesan Lapangan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold {{ request()->routeIs('reservations.check') ? 'active text-success' : '' }}" href="{{ route('reservations.check') }}">
                            <i class="bi bi-search me-1"></i> Cek Status Pesanan
                        </a>
                    </li>
                    @auth
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item ms-lg-2">
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-success px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5">
                                    <i class="bi bi-speedometer2"></i>
                                    <span>Dashboard Admin</span>
                                </a>
                            </li>
                            <li class="nav-item ms-lg-1">
                                <form action="{{ route('logout') }}" method="POST" class="d-inline m-0">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5 text-white shadow-sm" title="Keluar dari Akun Admin">
                                        <i class="bi bi-box-arrow-right"></i>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </li>
                        @else
                            <li class="nav-item ms-lg-2">
                                <form action="{{ route('logout') }}" method="POST" class="d-inline m-0">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5">
                                        <i class="bi bi-box-arrow-right"></i>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </li>
                        @endif
                    @else
                        <li class="nav-item ms-lg-2">
                            <a href="{{ route('login') }}" class="btn btn-sm btn-outline-secondary px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5 text-muted" title="Masuk ke Panel Pengelola">
                                <i class="bi bi-lock me-1"></i>
                                <span>Login Admin</span>
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- KONTEN UTAMA -->
    <main>
        @if(session('success'))
            <div class="container mt-3">
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm d-flex align-items-center gap-2" style="background-color: #ECFDF5; border-left: 4px solid #059669 !important; color: #065F46;" role="alert">
                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="container mt-3">
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm d-flex align-items-center gap-2" style="background-color: #FEF2F2; border-left: 4px solid #EF4444 !important; color: #991B1B;" role="alert">
                    <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @if(session('warning'))
            <div class="container mt-3">
                <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm d-flex align-items-center gap-2" style="background-color: #FFFBEB; border-left: 4px solid #F59E0B !important; color: #92400E;" role="alert">
                    <i class="bi bi-exclamation-circle-fill text-warning fs-5"></i>
                    <div>{{ session('warning') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- FOOTER SEDERHANA -->
    <footer class="site-footer">
        <div class="container">
            <div class="row g-3 align-items-center justify-content-between">
                <div class="col-md-6 text-center text-md-start">
                    <div class="fw-bold text-dark mb-1 d-flex align-items-center justify-content-center justify-content-md-start gap-2">
                        <span class="brand-monogram" style="width: 26px; height: 26px; font-size: 0.72rem; border-radius: 6px;">SM</span>
                        <span>SM Sport Center</span>
                    </div>
                    <div class="small text-muted">
                        <i class="bi bi-geo-alt text-danger me-1"></i> Jati Padang, RT/RW 006/003, Pasar Minggu, Jakarta Selatan
                    </div>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="small text-muted mb-1">
                        <i class="bi bi-clock text-success me-1"></i> Buka Setiap Hari: <strong>08:00 - 23:00 WIB</strong>
                    </div>
                    <div class="small">
                        <i class="bi bi-whatsapp text-success me-1"></i> Bantuan WhatsApp Admin: 
                        <a href="https://wa.me/6281384665386?text=Halo%20Admin%20SM%20Sport%20Center%2C%20saya%20ingin%20bertanya%20seputar%20booking%20lapangan" target="_blank" rel="noopener noreferrer" class="fw-bold text-success">
                            0813-8466-5386
                        </a>
                    </div>
                </div>
            </div>
            <div class="pt-3 mt-3 border-top d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 small text-muted">
                <div>
                    &copy; {{ date('Y') }} <strong>SM Sport Center</strong>. Sistem Booking Lapangan Olahraga.
                </div>
                <div>
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-secondary text-decoration-none">
                                <i class="bi bi-shield-lock me-1"></i> Dashboard Admin
                            </a>
                        @else
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link btn-sm text-secondary text-decoration-none p-0">Logout</button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="text-secondary text-decoration-none" title="Masuk ke Panel Pengelola SM Sport Center">
                            <i class="bi bi-shield-lock me-1"></i> Portal Admin
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </footer>

    <!-- TOMBOL FLOATING WHATSAPP (Hanya untuk Tamu / Pelanggan, disembunyikan untuk Admin) -->
    @if(!auth()->check() || !auth()->user()->isAdmin())
        <a href="https://wa.me/6281384665386?text=Halo%20Admin%20SM%20Sport%20Center%20Pasar%20Minggu%2C%20saya%20ingin%20bertanya%20seputar%20jadwal%20lapangan" class="floating-whatsapp" target="_blank" rel="noopener noreferrer" aria-label="Chat WhatsApp Admin">
            <i class="bi bi-whatsapp fs-5"></i>
            <span>Chat Admin</span>
        </a>
    @endif

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
