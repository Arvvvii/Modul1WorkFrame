<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Regency extends Model {
    protected $table = 'reg_regencies';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
}