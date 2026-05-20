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
        Schema::table('coa', function (Blueprint $table) {
            $table->char('kode_akun_portax', 6)->nullable()->after('nama_akun');
            
            $table->foreign('kode_akun_portax')
                  ->references('kode_akun')
                  ->on('coa_portax')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coa', function (Blueprint $table) {
            $table->dropForeign(['kode_akun_portax']);
            $table->dropColumn('kode_akun_portax');
        });
    }
};
