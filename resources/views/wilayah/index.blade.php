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

    <div class="row justify-content-center"> {{-- Tambahkan justify-content-center --}}
        <div class="col-md-8 grid-margin stretch-card"> {{-- Aku besarin dikit ke 8 biar lebih lega --}}
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Chained Dropdown Wilayah</h4>
                    <p class="card-description"> Tugas Modul 5: Implementasi AJAX & Axios </p>
                    
                    <form class="forms-sample">
                        {{-- Dropdown Provinsi --}}
                        <div class="form-group mb-3">
                            <label for="provinsi">1. Provinsi</label>
                            <select id="provinsi" class="form-control form-control-lg" style="color: #000;">
                                <option value="">Pilih Provinsi</option>
                                @foreach($provinces as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Dropdown Kabupaten --}}
                        <div class="form-group mb-3">
                            <label for="kabupaten">2. Kabupaten/Kota (AJAX JQuery)</label>
                            <select id="kabupaten" class="form-control form-control-lg" style="color: #000;" disabled>
                                <option value="">Pilih Kabupaten</option>
                            </select>
                        </div>

                        {{-- Dropdown Kecamatan --}}
                        <div class="form-group mb-3">
                            <label for="kecamatan">3. Kecamatan (Axios)</label>
                            <select id="kecamatan" class="form-control form-control-lg" style="color: #000;" disabled>
                                <option value="">Pilih Kecamatan</option>
                            </select>
                        </div>

                        {{-- Dropdown Desa --}}
                        <div class="form-group mb-3">
                            <label for="desa">4. Desa/Kelurahan (Axios)</label>
                            <select id="desa" class="form-control form-control-lg" style="color: #000;" disabled>
                                <option value="">Pilih Desa</option>
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

    // 1. PROVINSI -> KABUPATEN (JQuery AJAX)
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
                    res.forEach(item => { $('#kabupaten').append(`<option value="${item.id}">${item.name}</option>`) });
                }
            });
        }
    });

    // 2. KABUPATEN -> KECAMATAN (Axios)
    $('#kabupaten').on('change', function() {
        let id = $(this).val();
        $('#kecamatan, #desa').html('<option value="">Pilih...</option>').prop('disabled', true);
        if(id) {
            loading();
            axios.get("{{ url('wilayah/districts') }}/" + id).then(res => {
                Swal.close();
                $('#kecamatan').prop('disabled', false);
                res.data.forEach(item => { $('#kecamatan').append(`<option value="${item.id}">${item.name}</option>`) });
            });
        }
    });

    // 3. KECAMATAN -> DESA (Axios)
    $('#kecamatan').on('change', function() {
        let id = $(this).val();
        $('#desa').html('<option value="">Pilih...</option>').prop('disabled', true);
        if(id) {
            loading();
            axios.get("{{ url('wilayah/villages') }}/" + id).then(res => {
                Swal.close();
                $('#desa').prop('disabled', false);
                res.data.forEach(item => { $('#desa').append(`<option value="${item.id}">${item.name}</option>`) });
            });
        }
    });
});
</script>
@endpush