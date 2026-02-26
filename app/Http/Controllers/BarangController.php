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

    public function create()
    {
        return view('barang.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
        ]);

        // generate a simple unique id for id_barang
        $id = uniqid();

        $barang = new Barang();
        $barang->id_barang = $id;
        $barang->nama = $data['nama'];
        $barang->harga = $data['harga'];
        $barang->save();

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambah.');
    }

    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        return view('barang.edit', compact('barang'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
        ]);

        $barang = Barang::findOrFail($id);
        $barang->nama = $data['nama'];
        $barang->harga = $data['harga'];
        $barang->save();

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }
}