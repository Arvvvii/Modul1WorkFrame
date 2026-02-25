<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Sertifikat</title>
    <style>
        [cite_start]/* Pengaturan ukuran kertas A4 Landscape sesuai modul [cite: 1] */
        @page { size: A4 landscape; margin: 0; }
        html, body { margin: 0; padding: 0; height: 100%; width: 100%; }
        
        body { 
            font-family: 'Times New Roman', serif; 
            color: #111;
        }

        .sheet {
            position: relative;
            width: 297mm;
            height: 210mm;
            overflow: hidden;
        }

        /* Container background untuk menampung gambar Base64 */
        .background {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            z-index: -1;
        }

        .background img {
            width: 100%;
            height: 100%;
        }

        /* Penempatan Nama Utama agar pas di atas garis "Diberikan Kepada" */
        .name-container {
            position: absolute;
            top: 78mm; /* Sesuaikan angka ini agar pas di atas garis */
            width: 100%;
            text-align: center;
        }

        .name-text {
            font-size: 40pt;
            font-weight: bold;
            color: #3e2b77; /* Warna ungu gelap sesuai desain sertifikat */
        }

        /* Penempatan Jabatan/Role */
        .role-container {
            position: absolute;
            top: 110mm;
            width: 100%;
            text-align: center;
        }

        .role-text {
            font-size: 22pt;
            font-weight: bold;
            color: #333;
        }

        /* Bagian Tanda Tangan di bawah agar presisi di atas garis tanda tangan */
        .signature-section {
            position: absolute;
            bottom: 25mm;
            width: 100%;
            padding: 0 15mm;
        }

        .signature-box {
            float: left;
            width: 33.33%;
            text-align: center;
        }

        .signer-name {
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .signer-id {
            font-size: 9pt;
        }
    </style>
</head>
<body>
    <div class="sheet">
        <div class="background">
            <img src="{{ $base64 }}" alt="Background Sertifikat">
        </div>

        <div class="name-container">
            <div class="name-text">{{ $user->name }}</div>
        </div>

        <div class="role-container">
            <div style="font-size: 14pt; color: #6b5494; margin-bottom: 5px;">Atas partisipasinya sebagai:</div>
            <div class="role-text">Panitia</div>
        </div>

        <div style="position: absolute; top: 135mm; width: 100%; text-align: center; padding: 0 40mm;">
            <p style="font-size: 12pt; margin: 0;">Dalam acara Kuliah Tamu <b>"AI, IoT dan Teknologi yang Mengubah Dunia"</b></p>
            <p style="font-size: 11pt; margin: 5px 0;">Himpunan Mahasiswa D4 Teknik Informatika Universitas Airlangga</p>
            <p style="font-size: 11pt; margin: 0;">Kamis, 19 Februari 2026</p>
        </div>

        <div class="signature-section">
            <div class="signature-box">
                <div style="margin-bottom: 15mm;">Dekan</div>
                <div class="signer-name">Prof. Dr. Fernawati, drh., M.Kes</div>
                <div class="signer-id">NIP. 1965784787387387373</div>
            </div>
            <div class="signature-box">
                <div style="margin-bottom: 15mm;">Ketua Program Studi</div>
                <div class="signer-name">Anastasya Lanie, S.Kom., M.Kom.</div>
                <div class="signer-id">NIP. 199328398293819238</div>
            </div>
            <div class="signature-box">
                <div style="margin-bottom: 15mm;">Ketua Pelaksana</div>
                <div class="signer-name">M. Arviansyah Desta Andini</div>
                <div class="signer-id">NIM. 434241003</div>
            </div>
        </div>
    </div>
</body>
</html>