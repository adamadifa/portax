<?php

namespace Database\Seeders;

use App\Models\CoaPortax;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CoaPortaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coas = [
            // 10000 AKTIVA
            [
                'kode_akun' => '10000',
                'nama_akun' => 'AKTIVA',
                'sub_akun' => '0',
                'level' => 0,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 11000 Aktiva Lancar
            [
                'kode_akun' => '11000',
                'nama_akun' => 'Aktiva Lancar',
                'sub_akun' => '10000',
                'level' => 1,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 11100 KAS
            [
                'kode_akun' => '11100',
                'nama_akun' => 'KAS',
                'sub_akun' => '11000',
                'level' => 2,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 11101 Kas Besar
            [
                'kode_akun' => '11101',
                'nama_akun' => 'Kas Besar',
                'sub_akun' => '11100',
                'level' => 3,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 11102 Kas Kecil
            [
                'kode_akun' => '11102',
                'nama_akun' => 'Kas Kecil',
                'sub_akun' => '11100',
                'level' => 3,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 11200 BANK
            [
                'kode_akun' => '11200',
                'nama_akun' => 'BANK',
                'sub_akun' => '11000',
                'level' => 2,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 11201 BNI Giro
            [
                'kode_akun' => '11201',
                'nama_akun' => 'BNI Giro',
                'sub_akun' => '11200',
                'level' => 3,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 11202 BNI Taplus
            [
                'kode_akun' => '11202',
                'nama_akun' => 'BNI Taplus',
                'sub_akun' => '11200',
                'level' => 3,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 11203 BCA Giro
            [
                'kode_akun' => '11203',
                'nama_akun' => 'BCA Giro',
                'sub_akun' => '11200',
                'level' => 3,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 11300 PIUTANG
            [
                'kode_akun' => '11300',
                'nama_akun' => 'PIUTANG',
                'sub_akun' => '11000',
                'level' => 2,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 11301 Account Receivable
            [
                'kode_akun' => '11301',
                'nama_akun' => 'Account Receivable',
                'sub_akun' => '11300',
                'level' => 3,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 11400 PERSEDIAAN
            [
                'kode_akun' => '11400',
                'nama_akun' => 'PERSEDIAAN',
                'sub_akun' => '11000',
                'level' => 2,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 11401 Persediaan Barang Dagangan
            [
                'kode_akun' => '11401', // Corrected from 11301 in the image under 11400 to prevent primary key collision
                'nama_akun' => 'Persediaan Barang Dagangan',
                'sub_akun' => '11400',
                'level' => 3,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 11500 AKTIVA LANCAR LAINNYA
            [
                'kode_akun' => '11500',
                'nama_akun' => 'AKTIVA LANCAR LAINNYA',
                'sub_akun' => '11000',
                'level' => 2,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 11501 Sewa Gedung Dibayar di Muka
            [
                'kode_akun' => '11501',
                'nama_akun' => 'Sewa Gedung Dibayar di Muka',
                'sub_akun' => '11500',
                'level' => 3,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 12000 Aktiva Tetap
            [
                'kode_akun' => '12000',
                'nama_akun' => 'Aktiva Tetap',
                'sub_akun' => '10000',
                'level' => 1,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 12100 KENDARAAN
            [
                'kode_akun' => '12100',
                'nama_akun' => 'KENDARAAN',
                'sub_akun' => '12000',
                'level' => 2,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 12101 Kendaraan
            [
                'kode_akun' => '12101',
                'nama_akun' => 'Kendaraan',
                'sub_akun' => '12100',
                'level' => 3,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 12102 Akumulasi Penyusutan Kendaraan
            [
                'kode_akun' => '12102',
                'nama_akun' => 'Akumulasi Penyusutan Kendaraan',
                'sub_akun' => '12100',
                'level' => 3,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],

            // 20000 PASIVA
            [
                'kode_akun' => '20000',
                'nama_akun' => 'PASIVA',
                'sub_akun' => '0',
                'level' => 0,
                'jenis_akun' => '1',
                'kode_kategori' => 'C00',
            ],
            // 21000 Hutang Usaha
            [
                'kode_akun' => '21000',
                'nama_akun' => 'Hutang Usaha',
                'sub_akun' => '20000',
                'level' => 1,
                'jenis_akun' => '1',
                'kode_kategori' => 'C00',
            ],
            // 21001 Account Payable
            [
                'kode_akun' => '21001',
                'nama_akun' => 'Account Payable',
                'sub_akun' => '21000',
                'level' => 2,
                'jenis_akun' => '1',
                'kode_kategori' => 'C00',
            ],
            // 22000 Hutang Pajak
            [
                'kode_akun' => '22000',
                'nama_akun' => 'Hutang Pajak',
                'sub_akun' => '20000',
                'level' => 1,
                'jenis_akun' => '1',
                'kode_kategori' => 'C00',
            ],
            // 22001 Hutang PPN
            [
                'kode_akun' => '22001',
                'nama_akun' => 'Hutang PPN',
                'sub_akun' => '22000',
                'level' => 2,
                'jenis_akun' => '1',
                'kode_kategori' => 'C00',
            ],
            // 22002 Hutang Pph 21
            [
                'kode_akun' => '22002',
                'nama_akun' => 'Hutang Pph 21',
                'sub_akun' => '22000',
                'level' => 2,
                'jenis_akun' => '1',
                'kode_kategori' => 'C00',
            ],
            // 22003 Hutang Pph 23
            [
                'kode_akun' => '22003',
                'nama_akun' => 'Hutang Pph 23',
                'sub_akun' => '22000',
                'level' => 2,
                'jenis_akun' => '1',
                'kode_kategori' => 'C00',
            ],
            // 22004 Hutang Pph 29
            [
                'kode_akun' => '22004',
                'nama_akun' => 'Hutang Pph 29',
                'sub_akun' => '22000',
                'level' => 2,
                'jenis_akun' => '1',
                'kode_kategori' => 'C00',
            ],
            // 22005 Hutang PPh Final
            [
                'kode_akun' => '22005',
                'nama_akun' => 'Hutang PPh Final',
                'sub_akun' => '22000',
                'level' => 2,
                'jenis_akun' => '1',
                'kode_kategori' => 'C00',
            ],
            // 23000 Biaya Yang Masih Harus di Bayar
            [
                'kode_akun' => '23000',
                'nama_akun' => 'Biaya Yang Masih Harus di Bayar',
                'sub_akun' => '20000',
                'level' => 1,
                'jenis_akun' => '1',
                'kode_kategori' => 'C00',
            ],
            // 23001 Biaya Yang Masih Harus di Bayar
            [
                'kode_akun' => '23001',
                'nama_akun' => 'Biaya Yang Masih Harus di Bayar',
                'sub_akun' => '23000',
                'level' => 2,
                'jenis_akun' => '1',
                'kode_kategori' => 'C00',
            ],

            // 30000 EKUITAS
            [
                'kode_akun' => '30000',
                'nama_akun' => 'EKUITAS',
                'sub_akun' => '0',
                'level' => 0,
                'jenis_akun' => '1',
                'kode_kategori' => 'C00',
            ],
            // 31001 Modal
            [
                'kode_akun' => '31001',
                'nama_akun' => 'Modal',
                'sub_akun' => '30000',
                'level' => 1,
                'jenis_akun' => '1',
                'kode_kategori' => 'C00',
            ],
            // 32001 Retained Earnings
            [
                'kode_akun' => '32001',
                'nama_akun' => 'Retained Earnings',
                'sub_akun' => '30000',
                'level' => 1,
                'jenis_akun' => '1',
                'kode_kategori' => 'C00',
            ],
            // 33001 Laba Tahun Berjalan
            [
                'kode_akun' => '33001',
                'nama_akun' => 'Laba Tahun Berjalan',
                'sub_akun' => '30000',
                'level' => 1,
                'jenis_akun' => '1',
                'kode_kategori' => 'C00',
            ],

            // 40000 PENDAPATAN
            [
                'kode_akun' => '40000',
                'nama_akun' => 'PENDAPATAN',
                'sub_akun' => '0',
                'level' => 0,
                'jenis_akun' => '1',
                'kode_kategori' => 'C00',
            ],
            // 41000 Pendapatan Utama
            [
                'kode_akun' => '41000',
                'nama_akun' => 'Pendapatan Utama',
                'sub_akun' => '40000',
                'level' => 1,
                'jenis_akun' => '1',
                'kode_kategori' => 'C00',
            ],
            // 41001 Penjualan
            [
                'kode_akun' => '41001',
                'nama_akun' => 'Penjualan',
                'sub_akun' => '41000',
                'level' => 2,
                'jenis_akun' => '1',
                'kode_kategori' => 'C00',
            ],
            // 42000 Pendapatan Lainnya
            [
                'kode_akun' => '42000',
                'nama_akun' => 'Pendapatan Lainnya',
                'sub_akun' => '40000',
                'level' => 1,
                'jenis_akun' => '1',
                'kode_kategori' => 'C00',
            ],
            // 42001 Bunga & Jasa Giro
            [
                'kode_akun' => '42001',
                'nama_akun' => 'Bunga & Jasa Giro',
                'sub_akun' => '42000',
                'level' => 2,
                'jenis_akun' => '1',
                'kode_kategori' => 'C00',
            ],

            // 50000 BIAYA
            [
                'kode_akun' => '50000',
                'nama_akun' => 'BIAYA',
                'sub_akun' => '0',
                'level' => 0,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 51000 Beban Penjualan
            [
                'kode_akun' => '51000',
                'nama_akun' => 'Beban Penjualan',
                'sub_akun' => '50000',
                'level' => 1,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 51001 Transport, Parkir Penj
            [
                'kode_akun' => '51001',
                'nama_akun' => 'Transport, Parkir Penj',
                'sub_akun' => '51000',
                'level' => 2,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 51002 Bhn Bkr, Bensin Penj
            [
                'kode_akun' => '51002',
                'nama_akun' => 'Bhn Bkr, Bensin Penj',
                'sub_akun' => '51000',
                'level' => 2,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 51003 Listrik Penj
            [
                'kode_akun' => '51003',
                'nama_akun' => 'Listrik Penj',
                'sub_akun' => '51000',
                'level' => 2,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 51004 Telepon/Fax Penj
            [
                'kode_akun' => '51004',
                'nama_akun' => 'Telepon/Fax Penj',
                'sub_akun' => '51000',
                'level' => 2,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 51005 Penj lainnya
            [
                'kode_akun' => '51005',
                'nama_akun' => 'Penj lainnya',
                'sub_akun' => '51000',
                'level' => 2,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 51006 Perlengkapan Kantor
            [
                'kode_akun' => '51006',
                'nama_akun' => 'Perlengkapan Kantor',
                'sub_akun' => '51000',
                'level' => 2,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 51007 Surat, materai, paket
            [
                'kode_akun' => '51007',
                'nama_akun' => 'Surat, materai, paket',
                'sub_akun' => '51000',
                'level' => 2,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 51008 Alat Tulis Kantor Penj
            [
                'kode_akun' => '51008',
                'nama_akun' => 'Alat Tulis Kantor Penj',
                'sub_akun' => '51000',
                'level' => 2,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 51009 Pajak & Perijinan Penj
            [
                'kode_akun' => '51009',
                'nama_akun' => 'Pajak & Perijinan Penj',
                'sub_akun' => '51000',
                'level' => 2,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 51010 Biaya Adm Bank_Prj
            [
                'kode_akun' => '51010',
                'nama_akun' => 'Biaya Adm Bank_Prj',
                'sub_akun' => '51000',
                'level' => 2,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 51011 Jamsostek/BPJS_Penj
            [
                'kode_akun' => '51011',
                'nama_akun' => 'Jamsostek/BPJS_Penj',
                'sub_akun' => '51000',
                'level' => 2,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 51012 B Pem&Prbtkn Kend Penj
            [
                'kode_akun' => '51012',
                'nama_akun' => 'B Pem&Prbtkn Kend Penj',
                'sub_akun' => '51000',
                'level' => 2,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 52000 Biaya Umum & Administrasi
            [
                'kode_akun' => '52000',
                'nama_akun' => 'Biaya Umum & Administrasi',
                'sub_akun' => '50000',
                'level' => 1,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 52001 Biaya Gaji & Tunjangan
            [
                'kode_akun' => '52001',
                'nama_akun' => 'Biaya Gaji & Tunjangan',
                'sub_akun' => '52000',
                'level' => 2,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 52002 Komisi
            [
                'kode_akun' => '52002',
                'nama_akun' => 'Komisi',
                'sub_akun' => '52000',
                'level' => 2,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 53000 Beban Utiliti, Adm, Sewa & Lainnya
            [
                'kode_akun' => '53000',
                'nama_akun' => 'Beban Utiliti, Adm, Sewa & Lainnya',
                'sub_akun' => '50000',
                'level' => 1,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 53001 Biaya Sewa Gedung
            [
                'kode_akun' => '53001',
                'nama_akun' => 'Biaya Sewa Gedung',
                'sub_akun' => '53000',
                'level' => 2,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 53002 Biaya Sewa Angkutan
            [
                'kode_akun' => '53002',
                'nama_akun' => 'Biaya Sewa Angkutan',
                'sub_akun' => '53000',
                'level' => 2,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 53003 Biaya Umum & Adm Lainnya
            [
                'kode_akun' => '53003',
                'nama_akun' => 'Biaya Umum & Adm Lainnya',
                'sub_akun' => '53000',
                'level' => 2,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 54000 Beban Lainnya
            [
                'kode_akun' => '54000',
                'nama_akun' => 'Beban Lainnya',
                'sub_akun' => '50000',
                'level' => 1,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 54001 Pajak Jasa Giro
            [
                'kode_akun' => '54001',
                'nama_akun' => 'Pajak Jasa Giro',
                'sub_akun' => '54000',
                'level' => 2,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 54002 Jas Perbaikan Kendaraan
            [
                'kode_akun' => '54002',
                'nama_akun' => 'Jas Perbaikan Kendaraan',
                'sub_akun' => '54000',
                'level' => 2,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],

            // 60000 IKHTISAR LABA RUGI
            [
                'kode_akun' => '60000',
                'nama_akun' => 'IKHTISAR LABA RUGI',
                'sub_akun' => '0',
                'level' => 0,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 61000 Ikhtisar Laba Rugi
            [
                'kode_akun' => '61000',
                'nama_akun' => 'Ikhtisar Laba Rugi',
                'sub_akun' => '60000',
                'level' => 1,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ],
            // 61001 Ikhtisar Laba Rugi (corrected to avoid duplicate primary key 61000)
            [
                'kode_akun' => '61001',
                'nama_akun' => 'Ikhtisar Laba Rugi',
                'sub_akun' => '61000',
                'level' => 2,
                'jenis_akun' => null,
                'kode_kategori' => 'C00',
            ]
        ];

        foreach ($coas as $coa) {
            if (!CoaPortax::where('kode_akun', $coa['kode_akun'])->exists()) {
                CoaPortax::create($coa);
            }
        }
    }
}
