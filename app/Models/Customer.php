<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'alamat',
        'province_id',
        'regency_id',
        'district_id',
        'postal_code',
        'foto_blob',
        'foto_path',
    ];

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function regency()
    {
        return $this->belongsTo(Regency::class, 'regency_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function getFotoBlobBase64Attribute()
    {
        return $this->foto_blob ? 'data:image/png;base64,' . base64_encode($this->foto_blob) : null;
    }
}
