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
        Schema::table('keuangan_kasbesar_saldoawal', function (Blueprint $table) {
            $table->dropColumn(['uang_kertas', 'uang_logam', 'giro', 'transfer']);
            $table->bigInteger('jumlah_saldo')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('keuangan_kasbesar_saldoawal', function (Blueprint $table) {
            $table->dropColumn('jumlah_saldo');
            $table->bigInteger('uang_kertas')->default(0);
            $table->bigInteger('uang_logam')->default(0);
            $table->bigInteger('giro')->default(0);
            $table->bigInteger('transfer')->default(0);
        });
    }
};
