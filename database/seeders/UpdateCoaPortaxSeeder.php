<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateCoaPortaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // 1. Move old 32001 (Retained Earnings) to 32002 if it exists
            $retainedEarnings = DB::table('coa_portax')->where('kode_akun', '32001')->first();
            if ($retainedEarnings && strtolower($retainedEarnings->nama_akun) === 'retained earnings') {
                // Check if 32002 already exists to prevent duplicate keys
                if (!DB::table('coa_portax')->where('kode_akun', '32002')->exists()) {
                    DB::table('coa_portax')
                        ->where('kode_akun', '32001')
                        ->update([
                            'kode_akun' => '32002',
                            'nama_akun' => 'Retained Earnings',
                            'sub_akun' => '30000',
                            'level' => 1,
                            'jenis_akun' => '1',
                            'kode_kategori' => 'C00',
                        ]);
                }
            }

            // 2. Move 60000 (old IKHTISAR LABA RUGI level 0) to 32000 (Ikhtisar Laba Rugi level 1)
            $old60000 = DB::table('coa_portax')->where('kode_akun', '60000')->first();
            if ($old60000 && strtolower($old60000->nama_akun) === 'ikhtisar laba rugi') {
                DB::table('coa_portax')
                    ->where('kode_akun', '60000')
                    ->update([
                        'kode_akun' => '32000',
                        'nama_akun' => 'Ikhtisar Laba Rugi',
                        'sub_akun' => '30000',
                        'level' => 1,
                        'jenis_akun' => null,
                        'kode_kategori' => 'C00',
                    ]);
            } else {
                // Ensure 32000 exists
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
            }

            // 3. Move 61001 (old Ikhtisar Laba Rugi level 2) to 32001 (Ikhtisar Laba Rugi level 2)
            $old61001 = DB::table('coa_portax')->where('kode_akun', '61001')->first();
            if ($old61001 && strtolower($old61001->nama_akun) === 'ikhtisar laba rugi') {
                DB::table('coa_portax')
                    ->where('kode_akun', '61001')
                    ->update([
                        'kode_akun' => '32001',
                        'nama_akun' => 'Ikhtisar Laba Rugi',
                        'sub_akun' => '32000',
                        'level' => 2,
                        'jenis_akun' => null,
                        'kode_kategori' => 'C00',
                    ]);
            } else {
                // Ensure 32001 exists
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
            }

            // 4. Handle 61000 (old Ikhtisar Laba Rugi level 1)
            // Redirect any foreign key references to 32000 first, then delete 61000
            $old61000 = DB::table('coa_portax')->where('kode_akun', '61000')->first();
            if ($old61000 && strtolower($old61000->nama_akun) === 'ikhtisar laba rugi') {
                // Update references in coa table if any
                if (Schema::hasColumn('coa', 'kode_akun_portax')) {
                    DB::table('coa')->where('kode_akun_portax', '61000')->update(['kode_akun_portax' => '32000']);
                }
                // Update references in accounting_jurnalumum table if any
                if (Schema::hasColumn('accounting_jurnalumum', 'kode_akun_portax')) {
                    DB::table('accounting_jurnalumum')->where('kode_akun_portax', '61000')->update(['kode_akun_portax' => '32000']);
                }
                // Update references in bukubesar_saldoawal_detail table if any
                if (Schema::hasTable('bukubesar_saldoawal_detail')) {
                    DB::table('bukubesar_saldoawal_detail')->where('kode_akun', '61000')->update(['kode_akun' => '32000']);
                }

                DB::table('coa_portax')->where('kode_akun', '61000')->delete();
            }

            // 5. Update BIAYA accounts from 5xxxx to 6xxxx
            $biayaUpdates = [
                // Level 0
                '50000' => ['kode' => '60000', 'nama' => 'BIAYA', 'sub' => '0', 'level' => 0],
                
                // Level 1
                '51000' => ['kode' => '61000', 'nama' => 'Beban Penjualan', 'sub' => '60000', 'level' => 1],
                '52000' => ['kode' => '62000', 'nama' => 'Biaya Umum & Administrasi', 'sub' => '60000', 'level' => 1],
                '53000' => ['kode' => '63000', 'nama' => 'Beban Utiliti, Adm, Sewa & Lainnya', 'sub' => '60000', 'level' => 1],
                '54000' => ['kode' => '64000', 'nama' => 'Beban Lainnya', 'sub' => '60000', 'level' => 1],
                
                // Level 2 (Beban Penjualan)
                '51001' => ['kode' => '61001', 'nama' => 'Transport, Parkir Penj', 'sub' => '61000', 'level' => 2],
                '51002' => ['kode' => '61002', 'nama' => 'Bhn Bkr, Bensin Penj', 'sub' => '61000', 'level' => 2],
                '51003' => ['kode' => '61003', 'nama' => 'Listrik Penj', 'sub' => '61000', 'level' => 2],
                '51004' => ['kode' => '61004', 'nama' => 'Telepon/Fax Penj', 'sub' => '61000', 'level' => 2],
                '51005' => ['kode' => '61005', 'nama' => 'Penj lainnya', 'sub' => '61000', 'level' => 2],
                '51006' => ['kode' => '61006', 'nama' => 'Perlengkapan Kantor', 'sub' => '61000', 'level' => 2],
                '51007' => ['kode' => '61007', 'nama' => 'Surat, materai, paket', 'sub' => '61000', 'level' => 2],
                '51008' => ['kode' => '61008', 'nama' => 'Alat Tulis Kantor Penj', 'sub' => '61000', 'level' => 2],
                '51009' => ['kode' => '61009', 'nama' => 'Pajak & Perijinan Penj', 'sub' => '61000', 'level' => 2],
                '51010' => ['kode' => '61010', 'nama' => 'Biaya Adm Bank_Pnj', 'sub' => '61000', 'level' => 2],
                '51011' => ['kode' => '61011', 'nama' => 'Jamsostek/BPJS_Penj', 'sub' => '61000', 'level' => 2],
                '51012' => ['kode' => '61012', 'nama' => 'B Pem&Prbkn Kend Penj', 'sub' => '61000', 'level' => 2],
                
                // Level 2 (Biaya Umum & Administrasi)
                '52001' => ['kode' => '62001', 'nama' => 'Biaya Gaji & Tunjangan', 'sub' => '62000', 'level' => 2],
                '52002' => ['kode' => '62002', 'nama' => 'Komisi', 'sub' => '62000', 'level' => 2],
                
                // Level 2 (Beban Utiliti, Adm, Sewa & Lainnya)
                '53001' => ['kode' => '63001', 'nama' => 'Biaya Sewa Gedung', 'sub' => '63000', 'level' => 2],
                '53002' => ['kode' => '63002', 'nama' => 'Biaya Sewa Angkutan', 'sub' => '63000', 'level' => 2],
                '53003' => ['kode' => '63003', 'nama' => 'Biaya Umum & Adm Lainnya', 'sub' => '63000', 'level' => 2],
                
                // Level 2 (Beban Lainnya)
                '54001' => ['kode' => '64001', 'nama' => 'Pajak Jasa Giro', 'sub' => '64000', 'level' => 2],
                '54002' => ['kode' => '64002', 'nama' => 'Jasa Perbaikan Kendaraan', 'sub' => '64000', 'level' => 2],
            ];

            foreach ($biayaUpdates as $oldCode => $info) {
                $record = DB::table('coa_portax')->where('kode_akun', $oldCode)->first();
                if ($record) {
                    // If the old code is one of the new Pembelian codes (50000, 51000, 51001)
                    // but it has already been converted/created as Pembelian, we should skip updating it.
                    if (in_array((string) $oldCode, ['50000', '51000', '51001']) && strtolower($record->nama_akun) === 'pembelian') {
                        continue;
                    }

                    DB::table('coa_portax')
                        ->where('kode_akun', $oldCode)
                        ->update([
                            'kode_akun' => $info['kode'],
                            'nama_akun' => $info['nama'],
                            'sub_akun' => $info['sub'],
                            'level' => $info['level'],
                            'jenis_akun' => null,
                            'kode_kategori' => 'C00',
                        ]);
                } else {
                    // Update or insert directly if the target doesn't exist yet
                    $targetExists = DB::table('coa_portax')->where('kode_akun', $info['kode'])->exists();
                    if (!$targetExists) {
                        DB::table('coa_portax')->insert([
                            'kode_akun' => $info['kode'],
                            'nama_akun' => $info['nama'],
                            'sub_akun' => $info['sub'],
                            'level' => $info['level'],
                            'jenis_akun' => null,
                            'kode_kategori' => 'C00',
                        ]);
                    }
                }
            }

            // 6. Create the new PEMBELIAN accounts in the now-vacant 5xxxx range
            $pembelianAccounts = [
                // 50000 PEMBELIAN (level 0)
                [
                    'kode_akun' => '50000',
                    'nama_akun' => 'PEMBELIAN',
                    'sub_akun' => '0',
                    'level' => 0,
                    'jenis_akun' => null,
                    'kode_kategori' => 'C00',
                ],
                // 51000 PEMBELIAN (level 1)
                [
                    'kode_akun' => '51000',
                    'nama_akun' => 'PEMBELIAN',
                    'sub_akun' => '50000',
                    'level' => 1,
                    'jenis_akun' => null,
                    'kode_kategori' => 'C00',
                ],
                // 51001 Pembelian (level 2)
                [
                    'kode_akun' => '51001',
                    'nama_akun' => 'Pembelian',
                    'sub_akun' => '51000',
                    'level' => 2,
                    'jenis_akun' => null,
                    'kode_kategori' => 'C00',
                ],
            ];

            foreach ($pembelianAccounts as $pembelian) {
                DB::table('coa_portax')->updateOrInsert(
                    ['kode_akun' => $pembelian['kode_akun']],
                    $pembelian
                );
            }

            // 7. Update coa table's kode_akun_portax column based on the image mapping
            $coaUpdates = [
                '6-1106' => '61001',
                '6-1108' => '61002',
                '6-1110' => '61003',
                '6-1112' => '61004',
                '6-1116' => '61005',
                '6-1119' => '61006',
                '6-1120' => '61007',
                '6-1121' => '61008',
                '6-1123' => '61009',
                '6-1134' => '61011',
                '6-1135' => '61012',
                '5-1301' => '62001',
                '6-1101' => '62002',
            ];

            foreach ($coaUpdates as $kodeCoa => $kodePortax) {
                DB::table('coa')
                    ->where('kode_akun', $kodeCoa)
                    ->update(['kode_akun_portax' => $kodePortax]);
            }
        });
    }
}

