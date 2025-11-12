<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saldoawalmutasiproduksi extends Model
{
    use HasFactory;
    protected $table = "produksi_mutasi_saldoawal";
    protected $primaryKey = "kode_saldo_awal";
    protected $guarded = [];
    public $incrementing  = false;
}
