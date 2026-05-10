<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KunjunganController extends Controller
{
    public function index()
    {
        return view('kunjungan.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'barcode' => 'required',
            'latitude_vendor' => 'required|numeric',
            'longitude_vendor' => 'required|numeric',
            'accuracy_vendor' => 'nullable|numeric'
        ]);

        $toko = \App\Models\Toko::where('barcode', $request->barcode)->first();

        if (!$toko) {
            return response()->json(['success' => false, 'message' => 'Toko tidak ditemukan.']);
        }

        $jarak = $this->haversineDistance(
            $request->latitude_vendor, 
            $request->longitude_vendor, 
            $toko->latitude, 
            $toko->longitude
        );

        $threshold_standar = 50;
        $accuracy_vendor = $request->accuracy_vendor ?? 0;
        $accuracy_toko = $toko->accuracy ?? 0;
        $threshold_efektif = $threshold_standar + $accuracy_vendor + $accuracy_toko;

        $status = ($jarak <= $threshold_efektif) ? 'DITERIMA' : 'DITOLAK';

        $kunjungan = \App\Models\Kunjungan::create([
            'idvendor' => auth()->id() ?? 1, // Asumsi 1 jika belum login, sebaiknya auth()->id()
            'idtoko' => $toko->idtoko,
            'latitude_vendor' => $request->latitude_vendor,
            'longitude_vendor' => $request->longitude_vendor,
            'accuracy_vendor' => $accuracy_vendor,
            'jarak' => round($jarak, 2),
            'threshold' => $threshold_standar,
            'threshold_efektif' => round($threshold_efektif, 2),
            'status' => $status,
            'waktu_kunjungan' => now()
        ]);

        return response()->json([
            'success' => true, 
            'status' => $status, 
            'jarak' => round($jarak, 2),
            'threshold_efektif' => round($threshold_efektif, 2),
            'message' => 'Kunjungan ' . $status
        ]);
    }

    private function haversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earth_radius = 6371000; // Radius of the earth in meters
        
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * asin(sqrt($a));
        $d = $earth_radius * $c;
        
        return $d;
    }
}
