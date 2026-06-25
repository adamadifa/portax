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
        Schema::table('marketing_pembelian', function (Blueprint $table) {
            $table->char('kode_akun_ppn', 6)->default('11601');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_pembelian', function (Blueprint $table) {
            $table->dropColumn('kode_akun_ppn');
        });
    }
};
