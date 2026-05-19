<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coa_portax', function (Blueprint $table) {
            $table->char('kode_akun', 6)->primary();
            $table->string('nama_akun');
            $table->char('sub_akun', 6)->nullable();
            $table->smallInteger('level')->nullable();
            $table->char('jenis_akun', 1)->nullable();
            $table->char('kode_kategori', 3)->nullable();
            $table->char('kode_transaksi', 6)->nullable();
            $table->char('kode_cabang_coa', 6)->nullable();
            $table->timestamps();

            $table->index('sub_akun');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coa_portax');
    }
};
