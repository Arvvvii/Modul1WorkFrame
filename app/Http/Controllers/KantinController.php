<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans\Snap;
use Midtrans\Config;

class KantinController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function index() {
        $vendors = Vendor::all();
        $maxId = Pesanan::max('idpesanan'); 
        $nextNumber = $maxId ? $maxId + 1 : 1;
        $guestName = "Guest_" . str_pad($nextNumber, 7, '0', STR_PAD_LEFT); 

        return view('kantin.index', compact('vendors', 'guestName'));
    }

    public function getMenu($idvendor) {
        $menus = Menu::where('idvendor', $idvendor)->get();
        return response()->json($menus);
    }

    public function checkout(Request $request) {
        return DB::transaction(function () use ($request) {
            $pesanan = Pesanan::create([
                'nama' => $request->nama,
                'total' => $request->total,
                'status_bayar' => 0,
            ]);

            foreach ($request->items as $item) {
                DetailPesanan::create([
                    'idpesanan' => $pesanan->idpesanan,
                    'idmenu' => $item['idmenu'],
                    'jumlah' => $item['qty'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            // ORDER ID unik untuk Midtrans
            $order_id = 'KANTIN-' . $pesanan->idpesanan . '-' . time();

            $params = [
                'transaction_details' => [
                    'order_id' => $order_id,
                    'gross_amount' => (int)$request->total,
                ],
                'customer_details' => [
                    'first_name' => $request->nama,
                ],
            ]; 

            $snapToken = Snap::getSnapToken($params);
            $pesanan->update(['snap_token' => $snapToken]);

            return response()->json(['snap_token' => $snapToken]);
        });
    }

    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                // Ambil ID Pesanan dari string 'KANTIN-ID-TIME'
                $parts = explode('-', $request->order_id);
                $id_asli = $parts[1]; // Mengambil angka ID di antara KANTIN dan TIME
                
                $pesanan = Pesanan::find($id_asli);
                if ($pesanan) {
                    $pesanan->update(['status_bayar' => 1]);
                }
            }
        }
    }

    public function pembayaranCustomer()
    {
        $transaksi = Pesanan::latest()->take(5)->get();
        return view('kantin.pembayaran', compact('transaksi'));
    }

    public function masterVendor() {
        $vendors = Vendor::all();
        return view('vendor.index', compact('vendors'));
    }

    public function masterMenu() {
        $menus = Menu::with('vendor')->get(); 
        return view('menu.index', compact('menus'));
    }

    public function transaksiLunas() {
        $transaksi = Pesanan::where('status_bayar', 1)->latest('idpesanan')->get(); 
        return view('vendor.transaksi', compact('transaksi'));
    }
}