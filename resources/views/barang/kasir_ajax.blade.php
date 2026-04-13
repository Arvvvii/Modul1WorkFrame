@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-cash-register"></i>
            </span> Kasir (POS) - Versi Ajax</h3>
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
                            <tbody></tbody>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let keranjang = [];

$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function validasiTombol() {
        let nama = $('#nama_barang').val();
        let qty = parseInt($('#jumlah_input').val());
        $('#btn-tambahkan').prop('disabled', !(nama !== '' && qty > 0));
    }

    $('#jumlah_input').on('input change', validasiTombol);

    $('#id_barang_input').on('keypress', function(e) {
        if (e.which !== 13) return;
        e.preventDefault();

        let id = $(this).val().trim();
        if (!id) return;

        $.ajax({
            url: "{{ url('kasir/cari') }}/" + id,
            method: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    $('#nama_barang').val(res.data.nama);
                    $('#harga_barang').val(res.data.harga);
                    validasiTombol();
                } else {
                    Swal.fire('Error', 'Barang tidak ditemukan!', 'error');
                    resetFormInput();
                }
            },
            error: function() {
                Swal.fire('Error', 'Gagal terhubung ke server.', 'error');
                resetFormInput();
            }
        });
    });

    $('#btn-tambahkan').on('click', function() {
        let id_barang = $('#id_barang_input').val().trim();
        let nama = $('#nama_barang').val().trim();
        let harga = parseInt($('#harga_barang').val()) || 0;
        let qty = parseInt($('#jumlah_input').val()) || 1;

        let index = keranjang.findIndex(x => x.id_barang === id_barang);
        if (index !== -1) {
            keranjang[index].qty += qty;
            keranjang[index].subtotal = keranjang[index].qty * keranjang[index].harga;
        } else {
            keranjang.push({ id_barang, nama, harga, qty, subtotal: harga * qty });
        }

        renderTabel();
        resetFormInput();
    });

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

    $(document).on('change', '.edit-qty', function() {
        let i = $(this).data('index');
        let newQty = parseInt($(this).val());
        if (newQty > 0) {
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

    $('#btn-bayar').on('click', function() {
        let btn = $(this);
        btn.prop('disabled', true).text('Proses...');

        let totalAll = parseInt($('#label-total').text().replace(/\./g, '')) || 0;

        $.ajax({
            url: "{{ route('kasir.simpan') }}",
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ total: totalAll, items: keranjang }),
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    Swal.fire('Berhasil!', 'Pembayaran disimpan.', 'success').then(() => {
                        keranjang = [];
                        renderTabel();
                        resetFormInput();
                    });
                } else {
                    Swal.fire('Gagal', res.message || 'Gagal menyimpan pembayaran.', 'error');
                }
            },
            error: function(xhr) {
                let message = 'Terjadi kesalahan saat menyimpan pembayaran.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire('Gagal', message, 'error');
            },
            complete: function() {
                btn.prop('disabled', keranjang.length === 0).text('Bayar (Simpan)');
            }
        });
    });
});
</script>
@endpush
