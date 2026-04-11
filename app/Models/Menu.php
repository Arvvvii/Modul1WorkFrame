<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';
    protected $primaryKey = 'idmenu';
    protected $fillable = ['nama_menu', 'harga', 'path_gambar', 'idvendor'];

    // TAMBAHKAN INI: Agar bisa memanggil $menu->vendor->nama_vendor
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'idvendor', 'idvendor');
    }
}