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
        Schema::table('bukubesar_saldoawal_detail', function (Blueprint $table) {
            $table->dropForeign('bukubesar_saldoawal_detail_kode_akun_foreign');
            
            $table->foreign('kode_akun')->references('kode_akun')
                ->on('coa_portax')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bukubesar_saldoawal_detail', function (Blueprint $table) {
            $table->dropForeign('bukubesar_saldoawal_detail_kode_akun_foreign');
            
            $table->foreign('kode_akun')->references('kode_akun')
                ->on('coa')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }
};
