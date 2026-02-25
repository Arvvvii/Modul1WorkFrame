<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';

    protected $primaryKey = 'id_barang';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nama',
        'harga',
    ];
}
