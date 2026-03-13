<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Province extends Model {
    protected $table = 'reg_provinces';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
}