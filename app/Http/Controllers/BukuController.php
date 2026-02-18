<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    /**
     * Display a listing of buku.
     */
    public function index()
    {
        $bukus = Buku::with('kategori')->get();

        return view('buku.index', compact('bukus'));
    }
    
    public function create()
    {
        $kategoris = Kategori::all();
        return view('buku.create', compact('kategoris'));
    }
    
    public function store(Request $request)
    {
        $data = $request->validate([
            'kode' => 'required|string|max:50',
            'judul' => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'idkategori' => 'required|integer|exists:kategori,idkategori',
        ]);
        
        Buku::create($data);
        
        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan.');
    }
    
    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        $kategoris = Kategori::all();
        
        return view('buku.edit', compact('buku', 'kategoris'));
    }
    
    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);
        
        $data = $request->validate([
            'kode' => 'required|string|max:50',
            'judul' => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'idkategori' => 'required|integer|exists:kategori,idkategori',
        ]);
        
        $buku->update($data);
        
        return redirect()->route('buku.index')->with('success', 'Buku berhasil diperbarui.');
    }
    
    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);
        $buku->delete();
        
        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus.');
    }
}
 
