<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permintaanproduksi extends Model
{
    use HasFactory;
    protected $table = "produksi_permintaan";
    protected $primaryKey = "no_permintaan";
    protected $guarded = [];
    public $incrementing = false;
}
