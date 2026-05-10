<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Toko extends Model
{
    protected $table = 'toko';
    protected $primaryKey = 'idtoko';
    public $timestamps = true;

    protected $fillable = [
        'barcode', 
        'nama_toko', 
        'alamat', 
        'latitude', 
        'longitude', 
        'accuracy'
    ];
}
