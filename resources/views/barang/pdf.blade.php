<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        /* A4 portrait, zero margins */
        @page { 
            size: A4 portrait; 
            margin: 0; 
        }
        body { 
            font-family: Arial, Helvetica, sans-serif; 
            margin: 0; 
            padding-top: 5mm; /* Memberi sedikit ruang di atas agar printer tidak narik terlalu cepat */
        }

        /* Table width: 5 cols * 38mm = 190mm. Center horizontally on A4 (210mm) */
        .labels-table { 
            width: 190mm; 
            margin: 0 auto; 
            border-collapse: collapse; 
            table-layout: fixed; /* Mengunci ukuran kolom agar tidak melar */
        }
        
        .labels-table td { 
            border: none; 
            padding: 0;
        }

        /* Label Box: 38mm x 18mm */
        .label-cell {
            width: 38mm;
            height: 18mm;
            box-sizing: border-box;
            vertical-align: top;
            overflow: hidden;
        }

        /* Wrapper konten di dalam label */
        .label-content { 
            padding: 1.5mm 1mm 1.5mm 4mm; /* Kiri 4mm supaya tidak terlalu mepet garis */
            height: 18mm;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-sizing: border-box;
        }

        .label-id { 
            font-size: 6.5pt; 
            color: #555; 
            margin: 0;
            line-height: 1;
        }

        .label-name { 
            font-weight: 700; 
            font-size: 8pt; /* Sedikit dikecilkan agar muat banyak karakter */
            line-height: 1.1; 
            color: #000;
            margin: 0.5mm 0;
            /* Fitur Potong Otomatis jika nama kepanjangan (maks 2 baris) */
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            word-wrap: break-word;
        }

        .label-price { 
            font-size: 8.5pt; 
            font-weight: bold;
            color: #000;
            margin: 0;
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
                            <td class="label-cell">
                                @if($item)
                                    <div class="label-content">
                                        <div class="label-id">{{ $item->id_barang }}</div>
                                        <div class="label-name">{{ $item->nama }}</div>
                                        <div class="label-price">Rp {{ number_format($item->harga, 0, ',', '.') }}</div>
                                    </div>
                                @endif
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