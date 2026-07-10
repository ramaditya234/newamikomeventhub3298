<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\TransactionController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\Admin\EventController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth Routes (Hanya untuk yang belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Logout (Harus login terlebih dahulu)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Area khusus Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return "Ini adalah Halaman Admin. Layout sidebar gelap akan diterapkan di sini.";
    })->name('admin.dashboard');
});

// Area khusus Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return "Ini adalah Halaman Admin. Layout sidebar gelap akan diterapkan di sini.";
    })->name('admin.dashboard');
    
    Route::resource('events', EventController::class);
    // Nanti rute CRUD Event, Kategori, Laporan taruh di sini
});

// Area khusus User (Pembeli)
Route::middleware(['auth', 'role:user'])->prefix('user')->group(function () {
    Route::get('/dashboard', function () {
        return "Ini adalah Dashboard User untuk melihat tiket.";
    })->name('user.dashboard');
    
    // Rute Submit Keranjang dari Halaman Detail (Yang kita buat di langkah sebelumnya)
    Route::post('/cart/add/{id}', [CartController::class, 'store'])->name('cart.store');

    // Rute Proses Checkout (Ke Midtrans)
    Route::post('/checkout', [TransactionController::class, 'checkout'])->name('checkout');
});

// Tambahkan rute detail event (Publik) di bawah rute home
Route::get('/event/{id}', [HomeController::class, 'show'])->name('event.show');

// Perbarui area khusus User (Pembeli) untuk menambahkan rute Cart
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', function () {
        return "Ini adalah Dashboard User untuk melihat keranjang dan tiket.";
    })->name('dashboard');
    
    // Rute Submit Keranjang
    Route::post('/cart/add/{id}', [CartController::class, 'store'])->name('cart.store');
});