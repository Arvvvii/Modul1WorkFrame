<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $customers = Customer::with(['province', 'regency', 'district'])->latest()->get();
        return view('customer.index', compact('customers'));
    }

    public function create()
    {
        $provinces = Province::orderBy('name', 'asc')->get();
        return view('customer.create', compact('provinces'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'province_id' => 'required|integer',
            'regency_id' => 'required|integer',
            'district_id' => 'required|integer',
            'postal_code' => 'nullable|string|max:20',
            'foto_blob_data' => 'required|string',
        ]);

        $base64 = preg_replace('/^data:image\/png;base64,/', '', $data['foto_blob_data']);
        $image = base64_decode($base64);

        if ($image === false) {
            return redirect()->back()->withInput()->with('error', 'Foto tidak valid. Silakan ambil ulang foto.');
        }

        $filename = 'customers/customer_' . time() . '.png';
        Storage::disk('public')->put($filename, $image);

        $customer = Customer::create([
            'nama' => $data['nama'],
            'alamat' => $data['alamat'],
            'province_id' => $data['province_id'],
            'regency_id' => $data['regency_id'],
            'district_id' => $data['district_id'],
            'postal_code' => $data['postal_code'],
            'foto_blob' => $image,
            'foto_path' => $filename,
        ]);

        return redirect()->route('customer.index')->with('success', 'Customer berhasil disimpan.');
    }
}
