<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Village extends Model {
    protected $table = 'reg_villages';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
}