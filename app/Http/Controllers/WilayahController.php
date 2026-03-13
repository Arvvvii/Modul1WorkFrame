<?php
namespace App\Http\Controllers;

use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;
use Illuminate\Http\Request;

class WilayahController extends Controller {
    public function index() {
        $provinces = Province::orderBy('name', 'asc')->get();
        return view('wilayah.index', compact('provinces'));
    }

    public function getRegencies($province_id) {
        // Relasi: reg_regencies.province_id -> reg_provinces.id
        $data = Regency::where('province_id', $province_id)->orderBy('name', 'asc')->get();
        return response()->json($data);
    }

    public function getDistricts($regency_id) {
        // Relasi: reg_districts.regency_id -> reg_regencies.id
        $data = District::where('regency_id', $regency_id)->orderBy('name', 'asc')->get();
        return response()->json($data);
    }

    public function getVillages($district_id) {
        // Relasi: reg_villages.district_id -> reg_districts.id
        $data = Village::where('district_id', $district_id)->orderBy('name', 'asc')->get();
        return response()->json($data);
    }
}