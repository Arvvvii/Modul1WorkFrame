@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-map-marker"></i>
            </span> Wilayah Administrasi
        </h3>
    </div>

    <div class="row">
        <div class="col-lg-6 col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Wilayah Administrasi - AJAX JQuery</h4>
                    <p class="card-description">Level 1: Provinsi • Level 2: Kota • Level 3: Kecamatan • Level 4: Kelurahan</p>
                    <form class="forms-sample">
                        <div class="form-group mb-3">
                            <label for="provinsi_ajax">1. Provinsi</label>
                            <select id="provinsi_ajax" class="form-control form-control-lg" style="color: #000;">
                                <option value="0">Pilih Provinsi</option>
                                @foreach($provinces as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="kabupaten_ajax">2. Kota/Kabupaten</label>
                            <select id="kabupaten_ajax" class="form-control form-control-lg" style="color: #000;" disabled>
                                <option value="0">Pilih Kota</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="kecamatan_ajax">3. Kecamatan</label>
                            <select id="kecamatan_ajax" class="form-control form-control-lg" style="color: #000;" disabled>
                                <option value="0">Pilih Kecamatan</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="desa_ajax">4. Kelurahan</label>
                            <select id="desa_ajax" class="form-control form-control-lg" style="color: #000;" disabled>
                                <option value="0">Pilih Kelurahan</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Wilayah Administrasi - Axios</h4>
                    <p class="card-description">Level 1: Provinsi • Level 2: Kota • Level 3: Kecamatan • Level 4: Kelurahan</p>
                    <form class="forms-sample">
                        <div class="form-group mb-3">
                            <label for="provinsi_axios">1. Provinsi</label>
                            <select id="provinsi_axios" class="form-control form-control-lg" style="color: #000;">
                                <option value="0">Pilih Provinsi</option>
                                @foreach($provinces as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="kabupaten_axios">2. Kota/Kabupaten</label>
                            <select id="kabupaten_axios" class="form-control form-control-lg" style="color: #000;" disabled>
                                <option value="0">Pilih Kota</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="kecamatan_axios">3. Kecamatan</label>
                            <select id="kecamatan_axios" class="form-control form-control-lg" style="color: #000;" disabled>
                                <option value="0">Pilih Kecamatan</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="desa_axios">4. Kelurahan</label>
                            <select id="desa_axios" class="form-control form-control-lg" style="color: #000;" disabled>
                                <option value="0">Pilih Kelurahan</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js-page')
{{-- Gunakan push js-page karena master.blade.php kamu pakai @stack('js-page') --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    function loading() {
        Swal.fire({title: 'Memuat data...', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });
    }

    function resetAjaxSelects(level) {
        if (level <= 2) {
            $('#kabupaten_ajax').html('<option value="0">Pilih Kota</option>').prop('disabled', true);
        }
        if (level <= 3) {
            $('#kecamatan_ajax').html('<option value="0">Pilih Kecamatan</option>').prop('disabled', true);
        }
        if (level <= 4) {
            $('#desa_ajax').html('<option value="0">Pilih Kelurahan</option>').prop('disabled', true);
        }
    }

    function resetAxiosSelects(level) {
        if (level <= 2) {
            $('#kabupaten_axios').html('<option value="0">Pilih Kota</option>').prop('disabled', true);
        }
        if (level <= 3) {
            $('#kecamatan_axios').html('<option value="0">Pilih Kecamatan</option>').prop('disabled', true);
        }
        if (level <= 4) {
            $('#desa_axios').html('<option value="0">Pilih Kelurahan</option>').prop('disabled', true);
        }
    }

    $('#provinsi_ajax').on('change', function() {
        const provinceId = $(this).val();
        resetAjaxSelects(2);

        if (provinceId && provinceId !== '0') {
            loading();
            $.ajax({
                url: "{{ url('wilayah/regencies') }}/" + provinceId,
                method: 'GET',
                success: function(res) {
                    Swal.close();
                    $('#kabupaten_ajax').prop('disabled', false);
                    res.forEach(item => {
                        $('#kabupaten_ajax').append(`<option value="${item.id}">${item.name}</option>`);
                    });
                },
                error: function() {
                    Swal.close();
                    Swal.fire('Gagal', 'Tidak dapat memuat kota/kabupaten. Periksa koneksi atau coba lagi.', 'error');
                }
            });
        }
    });

    $('#kabupaten_ajax').on('change', function() {
        const regencyId = $(this).val();
        resetAjaxSelects(3);

        if (regencyId && regencyId !== '0') {
            loading();
            $.ajax({
                url: "{{ url('wilayah/districts') }}/" + regencyId,
                method: 'GET',
                success: function(res) {
                    Swal.close();
                    $('#kecamatan_ajax').prop('disabled', false);
                    res.forEach(item => {
                        $('#kecamatan_ajax').append(`<option value="${item.id}">${item.name}</option>`);
                    });
                },
                error: function() {
                    Swal.close();
                    Swal.fire('Gagal', 'Tidak dapat memuat kecamatan. Periksa koneksi atau coba lagi.', 'error');
                }
            });
        }
    });

    $('#kecamatan_ajax').on('change', function() {
        const districtId = $(this).val();
        resetAjaxSelects(4);

        if (districtId && districtId !== '0') {
            loading();
            $.ajax({
                url: "{{ url('wilayah/villages') }}/" + districtId,
                method: 'GET',
                success: function(res) {
                    Swal.close();
                    $('#desa_ajax').prop('disabled', false);
                    res.forEach(item => {
                        $('#desa_ajax').append(`<option value="${item.id}">${item.name}</option>`);
                    });
                },
                error: function() {
                    Swal.close();
                    Swal.fire('Gagal', 'Tidak dapat memuat kelurahan. Periksa koneksi atau coba lagi.', 'error');
                }
            });
        }
    });

    $('#provinsi_axios').on('change', function() {
        const provinceId = $(this).val();
        resetAxiosSelects(2);

        if (provinceId && provinceId !== '0') {
            loading();
            axios.get(`{{ url('wilayah/regencies') }}/${provinceId}`)
                .then(response => {
                    Swal.close();
                    $('#kabupaten_axios').prop('disabled', false);
                    response.data.forEach(item => {
                        $('#kabupaten_axios').append(`<option value="${item.id}">${item.name}</option>`);
                    });
                })
                .catch(() => {
                    Swal.close();
                    Swal.fire('Gagal', 'Tidak dapat memuat kota/kabupaten. Periksa koneksi atau coba lagi.', 'error');
                });
        }
    });

    $('#kabupaten_axios').on('change', function() {
        const regencyId = $(this).val();
        resetAxiosSelects(3);

        if (regencyId && regencyId !== '0') {
            loading();
            axios.get(`{{ url('wilayah/districts') }}/${regencyId}`)
                .then(response => {
                    Swal.close();
                    $('#kecamatan_axios').prop('disabled', false);
                    response.data.forEach(item => {
                        $('#kecamatan_axios').append(`<option value="${item.id}">${item.name}</option>`);
                    });
                })
                .catch(() => {
                    Swal.close();
                    Swal.fire('Gagal', 'Tidak dapat memuat kecamatan. Periksa koneksi atau coba lagi.', 'error');
                });
        }
    });

    $('#kecamatan_axios').on('change', function() {
        const districtId = $(this).val();
        resetAxiosSelects(4);

        if (districtId && districtId !== '0') {
            loading();
            axios.get(`{{ url('wilayah/villages') }}/${districtId}`)
                .then(response => {
                    Swal.close();
                    $('#desa_axios').prop('disabled', false);
                    response.data.forEach(item => {
                        $('#desa_axios').append(`<option value="${item.id}">${item.name}</option>`);
                    });
                })
                .catch(() => {
                    Swal.close();
                    Swal.fire('Gagal', 'Tidak dapat memuat kelurahan. Periksa koneksi atau coba lagi.', 'error');
                });
        }
    });
});
</script>
@endpush