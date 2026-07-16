<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Detailmutasigudangcabang;
use App\Models\Jenismutasigudangcabang;
use App\Models\Mutasigudangcabang;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class SuratjalancbgController extends Controller
{
    public function index(Request $request)
    {
        $start_date = config('global.start_date');
        $end_date = config('global.end_date');
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $user = User::findorfail(auth()->user()->id);
        if (!empty($request->dari) && !empty($request->sampai)) {
            if (lockreport($request->dari) == "error") {
                return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
            }
        }


        $query = Mutasigudangcabang::query();
        $query->select(
            'gudang_cabang_mutasi.no_mutasi',
            'gudang_cabang_mutasi.no_surat_jalan',
            'gudang_cabang_mutasi.tanggal',
            'gudang_cabang_mutasi.kode_cabang',
            'nama_cabang',
            'keterangan',
            'gudang_cabang_jenis_mutasi.jenis_mutasi'
        );
        $query->join('cabang', 'gudang_cabang_mutasi.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('gudang_cabang_jenis_mutasi', 'gudang_cabang_mutasi.jenis_mutasi', '=', 'gudang_cabang_jenis_mutasi.kode_jenis_mutasi');
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('gudang_cabang_mutasi.tanggal', [$request->dari, $request->sampai]);
        } else {
            $query->whereBetween('gudang_cabang_mutasi.tanggal', [$start_date, $end_date]);
        }

        if (!empty($request->kode_cabang_search)) {
            $query->where('gudang_cabang_mutasi.kode_cabang', $request->kode_cabang_search);
        }

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('gudang_cabang_mutasi.kode_cabang', auth()->user()->kode_cabang);
            }
        }

        $query->where('gudang_cabang_mutasi.jenis_mutasi', 'SJ');
        $query->orderBy('gudang_cabang_mutasi.tanggal', 'desc');
        $query->orderBy('gudang_cabang_mutasi.created_at', 'desc');
        $suratjalan = $query->paginate(10);
        $suratjalan->appends(request()->all());
        $data['suratjalan'] = $suratjalan;

        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;


        return view('gudangcabang.suratjalan.index', $data);
    }


    public function create()
    {
        $data['produk'] = Produk::orderBy('kode_produk')->where('status_aktif_produk', 1)->get();
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        return view('gudangcabang.suratjalan.create', $data);
    }

    public function store(Request $request)
    {
        $user = User::findorFail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_show_cabang');
        
        $rules = [
            'bulan' => 'required|integer|between:1,12',
            'tahun' => 'required|integer',
        ];
        
        if ($user->hasRole($roles_show_cabang)) {
            $rules['kode_cabang'] = 'required';
        }
        
        $request->validate($rules);
        
        $kode_cabang = $user->hasRole($roles_show_cabang) ? $request->kode_cabang : auth()->user()->kode_cabang;
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $cabang = Cabang::where('kode_cabang', $kode_cabang)->first();
        $kode_pt = $cabang ? $cabang->kode_pt : 'GCP';

        DB::beginTransaction();
        try {
            $produk = Produk::where('status_aktif_produk', 1)->get();
            $daysInMonth = (int)date('t', strtotime("$tahun-$bulan-01"));

            $kode_mutasi_prefix = "SJC" . substr($tahun, 2, 2);
            $lastsuratjalan = Mutasigudangcabang::select('no_mutasi')
                ->where('jenis_mutasi', 'SJ')
                ->whereRaw('YEAR(tanggal)="' . $tahun . '"')
                ->whereRaw('LENGTH(no_mutasi)="10"')
                ->orderBy('no_mutasi', 'desc')
                ->first();
            $last_no_suratjalan = $lastsuratjalan != null ? $lastsuratjalan->no_mutasi : '';

            $sj_prefix = "SJ" . $kode_pt . substr($tahun, 2, 2);
            $lastsj = Mutasigudangcabang::select('no_surat_jalan')
                ->where('no_surat_jalan', 'like', $sj_prefix . '%')
                ->whereRaw('LENGTH(no_surat_jalan) = 11')
                ->orderBy('no_surat_jalan', 'desc')
                ->first();
            $last_no_sj = $lastsj != null ? $lastsj->no_surat_jalan : '';

            $has_changes = false;

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $tanggal = sprintf('%04d-%02d-%02d', $tahun, $bulan, $day);
                
                $day_details = [];
                foreach ($produk as $p) {
                    $kode_p = $p->kode_produk;
                    if (isset($request->jml_dus[$day][$kode_p])) {
                        $dus = toNumber($request->jml_dus[$day][$kode_p]);
                        if ($dus > 0) {
                            $jumlah = (float)$dus * (float)$p->isi_pcs_dus;
                            $day_details[] = [
                                'kode_produk' => $kode_p,
                                'jumlah' => $jumlah
                            ];
                        }
                    }
                }

                $existing_record = Mutasigudangcabang::where('kode_cabang', $kode_cabang)
                    ->where('jenis_mutasi', 'SJ')
                    ->where('tanggal', $tanggal)
                    ->first();

                if (!empty($day_details)) {
                    $cektutuplaporan = cektutupLaporan($tanggal, "gudangcabang");
                    if ($cektutuplaporan > 0) {
                        return Redirect::back()->with(messageError("Periode Laporan Tanggal " . date('d-m-Y', strtotime($tanggal)) . " Sudah Ditutup !"));
                    }

                    if ($existing_record) {
                        $no_suratjalan = $existing_record->no_mutasi;
                        $existing_record->update([
                            'keterangan' => $request->keterangan,
                            'id_user' => auth()->user()->id
                        ]);
                        Detailmutasigudangcabang::where('no_mutasi', $no_suratjalan)->delete();
                    } else {
                        $no_suratjalan = buatkode($last_no_suratjalan, $kode_mutasi_prefix, 5);
                        $last_no_suratjalan = $no_suratjalan;

                        $no_surat_jalan = buatkode($last_no_sj, $sj_prefix, 4);
                        $last_no_sj = $no_surat_jalan;

                        Mutasigudangcabang::create([
                            'no_mutasi'  => $no_suratjalan,
                            'no_surat_jalan' => $no_surat_jalan,
                            'tanggal' => $tanggal,
                            'kode_cabang' => $kode_cabang,
                            'kondisi' => 'G',
                            'in_out_good' => 'I',
                            'in_out_bad' => null,
                            'jenis_mutasi' => 'SJ',
                            'keterangan' => $request->keterangan,
                            'id_user' => auth()->user()->id
                        ]);
                    }

                    $detail_inserts = [];
                    foreach ($day_details as $det) {
                        $detail_inserts[] = [
                            'no_mutasi' => $no_suratjalan,
                            'kode_produk' => $det['kode_produk'],
                            'jumlah' => $det['jumlah']
                        ];
                    }
                    Detailmutasigudangcabang::insert($detail_inserts);
                    $has_changes = true;
                } else {
                    if ($existing_record) {
                        $cektutuplaporan = cektutupLaporan($tanggal, "gudangcabang");
                        if ($cektutuplaporan > 0) {
                            return Redirect::back()->with(messageError("Periode Laporan Tanggal " . date('d-m-Y', strtotime($tanggal)) . " Sudah Ditutup !"));
                        }
                        $existing_record->delete();
                        $has_changes = true;
                    }
                }
            }

            if (!$has_changes) {
                return Redirect::back()->with(messageError('Data Produk Masih Kosong (Tidak ada qty > 0 yang diinput)'));
            }

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($no_mutasi)
    {
        $no_mutasi = Crypt::decrypt($no_mutasi);
        $data['produk'] = Produk::orderBy('kode_produk')
            ->select('produk.kode_produk', 'nama_produk', 'isi_pcs_dus', 'isi_pcs_pack', 'jumlah')
            ->leftJoin(
                DB::raw("(
            SELECT
            kode_produk,jumlah
            FROM
            gudang_cabang_mutasi_detail
            WHERE no_mutasi = '$no_mutasi'
        ) mutasi"),
                function ($join) {
                    $join->on('produk.kode_produk', '=', 'mutasi.kode_produk');
                }
            )
            ->where('status_aktif_produk', 1)->get();
        $data['suratjalan'] = Mutasigudangcabang::select(
            'no_mutasi',
            'no_surat_jalan',
            'tanggal',
            'gudang_cabang_mutasi.jenis_mutasi',
            'keterangan',
            'gudang_cabang_mutasi.kode_cabang'
        )
            ->where('no_mutasi', $no_mutasi)
            ->first();
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        return view('gudangcabang.suratjalan.edit', $data);
    }

    public function update($no_mutasi, Request $request)
    {

        $user = User::findorFail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_show_cabang');
        if ($user->hasRole($roles_show_cabang)) {
            $kode_cabang = $request->kode_cabang;
            $request->validate([
                'tanggal' => 'required',
                'kode_cabang' => 'required',
            ]);
        } else {
            $kode_cabang = auth()->user()->kode_cabang;
            $request->validate([
                'no_surat_jalan' => 'required',
                'tanggal' => 'required'
            ]);
        }
        $no_mutasi = Crypt::decrypt($request->no_mutasi);
        $kode_produk = $request->kode_produk;
        $jml_dus = $request->jml_dus;
        $isi_pcs_dus = $request->isi_pcs_dus;

        DB::beginTransaction();
        try {

            $suratjalan = Mutasigudangcabang::where('no_mutasi', $no_mutasi)->first();

            //Checking
            $cektutuplaporan_suratjalan = cektutupLaporan($suratjalan->tanggal, "gudangcabang");
            if ($cektutuplaporan_suratjalan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }


            $cektutuplaporan = cektutupLaporan($request->tanggal, "gudangcabang");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }



            $detail = [];
            for ($i = 0; $i < count($kode_produk); $i++) {
                $dus = toNumber(!empty($jml_dus[$i]) ? $jml_dus[$i] : 0);

                $jumlah = (float)$dus * (float)$isi_pcs_dus[$i];
                if (!empty($jumlah)) {
                    $detail[]   = [
                        'no_mutasi' => $no_mutasi,
                        'kode_produk' => $kode_produk[$i],
                        'jumlah' => $jumlah
                    ];
                }
            }


            if (empty($detail)) {
                return Redirect::back()->with(messageError('Data Produk Masih Kosong'));
            }
            Detailmutasigudangcabang::where('no_mutasi', $no_mutasi)->delete();

            Mutasigudangcabang::where('no_mutasi', $no_mutasi)->update([
                'no_surat_jalan' => $request->no_surat_jalan,
                'tanggal' => $request->tanggal,
                'kode_cabang' => $kode_cabang,
                'keterangan' => $request->keterangan,
            ]);

            Detailmutasigudangcabang::insert($detail);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            //dd($e);
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function show($no_mutasi)
    {
        $no_mutasi = Crypt::decrypt($no_mutasi);
        $data['mutasi'] = Mutasigudangcabang::select('no_mutasi', 'no_surat_jalan', 'tanggal', 'gudang_cabang_jenis_mutasi.jenis_mutasi', 'keterangan', 'nama_cabang')
            ->join('gudang_cabang_jenis_mutasi', 'gudang_cabang_mutasi.jenis_mutasi', '=', 'gudang_cabang_jenis_mutasi.kode_jenis_mutasi')
            ->join('cabang', 'gudang_cabang_mutasi.kode_cabang', '=', 'cabang.kode_cabang')
            ->where('no_mutasi', $no_mutasi)
            ->first();
        $data['detail'] = Detailmutasigudangcabang::select('gudang_cabang_mutasi_detail.kode_produk', 'nama_produk', 'jumlah', 'isi_pcs_dus', 'isi_pcs_pack')
            ->join('produk', 'gudang_cabang_mutasi_detail.kode_produk', '=', 'produk.kode_produk')
            ->where('no_mutasi', $no_mutasi)
            ->get();
        return view('gudangcabang.suratjalan.show', $data);
    }


    public function destroy($no_mutasi)
    {
        $no_mutasi = Crypt::decrypt($no_mutasi);
        $suratjalan = Mutasigudangcabang::where('no_mutasi', $no_mutasi)->first();
        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($suratjalan->tanggal, "gudangcabang");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            //Hapus Surat Jalan
            Mutasigudangcabang::where('no_mutasi', $no_mutasi)->delete();
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function getExistingData(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $kode_cabang = $request->kode_cabang;

        if (empty($bulan) || empty($tahun) || empty($kode_cabang)) {
            return response()->json([]);
        }

        $startDate = sprintf('%04d-%02d-01', $tahun, $bulan);
        $endDate = date('Y-m-t', strtotime($startDate));

        $produkMap = Produk::where('status_aktif_produk', 1)->pluck('isi_pcs_dus', 'kode_produk')->toArray();

        $data = DB::table('gudang_cabang_mutasi')
            ->join('gudang_cabang_mutasi_detail', 'gudang_cabang_mutasi.no_mutasi', '=', 'gudang_cabang_mutasi_detail.no_mutasi')
            ->where('gudang_cabang_mutasi.kode_cabang', $kode_cabang)
            ->where('gudang_cabang_mutasi.jenis_mutasi', 'SJ')
            ->whereBetween('gudang_cabang_mutasi.tanggal', [$startDate, $endDate])
            ->select(
                DB::raw('DAY(gudang_cabang_mutasi.tanggal) as day'),
                'gudang_cabang_mutasi_detail.kode_produk',
                'gudang_cabang_mutasi_detail.jumlah'
            )
            ->get();

        $result = [];
        foreach ($data as $row) {
            $day = (int)$row->day;
            $kode_produk = $row->kode_produk;
            $isi_pcs_dus = isset($produkMap[$kode_produk]) ? (float)$produkMap[$kode_produk] : 0;
            
            $qty_dus = $isi_pcs_dus > 0 ? ($row->jumlah / $isi_pcs_dus) : 0;
            
            $result[$day][$kode_produk] = $qty_dus;
        }

        return response()->json($result);
    }
}
