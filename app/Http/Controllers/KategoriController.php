<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Display a listing of kategori.
     */
    public function index()
    {
        $kategoris = Kategori::all();

        return view('kategori.index', compact('kategoris'));
    }

        public function create()
        {
            return view('kategori.create');
        }

        public function store(Request $request)
        {
            $data = $request->validate([
                'nama_kategori' => 'required|string|max:255',
            ]);

            Kategori::create($data);

            return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
        }

        public function edit($id)
        {
            $kategori = Kategori::findOrFail($id);

            return view('kategori.edit', compact('kategori'));
        }

        public function update(Request $request, $id)
        {
            $kategori = Kategori::findOrFail($id);

            $data = $request->validate([
                'nama_kategori' => 'required|string|max:255',
            ]);

            $kategori->update($data);

            return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui.');
        }

        public function destroy($id)
        {
            $kategori = Kategori::findOrFail($id);
            $kategori->delete();

            return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus.');
        }
}
