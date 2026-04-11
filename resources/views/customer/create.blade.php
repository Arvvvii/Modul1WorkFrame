@extends('layouts.master')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-account-plus"></i>
            </span>
            Tambah Customer
        </h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form class="forms-sample" action="{{ route('customer.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" placeholder="Masukkan nama customer" required>
                        </div>

                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea name="alamat" class="form-control" rows="4" placeholder="Alamat lengkap" required>{{ old('alamat') }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Provinsi</label>
                                    <select name="province_id" id="province" class="form-control" required>
                                        <option value="">Pilih Provinsi</option>
                                        @foreach($provinces as $province)
                                            <option value="{{ $province->id }}" {{ old('province_id') == $province->id ? 'selected' : '' }}>{{ $province->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Kota / Kabupaten</label>
                                    <select name="regency_id" id="regency" class="form-control" required disabled>
                                        <option value="">Pilih Kota / Kabupaten</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Kecamatan</label>
                                    <select name="district_id" id="district" class="form-control" required disabled>
                                        <option value="">Pilih Kecamatan</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Kodepos / Kelurahan</label>
                            <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code') }}" placeholder="Contoh: 60115 / Sukolilo" required>
                        </div>

                        <div class="form-group">
                            <label>Foto Customer (Webcam)</label>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-gradient-info btn-icon-text" data-bs-toggle="modal" data-bs-target="#cameraModal">
                                        <i class="mdi mdi-camera btn-icon-prepend"></i> Ambil Foto 
                                    </button>
                                </div>
                                <div class="col-md-4 mt-3">
                                    <div class="border rounded text-center p-2 bg-light">
                                        <img id="preview-photo" src="" alt="Preview Foto" class="img-fluid rounded" style="max-height: 200px; display: none;" />
                                        <div id="preview-hint" class="py-5 text-muted">Belum ada foto.</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="foto_blob_data" id="foto_blob_data" value="{{ old('foto_blob_data') }}">

                        <div class="mt-4">
                            <button type="submit" class="btn btn-gradient-primary me-2">Simpan Data</button>
                            <a href="{{ route('customer.index') }}" class="btn btn-light">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL KAMERA --}}
<div class="modal fade" id="cameraModal" tabindex="-1" aria-labelledby="cameraModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ambil Foto Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-light">
                <div class="row text-center">
                    <div class="col-md-6 mb-3">
                        <p class="fw-bold mb-2">Video Realtime</p>
                        <div class="border rounded bg-dark d-flex align-items-center justify-content-center" style="height: 250px; overflow: hidden;">
                            <video id="video" autoplay playsinline style="width: 100%; height: 100%; object-fit: cover; display: none;"></video>
                            <div id="video-placeholder" class="text-white small">Kamera Belum Aktif</div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="fw-bold mb-2">Hasil Snapshot</p>
                        <div class="border rounded bg-white d-flex align-items-center justify-content-center" style="height: 250px; overflow: hidden;">
                            <img id="snapshot" src="" alt="Snapshot" style="width: 100%; height: 100%; object-fit: cover; display: none;" />
                            <canvas id="canvas" class="d-none"></canvas>
                            <div id="snapshot-placeholder" class="text-muted small">Klik Ambil Foto</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-sm btn-info" id="btn-start-camera">Aktifkan Kamera</button>
                <button type="button" class="btn btn-sm btn-primary" id="btn-capture" disabled>Ambil Foto</button>
                <button type="button" class="btn btn-sm btn-success" id="btn-save-photo" disabled>Gunakan Foto</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js-page')
<script>
    let cameraStream = null;
    const oldPhotoData = @json(old('foto_blob_data'));
    const oldRegency = @json(old('regency_id'));
    const oldDistrict = @json(old('district_id'));

    function resetCameraView() {
        document.getElementById('video').style.display = 'none';
        document.getElementById('video-placeholder').style.display = 'block';
        document.getElementById('snapshot').style.display = 'none';
        document.getElementById('snapshot-placeholder').style.display = 'block';
        document.getElementById('btn-capture').disabled = true;
        document.getElementById('btn-save-photo').disabled = true;
    }

    function showPreviewPhoto(dataUrl) {
        if (!dataUrl) return;
        document.getElementById('foto_blob_data').value = dataUrl;
        document.getElementById('preview-photo').src = dataUrl;
        document.getElementById('preview-photo').style.display = 'block';
        document.getElementById('preview-hint').style.display = 'none';
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (oldPhotoData) showPreviewPhoto(oldPhotoData);

        document.getElementById('btn-start-camera').addEventListener('click', function() {
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(function(stream) {
                    cameraStream = stream;
                    const video = document.getElementById('video');
                    video.srcObject = stream;
                    video.style.display = 'block';
                    document.getElementById('video-placeholder').style.display = 'none';
                    document.getElementById('btn-capture').disabled = false;
                })
                .catch(function() {
                    alert('Kamera tidak tersedia atau izin ditolak.');
                });
        });

        document.getElementById('btn-capture').addEventListener('click', function() {
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

            const dataUrl = canvas.toDataURL('image/png');
            document.getElementById('snapshot').src = dataUrl;
            document.getElementById('snapshot').style.display = 'block';
            document.getElementById('snapshot-placeholder').style.display = 'none';
            document.getElementById('btn-save-photo').disabled = false;
        });

        document.getElementById('btn-save-photo').addEventListener('click', function() {
            const canvas = document.getElementById('canvas');
            showPreviewPhoto(canvas.toDataURL('image/png'));
            bootstrap.Modal.getInstance(document.getElementById('cameraModal')).hide();
        });

        // Handle wilayah AJAX
        $('#province').on('change', function() {
            let id = $(this).val();
            $('#regency').empty().append('<option value="">Pilih Kota / Kabupaten</option>').prop('disabled', true);
            $('#district').empty().append('<option value="">Pilih Kecamatan</option>').prop('disabled', true);
            if(id) {
                $.get("{{ url('wilayah/regencies') }}/" + id, function(data) {
                    $('#regency').prop('disabled', false);
                    data.forEach(d => $('#regency').append(`<option value="${d.id}">${d.name}</option>`));
                });
            }
        });

        $('#regency').on('change', function() {
            let id = $(this).val();
            $('#district').empty().append('<option value="">Pilih Kecamatan</option>').prop('disabled', true);
            if(id) {
                $.get("{{ url('wilayah/districts') }}/" + id, function(data) {
                    $('#district').prop('disabled', false);
                    data.forEach(d => $('#district').append(`<option value="${d.id}">${d.name}</option>`));
                });
            }
        });
    });
</script>
@endpush