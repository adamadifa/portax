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
        Schema::table('marketing_penjualan', function (Blueprint $table) {
            $table->string('no_fak_new', 20)->nullable()->after('no_faktur');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_penjualan', function (Blueprint $table) {
            $table->dropColumn('no_fak_new');
        });
    }
};
