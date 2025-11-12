<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Itempenilaian extends Model
{
    use HasFactory;
    protected $table = "hrd_penilaian_item";
    protected $primaryKey = "kode_item";
    protected $guarded = [];
    public $incrementing  = false;
}
