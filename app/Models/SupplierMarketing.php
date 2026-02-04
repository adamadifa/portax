<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierMarketing extends Model
{
    use HasFactory;
    protected $table = "supplier_marketing";
    protected $primaryKey = "kode_supplier";
    protected $guarded = [];
    public $incrementing  = false;
}
