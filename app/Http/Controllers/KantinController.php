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
use Endroid\QrCode\Builder\Builder;

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

    public function checkout(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $pesanan = Pesanan::create([
                    'nama' => $request->nama,
                    'total' => $request->total,
                    'status_bayar' => 0,
                ]);

                $rawItems = $request->items ?? [];
                if (is_object($rawItems)) {
                    $rawItems = (array) $rawItems;
                }

                \Log::info('Checkout items payload', ['items' => $rawItems]);

                $normalizedItems = [];
                foreach ($rawItems as $key => $item) {
                    if (is_object($item)) {
                        $item = (array) $item;
                    }

                    if (!is_array($item)) {
                        \Log::warning('Checkout item invalid type', ['key' => $key, 'item' => $item]);
                        continue;
                    }

                    if (!isset($item['idmenu']) && is_numeric($key)) {
                        $item['idmenu'] = $key;
                    }

                    $normalizedItems[] = $item;
                }

                \Log::info('Checkout items normalized payload', ['items' => $normalizedItems]);

                foreach ($normalizedItems as $item) {
                    $idmenu = $item['idmenu'] ?? null;
                    $qty = isset($item['qty']) ? (int) $item['qty'] : null;
                    $harga = isset($item['harga']) ? (int) $item['harga'] : null;
                    $subtotal = isset($item['subtotal']) ? (int) $item['subtotal'] : null;

                    if (!$idmenu || $qty <= 0 || $harga === null || $subtotal === null) {
                        \Log::warning('Checkout item skipped due missing required fields or invalid values', [
                            'item' => $item,
                            'idmenu' => $idmenu,
                            'qty' => $qty,
                            'harga' => $harga,
                            'subtotal' => $subtotal,
                        ]);
                        continue;
                    }

                    DetailPesanan::create([
                        'idpesanan' => $pesanan->idpesanan,
                        'idmenu' => $idmenu,
                        'jumlah' => $qty,
                        'harga' => $harga,
                        'subtotal' => $subtotal,
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

                // Override cURL certificate path untuk Laragon di D: dan hindari referensi C:\laragon
                Config::$curlOptions = [
                    CURLOPT_CAINFO => "D:\\laragon\\etc\\ssl\\cacert.pem",
                    CURLOPT_HTTPHEADER => [],
                ];

                $snapToken = Snap::getSnapToken($params);
                $pesanan->update(['snap_token' => $snapToken]);

                return response()->json(['snap_token' => $snapToken]);
            });
        } catch (\Exception $e) {
            \Log::error('Midtrans checkout failed: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'message' => 'Checkout gagal: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                // Ambil ID Pesanan dari string 'KANTIN-ID-TIME'
                $parts = explode('-', $request->order_id);
                $id_asli = $parts[1]; 
                
                $pesanan = Pesanan::find($id_asli);
                if ($pesanan) {
                    $pesanan->update([
                        'status_bayar' => 1,
                        'metode_bayar' => $request->payment_type // MENANGKAP METODE BAYAR
                    ]);
                }
            }
        }
        return response()->json(['status' => 'success']);
    }

    public function pembayaranCustomer()
    {
        $transaksi = Pesanan::latest()->take(5)->get();
        $latestPaid = Pesanan::where('status_bayar', 1)->latest('idpesanan')->first();
        $qrCode = null;

        if ($latestPaid) {
            $result = (new Builder())->build(
                null,
                null,
                null,
                (string) $latestPaid->idpesanan,
                null,
                null,
                180,
                10
            );
            $qrCode = $result->getDataUri();
        }

        return view('kantin.pembayaran', compact('transaksi', 'latestPaid', 'qrCode'));
    }

    public function qrCode($order_id)
    {
        // Format order_id: KANTIN-{idpesanan}-{timestamp}
        $parts = explode('-', $order_id);
        $id_asli = $parts[1] ?? null;
        $pesanan = Pesanan::find($id_asli);

        if (!$pesanan) {
            return response()->json(['success' => false, 'message' => 'Pesanan tidak ditemukan.'], 404);
        }

        $result = (new Builder())->build(
            null,
            null,
            null,
            (string) $pesanan->idpesanan,
            null,
            null,
            180,
            10
        );

        return response()->json([
            'success' => true,
            'qr_code' => $result->getDataUri(),
            'idpesanan' => $pesanan->idpesanan,
        ]);
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