<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use App\Models\Field;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - SM Sport Center
|--------------------------------------------------------------------------
| Alur pemesanan publik (guest booking tanpa wajib login),
| pengecekan slot jam, cek pesanan, dan panel admin.
*/

// Public: Halaman Utama (Langsung Kartu Lapangan & Booking)
Route::get('/', function () {
    $fields = Field::orderBy('id', 'asc')->get();
    return view('welcome', compact('fields'));
})->name('home');

// Public: Slot Jadwal Real-time (08:00 - 23:00 WIB)
Route::get('/reservations/schedule', [ReservationController::class, 'schedule'])->name('reservations.schedule');

// Public: Daftar & Detail Lapangan
Route::get('/fields', [FieldController::class, 'index'])->name('fields.index');
Route::get('/fields/{field}', [FieldController::class, 'show'])->name('fields.show');

// Public: Alur Pemesanan Pelanggan (Guest Booking Tanpa Wajib Login)
Route::get('/reservations', [ReservationController::class, 'create'])->name('reservations.create');
Route::redirect('/reservations/create', '/reservations');
Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');
Route::post('/reservations/{reservation}/upload-proof', [ReservationController::class, 'uploadProof'])->name('reservations.upload_proof');
Route::get('/reservations/{reservation}/ticket', [ReservationController::class, 'ticket'])->name('reservations.ticket');
Route::post('/reservations/{reservation}/transaction', [ReservationController::class, 'createTransaction'])->name('reservations.transaction.create');
Route::post('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');

// Public: Fitur Cek Status Pesanan (via Kode Booking atau WhatsApp)
Route::match(['get', 'post'], '/cek-pesanan', [ReservationController::class, 'checkOrder'])->name('reservations.check');

// Public: Mock Payment Flows (QRIS, VA, E-Wallet)
Route::get('/payments/qris/{transaction}', [App\Http\Controllers\QrisController::class, 'show'])->name('payments.qris');
Route::get('/payments/qris/status/{transaction}', [App\Http\Controllers\QrisController::class, 'status']);
Route::match(['get', 'post'], '/payments/qris/webhook', [App\Http\Controllers\QrisController::class, 'webhook'])->name('payments.qris.webhook');

Route::get('/payments/va/{transaction}', [App\Http\Controllers\VirtualAccountController::class, 'show'])->name('payments.va');
Route::post('/payments/va/initiate/{transaction}', [App\Http\Controllers\VirtualAccountController::class, 'initiate']);
Route::match(['get', 'post'], '/payments/va/webhook', [App\Http\Controllers\VirtualAccountController::class, 'webhook'])->name('payments.va.webhook');
Route::get('/payments/va/status/{transaction}', [App\Http\Controllers\VirtualAccountController::class, 'status']);

Route::get('/payments/ewallet/{transaction}', [App\Http\Controllers\EWalletController::class, 'show'])->name('payments.ewallet');
Route::post('/payments/ewallet/initiate/{transaction}', [App\Http\Controllers\EWalletController::class, 'initiate']);
Route::get('/payments/ewallet/deeplink/{transaction}/{provider}', [App\Http\Controllers\EWalletController::class, 'deeplink']);
Route::match(['get', 'post'], '/payments/ewallet/webhook', [App\Http\Controllers\EWalletController::class, 'webhook'])->name('payments.ewallet.webhook');
Route::get('/payments/ewallet/status/{transaction}', [App\Http\Controllers\EWalletController::class, 'status']);

// Auth: Login & Register
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/demo-login/{role}', [AuthController::class, 'demoLogin'])->name('demo.login');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Authenticated User Routes (Opsional bagi pelanggan terdaftar)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $availableFields = Field::orderBy('id', 'asc')->get();
        return view('dashboard', compact('availableFields'));
    })->name('dashboard');

    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/my-reservations', [ReservationController::class, 'myReservations'])->name('customer.reservations');
    Route::get('/my-transactions', [TransactionController::class, 'myTransactions'])->name('customer.transactions');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::post('/transactions/{transaction}/cancel', [TransactionController::class, 'cancel'])->name('transactions.cancel');
    Route::get('/transactions/{transaction}/invoice', [InvoiceController::class, 'show'])->name('transactions.invoice');
    Route::get('/transactions/{transaction}/pdf', [InvoiceController::class, 'pdf'])->name('transactions.pdf');
    Route::post('/transactions/{transaction}/upload-proof', [TransactionController::class, 'uploadProof'])->name('transactions.upload_proof');
});

// Admin Management Panel
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::resource('fields', FieldController::class);
    Route::resource('users', UserController::class);
    Route::post('/users/{user}/status/{status}', [UserController::class, 'updateStatus'])->name('users.status');

    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');
    Route::match(['put', 'post'], '/reservations/{reservation}/reschedule', [AdminController::class, 'rescheduleReservation'])->name('reservations.reschedule');
    Route::post('/reservations/{reservation}/status', [ReservationController::class, 'updateStatus'])->name('reservations.status');
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');

    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::post('/transactions/{transaction}/verify', [AdminController::class, 'verifyPayment'])->name('transactions.verify');

    Route::get('/manual-booking', [AdminController::class, 'manualBookingForm'])->name('manual_booking');
    Route::post('/manual-booking', [AdminController::class, 'storeManualBooking'])->name('manual_booking.store');
});
