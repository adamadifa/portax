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
        // Drop foreign key first to allow changing column type
        Schema::table('bukubesar_saldoawal_detail', function (Blueprint $table) {
            $table->dropForeign('bukubesar_saldoawal_detail_kode_saldo_awal_foreign');
        });

        // Change column length in both tables
        Schema::table('bukubesar_saldoawal', function (Blueprint $table) {
            $table->string('kode_saldo_awal', 20)->change();
            $table->char('kode_cabang', 3)->after('kode_saldo_awal')->nullable();
            $table->foreign('kode_cabang')->references('kode_cabang')->on('cabang')->onDelete('restrict')->onUpdate('cascade');
        });

        Schema::table('bukubesar_saldoawal_detail', function (Blueprint $table) {
            $table->string('kode_saldo_awal', 20)->change();
        });

        // Update existing records to default branch 'PST'
        DB::table('bukubesar_saldoawal')->update(['kode_cabang' => 'PST']);

        // Update existing kode_saldo_awal to include 'PST' for consistency
        // e.g. SA012024 -> SAPST012024
        $records = DB::table('bukubesar_saldoawal')->get();
        foreach ($records as $record) {
            $new_code = "SAPST" . substr($record->kode_saldo_awal, 2);
            DB::table('bukubesar_saldoawal_detail')->where('kode_saldo_awal', $record->kode_saldo_awal)->update(['kode_saldo_awal' => $new_code]);
            DB::table('bukubesar_saldoawal')->where('kode_saldo_awal', $record->kode_saldo_awal)->update(['kode_saldo_awal' => $new_code]);
        }

        // Recreate foreign key
        Schema::table('bukubesar_saldoawal_detail', function (Blueprint $table) {
            $table->foreign('kode_saldo_awal')->references('kode_saldo_awal')->on('bukubesar_saldoawal')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bukubesar_saldoawal_detail', function (Blueprint $table) {
            $table->dropForeign('bukubesar_saldoawal_detail_kode_saldo_awal_foreign');
        });

        Schema::table('bukubesar_saldoawal', function (Blueprint $table) {
            $table->dropForeign(['kode_cabang']);
            $table->dropColumn('kode_cabang');
            $table->char('kode_saldo_awal', 8)->change();
        });

        Schema::table('bukubesar_saldoawal_detail', function (Blueprint $table) {
            $table->char('kode_saldo_awal', 8)->change();
            $table->foreign('kode_saldo_awal')->references('kode_saldo_awal')->on('bukubesar_saldoawal')->onDelete('cascade')->onUpdate('cascade');
        });
    }

};
