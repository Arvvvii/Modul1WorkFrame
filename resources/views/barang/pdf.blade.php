<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { 
            size: A4 portrait; 
            /* Margin fisik hasil penggaris temanmu: Atas 0.4, Kanan 0.3, Bawah 0.3, Kiri 0.5 */
            margin: 0.4cm 0.3cm 0.3cm 0.5cm; 
        }

        body { 
            font-family: Arial, Helvetica, sans-serif; 
            margin: 0; 
            padding: 0;
            /* Pastikan lebar dan tinggi body sesuai standar A4 agar tidak memotong halaman */
            width: 210mm;
            position: relative;
        }

        .page {
            position: relative;
            width: 210mm;
            height: 297mm; /* Tinggi standar A4 */
            overflow: hidden;
        }

        .labels-table { 
            /* Paksa posisi di titik nol margin @page sesuai saran temanmu */
            position: absolute;
            top: 0;
            left: 0;
            border-collapse: separate; 
            /* HORIZONTAL 0.2cm (untuk cegah kanan kepotong), VERTIKAL 0.2cm */
            border-spacing: 0.2cm 0.2cm; 
            table-layout: fixed;
            width: auto;
        }
        
        .labels-table td { 
            padding: 0;
            margin: 0;
            vertical-align: middle; 
        }

        /* UKURAN FISIK STIKER TnJ 108: 38mm x 18mm */
        .label-cell {
            width: 38mm;
            height: 18mm;
            box-sizing: border-box;
            overflow: hidden;
            /* Border merah untuk panduan mata (Hapus baris ini setelah mantap) */
        }

        .label-content { 
            width: 100%;
            height: 18mm;
            display: flex;
            flex-direction: column;
            /* Membuat konten di tengah secara vertikal */
            justify-content: center; 
            /* Membuat konten rata kiri sesuai instruksi temanmu */
            align-items: flex-start; 
            text-align: left;
            /* Jarak aman 3mm dari tepi kiri agar tidak meleset saat diprint */
            padding-left: 3mm; 
            padding-right: 1mm;
            box-sizing: border-box;
        }

        .label-id { 
            font-size: 6pt; 
            color: #555; 
            margin-bottom: 1px;
            line-height: 1;
        }

        .label-name { 
            font-weight: 700; 
            font-size: 8pt; 
            line-height: 1.1; 
            color: #000;
            margin: 1px 0;
            /* Batasi maksimal 2 baris agar tidak merusak tata letak harga */
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            width: 100%;
        }

        .label-price { 
            font-size: 8.5pt; 
            font-weight: bold; 
            margin-top: 1px;
            line-height: 1;
        }

        .page-break { 
            page-break-after: always; 
        }
    </style>
</head>
<body>
@foreach($pages as $pIndex => $page)
    <div class="page">
        <table class="labels-table">
            <tbody>
                @for ($r = 0; $r < 8; $r++)
                    <tr>
                        @for ($c = 0; $c < 5; $c++)
                            @php 
                                $slotIndex = $r * 5 + $c; 
                                $item = $page[$slotIndex] ?? null; 
                            @endphp
                            <td>
                                <div class="label-cell">
                                    @if($item)
                                        <div class="label-content">
                                            <div class="label-id">{{ $item->id_barang }}</div>
                                            <div class="label-name">{{ $item->nama }}</div>
                                            <div class="label-price">Rp {{ number_format($item->harga, 0, ',', '.') }}</div>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        @endfor
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
    @if ($pIndex < count($pages) - 1)
        <div class="page-break"></div>
    @endif
@endforeach
</body>
</html>