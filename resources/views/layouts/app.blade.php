<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SM Sport Center - Platform sewa lapangan futsal dan badminton dengan booking cepat, harga transparan, dan fasilitas terbaik.">
    <meta name="theme-color" content="#081124">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/sm-sport-logo.svg') }}">
    <title>@yield('title') - SM Sport Center</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('css/app.css') }}?v=3">
        <script src="{{ asset('js/app.js') }}" defer></script>
    @endif

    <style>
        /* Global Dark Theme & Contrast Reset */
        :root {
            --bg-dark: #081124;
            --surface-dark: #0f1d36;
            --surface-card: #0f1d36;
            --surface-inner: #0b172a;
            --border-dark: rgba(255, 255, 255, 0.12);
            --text-pure: #ffffff;
            --text-bright: #f8fafc;
            --text-muted: #cbd5e1;
            --primary-accent: #22c55e;
            --primary-gradient: linear-gradient(135deg, #16a34a, #22c55e);
        }

        body {
            background: var(--bg-dark) !important;
            color: var(--text-bright) !important;
            font-size: 0.95rem !important;
            line-height: 1.65 !important;
            min-height: 100vh;
        }

        /* Navbar Styling */
        .navbar {
            background: rgba(8, 17, 36, 0.95) !important;
            backdrop-filter: blur(14px) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.45) !important;
            z-index: 1050 !important;
        }

        .navbar-brand {
            letter-spacing: 0.02em;
            font-weight: 700;
            font-size: 1.15rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #ffffff !important;
        }

        .brand-mark {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: rgba(34, 197, 94, 0.2);
            border: 1px solid rgba(34, 197, 94, 0.35);
        }

        .brand-subtitle {
            display: block;
            font-size: 0.72rem;
            color: #4ade80 !important;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .navbar .nav-link {
            color: #f1f5f9 !important;
            font-weight: 500;
            padding: 0.55rem 1rem !important;
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .navbar .nav-link:hover,
        .navbar .nav-link:focus {
            background: rgba(255, 255, 255, 0.08) !important;
            color: #4ade80 !important;
        }

        .user-pill {
            background: rgba(255, 255, 255, 0.08) !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            border-radius: 999px;
            padding: 0.4rem 0.9rem !important;
            color: #ffffff !important;
        }

        .user-avatar {
            width: 26px;
            height: 26px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #22c55e;
            color: #06170b;
            font-weight: 700;
            font-size: 0.85rem;
        }

        .dropdown-menu {
            background: #0f1d36 !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            border-radius: 14px;
            box-shadow: 0 16px 38px rgba(0, 0, 0, 0.5) !important;
            padding: 0.6rem;
        }

        .dropdown-item {
            color: #f1f5f9 !important;
            border-radius: 8px;
            padding: 0.55rem 1rem;
            font-size: 0.92rem;
        }

        .dropdown-item:hover {
            background: rgba(34, 197, 94, 0.15) !important;
            color: #4ade80 !important;
        }

        /* Sidebar Styling */
        .sidebar {
            background: linear-gradient(180deg, #070e1c, #0b172a) !important;
            min-height: 100vh !important;
            position: fixed !important;
            left: 0 !important;
            top: 56px !important;
            width: 280px !important;
            overflow-y: auto !important;
            color: #e2e8f0 !important;
            padding: 24px 16px !important;
            box-shadow: 0 24px 80px rgba(0, 0, 0, 0.45) !important;
            border-right: 1px solid rgba(255, 255, 255, 0.08) !important;
            z-index: 1000 !important;
        }

        .sidebar-title {
            padding: 10px 14px !important;
            font-size: 0.75rem !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            color: #94a3b8 !important;
            letter-spacing: 0.12em !important;
            margin-bottom: 0.5rem !important;
        }

        .sidebar-nav {
            list-style: none !important;
            padding: 0 !important;
            margin: 0 !important;
            display: grid !important;
            gap: 0.4rem !important;
        }

        .sidebar-nav a {
            display: inline-grid !important;
            grid-template-columns: auto 1fr !important;
            gap: 0.85rem !important;
            align-items: center !important;
            color: #cbd5e1 !important;
            text-decoration: none !important;
            padding: 12px 16px !important;
            border-left: 3px solid transparent !important;
            border-radius: 12px !important;
            background: rgba(255, 255, 255, 0.02) !important;
            font-size: 0.95rem !important;
            transition: all 0.2s ease !important;
        }

        .sidebar-nav a:hover {
            background: rgba(255, 255, 255, 0.08) !important;
            color: #ffffff !important;
            border-left-color: #22c55e !important;
            transform: translateX(3px) !important;
        }

        .sidebar-nav a.active {
            background: linear-gradient(135deg, rgba(22, 163, 74, 0.22), rgba(15, 23, 42, 0.9)) !important;
            color: #ffffff !important;
            border-left-color: #22c55e !important;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.08) !important;
        }

        /* Layout Margins */
        main {
            margin-top: 56px !important;
            padding: 28px !important;
            min-height: calc(100vh - 56px);
        }

        @auth
            @media (min-width: 992px) {
                main:not(.no-sidebar) {
                    margin-left: 280px !important;
                }
                .has-sidebar-footer {
                    margin-left: 280px !important;
                }
            }
        @endauth

        @guest
            main,
            .no-sidebar {
                margin-left: 0 !important;
            }
        @endguest

        /* All Cards & Containers Uniform Dark */
        .card,
        .content .card,
        .glass-card,
        .pricing-card,
        .testimonial-card,
        .contact-card,
        .stat-card,
        .glass-stat-card,
        .table {
            background: #0f1d36 !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            color: #ffffff !important;
            border-radius: 18px !important;
        }

        .content .card .card-body,
        .content .card .card-footer,
        .content .card .card-header {
            background: #0f1d36 !important;
            color: #ffffff !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
        }

        .content .card .card-title,
        .card-title,
        h1, h2, h3, h4, h5, h6 {
            color: #ffffff !important;
            font-weight: 700 !important;
        }

        .card-text,
        .content .card .card-text,
        .card-text.text-muted,
        .content .card .card-text.text-muted,
        .text-muted {
            color: #cbd5e1 !important;
        }

        /* Field Card Specific Styling */
        .field-card {
            background: #0f1d36 !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            border-radius: 20px !important;
            overflow: hidden !important;
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease !important;
            box-shadow: 0 14px 34px rgba(0, 0, 0, 0.35) !important;
        }

        .field-card:hover {
            transform: translateY(-5px) !important;
            box-shadow: 0 22px 48px rgba(0, 0, 0, 0.5) !important;
            border-color: rgba(34, 197, 94, 0.45) !important;
        }

        .field-card-img {
            height: 220px !important;
            width: 100% !important;
            object-fit: cover !important;
            transition: transform 0.4s ease !important;
        }

        .field-card:hover .field-card-img {
            transform: scale(1.03) !important;
        }

        /* FAQ Accordion Styling: Gelap Sempurna, Teks Putih Terang */
        .accordion {
            --bs-accordion-bg: #0f1d36 !important;
            --bs-accordion-border-color: rgba(255, 255, 255, 0.12) !important;
            --bs-accordion-btn-color: #ffffff !important;
            --bs-accordion-btn-bg: #0f1d36 !important;
            --bs-accordion-active-color: #ffffff !important;
            --bs-accordion-active-bg: rgba(22, 163, 74, 0.20) !important;
            --bs-accordion-btn-focus-box-shadow: none !important;
        }

        .accordion-item {
            background: #0f1d36 !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            border-radius: 14px !important;
            overflow: hidden !important;
            margin-bottom: 0.85rem !important;
        }

        .accordion-button {
            background: #0f1d36 !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            font-size: 1.05rem !important;
            padding: 1.25rem 1.5rem !important;
            border: none !important;
            box-shadow: none !important;
        }

        .accordion-button:not(.collapsed) {
            background: rgba(22, 163, 74, 0.22) !important;
            color: #ffffff !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
        }

        .accordion-button::after {
            filter: brightness(0) invert(1) !important;
        }

        .accordion-body {
            background: #0b172a !important;
            color: #f1f5f9 !important;
            font-size: 0.98rem !important;
            line-height: 1.8 !important;
            padding: 1.25rem 1.5rem !important;
        }

        /* Filter Lapangan Pill Buttons */
        .field-filter-btn {
            background: rgba(255, 255, 255, 0.06) !important;
            color: #cbd5e1 !important;
            border: 1px solid rgba(255, 255, 255, 0.14) !important;
            font-weight: 600 !important;
            font-size: 0.88rem !important;
            transition: all 0.2s ease !important;
        }

        .field-filter-btn:hover {
            background: rgba(255, 255, 255, 0.14) !important;
            color: #ffffff !important;
            border-color: rgba(34, 197, 94, 0.4) !important;
        }

        .field-filter-btn.active {
            background: linear-gradient(135deg, #16a34a, #22c55e) !important;
            color: #ffffff !important;
            border-color: #22c55e !important;
            box-shadow: 0 4px 14px rgba(34, 197, 94, 0.35) !important;
        }

        /* Buttons & Forms */
        .btn-primary {
            background: var(--primary-gradient) !important;
            border: none !important;
            color: #ffffff !important;
            box-shadow: 0 10px 24px rgba(22, 163, 74, 0.35) !important;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #15803d, #16a34a) !important;
            transform: translateY(-1px);
            box-shadow: 0 14px 28px rgba(22, 163, 74, 0.45) !important;
        }

        .form-control, .form-select, textarea {
            background: #0b172a !important;
            border: 1px solid rgba(255, 255, 255, 0.14) !important;
            color: #ffffff !important;
            border-radius: 12px !important;
        }

        .form-control:focus, .form-select:focus {
            background: #0b172a !important;
            border-color: #22c55e !important;
            color: #ffffff !important;
            box-shadow: 0 0 0 0.25rem rgba(34, 197, 94, 0.25) !important;
        }

        /* Footer Styling Tertata Rapi */
        .luxury-footer {
            background: #050b18 !important;
            border-top: 1px solid rgba(255, 255, 255, 0.08) !important;
            padding: 50px 0 25px 0 !important;
            color: #e2e8f0 !important;
            margin-top: 50px !important;
        }

        .footer-col-title {
            color: #ffffff !important;
            font-size: 1.05rem !important;
            font-weight: 700 !important;
            margin-bottom: 1.2rem !important;
            letter-spacing: 0.02em !important;
        }

        .footer-links {
            list-style: none !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .footer-links li {
            margin-bottom: 0.65rem !important;
        }

        .footer-links a {
            color: #94a3b8 !important;
            text-decoration: none !important;
            transition: all 0.2s ease !important;
            display: inline-block !important;
            font-size: 0.92rem !important;
        }

        .footer-links a:hover {
            color: #4ade80 !important;
            transform: translateX(4px) !important;
        }

        /* Floating CTA / WhatsApp */
        .floating-cta {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 999;
            background: #25d366;
            color: #ffffff;
            border-radius: 999px;
            padding: 12px 22px;
            font-weight: 700;
            box-shadow: 0 10px 28px rgba(37, 211, 102, 0.4);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }

        .floating-cta:hover {
            background: #1ebc57;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 14px 34px rgba(37, 211, 102, 0.55);
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Navbar dengan Bahasa Sehari-hari yang Mudah Dipahami -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid px-lg-4">
            <a class="navbar-brand" href="{{ route('home') }}">
                <span class="brand-mark">
                    <img src="{{ asset('images/sm-sport-logo.svg') }}" alt="SM Sport Center logo" style="width: 24px; height: 24px;">
                </span>
                <span>
                    SM Sport Center
                    <small class="brand-subtitle">Sewa Lapangan Futsal & Badminton</small>
                </span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="bi bi-house-door me-1"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ Route::is('home') ? '#daftar-lapangan' : route('home') . '#daftar-lapangan' }}">
                            <i class="bi bi-grid me-1"></i> Daftar Lapangan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ Route::is('home') ? '#faqSection' : route('home') . '#faqSection' }}">
                            <i class="bi bi-question-circle me-1"></i> Tanya Jawab
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ Route::is('home') ? '#kontak' : route('home') . '#kontak' }}">
                            <i class="bi bi-telephone me-1"></i> Hubungi Kami
                        </a>
                    </li>

                    @auth
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-outline-success btn-sm px-3 py-2 fw-semibold" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2 me-1"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item dropdown ms-lg-1">
                            <a class="nav-link dropdown-toggle user-pill" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <span class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                                <span class="fw-semibold">{{ auth()->user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="bi bi-person me-2"></i> Profil Saya
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('customer.reservations') }}">
                                        <i class="bi bi-calendar2-check me-2"></i> Pesanan Saya
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('customer.transactions') }}">
                                        <i class="bi bi-receipt me-2"></i> Riwayat Transaksi
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider border-secondary border-opacity-25"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-block">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger border-0 bg-transparent w-100 text-start">
                                            <i class="bi bi-box-arrow-right me-2"></i> Keluar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item ms-lg-2">
                            <a class="nav-link text-white fw-semibold" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary btn-sm px-3 py-2 fw-bold" href="{{ route('register') }}" style="border-radius: 10px;">
                                <i class="bi bi-person-plus me-1"></i> Daftar Akun
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="d-flex">
        @auth
            <!-- Sidebar: Menu Sederhana Bahasa Sehari-hari -->
            @if(auth()->user()->isAdmin())
                <aside class="sidebar">
                    <ul class="sidebar-nav">
                        <li class="sidebar-title">Menu Admin</li>
                        <li>
                            <a href="{{ route('admin.dashboard') }}" class="@routeIs('admin.dashboard') active @endrouteIs">
                                <i class="bi bi-speedometer2"></i> Dashboard Admin
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.manual_booking') }}" class="@routeIs('admin.manual_booking*') active @endrouteIs">
                                <i class="bi bi-person-walking"></i> Booking Kasir (Walk-in)
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.fields.index') }}" class="@routeIs('admin.fields.*') active @endrouteIs">
                                <i class="bi bi-building"></i> Kelola Lapangan
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.users.index') }}" class="@routeIs('admin.users.*') active @endrouteIs">
                                <i class="bi bi-people"></i> Data Pengguna
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.reservations.index') }}" class="@routeIs('admin.reservations.*') active @endrouteIs">
                                <i class="bi bi-calendar-check"></i> Data Reservasi
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.transactions.index') }}" class="@routeIs('admin.transactions.*') active @endrouteIs">
                                <i class="bi bi-credit-card"></i> Data Transaksi
                            </a>
                        </li>
                    </ul>
                </aside>
            @else
                <aside class="sidebar">
                    <ul class="sidebar-nav">
                        <li class="sidebar-title">Menu Utama</li>
                        <li>
                            <a href="{{ route('dashboard') }}" class="@routeIs('dashboard') active @endrouteIs">
                                <i class="bi bi-house"></i> Beranda Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('fields.index') }}" class="@routeIs('fields.*') active @endrouteIs">
                                <i class="bi bi-building"></i> Daftar Lapangan
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('reservations.create') }}" class="@routeIs('reservations.create') active @endrouteIs">
                                <i class="bi bi-plus-circle"></i> Pesan Lapangan
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('customer.reservations') }}" class="@routeIs('customer.reservations') active @endrouteIs">
                                <i class="bi bi-calendar-check"></i> Pesanan Saya
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('customer.transactions') }}" class="@routeIs('customer.transactions') active @endrouteIs">
                                <i class="bi bi-receipt"></i> Transaksi Saya
                            </a>
                        </li>
                    </ul>
                </aside>
            @endif
        @endauth

        <!-- Main Content -->
        <main class="flex-grow-1 @guest no-sidebar @endguest">
            <div class="content">
                <!-- Display Messages -->
                @if($message = session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert" style="background: rgba(34, 197, 94, 0.18); border: 1px solid rgba(34, 197, 94, 0.4); color: #86efac;">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ $message }}
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($message = session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="background: rgba(239, 68, 68, 0.18); border: 1px solid rgba(239, 68, 68, 0.4); color: #fca5a5;">
                        <i class="bi bi-exclamation-circle-fill me-2"></i> {{ $message }}
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="background: rgba(239, 68, 68, 0.18); border: 1px solid rgba(239, 68, 68, 0.4); color: #fca5a5;">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> 
                        <strong>Terjadi kesalahan:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @unless(Route::is('home'))
                    <div class="mb-4">
                        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('home') }}" onclick="event.preventDefault(); if (window.history.length > 1) { window.history.back(); } else { window.location.href = this.getAttribute('href'); }" class="btn btn-outline-light btn-sm px-3 py-2" style="border-radius: 10px;">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                @endunless

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Footer Tertata Rapi dalam Kolom-kolom yang Terstruktur -->
    <footer class="luxury-footer @auth has-sidebar-footer @endauth">
        <div class="container py-4">
            <div class="row g-4 mb-4">
                <!-- Kolom 1: Profil SM Sport Center -->
                <div class="col-lg-4 col-md-6">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="brand-mark">
                            <img src="{{ asset('images/sm-sport-logo.svg') }}" alt="SM Sport Center logo" style="width: 24px; height: 24px;">
                        </span>
                        <span class="fw-bold fs-5 text-white">SM Sport Center</span>
                    </div>
                    <p class="text-white-50 small mb-3" style="line-height: 1.7;">
                        Pusat penyewaan lapangan futsal dan badminton terbaik di Bandung. Tempat bersih, fasilitas lengkap, pencahayaan terang, dan alur booking yang cepat serta transparan.
                    </p>
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill" style="background: rgba(34, 197, 94, 0.14); border: 1px solid rgba(34, 197, 94, 0.3);">
                        <i class="bi bi-clock-fill text-success"></i>
                        <span class="small text-white fw-semibold">Buka Setiap Hari: 08.00 - 23.00 WIB</span>
                    </div>
                </div>

                <!-- Kolom 2: Navigasi Cepat -->
                <div class="col-lg-2 col-md-6 col-6">
                    <h5 class="footer-col-title">
                        <i class="bi bi-compass text-success me-1"></i> Navigasi
                    </h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">Beranda</a></li>
                        <li><a href="{{ route('home') }}#daftar-lapangan">Daftar Lapangan</a></li>
                        <li><a href="{{ route('home') }}#faqSection">Tanya Jawab (FAQ)</a></li>
                        <li><a href="{{ route('home') }}#kontak">Lokasi & Kontak</a></li>
                    </ul>
                </div>

                <!-- Kolom 3: Pilihan Lapangan -->
                <div class="col-lg-3 col-md-6 col-6">
                    <h5 class="footer-col-title">
                        <i class="bi bi-trophy text-info me-1"></i> Lapangan Kami
                    </h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}#daftar-lapangan">Futsal 1 (Lantai Standar)</a></li>
                        <li><a href="{{ route('home') }}#daftar-lapangan">Futsal 2 (Rumput Sintetis)</a></li>
                        <li><a href="{{ route('home') }}#daftar-lapangan">Badminton 1, 2, 3 (Indoor AC)</a></li>
                        <li><a href="https://wa.me/6281384665386" target="_blank" rel="noopener">Sewa Event & Turnamen</a></li>
                    </ul>
                </div>

                <!-- Kolom 4: Hubungi Kami -->
                <div class="col-lg-3 col-md-6">
                    <h5 class="footer-col-title">
                        <i class="bi bi-headset text-warning me-1"></i> Hubungi Kami
                    </h5>
                    <div class="d-flex flex-column gap-2 text-white-50 small">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-geo-alt-fill text-danger mt-1"></i>
                            <span>Jl. Raya Sport Center No. 12, Bandung</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-whatsapp text-success"></i>
                            <a href="https://wa.me/6281384665386" target="_blank" rel="noopener" class="text-decoration-none text-white fw-bold">
                                0813-8466-5386
                            </a>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-envelope-fill text-info"></i>
                            <span>admin@sm-sport-center.com</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Garis Bawah & Copyright -->
            <div class="pt-3 mt-4 border-top border-secondary border-opacity-25 d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <span class="text-white-50 small">
                    &copy; {{ date('Y') }} <strong>SM Sport Center</strong>. Semua Hak Cipta Dilindungi.
                </span>
                <span class="text-white-50 small">
                    Platform Booking Lapangan Futsal & Badminton
                </span>
            </div>
        </div>
    </footer>

    <!-- Tombol WhatsApp Mengambang -->
    <a href="https://wa.me/6281384665386" class="floating-cta" target="_blank" rel="noopener" aria-label="Chat WhatsApp Admin">
        <i class="bi bi-whatsapp fs-5"></i>
        <span>Chat WhatsApp</span>
    </a>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 600, once: true, easing: 'ease-out-cubic' });

        document.querySelectorAll('.alert').forEach(function(element) {
            if (!element.classList.contains('alert-dismissible-permanent')) {
                setTimeout(function() {
                    let alert = new bootstrap.Alert(element);
                    alert.close();
                }, 5000);
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
