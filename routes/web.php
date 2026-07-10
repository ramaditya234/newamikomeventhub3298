<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
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