<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Barcode - {{ $toko->nama_toko }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 40px;
        }
        .barcode-box {
            display: inline-block;
            border: 2px dashed #000;
            padding: 30px;
            border-radius: 10px;
        }
        .store-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 15px;
            text-transform: uppercase;
        }
        .barcode-wrapper {
            margin: 15px 0;
            display: flex;
            justify-content: center;
        }
        .barcode-text {
            font-size: 20px;
            letter-spacing: 5px;
            font-weight: bold;
            margin-top: 10px;
        }
        @media print {
            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .no-print {
                display: none !important;
            }
            .barcode-box {
                border: none;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="margin-bottom: 30px;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; cursor: pointer; background: #4CAF50; color: white; border: none; border-radius: 5px;">Print Sekarang</button>
        <button onclick="window.close()" style="padding: 10px 20px; font-size: 16px; cursor: pointer; background: #f44336; color: white; border: none; border-radius: 5px;">Tutup</button>
    </div>

    <div class="barcode-box">
        <div class="store-title">{{ $toko->nama_toko }}</div>
        <div class="barcode-wrapper">
            @php
                $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
            @endphp
            {!! $generator->getBarcode($toko->barcode, $generator::TYPE_CODE_128, 2, 80) !!}
        </div>
        <div class="barcode-text">{{ $toko->barcode }}</div>
    </div>
</body>
</html>
