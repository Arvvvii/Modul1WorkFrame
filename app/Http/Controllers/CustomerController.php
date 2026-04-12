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
        return $this->createBlob();
    }

    public function createBlob()
    {
        $provinces = Province::orderBy('name', 'asc')->get();

        return view('customer.create', [
            'provinces' => $provinces,
            'mode' => 'blob',
            'pageTitle' => 'Tambah Customer 1',
            'subtitle' => 'Simpan ke database (BLOB)',
            'storeRoute' => route('customer.store.blob'),
            'inputName' => 'foto_blob_data',
        ]);
    }

    public function createFile()
    {
        $provinces = Province::orderBy('name', 'asc')->get();

        return view('customer.create', [
            'provinces' => $provinces,
            'mode' => 'file',
            'pageTitle' => 'Tambah Customer 2',
            'subtitle' => 'Simpan ke storage, path disimpan di database',
            'storeRoute' => route('customer.store.file'),
            'inputName' => 'foto_path_data',
        ]);
    }

    public function store(Request $request)
    {
        return $this->storeBlob($request);
    }

    public function storeBlob(Request $request)
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

        $image = $this->decodeBase64Image($data['foto_blob_data']);

        if ($image === false) {
            return redirect()->back()->withInput()->with('error', 'Foto tidak valid. Silakan ambil ulang foto.');
        }

        Customer::create([
            'nama' => $data['nama'],
            'alamat' => $data['alamat'],
            'province_id' => $data['province_id'],
            'regency_id' => $data['regency_id'],
            'district_id' => $data['district_id'],
            'postal_code' => $data['postal_code'],
            'foto_blob' => $image,
            'foto_path' => null,
        ]);

        return redirect()->route('customer.index')->with('success', 'Customer berhasil disimpan ke database (BLOB).');
    }

    public function storeFile(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'province_id' => 'required|integer',
            'regency_id' => 'required|integer',
            'district_id' => 'required|integer',
            'postal_code' => 'nullable|string|max:20',
            'foto_path_data' => 'required|string',
        ]);

        $image = $this->decodeBase64Image($data['foto_path_data']);

        if ($image === false) {
            return redirect()->back()->withInput()->with('error', 'Foto tidak valid. Silakan ambil ulang foto.');
        }

        $filename = 'customers/customer_' . time() . '_' . uniqid() . '.png';
        Storage::disk('public')->put($filename, $image);

        Customer::create([
            'nama' => $data['nama'],
            'alamat' => $data['alamat'],
            'province_id' => $data['province_id'],
            'regency_id' => $data['regency_id'],
            'district_id' => $data['district_id'],
            'postal_code' => $data['postal_code'],
            'foto_blob' => null,
            'foto_path' => $filename,
        ]);

        return redirect()->route('customer.index')->with('success', 'Customer berhasil disimpan ke storage (Path).');
    }

    protected function decodeBase64Image(string $data)
    {
        $base64 = preg_replace('/^data:image\/(png|jpeg|jpg);base64,/', '', $data);
        return base64_decode($base64);
    }
}
