<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\DB; // Tambahkan ini agar fungsi DB jalan

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
        $offset = ($startY - 1) * 5 + ($startX - 1); 

        $pages = [];
        $remaining = $items;

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

    // ==========================================
    // --- FITUR KASIR (MODUL 5) ---
    // ==========================================

    public function kasir()
    {
        // Menampilkan halaman kasir
        return view('barang.kasir');
    }

    public function cariBarang($id)
    {
        // Mencari barang berdasarkan ID_BARANG (Primary Key)
        // Fungsi ini dipanggil via Axios saat user tekan ENTER
        $barang = Barang::find($id);

        if ($barang) {
            return response()->json([
                'success' => true,
                'data' => $barang
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Barang tidak ditemukan'
            ]);
        }
    }

    public function simpanTransaksi(Request $request)
    {
        // Validasi input dari frontend
        $request->validate([
            'total' => 'required|numeric',
            'items' => 'required|array'
        ]);

        try {
            // Database Transaction: Jika satu proses gagal, semua dibatalkan
            DB::beginTransaction();

            // 1. Simpan ke tabel 'penjualan'
            // Menggunakan query builder agar kamu tidak perlu buat model baru jika belum ada
            $id_penjualan = DB::table('penjualan')->insertGetId([
                'tanggal_transaksi' => now(),
                'total_harga' => $request->total,
            ]);

            // 2. Simpan semua item belanja ke tabel 'detail_penjualan'
            foreach ($request->items as $item) {
                DB::table('detail_penjualan')->insert([
                    'id_penjualan' => $id_penjualan,
                    'id_barang'    => $item['id_barang'],
                    'jumlah'       => $item['qty'],
                    'subtotal'     => $item['subtotal'],
                ]);
            }

            DB::commit();
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            // Jika ada error (misal koneksi mati), batalkan penyimpanan data
            DB::rollBack();
            return response()->json([
                'success' => false, 
                'message' => 'Gagal menyimpan: ' . $e->getMessage()
            ], 500);
        }
    }
}