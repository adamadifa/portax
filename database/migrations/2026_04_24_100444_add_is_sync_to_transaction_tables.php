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
        Schema::table('keuangan_ledger', function (Blueprint $table) {
            $table->tinyInteger('is_sync')->default(0)->after('keterangan_peruntukan')->comment('0: Manual, 1: Sync from PacificV4');
        });

        Schema::table('keuangan_kaskecil', function (Blueprint $table) {
            $table->tinyInteger('is_sync')->default(0)->after('status_pajak')->comment('0: Manual, 1: Sync from PacificV4');
        });

        Schema::table('accounting_jurnalumum', function (Blueprint $table) {
            $table->tinyInteger('is_sync')->default(0)->after('id_user')->comment('0: Manual, 1: Sync from PacificV4');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('keuangan_ledger', function (Blueprint $table) {
            $table->dropColumn('is_sync');
        });

        Schema::table('keuangan_kaskecil', function (Blueprint $table) {
            $table->dropColumn('is_sync');
        });

        Schema::table('accounting_jurnalumum', function (Blueprint $table) {
            $table->dropColumn('is_sync');
        });
    }
};
