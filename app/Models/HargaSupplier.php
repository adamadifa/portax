<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HargaSupplier extends Model
{
    use HasFactory;

    protected $table = 'harga_supplier';
    protected $primaryKey = 'kode_produk';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'kode_produk', 'kode_produk');
    }
}
