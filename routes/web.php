<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SteganoController;
use App\Http\Controllers\HistoryController;

// Rute untuk halaman utama Steganografi (Guest & User)
Route::get('/', [SteganoController::class, 'index'])->name('home');

// Halaman edukasi Cara Kerja
Route::get('/cara-kerja', [SteganoController::class, 'caraKerja'])->name('cara.kerja');

// Rute khusus untuk User yang sudah Login
Route::middleware(['auth'])->group(function () {
    // Halaman Dashboard untuk melihat tabel riwayat
    Route::get('/dashboard', [HistoryController::class, 'index'])->name('dashboard');
    
    // RUTE BARU UNTUK HAPUS
    Route::post('/history/store', [HistoryController::class, 'store'])->name('history.store');
    // Rute tersembunyi (API/AJAX) untuk menyimpan riwayat dari JavaScript
    Route::delete('/history/{id}', [HistoryController::class, 'destroy'])->name('history.destroy');
});

// Memuat rute bawaan Laravel Breeze (Login, Register, dll)
require __DIR__.'/auth.php';