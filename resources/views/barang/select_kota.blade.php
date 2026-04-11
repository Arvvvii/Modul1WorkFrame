@extends('layouts.master')

@push('style-page')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        height: 42px !important;
        border: 1px solid #ced4da !important;
        border-radius: .35rem !important;
        padding: 0 0.75rem !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 38px;
        padding-left: 0;
        color: #495057;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
        top: 1px !important;
        right: 10px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #6c757d;
        line-height: 38px;
    }
    .select2-container {
        width: 100% !important;
    }
    .card {
        border: 1px solid #e9ecef;
    }
    .card-header {
        padding: 1rem 1.25rem;
    }
    .card-header h4 {
        margin: 0;
        font-size: 1.05rem;
    }
    .form-group label {
        font-weight: 600;
    }
    .form-control::placeholder {
        color: #6c757d;
        opacity: 1;
    }
    .kota-summary {
        background: #f8f9fa;
        border: 1px solid #e4e7ea;
        border-radius: .35rem;
        padding: 1rem;
    }
    .kota-summary strong {
        display: block;
        margin-top: .5rem;
        font-size: 1.1rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="page-header mb-4">
        <h3 class="page-title">Tugas Modul 4 - Select Kota</h3>
        <p class="text-muted">Contoh input kota dengan select native dan Select2 agar tampilan lebih rapi.</p>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Select Native</h4>
                </div>
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="input-kota-1">Kota</label>
                        <input type="text" id="input-kota-1" class="form-control" placeholder="Ketik nama kota...">
                    </div>
                    <div class="d-grid mb-3">
                        <button id="btn-tambah-1" class="btn btn-info">Tambahkan</button>
                    </div>
                    <div class="form-group mb-3">
                        <label for="select-kota-1">Select Kota</label>
                        <select id="select-kota-1" class="form-control">
                            <option value="">-- Pilih Kota --</option>
                        </select>
                    </div>
                    <div class="kota-summary">
                        <div class="text-muted">Kota Terpilih</div>
                        <strong id="terpilih-1">-</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Select2</h4>
                </div>
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="input-kota-2">Kota</label>
                        <input type="text" id="input-kota-2" class="form-control" placeholder="Ketik nama kota...">
                    </div>
                    <div class="d-grid mb-3">
                        <button id="btn-tambah-2" class="btn btn-success">Tambahkan</button>
                    </div>
                    <div class="form-group mb-3">
                        <label for="select-kota-2">Select Kota</label>
                        <select id="select-kota-2" class="form-control select2">
                            <option value="">-- Pilih Kota --</option>
                        </select>
                    </div>
                    <div class="kota-summary">
                        <div class="text-muted">Kota Terpilih</div>
                        <strong id="terpilih-2">-</strong>
                    </div>
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

    function setSelected(selector, labelSelector) {
        var city = $(selector).val();
        $(labelSelector).text(city || '-');
    }

    $('#btn-tambah-1').click(function() {
        var kota = $('#input-kota-1').val().trim();
        if(kota) {
            $('#select-kota-1').append(new Option(kota, kota)).val(kota).trigger('change');
            setSelected('#select-kota-1', '#terpilih-1');
            $('#input-kota-1').val('');
        }
    });
    $('#select-kota-1').change(function() { setSelected(this, '#terpilih-1'); });

    $('#btn-tambah-2').click(function() {
        var kota = $('#input-kota-2').val().trim();
        if(kota) {
            $('#select-kota-2').append(new Option(kota, kota)).val(kota).trigger('change');
            setSelected('#select-kota-2', '#terpilih-2');
            $('#input-kota-2').val('');
        }
    });
    $('#select-kota-2').change(function() { setSelected(this, '#terpilih-2'); });
});
</script>
@endpush