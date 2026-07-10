<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::get('/', function () {
    return redirect()->route('login');
});

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
    
    // Nanti rute CRUD Event, Kategori, Laporan taruh di sini
});

// Area khusus User (Pembeli)
Route::middleware(['auth', 'role:user'])->prefix('user')->group(function () {
    Route::get('/dashboard', function () {
        return "Ini adalah Dashboard User untuk melihat tiket.";
    })->name('user.dashboard');
    
    // Nanti rute Checkout, Cart, Tiket taruh di sini
});