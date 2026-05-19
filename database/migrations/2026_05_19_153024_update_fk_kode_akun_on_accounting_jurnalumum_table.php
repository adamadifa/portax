<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Perbaiki data test lama terlebih dahulu agar tidak ada conflict (Integrity constraint violation)
        DB::statement("UPDATE accounting_jurnalumum SET kode_akun = '20000' WHERE kode_akun = '2-3500'");
        DB::statement("UPDATE accounting_jurnalumum SET kode_akun = '50000' WHERE kode_akun = '6-1101'");

        Schema::table('accounting_jurnalumum', function (Blueprint $table) {
            // Drop constraint foreign key lama
            $table->dropForeign('accounting_jurnalumum_kode_akun_foreign');
            
            // Tambahkan constraint foreign key baru ke coa_portax
            $table->foreign('kode_akun', 'accounting_jurnalumum_kode_akun_foreign')
                  ->references('kode_akun')->on('coa_portax')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounting_jurnalumum', function (Blueprint $table) {
            // Revert ke coa lama jika di-rollback
            $table->dropForeign('accounting_jurnalumum_kode_akun_foreign');
            
            $table->foreign('kode_akun', 'accounting_jurnalumum_kode_akun_foreign')
                  ->references('kode_akun')->on('coa')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
        });
    }
};
