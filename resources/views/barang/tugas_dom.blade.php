@extends('layouts.master')

@push('style-page')
<style>
    #barangs-table tbody tr:hover { cursor: pointer !important; background-color: #f5f7fa !important; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h4 class="mb-0">Tugas Modul: Manipulasi Tabel (U & D)</h4>
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
                <button type="button" class="btn btn-danger mr-auto" id="btn-hapus-dom">Hapus (D)</button>
                <button type="button" class="btn btn-primary" id="btn-ubah-dom">Update (U)</button>
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
        var nama = $('#modal-nama').val();
        var harga = $('#modal-harga').val();
        if(nama && harga) {
            $(window.rowTerpilih).find('td').eq(2).text(nama);
            $(window.rowTerpilih).find('td').eq(3).text(harga);
            $('#modalBarang').modal('hide');
        } else { alert('Required!'); }
    });

    // Hapus Tampilan (D)
    $('#btn-hapus-dom').on('click', function() {
        if(confirm('Hapus dari baris?')) {
            $(window.rowTerpilih).remove();
            $('#modalBarang').modal('hide');
        }
    });
});
</script>
@endpush