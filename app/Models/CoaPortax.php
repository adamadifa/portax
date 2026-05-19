<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoaPortax extends Model
{
    use HasFactory;
    protected $table = "coa_portax";
    protected $primaryKey = "kode_akun";
    protected $guarded = [];
    public $incrementing = false;
    protected $keyType = 'string';
}
