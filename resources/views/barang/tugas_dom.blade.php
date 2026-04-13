@extends('layouts.master')

@push('style-page')
<style>
    #barangs-table tbody tr:hover { cursor: pointer !important; background-color: #f5f7fa !important; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h4 class="mb-0">Tugas Modul: Manipulasi Tabel (U & D)</h4>
        </div>
        <div class="card-body">
            <form id="form-tambah-barang" class="row g-3">
                <div class="col-md-6">
                    <label for="input-nama" class="form-label">Nama Barang</label>
                    <input type="text" class="form-control" id="input-nama" placeholder="Masukkan nama barang" required>
                </div>
                <div class="col-md-4">
                    <label for="input-harga" class="form-label">Harga Barang</label>
                    <input type="text" class="form-control" id="input-harga" placeholder="Masukkan harga" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h4 class="mb-0">Tabel Barang</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="barangs-table">
                    <thead class="thead-light">
                        <tr>
                            <th width="40"><input type="checkbox"></th>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Harga</th>
                            <th width="100">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($barangs as $barang)
                        <tr>
                            <td><input type="checkbox"></td>
                            <td>{{ $barang->id_barang }}</td>
                            <td>{{ $barang->nama }}</td>
                            <td>{{ number_format($barang->harga, 0, ',', '.') }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-danger btn-delete-row">Hapus</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal sesuai perintah modul --}}
<div class="modal fade" id="modalBarang" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Edit/Hapus (DOM)</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="modal-barang-form">
                    <div class="form-group">
                        <label>ID Barang (Readonly)</label>
                        <input type="text" class="form-control bg-light" id="modal-id" readonly>
                    </div>
                    <div class="form-group">
                        <label>Nama Barang</label>
                        <input type="text" class="form-control" id="modal-nama" required>
                    </div>
                    <div class="form-group">
                        <label>Harga Barang</label>
                        <input type="text" class="form-control" id="modal-harga" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-danger mr-auto" id="btn-hapus-dom">
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    <span class="btn-text">Hapus (D)</span>
                </button>
                <button type="button" class="btn btn-primary" id="btn-ubah-dom">
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    <span class="btn-text">Update (U)</span>
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js-page')
<script>
$(document).ready(function() {
    // Klik Baris Muncul Modal
    $(document).on('click', '#barangs-table tbody tr', function(e) {
        if ($(e.target).closest('button, input').length) return;
        var row = $(this);
        window.rowTerpilih = row;
        $('#modal-id').val(row.find('td').eq(1).text().trim());
        $('#modal-nama').val(row.find('td').eq(2).text().trim());
        $('#modal-harga').val(row.find('td').eq(3).text().trim());
        $('#modalBarang').modal('show');
    });

    // Update Tampilan (U)
    $('#btn-ubah-dom').on('click', function() {
        var form = document.getElementById('modal-barang-form');
        if (!form.reportValidity()) {
            return;
        }

        var nama = $('#modal-nama').val();
        var harga = $('#modal-harga').val();
        var button = $(this);
        var spinner = button.find('.spinner-border');
        var text = button.find('.btn-text');

        button.prop('disabled', true);
        spinner.removeClass('d-none');
        text.text('Sedang memproses...');

        setTimeout(function() {
            $(window.rowTerpilih).find('td').eq(2).text(nama);
            $(window.rowTerpilih).find('td').eq(3).text(harga);
            $('#modalBarang').modal('hide');

            spinner.addClass('d-none');
            button.prop('disabled', false);
            text.text('Update (U)');
        }, 1000);
    });

    // Hapus Tampilan (D)
    $('#btn-hapus-dom').on('click', function() {
        if (!confirm('Hapus dari baris?')) {
            return;
        }

        var button = $(this);
        var spinner = button.find('.spinner-border');
        var text = button.find('.btn-text');

        button.prop('disabled', true);
        spinner.removeClass('d-none');
        text.text('Sedang menghapus...');

        setTimeout(function() {
            $(window.rowTerpilih).remove();
            $('#modalBarang').modal('hide');

            spinner.addClass('d-none');
            button.prop('disabled', false);
            text.text('Hapus (D)');
        }, 1000);
    });

    $('#form-tambah-barang').on('submit', function(e) {
        e.preventDefault();
        var form = this;
        if (!form.reportValidity()) {
            return;
        }

        var nama = $('#input-nama').val().trim();
        var harga = $('#input-harga').val().trim();
        var idBarang = Math.floor(10000000 + Math.random() * 90000000);

        $('#barangs-table tbody').append(`
            <tr>
                <td><input type="checkbox"></td>
                <td>${idBarang}</td>
                <td>${nama}</td>
                <td>${harga}</td>
                <td><button type="button" class="btn btn-sm btn-danger btn-delete-row">Hapus</button></td>
            </tr>
        `);

        form.reset();
    });
});
</script>
@endpush