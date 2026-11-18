@extends('layouts.app')

@section('title', 'Beranda - Sewa Lapangan Futsal & Badminton')

@section('content')
@php
    $fieldList = $fields ?? \App\Models\Field::where('status', 'available')->get();
@endphp

<div class="container-fluid px-0">
    <!-- Hero Section: Bersih, Menarik, Tanpa Kotak Statistik Rumit -->
    <section class="hero-section text-center py-5 mb-5" style="background: linear-gradient(180deg, rgba(8, 17, 37, 0.95), rgba(6, 12, 26, 0.85)); border-radius: 28px; border: 1px solid rgba(255, 255, 255, 0.08); padding: 60px 20px;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9" data-aos="fade-up">
                    <div class="hero-badge d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill mb-3" style="background: rgba(34, 197, 94, 0.16); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.3); font-weight: 600; font-size: 0.9rem;">
                        <i class="bi bi-trophy-fill"></i> SM Sport Center • Futsal & Badminton
                    </div>
                    
                    @if(auth()->check())
                        <h1 class="display-5 fw-bolder text-white mb-3">Selamat datang kembali, {{ auth()->user()->name }}!</h1>
                        <p class="lead text-white-50 mb-4 mx-auto" style="max-width: 650px;">
                            Pilih lapangan favorit Anda di bawah ini, cek tarif per jam, dan langsung booking jadwal main dengan mudah.
                        </p>
                        <div class="d-flex flex-wrap justify-content-center gap-3">
                            <a href="#daftar-lapangan" class="btn btn-primary btn-lg px-4 py-3 fw-bold d-inline-flex align-items-center gap-2" style="font-size: 1.1rem; border-radius: 14px;">
                                <i class="bi bi-arrow-down-circle"></i> Lihat Daftar Lapangan
                            </a>
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-lg px-4 py-3 fw-semibold d-inline-flex align-items-center gap-2" style="font-size: 1.1rem; border-radius: 14px;">
                                <i class="bi bi-speedometer2"></i> Masuk Dashboard
                            </a>
                        </div>
                    @else
                        <h1 class="display-5 fw-bolder text-white mb-3">Sewa Lapangan Olahraga Mudah, Cepat, dan Praktis</h1>
                        <p class="lead text-white-50 mb-4 mx-auto" style="max-width: 680px;">
                            Temukan lapangan futsal dan badminton terbaik dengan standar profesional di SM Sport Center. Cek harga per jam dan amankan jadwal Anda sekarang juga.
                        </p>
                        <div class="d-flex flex-wrap justify-content-center gap-3">
                            <a href="#daftar-lapangan" class="btn btn-primary btn-lg px-4 py-3 fw-bold d-inline-flex align-items-center gap-2" style="font-size: 1.1rem; border-radius: 14px;">
                                <i class="bi bi-calendar2-check"></i> Pesan Lapangan Sekarang
                            </a>
                            <a href="https://wa.me/6281384665386" target="_blank" rel="noopener" class="btn btn-outline-light btn-lg px-4 py-3 fw-semibold d-inline-flex align-items-center gap-2" style="font-size: 1.1rem; border-radius: 14px;">
                                <i class="bi bi-whatsapp text-success"></i> Tanya via WhatsApp
                            </a>
                        </div>
                    @endif

                    <div class="d-flex flex-wrap justify-content-center gap-3 mt-4 pt-2">
                        <span class="badge py-2 px-3 rounded-pill" style="background: rgba(255,255,255,0.06); color: #f8fafc; border: 1px solid rgba(255,255,255,0.1); font-weight: 500;">
                            <i class="bi bi-shield-check text-success me-1"></i> Lapangan Standar & Bersih
                        </span>
                        <span class="badge py-2 px-3 rounded-pill" style="background: rgba(255,255,255,0.06); color: #f8fafc; border: 1px solid rgba(255,255,255,0.1); font-weight: 500;">
                            <i class="bi bi-tag text-info me-1"></i> Harga Jelas & Terbuka
                        </span>
                        <span class="badge py-2 px-3 rounded-pill" style="background: rgba(255,255,255,0.06); color: #f8fafc; border: 1px solid rgba(255,255,255,0.1); font-weight: 500;">
                            <i class="bi bi-clock-history text-warning me-1"></i> Buka 08:00 - 23:00 WIB
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Fokus Utama: Daftar Lapangan, Harga per Jam, & Tombol Pesan Sekarang Besar -->
    <section id="daftar-lapangan" class="py-4 mb-5" data-aos="fade-up">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-end mb-3">
            <div>
                <span class="text-success fw-bold text-uppercase small letter-spacing-1">Pilihan Terbaik</span>
                <h2 class="display-6 fw-bold text-white mb-1">Daftar Lapangan Tersedia</h2>
                <p class="text-white-50 mb-0">Semua lapangan terawat dan siap pakai. Silakan pilih lapangan dan pesan jadwal Anda dengan mudah.</p>
            </div>
        </div>

        <!-- Filter Lapangan: Semua / Futsal / Badminton -->
        <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
            <span class="text-white-50 small me-2"><i class="bi bi-funnel-fill text-success"></i> Filter:</span>
            <button type="button" class="btn btn-sm px-3 py-2 rounded-pill field-filter-btn active" data-filter="all">
                <i class="bi bi-grid-fill me-1"></i> Semua Lapangan ({{ $fieldList->count() }})
            </button>
            <button type="button" class="btn btn-sm px-3 py-2 rounded-pill field-filter-btn" data-filter="futsal">
                <i class="bi bi-dribbble me-1"></i> Futsal ({{ $fieldList->where('type', 'futsal')->count() }})
            </button>
            <button type="button" class="btn btn-sm px-3 py-2 rounded-pill field-filter-btn" data-filter="badminton">
                <i class="bi bi-feather me-1"></i> Badminton ({{ $fieldList->where('type', 'badminton')->count() }})
            </button>
        </div>

        <div class="row g-4" id="fieldsContainer">
            @forelse($fieldList as $field)
                <div class="col-md-6 col-lg-4 field-item-col" data-field-type="{{ strtolower($field->type) }}" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 80 }}">
                    <div class="field-card card h-100 overflow-hidden" style="background: #0f1d36; border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 20px; box-shadow: 0 16px 36px rgba(0, 0, 0, 0.35);">
                        <!-- Card Header: Background Gradasi Elegan & Ilustrasi Lapangan Rapi di Tengah -->
                        <div class="position-relative overflow-hidden d-flex align-items-center justify-content-center p-3" 
                             style="height: 210px; background: {{ $field->gradient_style }};">
                            
                            <!-- Gambar Lapangan SVG Lokal di Tengah -->
                            <img src="{{ asset($field->image_url) }}" 
                                 alt="{{ $field->name }}" 
                                 class="field-card-vector img-fluid"
                                 style="max-height: 155px; max-width: 88%; object-fit: contain; filter: drop-shadow(0 10px 18px rgba(0, 0, 0, 0.45)); border-radius: 12px; transition: transform 0.3s ease;">
                            
                            <!-- Badges Overlay -->
                            <div class="position-absolute top-0 start-0 m-3 d-flex gap-2">
                                <span class="badge text-uppercase fw-bold px-3 py-2 shadow-sm rounded-pill" 
                                      style="backdrop-filter: blur(8px); background: rgba(0, 0, 0, 0.4); color: #fff; border: 1px solid rgba(255, 255, 255, 0.2);">
                                    <i class="bi {{ strtolower($field->type) === 'badminton' ? 'bi-feather text-info' : 'bi-dribbble text-success' }} me-1"></i>
                                    {{ $field->type }}
                                </span>
                            </div>

                            <div class="position-absolute top-0 end-0 m-3">
                                <span class="badge text-uppercase fw-bold px-3 py-2 shadow-sm rounded-pill" 
                                      style="backdrop-filter: blur(8px); background: rgba(0, 0, 0, 0.4); color: #4ade80; border: 1px solid rgba(74, 222, 128, 0.3);">
                                    <i class="bi bi-check-circle-fill me-1"></i> Tersedia
                                </span>
                            </div>
                        </div>

                        <!-- Card Body: Informasi Lapangan, Kapasitas & Harga Terang Jelas -->
                        <div class="card-body p-4 d-flex flex-column" style="background: #0f1d36;">
                            <h3 class="card-title fw-bold text-white mb-2" style="font-size: 1.4rem;">
                                {{ $field->name }}
                            </h3>
                            
                            <p class="card-text text-white-50 small mb-3">
                                {{ $field->description ?: 'Lapangan olahraga berstandar dengan pencahayaan terang dan sirkulasi udara optimal untuk kenyamanan bermain.' }}
                            </p>

                            <!-- Kotak Kapasitas & Informasi Fasilitas -->
                            <div class="p-3 rounded-3 mb-3" style="background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(255, 255, 255, 0.08);">
                                <div class="row g-2 align-items-center">
                                    <div class="col-6">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-people-fill text-info fs-5"></i>
                                            <div>
                                                <small class="text-white-50 d-block" style="font-size: 0.72rem; text-transform: uppercase;">Kapasitas</small>
                                                <strong class="text-white fs-6">{{ $field->capacity }} Orang</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-lightning-charge-fill text-warning fs-5"></i>
                                            <div>
                                                <small class="text-white-50 d-block" style="font-size: 0.72rem; text-transform: uppercase;">Kondisi</small>
                                                <strong class="text-white fs-6">Indoor & Prima</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Kotak Harga per Jam: Menonjol, Kontras Tinggi & Terbaca Jelas -->
                            <div class="p-3 rounded-3 mb-4 text-center mt-auto" style="background: linear-gradient(135deg, rgba(22, 163, 74, 0.20), rgba(15, 29, 54, 0.95)); border: 1px solid rgba(34, 197, 94, 0.35);">
                                <span class="text-white-50 small text-uppercase fw-semibold d-block mb-1">Tarif Sewa</span>
                                <div class="d-flex align-items-baseline justify-content-center gap-1">
                                    <span class="fs-2 fw-bolder" style="color: #4ade80;">Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}</span>
                                    <span class="text-white fs-6 fw-normal">/ jam</span>
                                </div>
                            </div>

                            <!-- Tombol Pesan Sekarang yang BESAR dan JELAS -->
                            <a href="{{ auth()->check() ? route('reservations.create', ['field_id' => $field->id]) : route('login') }}" 
                               class="btn btn-primary btn-lg w-100 fw-bold py-3 shadow-lg d-flex align-items-center justify-content-center gap-2"
                               style="font-size: 1.15rem; border-radius: 14px; background: linear-gradient(135deg, #16a34a, #22c55e); border: none;">
                                <i class="bi bi-calendar2-check-fill fs-5"></i>
                                <span>Pesan Sekarang</span>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="p-5 text-center rounded-4" style="background: #0f1d36; border: 1px solid rgba(255, 255, 255, 0.1);">
                        <i class="bi bi-info-circle text-warning display-4 mb-3 d-block"></i>
                        <h4 class="text-white fw-bold">Saat ini belum ada lapangan yang tersedia</h4>
                        <p class="text-white-50">Silakan hubungi admin kami melalui WhatsApp untuk informasi jadwal berikutnya.</p>
                        <a href="https://wa.me/6281384665386" class="btn btn-success fw-bold px-4 py-2 mt-2" target="_blank" rel="noopener">
                            <i class="bi bi-whatsapp"></i> Chat Admin WhatsApp
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Langkah Mudah Booking: Sederhana & Langsung Paham -->
    <section class="py-5 mb-5" data-aos="fade-up">
        <div class="p-4 p-lg-5 rounded-4" style="background: linear-gradient(180deg, #0f1d36, #091325); border: 1px solid rgba(255, 255, 255, 0.1);">
            <div class="text-center mb-4">
                <span class="text-success fw-bold text-uppercase small letter-spacing-1">Cara Mudah</span>
                <h3 class="fw-bold text-white mb-2">3 Langkah Praktis Memesan Lapangan</h3>
                <p class="text-white-50 mb-0">Tanpa antre, tanpa ribet, langsung siap bermain.</p>
            </div>

            <div class="row g-4 text-center mt-2">
                <div class="col-md-4">
                    <div class="p-3">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 60px; height: 60px; background: rgba(34, 197, 94, 0.15); color: #4ade80; font-size: 1.5rem; font-weight: bold; border: 1px solid rgba(34, 197, 94, 0.3);">
                            1
                        </div>
                        <h5 class="text-white fw-bold mb-2">Pilih Lapangan</h5>
                        <p class="text-white-50 small mb-0">Pilih lapangan futsal atau badminton yang cocok dengan kapasitas dan jadwal Anda.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 60px; height: 60px; background: rgba(59, 130, 246, 0.15); color: #60a5fa; font-size: 1.5rem; font-weight: bold; border: 1px solid rgba(59, 130, 246, 0.3);">
                            2
                        </div>
                        <h5 class="text-white fw-bold mb-2">Tentukan Jadwal</h5>
                        <p class="text-white-50 small mb-0">Pilih tanggal dan jam bermain yang masih kosong sesuai kebutuhan tim Anda.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 60px; height: 60px; background: rgba(245, 158, 11, 0.15); color: #fbbf24; font-size: 1.5rem; font-weight: bold; border: 1px solid rgba(245, 158, 11, 0.3);">
                            3
                        </div>
                        <h5 class="text-white fw-bold mb-2">Bayar & Main</h5>
                        <p class="text-white-50 small mb-0">Selesaikan pembayaran secara aman, dapatkan bukti reservasi, dan siap main tepat waktu.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Lokasi & Kontak -->
    <section id="kontak" class="py-4 mb-5" data-aos="fade-up">
        <div class="row g-4 align-items-stretch">
            <div class="col-lg-5">
                <div class="card h-100 p-4" style="background: #0f1d36; border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 20px;">
                    <span class="text-success fw-bold text-uppercase small letter-spacing-1 mb-1">Lokasi Kami</span>
                    <h3 class="fw-bold text-white mb-3">SM Sport Center</h3>
                    <p class="text-white-50 mb-3">
                        Pusat olahraga futsal dan badminton terbaik di Kota Bandung dengan tempat parkir luas, ruang ganti, dan mushola yang nyaman.
                    </p>

                    <div class="d-flex flex-column gap-3 mb-4">
                        <div class="d-flex align-items-start gap-3">
                            <i class="bi bi-geo-alt-fill text-danger fs-5 mt-1"></i>
                            <div>
                                <strong class="text-white d-block">Alamat:</strong>
                                <span class="text-white-50">Jl. Raya Sport Center No. 12, Bandung, Jawa Barat</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-start gap-3">
                            <i class="bi bi-clock-fill text-success fs-5 mt-1"></i>
                            <div>
                                <strong class="text-white d-block">Jam Buka:</strong>
                                <span class="text-white-50">Setiap Hari • 08.00 - 23.00 WIB</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-start gap-3">
                            <i class="bi bi-whatsapp text-success fs-5 mt-1"></i>
                            <div>
                                <strong class="text-white d-block">WhatsApp Admin:</strong>
                                <a href="https://wa.me/6281384665386" target="_blank" rel="noopener" class="text-decoration-none fw-bold" style="color: #4ade80;">
                                    0813-8466-5386
                                </a>
                            </div>
                        </div>
                        <div class="d-flex align-items-start gap-3">
                            <i class="bi bi-envelope-fill text-info fs-5 mt-1"></i>
                            <div>
                                <strong class="text-white d-block">Email:</strong>
                                <span class="text-white-50">admin@sm-sport-center.com</span>
                            </div>
                        </div>
                    </div>

                    <a href="https://wa.me/6281384665386" target="_blank" rel="noopener" class="btn btn-success btn-lg w-100 fw-bold py-3 mt-auto d-flex align-items-center justify-content-center gap-2" style="border-radius: 14px;">
                        <i class="bi bi-whatsapp fs-5"></i> Chat Admin WhatsApp
                    </a>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card h-100 p-2 overflow-hidden" style="background: #0f1d36; border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 20px;">
                    <iframe
                        src="https://maps.google.com/maps?q=bandung&t=&z=13&ie=UTF8&iwloc=&output=embed"
                        class="w-100 h-100 rounded-4"
                        style="min-height: 380px; border: 0;"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Peta lokasi SM Sport Center">
                    </iframe>
                </div>
            </div>
        </div>
    </section>

    <!-- Pertanyaan Umum (FAQ) - Latar Gelap, Teks Putih Terang Jelas -->
    <section id="faqSection" class="py-4 mb-5" data-aos="fade-up">
        <div class="text-center mb-4">
            <span class="text-success fw-bold text-uppercase small letter-spacing-1">Informasi</span>
            <h2 class="display-6 fw-bold text-white mb-2">Pertanyaan yang Sering Diajukan (FAQ)</h2>
            <p class="text-white-50 mb-0">Semua yang perlu Anda ketahui mengenai sewa lapangan dan cara pemesanan.</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item mb-3" style="background: #0f1d36; border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 14px; overflow: hidden;">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button fw-bold text-white" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="true" aria-controls="faq1" style="background: #0f1d36; font-size: 1.1rem; padding: 1.2rem 1.5rem;">
                                <i class="bi bi-question-circle-fill text-success me-2"></i> Bagaimana cara memesan lapangan?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-white" style="background: #0b172a; padding: 1.25rem 1.5rem; line-height: 1.8; color: #f1f5f9;">
                                Cukup pilih lapangan yang Anda inginkan pada daftar lapangan di atas, klik tombol <strong>"Pesan Sekarang"</strong>, pilih tanggal serta jam bermain, lalu selesaikan pemesanan. Anda juga dapat menghubungi admin via WhatsApp jika membutuhkan bantuan.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item mb-3" style="background: #0f1d36; border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 14px; overflow: hidden;">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed fw-bold text-white" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" aria-expanded="false" aria-controls="faq2" style="background: #0f1d36; font-size: 1.1rem; padding: 1.2rem 1.5rem;">
                                <i class="bi bi-wallet2 text-info me-2"></i> Apa saja metode pembayaran yang didukung?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-white" style="background: #0b172a; padding: 1.25rem 1.5rem; line-height: 1.8; color: #f1f5f9;">
                                Kami mendukung metode pembayaran digital instan seperti QRIS (bisa scan pakai GoPay, OVO, Dana, ShopeePay, dan mobile banking), Virtual Account BCA/Mandiri/BNI/BRI, dan pembayaran tunai langsung di kasir SM Sport Center.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item mb-3" style="background: #0f1d36; border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 14px; overflow: hidden;">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed fw-bold text-white" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" aria-expanded="false" aria-controls="faq3" style="background: #0f1d36; font-size: 1.1rem; padding: 1.2rem 1.5rem;">
                                <i class="bi bi-clock-history text-warning me-2"></i> Berapa jam minimal sewa lapangan?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-white" style="background: #0b172a; padding: 1.25rem 1.5rem; line-height: 1.8; color: #f1f5f9;">
                                Minimal sewa lapangan adalah 1 jam. Anda bisa memilih sewa beberapa jam sekaligus atau memesan rutin mingguan untuk tim Anda dengan menghubungi pihak admin.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item mb-3" style="background: #0f1d36; border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 14px; overflow: hidden;">
                        <h2 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed fw-bold text-white" type="button" data-bs-toggle="collapse" data-bs-target="#faq4" aria-expanded="false" aria-controls="faq4" style="background: #0f1d36; font-size: 1.1rem; padding: 1.2rem 1.5rem;">
                                <i class="bi bi-arrow-repeat text-danger me-2"></i> Apakah jadwal yang sudah dipesan bisa diubah atau dibatalkan?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-white" style="background: #0b172a; padding: 1.25rem 1.5rem; line-height: 1.8; color: #f1f5f9;">
                                Perubahan jadwal dapat dilakukan minimal 24 jam sebelum jam main dengan konfirmasi langsung ke nomor WhatsApp admin kami agar slot baru dapat disesuaikan.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
    document.querySelectorAll('.field-filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.field-filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const filter = this.dataset.filter;
            document.querySelectorAll('.field-item-col').forEach(col => {
                if (filter === 'all' || col.dataset.fieldType === filter) {
                    col.style.display = 'block';
                } else {
                    col.style.display = 'none';
                }
            });
        });
    });
</script>
@endpush
@endsection
