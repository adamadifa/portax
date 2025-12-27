<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelianmarketing extends Model
{
    use HasFactory;
    protected $table = "marketing_pembelian";
    protected $primaryKey = "no_bukti";
    protected $guarded = [];
    public $incrementing = false;
}
