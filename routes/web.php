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

// Resource routes for kategori and buku protected by auth
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
});

// Google OAuth routes
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// OTP verification routes
// Pastikan ini di luar grup auth
Route::get('/otp/verify', [GoogleController::class, 'showOtpForm'])->name('otp.verify');
Route::post('/otp/verify', [GoogleController::class, 'verifyOtp'])->name('otp.verify.post');
