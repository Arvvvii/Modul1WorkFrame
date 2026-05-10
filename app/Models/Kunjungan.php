<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    protected $table = 'kunjungan';
    protected $primaryKey = 'idkunjungan';
    public $timestamps = true;

    protected $fillable = [
        'idvendor', 
        'idtoko', 
        'latitude_vendor', 
        'longitude_vendor', 
        'accuracy_vendor', 
        'jarak', 
        'threshold', 
        'threshold_efektif', 
        'status', 
        'waktu_kunjungan'
    ];

    public function toko()
    {
        return $this->belongsTo(Toko::class, 'idtoko', 'idtoko');
    }
}
