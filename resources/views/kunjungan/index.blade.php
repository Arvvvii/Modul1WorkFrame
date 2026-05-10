@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-store"></i>
            </span> Kunjungan Toko
        </h3>
    </div>

    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body text-center">
                    <h4 class="card-title mb-4">Scanner Barcode Toko</h4>
                    
                    <div id="reader" style="width: 100%; border-radius: 10px; overflow: hidden; margin-bottom: 20px;"></div>
                    
                    <div class="mt-3">
                        <h5>Status Lokasi (Real-time)</h5>
                        <div id="gps-status" class="badge badge-warning p-2"><i class="mdi mdi-satellite-variant"></i> Mencari sinyal GPS...</div>
                        <p class="mt-2 text-muted small" id="gps-info">Lat: -, Lng: - <br>Akurasi: - meter</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Instruksi Kunjungan</h4>
                    <ul class="list-arrow">
                        <li>Pastikan Anda berada di lokasi toko (radius < 50m).</li>
                        <li>Arahkan kamera ke barcode toko.</li>
                        <li>Scanner akan otomatis membaca barcode.</li>
                        <li>Pastikan GPS/Lokasi pada perangkat Anda menyala.</li>
                        <li>Akurasi lokasi harus di bawah 50 meter agar dapat Diterima.</li>
                    </ul>
                    
                    <div class="mt-4 p-3 rounded" style="background: rgba(0,0,0,0.05);">
                        <h5 class="text-primary"><i class="mdi mdi-information-outline"></i> Threshold Efektif</h5>
                        <p class="small text-muted mb-0">Threshold efektif adalah jarak toleransi yang dihitung dengan rumus: <br><strong>50m + Akurasi Toko + Akurasi Perangkat Anda</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js-page')
<!-- Library html5-qrcode -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<!-- Library SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Library Axios -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let html5QrcodeScanner = new Html5Qrcode("reader");
    let isScanning = true;
    let vendorLat = null;
    let vendorLng = null;
    let vendorAcc = null;

    // GPS Watch Position
    const gpsStatus = document.getElementById('gps-status');
    const gpsInfo = document.getElementById('gps-info');
    
    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(
            function(position) {
                vendorLat = position.coords.latitude;
                vendorLng = position.coords.longitude;
                vendorAcc = position.coords.accuracy;
                
                gpsInfo.innerHTML = `Lat: ${vendorLat.toFixed(6)}, Lng: ${vendorLng.toFixed(6)} <br>Akurasi: <strong>${Math.round(vendorAcc)} meter</strong>`;
                
                if (vendorAcc <= 50) {
                    gpsStatus.className = 'badge badge-success p-2';
                    gpsStatus.innerHTML = '<i class="mdi mdi-check-circle"></i> GPS Akurat';
                } else {
                    gpsStatus.className = 'badge badge-warning p-2';
                    gpsStatus.innerHTML = '<i class="mdi mdi-alert"></i> Akurasi Lemah (>50m)';
                }
            },
            function(error) {
                gpsStatus.className = 'badge badge-danger p-2';
                gpsStatus.innerHTML = '<i class="mdi mdi-close-circle"></i> Gagal mendapatkan GPS';
                console.error(error);
            },
            {
                enableHighAccuracy: true,
                timeout: 20000,
                maximumAge: 0
            }
        );
    } else {
        gpsStatus.className = 'badge badge-danger p-2';
        gpsStatus.innerHTML = '<i class="mdi mdi-close-circle"></i> Browser tidak support Geolocation';
    }

    // Audio Beep
    const playBeep = () => {
        const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqPb3KEj5RziouTdpKJmH2OkZ19kZOhf5WWoICanqSDm52kfp2aqH6hoqp/oqOrf6Kkq3+kpan/pKam/6Smqf+kpan/pKam/6Wmqv+mpar/pqep/6anqf+np6r/qKmr/6iprP+pqqz/qaqt/6qqrf+qq67/q6uu/6urrv+rq67/q6uu/6urrv+rq67/q6uu/w==');
        audio.play().catch(e => console.log('Audio error:', e));
    };

    function onScanSuccess(decodedText, decodedResult) {
        if (!isScanning) return;
        isScanning = false;
        
        playBeep();
        
        // Pause scanner
        html5QrcodeScanner.pause();

        if (vendorLat === null || vendorLng === null) {
            Swal.fire({
                icon: 'warning',
                title: 'Lokasi Belum Ditemukan',
                text: 'Tunggu hingga sinyal GPS stabil sebelum scan.',
                timer: 3000,
                showConfirmButton: false
            }).then(() => {
                isScanning = true;
                html5QrcodeScanner.resume();
            });
            return;
        }

        Swal.fire({
            title: 'Memproses...',
            text: 'Mencocokkan data toko dan lokasi',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Send to backend
        axios.post('{{ route("kunjungan.store") }}', {
            barcode: decodedText,
            latitude_vendor: vendorLat,
            longitude_vendor: vendorLng,
            accuracy_vendor: vendorAcc,
            _token: '{{ csrf_token() }}'
        })
        .then(response => {
            let res = response.data;
            if (res.success) {
                let icon = res.status === 'DITERIMA' ? 'success' : 'error';
                let color = res.status === 'DITERIMA' ? '#4CAF50' : '#F44336';
                
                Swal.fire({
                    icon: icon,
                    title: `Kunjungan ${res.status}`,
                    html: `Jarak Aktual: <b>${res.jarak} m</b><br>Batas Toleransi: <b>${res.threshold_efektif} m</b>`,
                    color: color,
                    timer: 3000,
                    showConfirmButton: false
                }).then(() => {
                    isScanning = true;
                    html5QrcodeScanner.resume();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: res.message,
                    timer: 3000,
                    showConfirmButton: false
                }).then(() => {
                    isScanning = true;
                    html5QrcodeScanner.resume();
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan sistem.',
                timer: 3000,
                showConfirmButton: false
            }).then(() => {
                isScanning = true;
                html5QrcodeScanner.resume();
            });
        });
    }

    function onScanFailure(error) {
        // handle scan failure silently
    }

    // Start scanner automatically
    html5QrcodeScanner.start(
        { facingMode: "environment" },
        {
            fps: 10,
            qrbox: { width: 250, height: 250 }
        },
        onScanSuccess,
        onScanFailure
    ).catch((err) => {
        console.error("Gagal memulai scanner", err);
        Swal.fire('Error', 'Gagal mengakses kamera.', 'error');
    });
});
</script>
@endpush
