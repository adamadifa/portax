<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoaPortaxMappingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coas = DB::table('coa')->get();
        foreach ($coas as $coa) {
            $kode_akun = $coa->kode_akun;
            $kode_portax = null;

            if (preg_match('/^(\d+)-(\d+)$/', $kode_akun, $matches)) {
                $prefix = (int)$matches[1];
                $suffix = (int)$matches[2];

                if ($prefix === 1) {
                    if ($suffix === 1101) {
                        $kode_portax = '11101';
                    } elseif ($suffix >= 1102 && $suffix <= 1122) {
                        $kode_portax = '11102';
                    } elseif ($suffix >= 1200 && $suffix <= 1299) {
                        $name = strtolower($coa->nama_akun);
                        if (str_contains($name, 'bca')) {
                            $kode_portax = '11203';
                        } elseif (str_contains($name, 'bni taplus')) {
                            $kode_portax = '11202';
                        } elseif (str_contains($name, 'bni')) {
                            $kode_portax = '11201';
                        } else {
                            $kode_portax = '11200';
                        }
                    } elseif ($suffix >= 1401 && $suffix <= 1499) {
                        $kode_portax = '11301';
                    } elseif ($suffix >= 1705 && $suffix <= 1749) {
                        $kode_portax = '11501';
                    }
                } elseif ($prefix === 2) {
                    if ($suffix === 2300) {
                        $kode_portax = '22001';
                    } elseif ($suffix === 2200) {
                        $kode_portax = '22002';
                    } elseif ($suffix === 2500) {
                        $kode_portax = '22003';
                    } elseif ($suffix === 2400) {
                        $kode_portax = '22005';
                    }
                } elseif ($prefix === 3) {
                    if ($suffix === 0) {
                        $kode_portax = '31001';
                    } elseif ($suffix === 1000) {
                        $kode_portax = '32001';
                    } elseif ($suffix === 2000) {
                        $kode_portax = '33001';
                    }
                } elseif ($prefix === 4) {
                    if ($suffix >= 1000 && $suffix <= 1500) {
                        $kode_portax = '41001';
                    }
                } elseif ($prefix === 5) {
                    if ($suffix === 1301) {
                        $kode_portax = '62001';
                    } elseif ($suffix === 3000) {
                        $kode_portax = '61001';
                    }
                } elseif ($prefix === 6) {
                    if ($suffix === 1106) {
                        $kode_portax = '61001';
                    } elseif ($suffix === 1108) {
                        $kode_portax = '61002';
                    } elseif ($suffix === 1110) {
                        $kode_portax = '61003';
                    } elseif ($suffix === 1112) {
                        $kode_portax = '61004';
                    } elseif ($suffix === 1116) {
                        $kode_portax = '61005';
                    } elseif ($suffix === 1119) {
                        $kode_portax = '61006';
                    } elseif ($suffix === 1120) {
                        $kode_portax = '61007';
                    } elseif ($suffix === 1121) {
                        $kode_portax = '61008';
                    } elseif ($suffix === 1123) {
                        $kode_portax = '61009';
                    } elseif ($suffix === 1134) {
                        $kode_portax = '61010';
                    } elseif ($suffix === 1135) {
                        $kode_portax = '61011';
                    } elseif ($suffix === 1205) {
                        $kode_portax = '61012';
                    } elseif ($suffix === 1101) {
                        $kode_portax = '62002';
                    }
                } elseif ($prefix === 8) {
                    if ($suffix === 1000) {
                        $kode_portax = '42001';
                    }
                }
            }

            if ($kode_portax !== null) {
                DB::table('coa')
                    ->where('kode_akun', $kode_akun)
                    ->update(['kode_akun_portax' => $kode_portax]);
            }
        }
    }
}
