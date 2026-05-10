@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-qrcode-scan"></i>
            </span> Scanner QR Code Kantin
        </h3>
    </div>

    <div class="row">
        <div class="col-md-5 grid-margin stretch-card">
            <div class="card">
                <div class="card-body text-center">
                    <h4 class="card-title">Pindai QR Pesanan</h4>
                    
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
                <div class="card-body" id="detail-pesanan-container">
                    <h4 class="card-title text-center text-muted mt-5">Silakan scan QR Code pesanan untuk melihat detail.</h4>
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

        const config = { fps: 10, qrbox: { width: 250, height: 250 } };

        // Coba load kamera yang ada
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

        // Fungsi start scanner
        function startScanner(cameraId = null) {
            $('#btn-scan-ulang').hide();
            $('#detail-pesanan-container').html('<h4 class="card-title text-center text-muted mt-5">Menunggu hasil scan...</h4>');
            
            // Jika tidak ada ID Kamera, prioritas ke environment (kamera belakang)
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

        // Jalankan saat load pertama
        startScanner();

        $('#kamera-select').change(function() {
            let camId = $(this).val();
            html5QrCode.stop().then(() => {
                startScanner(camId);
            }).catch(err => {
                // Ignore if it wasn't running
                startScanner(camId);
            });
        });

        $('#btn-scan-ulang').click(function() {
            let camId = $('#kamera-select').val();
            startScanner(camId);
        });

        function onScanSuccess(decodedText, decodedResult) {
            // Berhenti scan saat berhasil
            html5QrCode.stop().then(() => {
                // Mainkan audio
                beepAudio.play().catch(e => console.error("Audio error:", e));
                $('#btn-scan-ulang').show();
                
                // Ambil detail pesanan
                fetchDetailPesanan(decodedText);
            }).catch(err => {
                console.error("Gagal menghentikan scanner", err);
            });
        }

        function onScanFailure(error) {
            // Abaikan error rutin jika tidak ada QR terdeteksi
        }

        function fetchDetailPesanan(idpesanan) {
            Swal.fire({
                title: 'Memproses Data...',
                text: 'Mengambil data pesanan dari server',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            axios.get(`{{ url('api/pesanan') }}/${idpesanan}`)
                .then(res => {
                    let data = res.data.data;
                    let statusBayar = data.status_bayar == 1 
                        ? '<span class="badge badge-success">LUNAS</span>' 
                        : '<span class="badge badge-danger">BELUM BAYAR</span>';

                    Swal.fire({
                        icon: data.status_bayar == 1 ? 'success' : 'warning',
                        title: 'Scan Berhasil!',
                        text: `Pesanan #${data.idpesanan} ditemukan.`
                    });

                    let itemsHtml = '';
                    if (data.detail_pesanan && data.detail_pesanan.length > 0) {
                        data.detail_pesanan.forEach(item => {
                            let namaMenu = item.menu ? item.menu.nama_menu : 'Menu Tidak Ditemukan';
                            itemsHtml += `
                                <tr>
                                    <td>${namaMenu}</td>
                                    <td>${item.jumlah}</td>
                                    <td>Rp ${parseInt(item.harga).toLocaleString('id-ID')}</td>
                                    <td>Rp ${parseInt(item.subtotal).toLocaleString('id-ID')}</td>
                                </tr>
                            `;
                        });
                    } else {
                        itemsHtml = `<tr><td colspan="4" class="text-center">Tidak ada detail menu</td></tr>`;
                    }

                    let html = `
                        <h4 class="card-title">Detail Pesanan #${data.idpesanan}</h4>
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <strong>Nama:</strong> ${data.nama} <br>
                                <strong>Total:</strong> Rp ${parseInt(data.total).toLocaleString('id-ID')} <br>
                                <strong>Metode:</strong> ${(data.metode_bayar || '-').toUpperCase()}
                            </div>
                            <div class="text-right">
                                <strong>Status:</strong> ${statusBayar}
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Menu</th>
                                        <th>Qty</th>
                                        <th>Harga</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${itemsHtml}
                                </tbody>
                            </table>
                        </div>
                    `;
                    $('#detail-pesanan-container').html(html);
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire('Error', 'Pesanan tidak ditemukan atau terjadi kesalahan.', 'error');
                    $('#detail-pesanan-container').html(`<div class="alert alert-danger">Gagal mengambil data untuk ID: ${idpesanan}</div>`);
                });
        }
    });
</script>
@endpush
