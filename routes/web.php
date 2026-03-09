<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleController;

Route::get('/', function () {
    return view('dashboard');
});

Auth::routes();

Route::get('/home', function () {
    return view('dashboard');
})->middleware('auth')->name('home');

// Resource routes protected by auth
Route::middleware('auth')->group(function () {
    Route::resource('kategori', App\Http\Controllers\KategoriController::class);
    Route::resource('buku', App\Http\Controllers\BukuController::class);
    Route::resource('barang', App\Http\Controllers\BarangController::class);
    
    Route::post('/barang/cetak', [App\Http\Controllers\BarangController::class, 'cetak'])->name('barang.cetak');
    
    // PDF document routes
    Route::get('/generate-sertifikat', [App\Http\Controllers\PdfController::class, 'sertifikat'])->name('generate.sertifikat');
    Route::get('/generate-undangan', [App\Http\Controllers\PdfController::class, 'undangan'])->name('generate.undangan');
    
    // Route to generate PDF certificate for authenticated users
    Route::get('/generate-pdf', [App\Http\Controllers\DashboardController::class, 'generatePDF'])->name('generate.pdf');

    // --- START REVISI: ROUTES TUGAS MODUL 4 ---
    
    // Route untuk Tugas Manipulasi Tabel (DOM)
    Route::get('/barang-dom', function () {
        // Kita ambil data dari database supaya tabel tugas tidak kosong
        $barangs = \App\Models\Barang::all(); 
        return view('barang.tugas_dom', compact('barangs'));
    })->name('barang.dom');

    // Route untuk Tugas Select & Select2 Kota
    Route::get('/select-kota', function () {
        return view('barang.select_kota');
    })->name('select.kota');

    // --- END REVISI ---
});

// Google OAuth routes
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// OTP verification routes
// Pastikan ini di luar grup auth
Route::get('/otp/verify', [GoogleController::class, 'showOtpForm'])->name('otp.verify');
Route::post('/otp/verify', [GoogleController::class, 'verifyOtp'])->name('otp.verify.post');