@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-cart"></i>
            </span> Pemesanan Kantin (Guest Mode)
        </h3>
    </div>

    <div class="row">
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Order Info: <span class="text-primary">{{ $guestName }}</span></h4>
                    <input type="hidden" id="nama_customer" value="{{ $guestName }}">

                    <div class="form-group">
                        <label>Pilih Vendor</label>
                        <select id="select-vendor" class="form-control">
                            <option value="">-- Pilih Vendor --</option>
                            @foreach($vendors as $v)
                                <option value="{{ $v->idvendor }}">{{ $v->nama_vendor }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Pilih Menu</label>
                        <select id="select-menu" class="form-control" disabled>
                            <option value="">-- Pilih Menu --</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Harga</label>
                        <input type="text" id="harga_menu" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label>Jumlah</label>
                        <input type="number" id="jumlah_input" class="form-control" value="1" min="1">
                    </div>

                    <button type="button" id="btn-tambahkan" class="btn btn-gradient-primary w-100" disabled>Tambahkan</button>
                </div>
            </div>
        </div>

        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Daftar Pesanan</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tabel-pesanan">
                            <thead>
                                <tr>
                                    <th>Menu</th>
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
                        <button type="button" id="btn-checkout" class="btn btn-gradient-success btn-lg" disabled>Bayar & Checkout</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js-page')
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let keranjang = [];
let totalHargaGlobal = 0;

$(document).ready(function() {
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

    // --- 1. AMBIL MENU (CHAINED DROPDOWN) ---
    $('#select-vendor').on('change', function() {
        let idvendor = $(this).val();
        let $menuSelect = $('#select-menu');
        
        $menuSelect.html('<option value="">Loading...</option>').prop('disabled', true);
        $('#harga_menu').val('');
        $('#btn-tambahkan').prop('disabled', true);
        
        if(idvendor) {
            // Perbaikan URL: Pakai backtick dan template literal
            axios.get(`{{ url('get-menu') }}/${idvendor}`)
                .then(res => {
                    let html = '<option value="">-- Pilih Menu --</option>';
                    if(res.data.length > 0) {
                        res.data.forEach(m => {
                            html += `<option value="${m.idmenu}" data-harga="${m.harga}">${m.nama_menu}</option>`;
                        });
                        $menuSelect.html(html).prop('disabled', false);
                    } else {
                        $menuSelect.html('<option value="">Menu Kosong</option>');
                    }
                })
                .catch(err => {
                    console.error("Error Detail:", err);
                    $menuSelect.html('<option value="">Gagal Memuat Menu</option>');
                });
        }
    });

    $('#select-menu').on('change', function() {
        let harga = $(this).find(':selected').data('harga');
        $('#harga_menu').val(harga || '');
        $('#btn-tambahkan').prop('disabled', !harga);
    });

    // --- 2. LOGIKA KERANJANG ---
    $('#btn-tambahkan').on('click', function() {
        let idmenu = $('#select-menu').val();
        let nama = $('#select-menu option:selected').text();
        let harga = parseInt($('#harga_menu').val());
        let qty = parseInt($('#jumlah_input').val());

        let index = keranjang.findIndex(x => x.idmenu === idmenu);
        if(index !== -1) {
            keranjang[index].qty += qty;
            keranjang[index].subtotal = keranjang[index].qty * keranjang[index].harga;
        } else {
            keranjang.push({ idmenu, nama, harga, qty, subtotal: harga * qty });
        }
        renderTabel();
    });

    function renderTabel() {
        let html = '';
        totalHargaGlobal = 0; 
        keranjang.forEach((item, i) => {
            totalHargaGlobal += item.subtotal;
            html += `<tr>
                <td>${item.nama}</td>
                <td>${item.harga.toLocaleString('id-ID')}</td>
                <td>${item.qty}</td>
                <td>${item.subtotal.toLocaleString('id-ID')}</td>
                <td><button class="btn btn-danger btn-sm btn-hapus" data-index="${i}">X</button></td>
            </tr>`;
        });
        $('#tabel-pesanan tbody').html(html);
        $('#label-total').text(totalHargaGlobal.toLocaleString('id-ID'));
        $('#btn-checkout').prop('disabled', keranjang.length === 0);
    }

    $(document).on('click', '.btn-hapus', function() {
        keranjang.splice($(this).data('index'), 1);
        renderTabel();
    });

    // --- 3. PROSES CHECKOUT ---
    $('#btn-checkout').on('click', function() {
        const btn = $(this);
        btn.prop('disabled', true).text('Processing...');

        let data = {
            nama: $('#nama_customer').val(),
            total: totalHargaGlobal,
            items: keranjang,
            _token: "{{ csrf_token() }}"
        };

        axios.post("{{ route('kantin.checkout') }}", data)
        .then(res => {
            window.snap.pay(res.data.snap_token, {
                onSuccess: function(result) {
                    Swal.fire('Lunas!', 'Pembayaran Berhasil!', 'success').then(() => {
                        window.location.href = "{{ route('kantin.pembayaran') }}";
                    });
                },
                onPending: function(result) {
                    Swal.fire('Pending', 'Selesaikan pembayaran Anda', 'info').then(() => {
                        window.location.href = "{{ route('kantin.pembayaran') }}";
                    });
                },
                onError: function(result) {
                    Swal.fire('Gagal', 'Pembayaran gagal!', 'error');
                    btn.prop('disabled', false).text('Bayar & Checkout');
                },
                onClose: function() {
                    btn.prop('disabled', false).text('Bayar & Checkout');
                }
            });
        })
        .catch(err => {
            console.error(err);
            Swal.fire('Error', 'Terjadi kesalahan server', 'error');
            btn.prop('disabled', false).text('Bayar & Checkout');
        });
    });
});
</script>
@endpush