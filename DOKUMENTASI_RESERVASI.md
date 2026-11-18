# Dokumentasi Fitur Reservasi SM Sport Center

Dokumentasi ini menjelaskan alur, struktur, dan implementasi fitur reservasi, DP, cetak nota, serta pembatalan reservasi di aplikasi SM Sport Center.

## 1. Ringkasan Fitur

### 1.1 Fitur Utama
- Reservasi lapangan dengan kontrol jam dan validasi double booking.
- Transaksi pembayaran untuk reservasi.
- Pilihan bayar: full payment atau DP (down payment).
- Pembatalan reservasi dengan alasan wajib.
- Cetak nota / invoice transaksi.
- Nota pembatalan menampilkan stempel `DIBATALKAN` dan alasan pembatalan.

## 2. Database

### 2.1 Migration Baru
- `database/migrations/2026_07_27_000001_add_cancellation_and_deposit_columns.php`
  - Menambahkan kolom di tabel `reservations`:
    - `cancellation_reason` (text, nullable)
    - `cancelled_at` (timestamp, nullable)
  - Menambahkan kolom di tabel `transactions`:
    - `is_deposit` (boolean, default false)
    - `deposit_amount` (decimal, nullable)

### 2.2 Model
- `app/Models/Reservation.php`
  - `fillable` ditambah:
    - `cancellation_reason`
    - `cancelled_at`
  - `casts` ditambah:
    - `cancelled_at` => `datetime`

- `app/Models/Transaction.php`
  - `fillable` ditambah:
    - `is_deposit`
    - `deposit_amount`
  - `casts` ditambah:
    - `is_deposit` => `boolean`
    - `deposit_amount` => `decimal:2`

## 3. Route dan Controller

### 3.1 Route
- `routes/web.php`
  - `Route::post('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');`

### 3.2 ReservationController
- `cancel(Request $request, Reservation $reservation)`
  - Autentikasi dan otorisasi:
    - Hanya user pemilik reservasi atau admin.
  - Validasi input:
    - `cancellation_reason` wajib diisi.
  - Update data reservasi:
    - `status = 'cancelled'`
    - `cancellation_reason`
    - `cancelled_at = now()`
  - Jika transaksi terkait belum dibayar, set status transaksi menjadi `cancelled`.
  - Log activity dengan alasan singkat.

### 3.3 createTransaction untuk DP
- `createTransaction(Request $request, Reservation $reservation)`
  - Menerima `type`:
    - `full` => bayar lunas.
    - `deposit` => bayar DP.
  - Jika `deposit`:
    - Validasi `deposit_amount`.
    - `amount` transaksi diset dari DP.
    - `is_deposit` = true.
    - `deposit_amount` diisi.

## 4. Frontend (Blade View)

### 4.1 Halaman Riwayat Reservasi
- `resources/views/reservations/my-reservations.blade.php`
  - Menampilkan list reservasi.
  - Tombol `Batalkan` hanya aktif saat status `pending` atau `confirmed`.
  - Tombol membuka modal pembatalan, bukan langsung submit.
  - Modal:
    - Menampilkan peringatan DP.
    - Memaksa input `Alasan Pembatalan`.
    - Submit ke `/reservations/{id}/cancel`.

### 4.2 Halaman Detail Reservasi
- `resources/views/reservations/show.blade.php`
  - Menambahkan tombol `Batalkan Reservasi` yang membuka modal sama.
  - Input alasan pembatalan wajib.

### 4.3 Invoice / Nota
- `resources/views/transactions/invoice.blade.php`
  - Menampilkan badge `DIBATALKAN` jika reservasi atau transaksi status `cancelled`.
  - Memunculkan alasan pembatalan.
  - Menyediakan tombol:
    - `Unduh PDF`
    - `Cetak Bukti Pembayaran`

## 5. Alur Pembayaran

### 5.1 Full Payment
- Total transaksi = `reservation.total_price`.
- `is_deposit = false`.
- `deposit_amount = null`.

### 5.2 Down Payment (DP)
- User dapat membayar sebagian (`deposit_amount`).
- `is_deposit = true`.
- Transaksi hanya mencatat nilai DP ke `amount`.
- Reservasi tetap disimpan.

## 6. UX Pembatalan

### 6.1 Modal Konfirmasi Pembatalan
- Judul: `Batalkan Reservasi`
- Pesan peringatan DP: `Pembatalan reservasi akan melepaskan slot jam ini. Kebijakan DP sesuai aturan pengelola.`
- Data reservasi ditampilkan di modal.
- `Alasan Pembatalan` wajib.
- Tombol `Konfirmasi Batalkan` submit form.

## 7. Catatan Teknis

### 7.1 Melepaskan Slot Jam
- Setelah status reservasi menjadi `cancelled`, query jadwal hanya mempertimbangkan reservasi `status = pending` atau `confirmed`.
- Slot dibebaskan otomatis ketika status `cancelled`.

### 7.2 Stempel Dibatalkan
- Invoice memeriksa:
  - `reservation.status === 'cancelled'`
  - atau `transaction.status === 'cancelled'`
- Menampilkan stempel dan alasan pembatalan.

## 8. Instruksi Migrasi dan Testing

### 8.1 Migrasi
```bash
php artisan migrate
```

### 8.2 Jika menggunakan seeder atau fresh setup
```bash
php artisan migrate:refresh --seed
```

### 8.3 Pastikan:
- Kolom `cancellation_reason` ada di tabel `reservations`.
- Kolom `is_deposit` dan `deposit_amount` ada di tabel `transactions`.
- Tombol batal tampil di `my-reservations` untuk status `pending` dan `confirmed`.
- Invoice menampilkan badge `DIBATALKAN` ketika transaksi / reservasi dibatalkan.

## 9. File Utama Terkait
- `app/Models/Reservation.php`
- `app/Models/Transaction.php`
- `app/Http/Controllers/ReservationController.php`
- `routes/web.php`
- `resources/views/reservations/my-reservations.blade.php`
- `resources/views/reservations/show.blade.php`
- `resources/views/transactions/invoice.blade.php`
- `database/migrations/2026_07_27_000001_add_cancellation_and_deposit_columns.php`

---

Dokumentasi ini bisa dipertajam lagi dengan diagram alur UI atau contoh payload API jika diperlukan.