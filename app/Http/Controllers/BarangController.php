<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class BarangController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $barangs = Barang::all();
        return view('barang.index', compact('barangs'));
    }

    public function cetak(Request $request)
    {
        $selected = $request->input('selected_ids', []);
        $startX = (int) $request->input('start_x', 1);
        $startY = (int) $request->input('start_y', 1);

        $startX = max(1, min(5, $startX));
        $startY = max(1, min(8, $startY));

        $items = Barang::whereIn('id_barang', $selected)->get()->values()->all();

        $slotsPerPage = 40; // 5 cols x 8 rows
        $offset = ($startY - 1) * 5 + ($startX - 1); // position within first page

        $pages = [];

        $remaining = $items;

        // Build pages; first page honors offset, subsequent pages start at 0
        $first = true;
        while (count($remaining) > 0) {
            $page = array_fill(0, $slotsPerPage, null);
            $pos = $first ? $offset : 0;

            while ($pos < $slotsPerPage && count($remaining) > 0) {
                $page[$pos] = array_shift($remaining);
                $pos++;
            }

            $pages[] = $page;
            $first = false;
        }

        if (empty($pages)) {
            return redirect()->back()->with('error', 'Tidak ada item yang dipilih.');
        }

        $pdf = PDF::loadView('barang.pdf', compact('pages'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('labels.pdf');
    }
}
