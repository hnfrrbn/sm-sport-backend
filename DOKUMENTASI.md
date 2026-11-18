# SM Sport Center - Sistem Reservasi Lapangan Olahraga

Aplikasi web berbasis Laravel untuk mengelola reservasi lapangan olahraga (Futsal dan Badminton) dengan fitur lengkap termasuk autentikasi, manajemen data, dan laporan.

## 📋 Fitur Utama

### Admin
- ✅ Dashboard dengan statistik dan grafik (Chart.js)
- ✅ Manajemen Lapangan (CRUD)
- ✅ Manajemen Pengguna
- ✅ Manajemen Reservasi
- ✅ Manajemen Transaksi
- ✅ Log Aktivitas

### Pelanggan
- ✅ Registrasi dan Login
- ✅ Melihat Daftar Lapangan
- ✅ Membuat Reservasi dengan Validasi Double Booking
- ✅ Melihat Riwayat Reservasi
- ✅ Melihat Transaksi
- ✅ Edit Profil

## 🏗️ Struktur Proyek

```
sm-sport-backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AdminController.php
│   │   │   ├── FieldController.php
│   │   │   ├── ReservationController.php
│   │   │   ├── TransactionController.php
│   │   │   ├── UserController.php
│   │   │   ├── ProfileController.php
│   │   │   └── Auth/
│   │   │       └── AuthController.php
│   │   ├── Middleware/
│   │   │   ├── CheckAdmin.php
│   │   │   └── CheckRole.php
│   │   └── Requests/
│   │       ├── LoginRequest.php
│   │       ├── RegisterRequest.php
│   │       ├── StoreReservationRequest.php
│   │       ├── UpdateProfileRequest.php
│   │       └── StoreFieldRequest.php
│   └── Models/
│       ├── User.php
│       ├── Field.php
│       ├── Reservation.php
│       ├── Transaction.php
│       └── ActivityLog.php
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 2026_07_24_000001_create_fields_table.php
│   │   ├── 2026_07_24_000002_create_reservations_table.php
│   │   ├── 2026_07_24_000003_create_transactions_table.php
│   │   └── 2026_07_24_000004_create_activity_logs_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── UserSeeder.php
│       └── FieldSeeder.php
├── resources/views/
│   ├── layouts/
│   │   └── app.blade.php
│   ├── auth/
│   │   ├── login.blade.php
│   │   └── register.blade.php
│   ├── admin/
│   │   └── dashboard.blade.php
│   ├── fields/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   └── show.blade.php
│   ├── reservations/
│   │   ├── create.blade.php
│   │   ├── index.blade.php
│   │   ├── show.blade.php
│   │   └── my-reservations.blade.php
│   ├── transactions/
│   │   ├── index.blade.php
│   │   ├── show.blade.php
│   │   └── my-transactions.blade.php
│   ├── users/
│   │   ├── index.blade.php
│   │   └── show.blade.php
│   ├── profile/
│   │   └── edit.blade.php
│   ├── welcome.blade.php
│   └── dashboard.blade.php
├── routes/
│   └── web.php
├── bootstrap/
│   └── app.php
├── public/
│   └── index.php
├── storage/
├── tests/
├── .env
├── .env.example
├── composer.json
├── package.json
├── artisan
└── README.md
```

## 🚀 Instalasi & Setup

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & npm (untuk asset management)
- Laragon atau web server lain

### Langkah Instalasi

1. **Clone Repository atau Buka Folder Proyek**
```bash
cd c:\laragon\www\sm-sport-backend
```

2. **Install Dependencies**
```bash
composer install
```

3. **Copy File Environment**
```bash
copy .env.example .env
```

4. **Generate Application Key**
```bash
php artisan key:generate
```

5. **Setup Database**
```bash
php artisan migrate --seed
```

6. **Start Development Server**
```bash
php artisan serve --port=8000
```

7. **Akses Aplikasi**
- URL: http://localhost:8000
- Admin: admin@gmail.com / password
- Pelanggan: budi@email.com / password

## 📊 Database Schema

### Tabel Users
```sql
CREATE TABLE users (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  phone VARCHAR(20),
  role ENUM('admin', 'customer') DEFAULT 'customer',
  address TEXT,
  status ENUM('active', 'inactive') DEFAULT 'active',
  email_verified_at TIMESTAMP NULL,
  remember_token VARCHAR(100),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Tabel Fields
```sql
CREATE TABLE fields (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  type ENUM('futsal', 'badminton') NOT NULL,
  capacity INT NOT NULL,
  price_per_hour DECIMAL(10,2) NOT NULL,
  status ENUM('available', 'unavailable', 'maintenance') DEFAULT 'available',
  description TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX (status)
);
```

### Tabel Reservations
```sql
CREATE TABLE reservations (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  user_id BIGINT NOT NULL,
  field_id BIGINT NOT NULL,
  reservation_date DATE NOT NULL,
  start_time TIME NOT NULL,
  end_time TIME NOT NULL,
  duration_hours INT NOT NULL,
  total_price DECIMAL(10,2) NOT NULL,
  status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
  notes TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (field_id) REFERENCES fields(id) ON DELETE CASCADE,
  INDEX (user_id),
  INDEX (field_id),
  INDEX (reservation_date),
  UNIQUE KEY unique_reservation_time (field_id, reservation_date, start_time, end_time)
);
```

### Tabel Transactions
```sql
CREATE TABLE transactions (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  user_id BIGINT NOT NULL,
  reservation_id BIGINT,
  transaction_code VARCHAR(255) UNIQUE NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  payment_method ENUM('cash', 'transfer', 'card') DEFAULT 'cash',
  status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
  description TEXT,
  payment_date TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (reservation_id) REFERENCES reservations(id) ON DELETE SET NULL,
  INDEX (user_id),
  INDEX (reservation_id),
  INDEX (payment_date)
);
```

### Tabel Activity_Logs
```sql
CREATE TABLE activity_logs (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  user_id BIGINT NOT NULL,
  action VARCHAR(255) NOT NULL,
  model_type VARCHAR(255),
  model_id BIGINT,
  description TEXT,
  old_values JSON,
  new_values JSON,
  ip_address VARCHAR(45),
  user_agent VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX (user_id),
  INDEX (action),
  INDEX (model_type),
  INDEX (created_at)
);
```

## 🔐 Keamanan

- CSRF Protection (Laravel Built-in)
- Session Authentication
- Password Hashing (Bcrypt)
- Input Validation
- Middleware Role-based Access Control
- XSS Protection

## 🎨 UI/UX

- Bootstrap 5 - Responsive Design
- Chart.js - Grafik Statistik
- SweetAlert2 - Alert Dialog
- Bootstrap Icons - Ikon UI
- Custom CSS - Styling Profesional

## 📝 Default Akun

### Admin
- Email: admin@gmail.com
- Password: password

### Pelanggan (Sample)
- Email: budi@email.com
- Password: password

### Lapangan (Sample Data)
- Futsal 1 - Rp 150.000/jam
- Futsal 2 - Rp 150.000/jam
- Badminton 1 - Rp 80.000/jam
- Badminton 2 - Rp 80.000/jam
- Badminton 3 - Rp 80.000/jam

## 📋 Alur Bisnis

### Customer Workflow
1. Register akun baru
2. Login dengan email dan password
3. Lihat daftar lapangan yang tersedia
4. Pilih lapangan, tanggal, dan jam
5. Sistem validasi double booking otomatis
6. Reservasi tersimpan dengan status "Pending"
7. Lihat riwayat reservasi
8. Admin mengkonfirmasi (status menjadi "Confirmed")
9. Pelanggan dapat membatalkan jika masih pending
10. Membayar dan transaksi tercatat

### Admin Workflow
1. Login sebagai admin
2. Lihat dashboard dengan statistik dan grafik
3. Kelola lapangan (tambah, edit, hapus)
4. Kelola pengguna
5. Kelola reservasi (lihat dan ubah status)
6. Kelola transaksi
7. Lihat log aktivitas

## 🧪 Testing

### Login Test
```
Akun Admin:
- Email: admin@gmail.com
- Password: password
- Expected: Redirect ke /admin/dashboard

Akun Pelanggan:
- Email: budi@email.com
- Password: password
- Expected: Redirect ke /dashboard
```

### Fitur Double Booking Validation
- Saat membuat reservasi, sistem akan mengecek apakah ada reservasi lain pada lapangan yang sama dengan waktu yang saling bertabrakan
- Jika ada konflik, tampilkan error: "Waktu reservasi bentrok dengan reservasi lain. Silakan pilih waktu lain."

## 📦 Dependencies

### Backend
- laravel/framework: ^12.0
- laravel/tinker: ^2.10.1

### Frontend
- Bootstrap 5
- Chart.js
- SweetAlert2
- Bootstrap Icons

## 🔧 Perintah Artisan Berguna

```bash
# Migrasi Database
php artisan migrate

# Rollback Migrasi
php artisan migrate:rollback

# Fresh Migration dengan Seeding
php artisan migrate:refresh --seed

# Buat Model dengan Migration
php artisan make:model Field -m

# Buat Controller
php artisan make:controller FieldController

# Buat Request Validation
php artisan make:request StoreFieldRequest

# Buat Middleware
php artisan make:middleware CheckRole

# Buat Seeder
php artisan make:seeder FieldSeeder
```

## 📸 Screenshots

### Welcome Page
- Landing page dengan info aplikasi
- Login dan Register buttons

### Admin Dashboard
- Statistik: Total Users, Fields, Reservations, Revenue
- Grafik Reservasi (7 hari terakhir)
- Grafik Revenue (7 hari terakhir)
- Lapangan Paling Populer

### Field Management
- Daftar semua lapangan dalam format card
- Opsi untuk create, read, update, delete
- Status lapangan (Available, Unavailable, Maintenance)
- Harga per jam

### Reservation Management
- Customer bisa membuat reservasi
- Sistem validasi double booking
- Customer bisa melihat riwayat
- Admin bisa manage semua reservasi

### User Management
- Daftar semua pengguna
- Info detail per user
- Role: Admin atau Customer
- Status: Active atau Inactive

## 🐛 Troubleshooting

### Database Connection Error
```
Solution:
- Pastikan SQLite atau MySQL sudah running
- Cek konfigurasi di file .env
- Jalankan php artisan migrate:refresh --seed
```

### CSRF Token Mismatch
```
Solution:
- Pastikan @csrf ada di setiap form
- Clear browser cache
- Jalankan php artisan cache:clear
```

### Route Not Found
```
Solution:
- Jalankan php artisan route:clear
- Jalankan php artisan cache:clear
- Periksa routes/web.php
```

## 📞 Support

Untuk pertanyaan atau issue, silakan cek:
- File dokumentasi di folder /docs
- Error logs di /storage/logs
- Database seeder untuk sample data

## 📄 License

MIT License

---

**Created for SM Sport Center** | **Last Updated: 2026-07-24**
