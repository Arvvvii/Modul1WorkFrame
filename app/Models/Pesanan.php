<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    public $timestamps = false;
    protected $table = 'pesanan';
    protected $primaryKey = 'idpesanan';
    protected $fillable = [
        'nama', 
        'total', 
        'metode_bayar', 
        'status_bayar', 
        'snap_token',
        'created_at',
        'updated_at',
    ];

    public function detail_pesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'idpesanan', 'idpesanan');
    }
}