<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pesanan #{{ $pesanan->idpesanan }}</title>
    <!-- Gunakan font modern -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .invoice-card {
            background: #ffffff;
            width: 100%;
            max-width: 400px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            padding: 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .invoice-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(90deg, #4CAF50, #8BC34A);
        }
        .header h2 {
            margin: 0 0 5px;
            color: #333;
            font-size: 24px;
        }
        .header p {
            margin: 0;
            color: #888;
            font-size: 14px;
        }
        .qr-container {
            margin: 25px 0;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 12px;
            display: inline-block;
        }
        .qr-container img {
            max-width: 200px;
            width: 100%;
            display: block;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 20px;
            color: white;
            background: {{ $pesanan->status_bayar ? '#4CAF50' : '#FF9800' }};
        }
        .details {
            text-align: left;
            margin-bottom: 20px;
            border-top: 1px dashed #ddd;
            padding-top: 20px;
        }
        .details-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
            color: #555;
        }
        .details-row.total {
            font-weight: 700;
            color: #333;
            font-size: 16px;
            border-top: 1px solid #eee;
            padding-top: 10px;
            margin-top: 10px;
        }
        .item-list {
            text-align: left;
            font-size: 13px;
            color: #666;
            margin-bottom: 20px;
        }
        .item-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .action-btn {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: background 0.3s ease;
        }
        .action-btn:hover {
            background: #43a047;
        }
        .instruction {
            margin-top: 15px;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="invoice-card" id="invoice-content">
        <div class="header">
            <h2>Kantin Kita</h2>
            <p>ID Pesanan: #{{ $pesanan->idpesanan }}</p>
        </div>

        <div class="qr-container">
            <img src="{{ $qrCode }}" alt="QR Code Pesanan">
        </div>

        <div class="status-badge">
            {{ $pesanan->status_bayar ? 'LUNAS' : 'BELUM BAYAR' }}
        </div>

        <div class="item-list">
            <strong>Daftar Menu:</strong>
            @foreach($pesanan->detail_pesanan as $detail)
            <div class="item-row">
                <span>{{ $detail->jumlah }}x {{ $detail->menu->nama_menu ?? 'Menu' }}</span>
                <span>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
            </div>
            @endforeach
        </div>

        <div class="details">
            <div class="details-row">
                <span>Nama Pemesan</span>
                <span>{{ $pesanan->nama }}</span>
            </div>
            <div class="details-row">
                <span>Metode Bayar</span>
                <span style="text-transform: uppercase;">{{ $pesanan->metode_bayar ?? '-' }}</span>
            </div>
            <div class="details-row total">
                <span>Total Belanja</span>
                <span>Rp {{ number_format($pesanan->total, 0, ',', '.') }}</span>
            </div>
        </div>

        <button class="action-btn" onclick="window.print()">Simpan / Cetak Struk</button>
        <p class="instruction">💡 Tip: Screenshot halaman ini agar QR Code tidak hilang, atau akses kembali melalui menu "Pesanan Saya".</p>
    </div>
</body>
</html>
