@extends('layouts.master')

@push('style-page')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single { height: 38px !important; border: 1px solid #ced4da !important; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        {{-- Card 1: Select Native --}}
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header"><h4>Select (Native)</h4></div>
                <div class="card-body">
                    <label>Kota:</label>
                    <input type="text" id="input-kota-1" class="form-control mb-2" placeholder="Ketik nama kota...">
                    <button id="btn-tambah-1" class="btn btn-info btn-block mb-3">Tambahkan</button>
                    <label>Select Kota:</label>
                    <select id="select-kota-1" class="form-control"><option value="">-- Pilih --</option></select>
                    <p class="mt-2">Kota Terpilih: <strong id="terpilih-1">-</strong></p>
                </div>
            </div>
        </div>

        {{-- Card 2: Select2 --}}
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header"><h4>Select 2</h4></div>
                <div class="card-body">
                    <label>Kota:</label>
                    <input type="text" id="input-kota-2" class="form-control mb-2" placeholder="Ketik nama kota...">
                    <button id="btn-tambah-2" class="btn btn-info btn-block mb-3">Tambahkan</button>
                    <label>Select Kota:</label>
                    <select id="select-kota-2" class="form-control select2"><option value="">-- Pilih --</option></select>
                    <p class="mt-2">Kota Terpilih: <strong id="terpilih-2">-</strong></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js-page')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2').select2({ width: '100%' });

    // Logic Card 1
    $('#btn-tambah-1').click(function() {
        var k = $('#input-kota-1').val();
        if(k) $('#select-kota-1').append(new Option(k, k)).val(k).trigger('change');
    });
    $('#select-kota-1').change(function() { $('#terpilih-1').text($(this).val() || '-'); });

    // Logic Card 2
    $('#btn-tambah-2').click(function() {
        var k = $('#input-kota-2').val();
        if(k) $('#select-kota-2').append(new Option(k, k)).val(k).trigger('change');
    });
    $('#select-kota-2').change(function() { $('#terpilih-2').text($(this).val() || '-'); });
});
</script>
@endpush