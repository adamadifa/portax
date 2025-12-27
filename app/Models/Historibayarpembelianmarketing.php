<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historibayarpembelianmarketing extends Model
{
    use HasFactory;
    protected $table = "marketing_pembelian_historibayar";
    protected $primaryKey = "no_bukti";
    protected $guarded = [];
    public $incrementing = false;
}
