<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coa extends Model
{
    use HasFactory;
    protected $table = "coa";
    protected $primaryKey = "kode_akun";
    protected $guarded = [];
    public $incrementing = false;

    public function coaPortax()
    {
        return $this->belongsTo(CoaPortax::class, 'kode_akun_portax', 'kode_akun');
    }
}
