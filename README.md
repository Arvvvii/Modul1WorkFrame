# Dokumentasi Aplikasi Laravel

> Proyek ini adalah kumpulan tugas Modul 1–7, dengan fokus teknis pada arsitektur Blade, keamanan Google OAuth + OTP, database, DOM/JQuery, AJAX/Axios, dan integrasi Midtrans/Barcode/QR.

---

## 1. Arsitektur Proyek (Modul 1 & 2)

### 1.1 Struktur Blade Layout: `@section` dan `@stack`

File utama layout aplikasi:

- `resources/views/layouts/master.blade.php`
  - memuat `@include('layouts.header')`
  - memuat `@include('layouts.style-global')`
  - memuat `@stack('style-page')`
  - memuat `@include('layouts.js-global')`
  - memuat `@stack('js-page')`

Contoh di layout utama:

```blade
<head>
  @include('layouts.header')
  @include('layouts.style-global')
  @stack('style-page')
</head>
<body>
  @yield('content')
  @include('layouts.js-global')
  @stack('js-page')
</body>
```

- `@yield('content')` adalah titik injeksi utama untuk konten halaman.
- Setiap file view menggunakan `@section('content')` untuk menyediakan konten halaman.

### 1.2 Global Styles / Scripts vs Page-Specific Styles / Scripts

- `layouts/style-global` dan `layouts/js-global` berisi aset yang dipakai di seluruh aplikasi.
- `@stack('style-page')` dan `@stack('js-page')` digunakan untuk menambahkan CSS/JS hanya di halaman tertentu.

Contoh halaman spesifik:

```blade
@push('style-page')
<style>
  #tabel-barang tbody tr:hover { cursor: pointer !important; }
</style>
@endpush

@section('content')
  <!-- konten halaman -->
@endsection

@push('js-page')
<script>
  // script khusus halaman
</script>
@endpush
```

### 1.3 Alur Integrasi Google OAuth dan OTP Email

Flow login di proyek:

1. User klik login Google -> `Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])`.
2. Google mengembalikan callback ke `handleGoogleCallback`.
3. Controller mengambil data user dari Socialite.
4. Jika email sudah ada, update `id_google`; jika baru, buat user baru.
5. Controller membuat OTP 6 digit dengan `random_int(0, 999999)`.
6. OTP disimpan ke kolom `users.otp`.
7. OTP dikirim via email dengan `Mail::raw(...)`.
8. Email disimpan ke session `otp_email`.
9. User diarahkan ke form OTP (`/otp/verify`).
10. Setelah validasi OTP, user `Auth::login($user)`.

Kode inti di `app/Http/Controllers/GoogleController.php`:

```php
$otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
$user->otp = $otp;
$user->save();
Mail::raw('Kode OTP Anda: ' . $user->otp, function ($message) use ($user) {
    $message->to($user->email)->subject('Kode OTP Verifikasi');
});
```

Keamanan ini membuat login Google menjadi dua langkah: autentikasi OAuth + verifikasi OTP email.

---

## 2. Database & Trigger (Modul 3)

### 2.1 Logika `trigger_id_barang` untuk format ID unik

Dalam modul ini, ekspektasi konsep DB trigger adalah membuat `id_barang` otomatis.

Aplikasi saat ini menggunakan generator PHP di `app/Http/Controllers/BarangController.php`:

```php
$id = uniqid();
$barang->id_barang = $id;
```

Jika ingin menerapkan `trigger_id_barang` di database, logikanya adalah:

- Trigger sebelum INSERT memproduksi ID unik.
- Format bisa berupa prefix + timestamp + counter.
- Contoh pseudo:

```sql
SET NEW.id_barang = CONCAT('BRG-', DATE_FORMAT(NOW(), '%Y%m%d'), LPAD((SELECT COALESCE(MAX(id_barang_seq),0)+1 FROM ...), 4, '0'));
```

Namun di repo ini, format unik digenerate melalui `uniqid()` agar setiap item langsung mempunyai ID saat disimpan.

### 2.2 Algoritma penempatan label TnJ 108 berdasarkan koordinat (X, Y)

File yang bertanggung jawab:

- `app/Http/Controllers/BarangController.php`
- `resources/views/barang/pdf.blade.php`

Label ditempatkan di grid:

- 5 kolom per baris
- 8 baris per halaman
- Total 40 label per halaman

Logika posisi awal di controller:

```php
$slotsPerPage = 40; // 5 cols x 8 rows
$offset = ($startY - 1) * 5 + ($startX - 1);
```

Artinya:

- `startX` adalah kolom awal (1..5)
- `startY` adalah baris awal (1..8)
- `slotIndex = (rowIndex * 5) + columnIndex`

Di view PDF, setiap halaman dibuat dengan nested loop 8x5:

```blade
@for ($r = 0; $r < 8; $r++)
  @for ($c = 0; $c < 5; $c++)
    @php $slotIndex = $r * 5 + $c; @endphp
```

Dengan CSS khusus ukuran label:

```css
.label-cell {
  width: 38mm;
  height: 18mm;
}
```

Label ini meniru ukuran fisik stikernya: `38mm x 18mm`.

---

## 3. DEEP DIVE: jQuery & DOM (Modul 4)

### 3.1 Perbandingan: Tabel HTML Biasa vs DataTables

| Aspek | HTML Biasa | DataTables |
|---|---|---|
| Inisialisasi | `<table>` statis + jQuery | `$('#tabel').DataTable()` |
| Pencarian | custom manual | built-in search, paging |
| Sort | manual | otomatis dengan plugin |
| Update row | DOM langsung (`.text()` / `.html()`) | API DataTables (`row().data()`) |
| Event click | `$('tbody tr').on('click',..) ` | `table.on('click', 'tr', ..)` |
| Kinerja | cocok untuk tabel kecil | lebih baik untuk tabel sedang/besar |

### 3.2 Tombol di luar `<form>` / `event.preventDefault()`

Pada modul ini, tombol edit dan update berada di dalam modal, bukan submit form standar. Oleh karena itu:

- `event.preventDefault()` biasanya digunakan untuk menghentikan perilaku submit default.
- Tombol di luar form membuat kita bisa mengeksekusi validasi manual tanpa reload.
- Contoh validasi manual:

```js
var form = document.getElementById('modal-barang-form');
if (!form.reportValidity()) {
    return;
}
```

Ini penting agar spinner tidak muncul jika form tidak valid.

### 3.3 Validation-First: `reportValidity()` sebelum spinner

Alur yang benar:

1. Tekan tombol `Update`
2. Panggil `form.reportValidity()`
3. Jika valid, tampilkan spinner
4. Update baris tabel
5. Tutup modal

Contoh:

```js
$('#btn-ubah-dom').on('click', function() {
    var form = document.getElementById('modal-barang-form');
    if (!form.reportValidity()) {
        return;
    }

    button.prop('disabled', true);
    spinner.removeClass('d-none');
    // ...
});
```

Jika field kosong atau invalid, modal tetap terbuka dan spinner tidak muncul.

### 3.4 jQuery mengubah teks tabel langsung tanpa database

Di modul DOM, hasil update tetap di browser. Contoh:

```js
$(window.rowTerpilih).find('td').eq(2).text(nama);
$(window.rowTerpilih).find('td').eq(3).text(harga);
```

Penjelasannya:

- `.find('td')` memilih sel di baris terpilih.
- `.eq(2)` memilih kolom `Nama`.
- `.text()` mengganti teks di elemen `<td>`.
- Tidak ada panggilan Ajax / refresh halaman.

Teknik memilih baris di view DOM:

```js
$(document).on('click', '#barangs-table tbody tr', function(e) {
    if ($(e.target).closest('button, input').length) return;
    window.rowTerpilih = $(this);
    $('#modal-id').val($(this).find('td').eq(1).text().trim());
    $('#modal-nama').val($(this).find('td').eq(2).text().trim());
    $('#modal-harga').val($(this).find('td').eq(3).text().trim());
    $('#modalBarang').modal('show');
});
```

Pada versi DataTables, baris dipilih melalui API DataTables dan data kolom diambil dari array:

```js
var table = $('#tabel-barang').DataTable();

$('#tabel-barang tbody').on('click', 'tr', function(e) {
    if ($(e.target).closest('button, input').length) return;
    selectedRow = table.row(this);
    let data = selectedRow.data();
    $('#modal-id').val(data[1]);
    $('#modal-nama').val(data[2]);
    $('#modal-harga').val(data[3]);
    $('#modalBarang').modal('show');
});
```

Update data DataTables juga dilakukan dengan `row().data()`:

```js
var rowData = selectedRow.data();
selectedRow.data([rowData[0], rowData[1], nama, harga, rowData[4]]).draw(false);
```

Ini menegaskan bahwa manipulasi DOM langsung dan DataTables API keduanya dapat dipakai untuk mengubah tampilan tanpa reload server.

### 3.5 CSS `cursor: pointer` untuk UX

Di kedua view Modul 4:

- `resources/views/barang/tugas_dom.blade.php`
- `resources/views/barang/tugas_datatables.blade.php`

Ditambahkan style:

```css
#tabel-barang tbody tr:hover {
  cursor: pointer !important;
  background-color: #f5f7fa !important;
}
```

Tujuan:
- Menandakan baris tersebut interaktif.
- Meningkatkan affordance pengguna bahwa baris bisa diklik.

### 3.6 Select Native vs Select2 di Modul 4

File `resources/views/barang/select_kota.blade.php` membandingkan dua cara menampilkan dropdown:

- Select native biasa: `#select-kota-1`
- Select2 yang lebih rapi: `#select-kota-2`

Sintaks utama:

```js
$('.select2').select2({ width: '100%' });
```

Menambahkan opsi secara dinamis dengan JavaScript:

```js
$('#select-kota-1').append(new Option(kota, kota)).val(kota).trigger('change');
$('#select-kota-2').append(new Option(kota, kota)).val(kota).trigger('change');
```

Kedua select menampilkan pilihan kota terpilih menggunakan event `change`:

```js
$('#select-kota-1').change(function() { setSelected(this, '#terpilih-1'); });
$('#select-kota-2').change(function() { setSelected(this, '#terpilih-2'); });
```

Ini membantu memahami kapan memakai select biasa dan kapan memakai plugin Select2 untuk UI yang lebih modern.

---

## 4. DEEP DIVE: AJAX & Axios (Modul 5)

### 4.1 Alur Select Berjenjang (Provinsi > Kota > Kecamatan > Kelurahan)

Pada halaman wilayah, flownya:

- User pilih `Provinsi`
- JavaScript memanggil endpoint `/wilayah/regencies/{province_id}`
- Endpoint mengembalikan kabupaten/kota
- User pilih `Kota/Kabupaten`
- JS memanggil `/wilayah/districts/{regency_id}`
- User pilih `Kecamatan`
- JS memanggil `/wilayah/villages/{district_id}`

Secara umum di Modul 5:

- jQuery digunakan untuk manipulasi DOM, event handler, dan select native/Select2.
- Axios digunakan untuk memanggil API JSON modern dari server.

Contoh di `wilayah/index.blade.php`:

```js
$('#provinsi').on('change', function() {
    let id = $(this).val();
    $('#kabupaten, #kecamatan, #desa').html('<option value="">Pilih...</option>').prop('disabled', true);
    if(id) {
        loading();
        $.ajax({
            url: "{{ url('wilayah/regencies') }}/" + id,
            method: "GET",
            success: function(res) {
                Swal.close();
                $('#kabupaten').prop('disabled', false);
                res.forEach(item => { $('#kabupaten').append(`<option value="${item.id}">${item.name}</option>`); });
            },
            error: function() {
                Swal.close();
                Swal.fire('Gagal', 'Tidak dapat memuat kabupaten. Periksa koneksi atau coba lagi.', 'error');
            }
        });
    }
});

$('#kabupaten').on('change', function() {
    let id = $(this).val();
    $('#kecamatan, #desa').html('<option value="">Pilih...</option>').prop('disabled', true);
    if(id) {
        loading();
        axios.get("{{ url('wilayah/districts') }}/" + id)
            .then(res => {
                Swal.close();
                $('#kecamatan').prop('disabled', false);
                res.data.forEach(item => { $('#kecamatan').append(`<option value="${item.id}">${item.name}</option>`); });
            })
            .catch(() => {
                Swal.close();
                Swal.fire('Gagal', 'Tidak dapat memuat kecamatan. Periksa koneksi atau coba lagi.', 'error');
            });
    }
});

$('#kecamatan').on('change', function() {
    let id = $(this).val();
    $('#desa').html('<option value="">Pilih...</option>').prop('disabled', true);
    if(id) {
        loading();
        axios.get("{{ url('wilayah/villages') }}/" + id)
            .then(res => {
                Swal.close();
                $('#desa').prop('disabled', false);
                res.data.forEach(item => { $('#desa').append(`<option value="${item.id}">${item.name}</option>`); });
            })
            .catch(() => {
                Swal.close();
                Swal.fire('Gagal', 'Tidak dapat memuat desa. Periksa koneksi atau coba lagi.', 'error');
            });
    }
});
```

### 4.2 Perbandingan sintaks: `$.ajax` vs Axios

| Aspek | `$.ajax` (jQuery) | Axios (Promise) |
|---|---|---|
| Pola | Callback | Promise / `.then()` |
| Error handling | `error: function(...)` | `.catch(...)` |
| JSON default | `dataType: 'json'` | otomatis parse JSON |
| Modern | Kurang modern | Lebih modern dan mudah dibaca |

Contoh `$.ajax`:

```js
$.ajax({
  url: '/api',
  type: 'GET',
  dataType: 'json',
  success: function(response) {
    console.log(response);
  },
  error: function(xhr) {
    console.error(xhr);
  }
});
```

Contoh Axios:

```js
axios.get('/api')
  .then(function(response) {
    console.log(response.data);
  })
  .catch(function(error) {
    console.error(error);
  });
```

### 4.3 `response()->json()` di Controller dan akses `response.data`

Di controller Laravel, API JSON dibuat dengan:

```php
return response()->json($data);
```

Endpoint wilayah mengembalikan data JSON dari model Eloquent:

```php
return response()->json(Regency::where('province_id', $province_id)->get());
```

Di JavaScript Axios, data diakses melalui properti `response.data`:

```js
axios.get('/get-menu/' + idvendor)
  .then(function(response) {
    const menus = response.data;
  });
```

Di jQuery, callback success menerima objek data yang sama.

### 4.4 Sintaks Kasir POS: Axios, CSRF, dan parsing mata uang

Pada halaman `resources/views/barang/kasir.blade.php`, alur kasir ditangani dengan array JavaScript `keranjang` dan beberapa langkah penting:

- Setup CSRF untuk Axios:

```js
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}
```

- Cari barang berdasarkan ID ketika pengguna menekan tombol Enter:

```js
$('#id_barang_input').on('keypress', function(e) {
    if (e.which == 13) {
        let id = $(this).val();
        if (!id) return;
        axios.get("{{ url('kasir/cari') }}/" + id)
            .then(res => {
                if (res.data.success) {
                    let b = res.data.data;
                    $('#nama_barang').val(b.nama);
                    $('#harga_barang').val(b.harga);
                } else {
                    Swal.fire('Error', 'Barang tidak ditemukan!', 'error');
                }
            });
    }
});
```

- Render tabel keranjang menggunakan `toLocaleString('id-ID')` untuk format rupiah:

```js
html += `<tr>
    <td>${item.id_barang}</td>
    <td>${item.nama}</td>
    <td>${item.harga.toLocaleString('id-ID')}</td>
    <td><input type="number" class="form-control edit-qty" data-index="${i}" value="${item.qty}" min="1"></td>
    <td>${item.subtotal.toLocaleString('id-ID')}</td>
    <td><button class="btn btn-danger btn-sm btn-hapus" data-index="${i}">Hapus</button></td>
</tr>`;
```

- Sebelum dikirim ke server, hapus titik pemisah ribuan:

```js
let totalAll = parseInt($('#label-total').text().replace(/\./g, '')) || 0;
```

- Kirim data pembayaran ke server melalui Axios POST:

```js
axios.post("{{ route('kasir.simpan') }}", {
    total: totalAll,
    items: keranjang
})
.then(res => {
    if (res.data && res.data.success) {
        keranjang = [];
        renderTabel();
    }
})
.catch(err => {
    Swal.fire('Gagal', err.response?.data?.message || err.message, 'error');
});
```

Subbagian ini membantu menjelaskan sintaks yang sebenarnya dipakai untuk Modul 5: penggunaan Axios untuk API, CSRF token, manipulasi array `keranjang`, dan konversi format angka.

---

## 5. Integrasi Lanjutan (Modul 6 & 7)

### 5.1 Alur Payment Gateway Midtrans

Modul pemesanan kantin menggunakan Midtrans:

1. `KantinController@checkout` membuat `Pesanan` dan detail pesanan.
2. Buat `order_id` unik:

```php
$order_id = 'KANTIN-' . $pesanan->idpesanan . '-' . time();
```

3. Buat parameter transaksi Midtrans:

```php
$params = [
  'transaction_details' => [
    'order_id' => $order_id,
    'gross_amount' => (int)$request->total,
  ],
  'customer_details' => [
    'first_name' => $request->nama,
  ],
];
```

4. Ambil `snap_token` dari `Snap::getSnapToken($params)`.
5. Kirim token ke frontend, lalu panggil Snap JS untuk menampilkan pembayaran.
6. Midtrans memanggil callback ke `/midtrans-callback`.
7. `KantinController@callback` memverifikasi signature dan mengupdate `status_bayar`.

Kode validasi signature:

```php
$hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
```

Setelah validasi, status menjadi:

- `status_bayar = 1` untuk settlement/capture
- `metode_bayar` disimpan dari `payment_type`

### 5.2 Barcode 1D dan QR Code 2D

Implementasi barcode dan QR:

- `app/Http/Controllers/BarangController.php`
  - Menggunakan `Picqerarcodearcodegeneratorpng` untuk membuat barcode `CODE_128`.
  - Barcode dimasukkan ke PDF sebagai base64 image.
- `app/Http/Controllers/KantinController.php`
  - Menggunakan `Endroid\u201cQrCode` builder untuk membuat QR Code dari `idpesanan`.

Contoh pembuatan barcode:

```php
$generator = new BarcodeGeneratorPNG();
$barcode = base64_encode($generator->getBarcode($item->id_barang, $generator::TYPE_CODE_128));
```

Contoh pembuatan QR Code:

```php
$result = (new Builder())->build(null, null, null, (string) $latestPaid->idpesanan, null, null, 180, 10);
$qrCode = $result->getDataUri();
```

### 5.3 Perbedaan teknis penyimpanan foto: BLOB vs File Path

Proyek ini mendukung dua cara menyimpan foto customer:

#### 1. BLOB (Binary Large Object)

- Data foto disimpan langsung di kolom database `foto_blob`.
- Kelebihan:
  - Tidak perlu file system tambahan.
  - Data tetap di dalam database.
- Kekurangan:
  - Ukuran database membesar.
  - Performa query foto besar bisa lambat.

#### 2. File Path (Server Storage)

- Gambar disimpan di disk publik, path disimpan di kolom `foto_path`.
- Kelebihan:
  - Database tetap ringan.
  - File bisa di-cache dan dilayani lebih cepat oleh webserver.
- Kekurangan:
  - Perlu `php artisan storage:link` agar path `storage` bisa diakses.
  - Perlu manajemen file, misalnya cleanup jika record dihapus.

Implementasi simpan Blob di `CustomerController@storeBlob`:

```php
$image = $this->decodeBase64Image($data['foto_blob_data']);
Customer::create([... 'foto_blob' => $image, 'foto_path' => null]);
```

Implementasi simpan Path di `CustomerController@storeFile`:

```php
Storage::disk('public')->put($filename, $image);
Customer::create([... 'foto_blob' => null, 'foto_path' => $filename]);
```

---

## 6. Struktur Folder Penting

- `app/Http/Controllers` : logika bisnis dan API.
- `app/Models` : entitas Eloquent seperti `Customer`, `Pesanan`, `Barang`, `Province`, `Regency`, `District`, `Village`.
- `resources/views` : tampilan Blade.
- `routes/web.php` : rute aplikasi, termasuk group `auth`.
- `database/migrations` : struktur tabel dan kolom tambahan `id_google` / `otp`.

---

## 7. Catatan Presentasi

### Fokus Utama Presentasi
- Modul 4: manipulasi DOM, validasi form, perbedaan HTML Table vs DataTables.
- Modul 5: AJAX/Axios, response JSON, dan chained dropdown wilayah.
- Modul 6/7: Midtrans checkout, callback status update, Barcode/QR, dan storage foto BLOB vs path.

### Kode yang Perlu Ditunjukkan
- `resources/views/layouts/master.blade.php` → arsitektur layout global vs page.
- `app/Http/Controllers/GoogleController.php` → alur Google OAuth + OTP.
- `resources/views/barang/tugas_dom.blade.php` dan `resources/views/barang/tugas_datatables.blade.php` → DOM update & DataTables.
- `resources/views/customer/create.blade.php` → chained select dan webcam capture.
- `app/Http/Controllers/KantinController.php` → Midtrans + QR Code.
- `resources/views/barang/pdf.blade.php` → tata letak label TnJ 108.

---

## 8. Jalankan Proyek

Standar Laravel:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
php artisan serve
```

Jika menggunakan Midtrans di Laragon, pastikan `cacert.pem` tersedia di `D:\laragon\etc\ssl\cacert.pem`.

---

## 9. Kesimpulan

Dokumentasi ini menjelaskan secara teknis setiap modul inti proyek:
- `@section` dan `@stack` untuk arsitektur Blade
- Google OAuth + OTP sebagai keamanan multi-step
- Diff `$.ajax` vs Axios
- Update DOM langsung tanpa refresh
- Midtrans checkout, callback, status transaksi
- Barcode 1D vs QR Code 2D
- Penyimpanan foto BLOB vs path

Gunakan dokumen ini sebagai "kitab belajar" saat presentasi dan sebagai referensi teknis terstruktur.
