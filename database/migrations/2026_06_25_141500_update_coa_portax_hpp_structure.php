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
            // Update 50000 to HARGA POKOK PENJUALAN
            DB::table('coa_portax')->updateOrInsert(
                ['kode_akun' => '50000'],
                [
                    'nama_akun' => 'HARGA POKOK PENJUALAN',
                    'sub_akun' => '0',
                    'level' => 0,
                    'jenis_akun' => null,
                    'kode_kategori' => 'C00',
                ]
            );

            // Update 51000 to PEMBELIAN (level 1, sub_akun 50000)
            DB::table('coa_portax')->updateOrInsert(
                ['kode_akun' => '51000'],
                [
                    'nama_akun' => 'PEMBELIAN',
                    'sub_akun' => '50000',
                    'level' => 1,
                    'jenis_akun' => null,
                    'kode_kategori' => 'C00',
                ]
            );

            // Update 51001 to Pembelian (level 2, sub_akun 51000)
            DB::table('coa_portax')->updateOrInsert(
                ['kode_akun' => '51001'],
                [
                    'nama_akun' => 'Pembelian',
                    'sub_akun' => '51000',
                    'level' => 2,
                    'jenis_akun' => null,
                    'kode_kategori' => 'C00',
                ]
            );

            // Insert 52000 Ikhtisar Laba Rugi (level 1, sub_akun 50000)
            DB::table('coa_portax')->updateOrInsert(
                ['kode_akun' => '52000'],
                [
                    'nama_akun' => 'Ikhtisar Laba Rugi',
                    'sub_akun' => '50000',
                    'level' => 1,
                    'jenis_akun' => null,
                    'kode_kategori' => 'C00',
                ]
            );

            // Insert 52001 P/L Persediaan Awal (level 2, sub_akun 52000)
            DB::table('coa_portax')->updateOrInsert(
                ['kode_akun' => '52001'],
                [
                    'nama_akun' => 'P/L Persediaan Awal',
                    'sub_akun' => '52000',
                    'level' => 2,
                    'jenis_akun' => null,
                    'kode_kategori' => 'C00',
                ]
            );

            // Insert 52002 P/L Persediaan Akhir (level 2, sub_akun 52000)
            DB::table('coa_portax')->updateOrInsert(
                ['kode_akun' => '52002'],
                [
                    'nama_akun' => 'P/L Persediaan Akhir',
                    'sub_akun' => '52000',
                    'level' => 2,
                    'jenis_akun' => null,
                    'kode_kategori' => 'C00',
                ]
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::transaction(function () {
            // Revert 50000 to PEMBELIAN
            DB::table('coa_portax')->where('kode_akun', '50000')->update([
                'nama_akun' => 'PEMBELIAN',
                'sub_akun' => '0',
                'level' => 0,
            ]);

            // Revert 51000
            DB::table('coa_portax')->where('kode_akun', '51000')->update([
                'nama_akun' => 'PEMBELIAN',
                'sub_akun' => '50000',
                'level' => 1,
            ]);

            // Revert 51001
            DB::table('coa_portax')->where('kode_akun', '51001')->update([
                'nama_akun' => 'Pembelian',
                'sub_akun' => '51000',
                'level' => 2,
            ]);

            // Delete 52000, 52001, 52002
            DB::table('coa_portax')->whereIn('kode_akun', ['52000', '52001', '52002'])->delete();
        });
    }
};
