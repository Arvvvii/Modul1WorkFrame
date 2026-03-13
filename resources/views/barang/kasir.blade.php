@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-cash-register"></i>
            </span> Kasir (POS)
        </h3>
    </div>

    <div class="row">
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Input Transaksi</h4>
                    <div class="form-group">
                        <label>ID Barang (Tekan Enter)</label>
                        <input type="text" id="id_barang_input" class="form-control" placeholder="Ketik ID lalu Enter">
                    </div>
                    <div class="form-group">
                        <label>Nama Barang</label>
                        <input type="text" id="nama_barang" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Harga</label>
                        <input type="text" id="harga_barang" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Jumlah (Ketentuan C)</label>
                        <input type="number" id="jumlah_input" class="form-control" value="1" min="1">
                    </div>
                    <button type="button" id="btn-tambahkan" class="btn btn-gradient-primary w-100" disabled>Tambahkan</button>
                </div>
            </div>
        </div>

        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Daftar Belanja</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tabel-kasir">
                            <thead>
                                <tr class="text-center">
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Harga</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                </tbody>
                        </table>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="text-danger">Total: Rp <span id="label-total">0</span></h3>
                        <button type="button" id="btn-bayar" class="btn btn-gradient-success btn-lg" disabled>Bayar (Simpan)</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js-page')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let keranjang = [];

$(document).ready(function() {
    // 0. SETUP CSRF TOKEN
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    let token = document.head.querySelector('meta[name="csrf-token"]');
    if (token) {
        axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    }

    function validasiTombol() {
        let nama = $('#nama_barang').val();
        let qty = parseInt($('#jumlah_input').val());
        if (nama !== "" && qty > 0) {
            $('#btn-tambahkan').prop('disabled', false);
        } else {
            $('#btn-tambahkan').prop('disabled', true);
        }
    }

    $('#jumlah_input').on('input change', function() {
        validasiTombol();
    });

    // 1. CARI BARANG
    $('#id_barang_input').on('keypress', function(e) {
        if(e.which == 13) { 
            let id = $(this).val();
            if(!id) return;
            axios.get("{{ url('kasir/cari') }}/" + id)
                .then(res => {
                    if(res.data.success) {
                        let b = res.data.data;
                        $('#nama_barang').val(b.nama);
                        $('#harga_barang').val(b.harga);
                        validasiTombol();
                    } else {
                        Swal.fire('Error', 'Barang tidak ditemukan!', 'error');
                        resetFormInput();
                    }
                });
        }
    });

    // 2. TAMBAHKAN KE TABEL
    $('#btn-tambahkan').on('click', function() {
        let id_barang = $('#id_barang_input').val();
        let nama = $('#nama_barang').val();
        let harga = parseInt($('#harga_barang').val());
        let qty = parseInt($('#jumlah_input').val());

        let index = keranjang.findIndex(x => x.id_barang === id_barang);
        if(index !== -1) {
            keranjang[index].qty += qty;
            keranjang[index].subtotal = keranjang[index].qty * keranjang[index].harga;
        } else {
            keranjang.push({ id_barang, nama, harga, qty, subtotal: harga * qty });
        }
        renderTabel();
        resetFormInput();
    });

    // 3. RENDER TABEL
    function renderTabel() {
        let html = '';
        let total = 0;
        keranjang.forEach((item, i) => {
            total += item.subtotal;
            html += `<tr>
                <td>${item.id_barang}</td>
                <td>${item.nama}</td>
                <td>${item.harga.toLocaleString('id-ID')}</td>
                <td><input type="number" class="form-control edit-qty" data-index="${i}" value="${item.qty}" min="1"></td>
                <td>${item.subtotal.toLocaleString('id-ID')}</td>
                <td><button class="btn btn-danger btn-sm btn-hapus" data-index="${i}">Hapus</button></td>
            </tr>`;
        });
        $('#tabel-kasir tbody').html(html);
        $('#label-total').text(total.toLocaleString('id-ID'));
        $('#btn-bayar').prop('disabled', keranjang.length === 0);
    }

    // 4. EDIT & HAPUS
    $(document).on('change', '.edit-qty', function() {
        let i = $(this).data('index');
        let newQty = parseInt($(this).val());
        if(newQty > 0) {
            keranjang[i].qty = newQty;
            keranjang[i].subtotal = newQty * keranjang[i].harga;
            renderTabel();
        }
    });

    $(document).on('click', '.btn-hapus', function() {
        let i = $(this).data('index');
        keranjang.splice(i, 1);
        renderTabel();
    });

    function resetFormInput() {
        $('#id_barang_input').val('').focus();
        $('#nama_barang').val('');
        $('#harga_barang').val('');
        $('#jumlah_input').val(1);
        $('#btn-tambahkan').prop('disabled', true);
    }

    // 5. SIMPAN (DIPERBAIKI)
    $('#btn-bayar').on('click', function() {
        let btn = $(this);
        btn.prop('disabled', true).text('Proses...');

        // Hapus titik ribuan agar jadi angka murni sebelum dikirim ke DB
        let totalAll = parseInt($('#label-total').text().replace(/\./g, ''));
        
        axios.post("{{ route('kasir.simpan') }}", {
            total: totalAll,
            items: keranjang
        })
        .then(res => {
            if(res.data.success) {
                Swal.fire('Berhasil!', 'Pembayaran disimpan.', 'success');
                keranjang = []; 
                renderTabel();
                resetFormInput();
            }
        })
        .catch(err => {
            Swal.fire('Error', 'Gagal menyimpan transaksi', 'error');
        })
        .finally(() => {
            btn.prop('disabled', false).text('Bayar (Simpan)');
        });
    });
});
</script>
@endpush