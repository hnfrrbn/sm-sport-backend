# 🏆 SM Sport Center

> **Sistem Reservasi & Manajemen Lapangan Olahraga (Futsal & Badminton)** berbasis web yang modern, cepat, dan mudah digunakan tanpa wajib registrasi akun.

![Status](https://img.shields.io/badge/Status-Production%20Ready-success)
![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?logo=php&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/TailwindCSS-v4.0-38B2AC?logo=tailwind-css&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-4479A1?logo=mysql&logoColor=white)
![Timezone](https://img.shields.io/badge/Timezone-Asia%2FJakarta%20(WIB)-emerald)
![License](https://img.shields.io/badge/License-MIT-blue)

---

## 📌 Tentang SM Sport Center

**SM Sport Center** adalah platform manajemen dan booking lapangan olahraga terintegrasi yang dirancang khusus untuk venue olahraga modern. Platform ini menghadirkan pengalaman pemesanan yang instan bagi pelanggan (tanpa ribet membuat akun atau menghafal kata sandi) sekaligus menyediakan panel kontrol manajemen yang lengkap bagi admin venue dalam memantau jadwal operasional dan keuangan secara *real-time*.

---

## ✨ Fitur Utama

### 👥 Sisi Pelanggan (Guest Booking Experience)
- **⚡ Booking Online Langsung Tanpa Wajib Login:** Pelanggan cukup memilih lapangan, tanggal, jam main, lalu memasukkan Nama dan Nomor WhatsApp aktif.
- **🏷️ Filter Kategori Lapangan:** Navigasi cepat untuk menampilkan lapangan *Semua*, *Futsal*, atau *Badminton*.
- **🕒 Jadwal Slot Real-Time (WIB / `Asia/Jakarta`):** Informasi ketersediaan jam bermain (`08:00 - 23:00 WIB`) diperbarui secara presisi dengan zona waktu Indonesia Barat. Slot yang sudah lewat otomatis dinonaktifkan.
- **📲 Integrasi WhatsApp Otomatis:** Tombol bayar langsung menyusun pesan konfirmasi terformat (Kode Booking, Nama, Lapangan, Jam, Total Biaya) ke nomor WhatsApp resmi Admin Venue.
- **🔍 Cek Status Pesanan Mandiri:** Pelanggan dapat melacak status pemesanan kapan saja cukup menggunakan Kode Booking atau Nomor WhatsApp.
- **🎟️ E-Tiket / Struk Resmi:** Cetak bukti booking digital yang dilengkapi rincian pesanan begitu pembayaran diverifikasi oleh admin.

### 🛡️ Sisi Admin (Dashboard & Venue Operations)
- **📊 Matriks Jadwal Lapangan Interaktif:** Visualisasi baris per lapangan dengan 5 indikator status warna dinamis:
  - 🟢 **Hijau (Tersedia):** Slot kosong dan siap dibooking via kasir atau online.
  - 🔵 **Biru (Sedang Dimainkan):** Sesi main sedang berlangsung saat ini (*real-time*) dan status reservasi telah Lunas.
  - 🔴 **Merah (Terisi / Lunas):** Slot telah terisi oleh reservasi yang telah disetujui/lunas.
  - 🟠 **Oranye (Menunggu Bayar WA):** Slot telah dipesan dan sedang menunggu konfirmasi pembayaran WhatsApp.
  - ⚪ **Abu-abu (Jam Lewat):** Waktu bermain atau slot operasional telah lewat dari jam saat ini.
- **💳 Kasir Booking Cepat (Walk-in Booking):** Admin dapat membuat reservasi manual langsung dari dashboard dengan kalkulasi tarif otomatis.
- **⚡ Verifikasi Pembayaran 1-Klik:** Konfirmasi pembayaran pelanggan secara cepat dari tabel *Menunggu Pembayaran*.
- **🔄 Fitur Reschedule (Pindah Jadwal):** Kemudahan memindahkan tanggal, jam, maupun jenis lapangan pelanggan jika ada perubahan mendadak.
- **📈 Laporan Rekap Keuangan Single Source of Truth:**
  - Perhitungan otomatis Pendapatan Hari Ini, Pendapatan Bulanan, dan Pendapatan Tahunan.
  - Rincian metode pembayaran (Tunai/Cash, Transfer Bank, QRIS, Debit/EDC).
  - Ekspor laporan keuangan ke format **Excel (.xlsx)** yang 100% konsisten dengan data dashboard.

---

## 🛠️ Teknologi yang Digunakan

| Komponen | Teknologi | Keterangan |
| :--- | :--- | :--- |
| **Backend Framework** | [Laravel 12](https://laravel.com/) | PHP 8.2+ dengan arsitektur MVC |
| **Frontend UI** | Blade, [Tailwind CSS](https://tailwindcss.com/), Bootstrap 5 | Desain modern, bersih, dan responsif (Mobile & Desktop) |
| **Icons** | [Bootstrap Icons](https://icons.getbootstrap.com/) | Ikonografi antarmuka yang konsisten |
| **Database** | [MySQL](https://www.mysql.com/) / MariaDB | Penyimpanan relasional dengan integritas data tinggi |
| **Spreadsheet Export** | [SheetJS (xlsx)](https://sheetjs.com/) | Ekspor rekap keuangan client-side instan |
| **Timezone Management** | Carbon (`Asia/Jakarta`) | Sinkronisasi waktu WIB di seluruh layer aplikasi |

---

## 🚀 Panduan Instalasi Lokal

Ikuti langkah-langkah berikut untuk menjalankan proyek ini di komputer lokal Anda:

### 1. Clone Repositori
```bash
git clone https://github.com/username/sm-sport-backend.git
cd sm-sport-backend
```

### 2. Instal Dependensi Composer
```bash
composer install
```

### 3. Salin File Konfigurasi Environment
Salin file template `.env.example` menjadi `.env`:
```bash
# Untuk Linux / macOS
cp .env.example .env

# Untuk Windows (Command Prompt / PowerShell)
copy .env.example .env
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Konfigurasi Database & Timezone di `.env`
Buka file `.env` dan pastikan konfigurasi database serta zona waktu sudah sesuai:
```env
APP_NAME="SM Sport Center"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
APP_TIMEZONE=Asia/Jakarta

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sm_sport_center
DB_USERNAME=root
DB_PASSWORD=
```
> **Catatan:** Pastikan server MySQL (misalnya melalui Laragon, XAMPP, atau Docker) sudah berjalan dan buat database bernama `sm_sport_center` jika belum ada.

### 6. Jalankan Migrasi Database & Seeder
Perintah ini akan membuat seluruh tabel yang dibutuhkan dan mengisi data awal (lapangan default, akun admin, serta contoh data transaksi):
```bash
php artisan migrate --seed
```

### 7. Jalankan Server Pengembangan
```bash
php artisan serve
```

Aplikasi kini dapat diakses di browser melalui:  
👉 **`http://localhost:8000`**

---

## 🔑 Kredensial Login Default Admin

Untuk mengakses panel Dashboard Admin:
- **URL Login:** `http://localhost:8000/login`
- **Email:** `admin@gmail.com`
- **Password:** `password`

*(Pelanggan umum tidak memerlukan login untuk melakukan pemesanan lapangan).*

---

## 📂 Struktur Direktori Utama

```text
sm-sport-backend/
├── app/
│   ├── Http/Controllers/
│   │   ├── AdminController.php         # Logika dashboard admin, jadwal lapangan, & rekap keuangan
│   │   ├── ReservationController.php   # Alur booking publik, cek pesanan, tiket, & schedule API
│   │   ├── FieldController.php         # Manajemen master data lapangan
│   │   └── Auth/AuthController.php     # Autentikasi admin
│   └── Models/
│       ├── Field.php                   # Model lapangan olahraga
│       ├── Reservation.php             # Model reservasi booking
│       ├── Transaction.php             # Model pencatatan transaksi & pembayaran
│       └── User.php                    # Model akun admin & pengguna
├── config/
│   └── app.php                         # Konfigurasi zona waktu ('Asia/Jakarta') & locale ('id')
├── database/
│   ├── migrations/                     # Skema tabel database
│   └── seeders/                        # Data awal admin, lapangan, dan contoh reservasi
├── resources/
│   └── views/
│       ├── layouts/app.blade.php       # Layout master, navbar dinamis, & floating WhatsApp
│       ├── welcome.blade.php           # Halaman beranda utama publik & filter lapangan
│       ├── reservations/
│       │   ├── create.blade.php        # Pemilihan slot jadwal & input data pemesan
│       │   ├── show.blade.php          # Rincian pesanan & instruksi pembayaran WhatsApp
│       │   ├── check.blade.php         # Halaman cek status pesanan mandiri
│       │   └── ticket.blade.php        # Halaman bukti/tiket booking resmi
│       └── admin/
│           └── dashboard.blade.php     # Dashboard admin, jadwal interaktif, modal reschedule, & ekspor Excel
└── routes/
    └── web.php                         # Definisi rute web publik dan rute proteksi admin
```

---

## 🔒 Keamanan & Praktik Terbaik

- **CSRF Protection:** Seluruh form HTTP POST/PUT/DELETE dilindungi oleh token CSRF bawaan Laravel.
- **SQL Injection Prevention:** Semua query database menggunakan Eloquent ORM dengan *parameter binding*.
- **Sensitif Environment:** File `.env` dan varian cadangannya telah diamankan pada `.gitignore` agar tidak pernah terunggah ke repositori publik.
- **WIB Time Consistency:** Seluruh validasi jam main dan kedaluwarsa slot dihitung berdasarkan waktu server `Asia/Jakarta`.

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah lisensi terbuka [MIT License](LICENSE).
