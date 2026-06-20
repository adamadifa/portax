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
        Schema::table('accounting_jurnalumum', function (Blueprint $table) {
            $table->string('kode_akun_portax', 20)->nullable()->after('kode_akun');
            $table->foreign('kode_akun_portax')->references('kode_akun')->on('coa_portax')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounting_jurnalumum', function (Blueprint $table) {
            $table->dropForeign(['kode_akun_portax']);
            $table->dropColumn('kode_akun_portax');
        });
    }
};
