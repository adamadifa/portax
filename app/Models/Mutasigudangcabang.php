<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mutasigudangcabang extends Model
{
    use HasFactory;
    protected $table = "gudang_cabang_mutasi";
    protected $primaryKey = "no_mutasi";
    protected $guarded = [];
    public $incrementing = false;
}
