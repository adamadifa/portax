<?php

namespace Database\Seeders;

use App\Models\Saldoawalbukubesar;
use App\Models\Detailsaldoawalbukubesar;
use Illuminate\Database\Seeder;

class SaldoawalbukubesarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kode_saldo_awal = "SABDG012026";
        
        // Remove existing record if any to ensure the seeder is re-runnable
        Saldoawalbukubesar::where('kode_saldo_awal', $kode_saldo_awal)->delete();

        Saldoawalbukubesar::create([
            'kode_saldo_awal' => $kode_saldo_awal,
            'kode_cabang' => 'BDG',
            'tanggal' => '2026-01-01',
            'bulan' => '01',
            'tahun' => '2026',
        ]);

        $details = [
            [
                'kode_saldo_awal' => $kode_saldo_awal,
                'kode_akun' => '11101', // Kas Besar
                'jumlah' => 149722605.67,
            ],
            [
                'kode_saldo_awal' => $kode_saldo_awal,
                'kode_akun' => '11201', // BNI Giro
                'jumlah' => 15836560.00,
            ],
            [
                'kode_saldo_awal' => $kode_saldo_awal,
                'kode_akun' => '11202', // BNI Taplus
                'jumlah' => 1135028.00,
            ],
            [
                'kode_saldo_awal' => $kode_saldo_awal,
                'kode_akun' => '11203', // BCA Giro
                'jumlah' => 42762912.62,
            ],
            [
                'kode_saldo_awal' => $kode_saldo_awal,
                'kode_akun' => '11301', // Account Receivable
                'jumlah' => 188121575.60,
            ],
            [
                'kode_saldo_awal' => $kode_saldo_awal,
                'kode_akun' => '11401', // Persediaan Barang Dagangan
                'jumlah' => 554664168.86,
            ],
            [
                'kode_saldo_awal' => $kode_saldo_awal,
                'kode_akun' => '21001', // Account Payable
                'jumlah' => 1067866926.15,
            ],
            [
                'kode_saldo_awal' => $kode_saldo_awal,
                'kode_akun' => '22001', // Hutang PPN
                'jumlah' => 38602437.00,
            ],
            [
                'kode_saldo_awal' => $kode_saldo_awal,
                'kode_akun' => '22002', // Hutang Pph 21
                'jumlah' => 4407392.00,
            ],
            [
                'kode_saldo_awal' => $kode_saldo_awal,
                'kode_akun' => '22003', // Hutang Pph 23
                'jumlah' => 8000.00,
            ],
            [
                'kode_saldo_awal' => $kode_saldo_awal,
                'kode_akun' => '22004', // Hutang Pph 29
                'jumlah' => 71551278.79,
            ],
            [
                'kode_saldo_awal' => $kode_saldo_awal,
                'kode_akun' => '23001', // Biaya Yang Masih Harus di Bayar
                'jumlah' => 609187000.00,
            ],
            [
                'kode_saldo_awal' => $kode_saldo_awal,
                'kode_akun' => '31001', // Modal
                'jumlah' => 200000000.00,
            ],
            [
                'kode_saldo_awal' => $kode_saldo_awal,
                'kode_akun' => '32001', // Retained Earnings
                'jumlah' => -1195726646.21,
            ],
            [
                'kode_saldo_awal' => $kode_saldo_awal,
                'kode_akun' => '33001', // Laba Tahun Berjalan
                'jumlah' => 227897741.82,
            ],
        ];

        foreach ($details as $detail) {
            Detailsaldoawalbukubesar::create($detail);
        }
    }
}
