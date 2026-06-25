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
        DB::transaction(function () {
            // Redirect any references to old Ikhtisar Laba Rugi codes to the new ones
            if (Schema::hasColumn('coa', 'kode_akun_portax')) {
                DB::table('coa')->whereIn('kode_akun_portax', ['61000', '32000'])->update(['kode_akun_portax' => '52000']);
                DB::table('coa')->whereIn('kode_akun_portax', ['61001', '32001'])->update(['kode_akun_portax' => '52001']);
            }
            if (Schema::hasColumn('accounting_jurnalumum', 'kode_akun_portax')) {
                DB::table('accounting_jurnalumum')->whereIn('kode_akun_portax', ['61000', '32000'])->update(['kode_akun_portax' => '52000']);
                DB::table('accounting_jurnalumum')->whereIn('kode_akun_portax', ['61001', '32001'])->update(['kode_akun_portax' => '52001']);
            }
            if (Schema::hasTable('bukubesar_saldoawal_detail')) {
                DB::table('bukubesar_saldoawal_detail')->whereIn('kode_akun', ['61000', '32000'])->update(['kode_akun' => '52000']);
                DB::table('bukubesar_saldoawal_detail')->whereIn('kode_akun', ['61001', '32001'])->update(['kode_akun' => '52001']);
            }

            // Delete 32000 and 32001 from coa_portax table
            DB::table('coa_portax')->whereIn('kode_akun', ['32000', '32001'])->delete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::transaction(function () {
            // Insert 32000
            DB::table('coa_portax')->updateOrInsert(
                ['kode_akun' => '32000'],
                [
                    'nama_akun' => 'Ikhtisar Laba Rugi',
                    'sub_akun' => '30000',
                    'level' => 1,
                    'jenis_akun' => null,
                    'kode_kategori' => 'C00',
                ]
            );

            // Insert 32001
            DB::table('coa_portax')->updateOrInsert(
                ['kode_akun' => '32001'],
                [
                    'nama_akun' => 'Ikhtisar Laba Rugi',
                    'sub_akun' => '32000',
                    'level' => 2,
                    'jenis_akun' => null,
                    'kode_kategori' => 'C00',
                ]
            );
        });
    }
};
