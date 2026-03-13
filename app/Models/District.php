<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class District extends Model {
    protected $table = 'reg_districts';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
}