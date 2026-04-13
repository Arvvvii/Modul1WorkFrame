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
use App\Http\Controllers\KantinController;
use App\Http\Controllers\CustomerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Route Halaman Depan
Route::get('/', function () {
    return view('dashboard');
});

// Route Autentikasi Bawaan Laravel
Auth::routes();

// ---------------------------------------------------------
// SEMUA ROUTE YANG WAJIB LOGIN (GROUP AUTH)
// ---------------------------------------------------------
Route::middleware('auth')->group(function () {
    
    Route::get('/home', function () {
        return view('dashboard');
    })->name('home');

    // Resource CRUD (Kategori, Buku, Barang)
    Route::resource('kategori', KategoriController::class);
    Route::resource('buku', BukuController::class);
    Route::resource('barang', BarangController::class);
    
    // Fitur Cetak Label & PDF
    Route::post('/barang/cetak', [BarangController::class, 'cetak'])->name('barang.cetak');
    Route::get('/generate-sertifikat', [PdfController::class, 'sertifikat'])->name('generate.sertifikat');
    Route::get('/generate-undangan', [PdfController::class, 'undangan'])->name('generate.undangan');
    Route::get('/generate-pdf', [DashboardController::class, 'generatePDF'])->name('generate.pdf');

    // Tugas Modul 4 (DOM & Select2)
    Route::get('/barang-dom', function () {
        $barangs = \App\Models\Barang::all(); 
        return view('barang.tugas_dom', compact('barangs'));
    })->name('barang.dom');

    Route::get('/barang-datatables', function () {
        $barangs = \App\Models\Barang::all(); 
        return view('barang.tugas_datatables', compact('barangs'));
    })->name('barang.datatables');

    Route::get('/select-kota', function () {
        return view('barang.select_kota');
    })->name('select.kota');

    // Fitur Wilayah (AJAX/Axios)
    Route::get('/wilayah', [WilayahController::class, 'index'])->name('wilayah.index');
    Route::get('/wilayah/regencies/{province_id}', [WilayahController::class, 'getRegencies'])->name('wilayah.getRegencies');
    Route::get('/wilayah/districts/{regency_id}', [WilayahController::class, 'getDistricts'])->name('wilayah.getDistricts');
    Route::get('/wilayah/villages/{district_id}', [WilayahController::class, 'getVillages'])->name('wilayah.getVillages');

    // Fitur Kasir / POS
    Route::get('/kasir', [BarangController::class, 'kasir'])->name('kasir.index');
    Route::get('/kasir-ajax', [BarangController::class, 'kasirAjax'])->name('kasir.ajax');
    Route::get('/kasir/cari/{kode}', [BarangController::class, 'cariBarang'])->name('kasir.cari');
    Route::post('/kasir/simpan', [BarangController::class, 'simpanTransaksi'])->name('kasir.simpan');

    // Customer Modul 7
    Route::get('/customers', [CustomerController::class, 'index'])->name('customer.index');
    Route::get('/customers/create', [CustomerController::class, 'create'])->name('customer.create');
    Route::get('/customers/create/blob', [CustomerController::class, 'createBlob'])->name('customer.create.blob');
    Route::get('/customers/create/file', [CustomerController::class, 'createFile'])->name('customer.create.file');
    Route::post('/customers/blob', [CustomerController::class, 'storeBlob'])->name('customer.store.blob');
    Route::post('/customers/file', [CustomerController::class, 'storeFile'])->name('customer.store.file');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customer.store');

    // Area Vendor (Kantin Master Data)
    Route::get('/vendor', [KantinController::class, 'masterVendor'])->name('vendor.index');
    Route::get('/menu', [KantinController::class, 'masterMenu'])->name('menu.index');
    Route::get('/transaksi-lunas', [KantinController::class, 'transaksiLunas'])->name('vendor.transaksi');

}); 


// ---------------------------------------------------------
// AREA PUBLIC / GUEST (LUAR AUTH)
// ---------------------------------------------------------

// Google OAuth & OTP
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');
Route::get('/otp/verify', [GoogleController::class, 'showOtpForm'])->name('otp.verify');
Route::post('/otp/verify', [GoogleController::class, 'verifyOtp'])->name('otp.verify.post');

// Fitur Pemesanan Kantin (Guest Mode)
Route::get('/pesan-kantin', [KantinController::class, 'index'])->name('kantin.index');
Route::get('/get-menu/{idvendor}', [KantinController::class, 'getMenu'])->name('kantin.getMenu'); 
Route::post('/checkout', [KantinController::class, 'checkout'])->name('kantin.checkout');
Route::get('/pembayaran-customer', [KantinController::class, 'pembayaranCustomer'])->name('kantin.pembayaran');
Route::get('/kantin/qr/{order_id}', [KantinController::class, 'qrCode'])->name('kantin.qr');

// Midtrans Callback (Izin CSRF sudah diatur di bootstrap/app.php)
Route::post('/midtrans-callback', [KantinController::class, 'callback'])->name('kantin.callback');
Route::post('/', [KantinController::class, 'callback'])->name('kantin.callback.root');