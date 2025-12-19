<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateDomainCabangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "=== Update Domain Cabang Seeder ===\n\n";
        
        // Tampilkan semua nama PT yang ada di database untuk referensi
        $existingPt = DB::table('cabang')
            ->select('nama_pt')
            ->distinct()
            ->orderBy('nama_pt')
            ->pluck('nama_pt')
            ->toArray();
        
        if (!empty($existingPt)) {
            echo "Daftar Nama PT yang ada di database:\n";
            foreach ($existingPt as $pt) {
                echo "  - {$pt}\n";
            }
            echo "\n";
        }
        
        // Mapping nama PT ke domain
        $domainMapping = [
            'PT. INTIRASA PANGAN PERSADA' => 'intirasapanganpersada.portax.site',
            'PT. ASIA BOGOR DISTRIBUSI' => 'asiabogordistribusi.portax.site',
            'PT. SUBUR MAKMUR UTAMA' => 'suburmakmurutama.portax.site',
            'PT. BANTEN MAJU JAYA' => 'bantenmajujaya.portax.site',
            'PT. SUBUR MAKMUR ALAMI' => 'suburmakmuralami.portax.site',
            'PT. GARUT CAHAYA PERKASA' => 'garutcahayaperkasa.portax.site',
            'PT. INTI SARI CAPSAICINDO' => 'intisaricapsaicindo.portax.site',
            'PT. INTIRASA ALAMI SEJAHTERA' => 'intirasalamisejahtera.portax.site',
            'PT. CAKRAWALA PANGAN DISTRIBUSI' => 'cakrawalapangandistribusi.portax.site',
            'PT. LANGGENG KARYA ABHINAYA' => 'langgengkaryaabhinaya.portax.site',
            'PT. RASA UTAMA GEMILANG' => 'rasautamagemilang.portax.site',
            'PT. PANGAN SEMARANG SEJAHTERA' => 'pangansemarangsejahtera.portax.site',
            'PT. MAKMUR ANUGRAH DISTRIBUSINDO' => 'makmuranugrahdistribusindo.portax.site',
            'PT. CAHAYA RIANG GALUNGGUNG' => 'cahayarianggalunggung.portax.site',
        ];

        $updated = 0;
        $notFound = [];

        foreach ($domainMapping as $namaPt => $domain) {
            // Normalisasi nama PT untuk pencocokan (hapus spasi berlebih, ubah ke uppercase)
            $normalizedNamaPt = strtoupper(trim($namaPt));
            
            // Coba beberapa variasi pencocokan
            $result = DB::table('cabang')
                ->whereRaw('UPPER(TRIM(nama_pt)) = ?', [$normalizedNamaPt])
                ->update(['domain' => $domain]);

            if ($result > 0) {
                $updated += $result;
                echo "✓ Updated {$result} cabang dengan nama PT: {$namaPt} → {$domain}\n";
            } else {
                // Coba dengan LIKE sebagai fallback
                $resultLike = DB::table('cabang')
                    ->whereRaw('UPPER(TRIM(nama_pt)) LIKE ?', ['%' . str_replace(' ', '%', $normalizedNamaPt) . '%'])
                    ->update(['domain' => $domain]);
                
                if ($resultLike > 0) {
                    $updated += $resultLike;
                    echo "✓ Updated {$resultLike} cabang dengan nama PT (fuzzy match): {$namaPt} → {$domain}\n";
                } else {
                    $notFound[] = $namaPt;
                    echo "⚠ Tidak ditemukan cabang dengan nama PT: {$namaPt}\n";
                }
            }
        }

        echo "\n";
        echo "Total cabang yang diupdate: {$updated}\n";
        
        if (!empty($notFound)) {
            echo "\nNama PT yang tidak ditemukan:\n";
            foreach ($notFound as $pt) {
                echo "  - {$pt}\n";
            }
        }
    }
}
