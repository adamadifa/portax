<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwalkerja extends Model
{
    use HasFactory;
    protected $table = "hrd_jadwalkerja";
    protected $primaryKey = "kode_jadwal";
    protected $guarded = [];
    public $incrementing  = false;
}
