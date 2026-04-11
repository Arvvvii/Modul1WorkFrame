<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## Modul 4 dan Modul 5

Dokumentasi untuk tugas yang sudah diimplementasikan pada proyek ini:

### Modul 4
- `resources/views/barang/select_kota.blade.php`
  - Implementasi input kota dan pilihan kota menggunakan select native dan Select2.
  - Tambah kota baru melalui input text, lalu masukkan ke select.
  - Menampilkan ringkasan kota terpilih.
- `resources/views/barang/tugas_dom.blade.php`
  - Manipulasi tabel DOM (update dan delete) tanpa reload.
  - Baris tabel dapat diklik untuk membuka modal edit.
  - Tombol `Update` mengganti nama dan harga di baris terpilih.
  - Tombol `Hapus` menghapus baris tabel di browser.

### Modul 5
- `resources/views/barang/kasir.blade.php`
  - Halaman Kasir (POS) untuk input barang, jumlah, dan daftar belanja.
  - Logika keranjang di browser untuk tambah barang, edit qty, hapus item, dan hitung total.
  - Tombol `Bayar (Simpan)` mengirim data pembayaran ke server via Axios.
  - Hanya mereset keranjang ketika respons server sukses, untuk mencegah kehilangan data.
- `resources/views/wilayah/index.blade.php`
  - Implementasi chained dropdown wilayah administrasi.
  - Provinsi -> kabupaten menggunakan jQuery AJAX.
  - Kabupaten -> kecamatan dan kecamatan -> desa menggunakan Axios.
  - Penanganan loading dan error untuk data dropdown.

### Modul 7
- `database/migrations/2026_04_11_120000_create_customers_table.php`
  - Membuat tabel `customers` dengan kolom profil lengkap dan dua jenis penyimpanan foto: `foto_blob` (LONGBLOB) dan `foto_path` (VARCHAR).
- `app/Models/Customer.php`
  - Model customer dengan relasi ke `Province`, `Regency`, dan `District`.
- `app/Http/Controllers/CustomerController.php`
  - Index customer, form tambah customer, dan simpan data kamera.
- `resources/views/customer/create.blade.php`
  - Form tambah customer dengan field `Nama`, `Alamat`, `Provinsi`, `Kota`, `Kecamatan`, `Kodepos/Kelurahan`, dan tombol kamera.
  - Modal Bootstrap untuk akses kamera dengan Webcam API dan snapshot.
- `resources/views/customer/index.blade.php`
  - Tabel customer menampilkan data profil lengkap dan preview foto.
- `app/Http/Controllers/BarangController.php`
  - Barcode ditambahkan ke PDF tag harga menggunakan library `picqer/php-barcode-generator`.
- `resources/views/barang/pdf.blade.php`
  - Barcode tampil di atas nomor `id_barang` pada tag harga.
- `app/Http/Controllers/KantinController.php`
  - QR Code `idpesanan` ditambahkan di halaman sukses pembayaran (`kantin.pembayaran`).
- `resources/views/kantin/pembayaran.blade.php`
  - Menampilkan QR Code dari `idpesanan` di bagian transaksi terakhir.

> Catatan: semua perubahan hanya dilakukan pada fitur modul yang diminta tanpa merusak modul lain.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
