<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saldoawalbarangproduksi extends Model
{
    use HasFactory;
    protected $table = "produksi_barang_saldoawal";
    protected $primaryKey = "kode_saldo_awal";
    protected $guarded = [];
    public $incrementing  = false;
}
