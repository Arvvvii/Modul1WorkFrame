@extends('layouts.master')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title"> Tambah Toko </h3>
    </div>

    <div class="row">
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border-radius: 15px;">
                <div class="card-body">
                    <h4 class="card-title">Form Tambah Toko</h4>
                    <p class="card-description"> Masukkan data toko dan lokasi akurat </p>

                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form class="forms-sample" action="{{ route('toko.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="barcode">Barcode</label>
                            <input type="text" class="form-control" id="barcode" name="barcode" placeholder="Barcode Toko" value="{{ old('barcode') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="nama_toko">Nama Toko</label>
                            <input type="text" class="form-control" id="nama_toko" name="nama_toko" placeholder="Nama Toko" value="{{ old('nama_toko') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required>{{ old('alamat') }}</textarea>
                        </div>
                        
                        <h4 class="mt-4 mb-3">Lokasi Geolocation</h4>
                        <div class="d-flex mb-1 align-items-center">
                            <button type="button" class="btn btn-gradient-warning btn-sm" id="btnAmbilLokasi">
                                <i class="mdi mdi-map-marker"></i> Ambil Lokasi Toko Saat Ini
                            </button>
                            <span id="lokasiStatus" class="ml-3 mt-1 text-muted"></span>
                        </div>
                        <small class="text-danger font-weight-bold d-block mb-3">Peringatan: Pastikan Anda berada tepat di depan toko saat mengambil lokasi agar koordinat akurat!</small>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Latitude</label>
                                    <input type="text" class="form-control" id="latitude" name="latitude" value="{{ old('latitude') }}" readonly required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Longitude</label>
                                    <input type="text" class="form-control" id="longitude" name="longitude" value="{{ old('longitude') }}" readonly required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Akurasi (meter)</label>
                                    <input type="text" class="form-control" id="accuracy" name="accuracy" value="{{ old('accuracy') }}" readonly>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-gradient-primary me-2">Simpan</button>
                        <a href="{{ route('toko.index') }}" class="btn btn-light">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnAmbil = document.getElementById('btnAmbilLokasi');
    const inputLat = document.getElementById('latitude');
    const inputLng = document.getElementById('longitude');
    const inputAcc = document.getElementById('accuracy');
    const statusText = document.getElementById('lokasiStatus');

    btnAmbil.addEventListener('click', function() {
        if (!navigator.geolocation) {
            statusText.innerHTML = "<span class='text-danger'>Geolocation tidak didukung browser ini.</span>";
            return;
        }

        statusText.innerHTML = "<span class='text-info'>Mencari lokasi...</span>";
        btnAmbil.disabled = true;

        navigator.geolocation.getCurrentPosition(
            function(position) {
                inputLat.value = position.coords.latitude;
                inputLng.value = position.coords.longitude;
                inputAcc.value = position.coords.accuracy;
                statusText.innerHTML = "<span class='text-success'>Lokasi berhasil didapatkan!</span>";
                btnAmbil.disabled = false;
            },
            function(error) {
                statusText.innerHTML = "<span class='text-danger'>Gagal mendapatkan lokasi: " + error.message + "</span>";
                btnAmbil.disabled = false;
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    });
});
</script>
@endsection
