<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SyncStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menandai data Kas Kecil yang sudah ada sebagai hasil sync
        $kaskecil = DB::table('keuangan_kaskecil')->where('is_sync', 0)->update(['is_sync' => 1]);
        echo "Updated {$kaskecil} records in keuangan_kaskecil\n";

        // Menandai data Ledger yang sudah ada sebagai hasil sync
        $ledger = DB::table('keuangan_ledger')->where('is_sync', 0)->update(['is_sync' => 1]);
        echo "Updated {$ledger} records in keuangan_ledger\n";

        // Menandai data Jurnal Umum yang sudah ada sebagai hasil sync
        $jurnalumum = DB::table('accounting_jurnalumum')->where('is_sync', 0)->update(['is_sync' => 1]);
        echo "Updated {$jurnalumum} records in accounting_jurnalumum\n";
    }
}
