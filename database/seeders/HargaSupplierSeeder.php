<?php

namespace Database\Seeders;

use App\Models\HargaSupplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HargaSupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['kode_produk' => 'AR', 'harga' => 358700],
            ['kode_produk' => 'AS', 'harga' => 482800],
            ['kode_produk' => 'AB', 'harga' => 724200],
            ['kode_produk' => 'CBR', 'harga' => 482433],
            ['kode_produk' => 'P1000', 'harga' => 91892],
            ['kode_produk' => 'PP500', 'harga' => 55135],
            ['kode_produk' => 'SC', 'harga' => 129200],
            ['kode_produk' => 'SS500', 'harga' => 28333],
            ['kode_produk' => 'BB', 'harga' => 33150],
            ['kode_produk' => 'BP500', 'harga' => 45946],
            ['kode_produk' => 'DEP', 'harga' => 40375],
            ['kode_produk' => 'SP', 'harga' => 68919],
            ['kode_produk' => 'SP500', 'harga' => 36757],
            ['kode_produk' => 'SP8', 'harga' => 52275],
        ];

        foreach ($data as $d) {
            HargaSupplier::updateOrCreate(
                ['kode_produk' => $d['kode_produk']],
                ['harga' => $d['harga']]
            );
        }
    }
}
