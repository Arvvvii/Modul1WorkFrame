<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        /* A4 portrait, zero margins as requested */
        @page { size: A4 portrait; margin: 0; }
        body { font-family: Arial, Helvetica, sans-serif; margin: 0; }

        /* Table width is exactly 5 * 38mm = 190mm; center horizontally */
        .labels-table { width: 190mm; margin: 0 auto; border-collapse: collapse; }
        .labels-table td { border: none; }

        /* Each label: exact 38mm x 18mm */
        .label-cell {
            width: 38mm;
            height: 18mm;
            padding: 0; /* keep tight */
            box-sizing: border-box;
            vertical-align: top;
            overflow: hidden;
        }

        .label-content { padding: 2mm; }
        .label-id { font-size: 7pt; color: #4a4a4a; margin-bottom: 1px; }
        .label-name { font-weight: 700; font-size: 9pt; line-height: 1; }
        .label-price { font-size: 8.5pt; margin-top: 2px; }
        .page-break { page-break-after: always; }
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
                            @php $slotIndex = $r * 5 + $c; $item = $page[$slotIndex] ?? null; @endphp
                            <td class="label-cell">
                                @if($item)
                                    <div class="label-content">
                                        <div class="label-id">{{ $item->id_barang }}</div>
                                        <div class="label-name">{{ $item->nama }}</div>
                                        <div class="label-price">Rp {{ number_format($item->harga,0,',','.') }}</div>
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