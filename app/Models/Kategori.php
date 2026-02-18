<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';
    protected $primaryKey = 'idkategori';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'nama_kategori',
    ];

    public function bukus()
    {
        return $this->hasMany(Buku::class, 'idkategori', 'idkategori');
    }
}
