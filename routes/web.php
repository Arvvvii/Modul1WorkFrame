<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KantinController; // Kelompokkan use di atas biar rapi

// Route Public
Route::get('/', function () {
    return view('dashboard');
});

Auth::routes();

// --- SEMUA ROUTE LOGIN (GRUP AUTH) ---
Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        return view('dashboard');
    })->name('home');

    // Resource CRUD
    Route::resource('kategori', KategoriController::class);
    Route::resource('buku', BukuController::class);
    Route::resource('barang', BarangController::class);
    
    // Cetak & PDF
    Route::post('/barang/cetak', [BarangController::class, 'cetak'])->name('barang.cetak');
    Route::get('/generate-sertifikat', [PdfController::class, 'sertifikat'])->name('generate.sertifikat');
    Route::get('/generate-undangan', [PdfController::class, 'undangan'])->name('generate.undangan');
    Route::get('/generate-pdf', [DashboardController::class, 'generatePDF'])->name('generate.pdf');

    // Modul 4 & 5
    Route::get('/barang-dom', function () {
        $barangs = \App\Models\Barang::all(); 
        return view('barang.tugas_dom', compact('barangs'));
    })->name('barang.dom');
    Route::get('/select-kota', function () { return view('barang.select_kota'); })->name('select.kota');

    Route::get('/wilayah', [WilayahController::class, 'index'])->name('wilayah.index');
    Route::get('/wilayah/regencies/{province_id}', [WilayahController::class, 'getRegencies'])->name('wilayah.getRegencies');
    Route::get('/wilayah/districts/{regency_id}', [WilayahController::class, 'getDistricts'])->name('wilayah.getDistricts');
    Route::get('/wilayah/villages/{district_id}', [WilayahController::class, 'getVillages'])->name('wilayah.getVillages');

    Route::get('/kasir', [BarangController::class, 'kasir'])->name('kasir.index');
    Route::get('/kasir/cari/{kode}', [BarangController::class, 'cariBarang'])->name('kasir.cari');
    Route::post('/kasir/simpan', [BarangController::class, 'simpanTransaksi'])->name('kasir.simpan');

    // AREA VENDOR (LOGIN)
    Route::get('/vendor', [KantinController::class, 'masterVendor'])->name('vendor.index');
    Route::get('/menu', [KantinController::class, 'masterMenu'])->name('menu.index');
    Route::get('/transaksi-lunas', [KantinController::class, 'transaksiLunas'])->name('vendor.transaksi');
});

// --- AREA PUBLIC (LUAR AUTH) ---
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');
Route::get('/otp/verify', [GoogleController::class, 'showOtpForm'])->name('otp.verify');
Route::post('/otp/verify', [GoogleController::class, 'verifyOtp'])->name('otp.verify.post');

// --- AREA KANTIN (GUEST & CALLBACK) ---
Route::get('/pesan-kantin', [KantinController::class, 'index'])->name('kantin.index');
Route::get('/get-menu/{idvendor}', [KantinController::class, 'getMenu'])->name('kantin.getMenu'); 
Route::post('/checkout', [KantinController::class, 'checkout'])->name('kantin.checkout');
Route::get('/pembayaran-customer', [KantinController::class, 'pembayaranCustomer'])->name('kantin.pembayaran');

// Midtrans Callback (Tanpa CSRF)
Route::post('/midtrans-callback', [KantinController::class, 'callback'])
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);