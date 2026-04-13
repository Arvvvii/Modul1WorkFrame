@extends('layouts.master')

@push('style-page')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
<style>
    #tabel-barang tbody tr:hover { cursor: pointer !important; background-color: #f5f7fa !important; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h4 class="mb-0">Tugas Modul: Manipulasi Tabel DataTables (U & D)</h4>
        </div>
        <div class="card-body">
            <form id="form-tambah-barang" class="row g-3 mb-4">
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
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="tabel-barang">
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

<div class="modal fade" id="modalBarang" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Edit/Hapus (DataTables)</h5>
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
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    var table = $('#tabel-barang').DataTable({
        columnDefs: [
            { orderable: false, targets: [0, 4] }
        ]
    });
    var selectedRow = null;

    $('#tabel-barang tbody').on('click', 'tr', function(e) {
        if ($(e.target).closest('button, input').length) return;
        selectedRow = table.row(this);
        let data = selectedRow.data();

        // Urutan kolom: [checkbox, id_barang, nama, harga, aksi]
        $('#modal-id').val(data[1]);
        $('#modal-nama').val(data[2]);
        $('#modal-harga').val(data[3]);
        $('#modalBarang').modal('show');
    });

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
            if (selectedRow) {
                var rowData = selectedRow.data();
                selectedRow.data([rowData[0], rowData[1], nama, harga, rowData[4]]).draw(false);
            }
            $('#modalBarang').modal('hide');
            spinner.addClass('d-none');
            button.prop('disabled', false);
            text.text('Update (U)');
        }, 1000);
    });

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
            if (selectedRow) {
                selectedRow.remove().draw(false);
            }
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

        table.row.add([
            '<input type="checkbox">',
            idBarang,
            nama,
            harga,
            '<button type="button" class="btn btn-sm btn-danger btn-delete-row">Hapus</button>'
        ]).draw(false);

        form.reset();
    });
});
</script>
@endpush