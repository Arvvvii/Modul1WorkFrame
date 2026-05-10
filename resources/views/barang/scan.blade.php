@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-barcode-scan"></i>
            </span> Scanner Barcode Barang
        </h3>
    </div>

    <div class="row">
        <div class="col-md-5 grid-margin stretch-card">
            <div class="card">
                <div class="card-body text-center">
                    <h4 class="card-title">Pindai Barcode</h4>
                    
                    <div class="form-group mt-3">
                        <select id="kamera-select" class="form-control">
                            <option value="">-- Pilih Kamera (Atau gunakan default) --</option>
                        </select>
                    </div>

                    <div id="reader" width="100%" style="border-radius: 12px; overflow: hidden; margin-top: 15px; border: 2px dashed #ccc;"></div>
                    
                    <button id="btn-scan-ulang" class="btn btn-gradient-info btn-fw mt-3 w-100" style="display: none;">
                        <i class="mdi mdi-camera-retake"></i> Scan Ulang
                    </button>
                    
                    <!-- Audio Beep -->
                    <audio id="beep-audio" src="{{ asset('sounds/beepframework.mpeg') }}" preload="auto"></audio>
                </div>
            </div>
        </div>

        <div class="col-md-7 grid-margin stretch-card">
            <div class="card">
                <div class="card-body" id="detail-barang-container">
                    <h4 class="card-title text-center text-muted mt-5">Silakan scan barcode barang untuk melihat detail.</h4>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js-page')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let html5QrCode;
    let beepAudio = document.getElementById('beep-audio');

    $(document).ready(function() {
        html5QrCode = new Html5Qrcode("reader");

        // Konfigurasi khusus untuk barcode 1D yang lebih lebar
        const config = { 
            fps: 10, 
            qrbox: { width: 300, height: 150 },
            formatsToSupport: [ 
                Html5QrcodeSupportedFormats.CODE_128,
                Html5QrcodeSupportedFormats.CODE_39,
                Html5QrcodeSupportedFormats.EAN_13,
                Html5QrcodeSupportedFormats.EAN_8,
                Html5QrcodeSupportedFormats.QR_CODE
            ]
        };

        Html5Qrcode.getCameras().then(devices => {
            if (devices && devices.length) {
                let options = '';
                devices.forEach(device => {
                    options += `<option value="${device.id}">${device.label}</option>`;
                });
                $('#kamera-select').append(options);
            }
        }).catch(err => {
            console.error("Gagal mendapatkan daftar kamera", err);
        });

        function startScanner(cameraId = null) {
            $('#btn-scan-ulang').hide();
            $('#detail-barang-container').html('<h4 class="card-title text-center text-muted mt-5">Menunggu hasil scan...</h4>');
            
            let cameraConfig = cameraId ? cameraId : { facingMode: "environment" };

            html5QrCode.start(
                cameraConfig, 
                config, 
                onScanSuccess,
                onScanFailure
            ).catch(err => {
                console.error("Gagal memulai scanner", err);
                Swal.fire('Error', 'Tidak dapat mengakses kamera.', 'error');
            });
        }

        startScanner();

        $('#kamera-select').change(function() {
            let camId = $(this).val();
            html5QrCode.stop().then(() => {
                startScanner(camId);
            }).catch(err => {
                startScanner(camId);
            });
        });

        $('#btn-scan-ulang').click(function() {
            let camId = $('#kamera-select').val();
            startScanner(camId);
        });

        function onScanSuccess(decodedText, decodedResult) {
            html5QrCode.stop().then(() => {
                beepAudio.play().catch(e => console.error("Audio error:", e));
                $('#btn-scan-ulang').show();
                
                fetchDetailBarang(decodedText);
            }).catch(err => {
                console.error("Gagal menghentikan scanner", err);
            });
        }

        function onScanFailure(error) {
            // Abaikan error rutin
        }

        function fetchDetailBarang(kodeBarang) {
            Swal.fire({
                title: 'Mencari Barang...',
                text: `Kode: ${kodeBarang}`,
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            axios.get(`{{ url('kasir/cari') }}/${kodeBarang}`)
                .then(res => {
                    if (res.data.success) {
                        let data = res.data.data;
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Barang Ditemukan!',
                            text: `${data.nama}`,
                            timer: 1500,
                            showConfirmButton: false
                        });

                        let html = `
                            <div class="text-center mt-3">
                                <h1 class="display-4 text-primary mb-3"><i class="mdi mdi-package-variant"></i></h1>
                                <h3 class="font-weight-bold">${data.nama}</h3>
                                <p class="text-muted">Kode Barang: ${data.id_barang}</p>
                                <hr>
                                <h2 class="text-success mt-4">Rp ${parseInt(data.harga).toLocaleString('id-ID')}</h2>
                            </div>
                        `;
                        $('#detail-barang-container').html(html);
                    } else {
                        Swal.fire('Tidak Ditemukan', res.data.message || 'Barang dengan kode tersebut tidak ada.', 'warning');
                        $('#detail-barang-container').html(`<div class="alert alert-warning text-center mt-5"><i class="mdi mdi-alert-circle-outline" style="font-size: 40px;"></i><br>Barang dengan kode <strong>${kodeBarang}</strong> tidak ditemukan.</div>`);
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire('Error', 'Terjadi kesalahan saat mencari barang.', 'error');
                    $('#detail-barang-container').html(`<div class="alert alert-danger text-center mt-5"><i class="mdi mdi-close-network-outline" style="font-size: 40px;"></i><br>Terjadi kesalahan jaringan.</div>`);
                });
        }
    });
</script>
@endpush
