<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TokoController extends Controller
{
    public function index()
    {
        $tokos = \App\Models\Toko::all();
        return view('toko.index', compact('tokos'));
    }

    public function create()
    {
        return view('toko.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'barcode' => 'required|unique:toko,barcode',
            'nama_toko' => 'required',
            'alamat' => 'required',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'accuracy' => 'nullable|numeric'
        ]);

        \App\Models\Toko::create($request->all());
        return redirect()->route('toko.index')->with('success', 'Data Toko berhasil ditambahkan');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $toko = \App\Models\Toko::findOrFail($id);
        return view('toko.edit', compact('toko'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'barcode' => 'required|unique:toko,barcode,'.$id.',idtoko',
            'nama_toko' => 'required',
            'alamat' => 'required',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'accuracy' => 'nullable|numeric'
        ]);

        $toko = \App\Models\Toko::findOrFail($id);
        $toko->update($request->all());
        return redirect()->route('toko.index')->with('success', 'Data Toko berhasil diupdate');
    }

    public function destroy(string $id)
    {
        $toko = \App\Models\Toko::findOrFail($id);
        $toko->delete();
        return redirect()->route('toko.index')->with('success', 'Data Toko berhasil dihapus');
    }

    public function cetakBarcode($id)
    {
        $toko = \App\Models\Toko::findOrFail($id);
        return view('toko.cetak', compact('toko'));
    }
}
