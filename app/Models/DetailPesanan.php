<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
protected $table = 'detail_pesanan';
protected $primaryKey = 'iddetail_pesanan';
protected $fillable = ['idmenu', 'idpesanan', 'jumlah', 'harga', 'subtotal', 'catatan'];
}
