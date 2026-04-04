<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'idpesanan';
    protected $fillable = [
        'nama', 
        'total', 
        'metode_bayar', 
        'status_bayar', 
        'snap_token'
    ];
}