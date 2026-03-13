<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // Tambahkan ini agar Auth::routes() tidak error
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\DashboardController;

// Route Public
Route::get('/', function () {
    return view('dashboard');
});

Auth::routes();

// Semua Route yang harus LOGIN ada di dalam grup ini
Route::middleware('auth')->group(function () {
    
    Route::get('/home', function () {
        return view('dashboard');
    })->name('home');

    // Resource CRUD
    Route::resource('kategori', KategoriController::class);
    Route::resource('buku', BukuController::class);
    Route::resource('barang', BarangController::class);
    
    // Cetak Label Barang
    Route::post('/barang/cetak', [BarangController::class, 'cetak'])->name('barang.cetak');
    
    // PDF Routes
    Route::get('/generate-sertifikat', [PdfController::class, 'sertifikat'])->name('generate.sertifikat');
    Route::get('/generate-undangan', [PdfController::class, 'undangan'])->name('generate.undangan');
    Route::get('/generate-pdf', [DashboardController::class, 'generatePDF'])->name('generate.pdf');

    // --- TUGAS MODUL 4 (DOM & SELECT2) ---
    Route::get('/barang-dom', function () {
        $barangs = \App\Models\Barang::all(); 
        return view('barang.tugas_dom', compact('barangs'));
    })->name('barang.dom');

    Route::get('/select-kota', function () {
        return view('barang.select_kota');
    })->name('select.kota');

    // --- FITUR WILAYAH (MODUL 5 - AJAX/AXIOS) ---
    Route::get('/wilayah', [WilayahController::class, 'index'])->name('wilayah.index');
    Route::get('/wilayah/regencies/{province_id}', [WilayahController::class, 'getRegencies'])->name('wilayah.getRegencies');
    Route::get('/wilayah/districts/{regency_id}', [WilayahController::class, 'getDistricts'])->name('wilayah.getDistricts');
    Route::get('/wilayah/villages/{district_id}', [WilayahController::class, 'getVillages'])->name('wilayah.getVillages');

    // --- FITUR KASIR / POS (MODUL 5 - AJAX/AXIOS) ---
    Route::get('/kasir', [BarangController::class, 'kasir'])->name('kasir.index');
    Route::get('/kasir/cari/{kode}', [BarangController::class, 'cariBarang'])->name('kasir.cari');
    Route::post('/kasir/simpan', [BarangController::class, 'simpanTransaksi'])->name('kasir.simpan');

}); // <--- PENUTUP GRUP AUTH

// Google OAuth (Luar Auth)
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// OTP Routes (Luar Auth)
Route::get('/otp/verify', [GoogleController::class, 'showOtpForm'])->name('otp.verify');
Route::post('/otp/verify', [GoogleController::class, 'verifyOtp'])->name('otp.verify.post');