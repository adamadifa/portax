<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Kuranglebihsetor;
use App\Models\Logamtokertas;
use App\Models\Saldoawalkasbesar;
use App\Models\Setoranpenjualan;
use App\Models\Setoranpusat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class SaldoawalkasbesarController extends Controller
{
    public function index(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');


        $list_bulan = config('global.list_bulan');
        $nama_bulan = config('global.nama_bulan');
        $start_year = config('global.start_year');
        $query = Saldoawalkasbesar::query();
        $query->join('cabang', 'keuangan_kasbesar_saldoawal.kode_cabang', '=', 'cabang.kode_cabang');
        if (!empty($request->bulan)) {
            $query->where('bulan', $request->bulan);
        }
        if (!empty($request->tahun)) {
            $query->where('tahun', $request->tahun);
        } else {
            $query->where('tahun', date('Y'));
        }

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('keuangan_kasbesar_saldoawal.kode_cabang', auth()->user()->kode_cabang);
            }
        }

        $query->orderBy('tahun', 'desc');
        $query->orderBy('bulan');
        $saldo_awal = $query->get();
        return view('keuangan.kasbesar.saldoawal.index', compact('list_bulan', 'start_year', 'saldo_awal', 'nama_bulan'));
    }


    public function create()
    {
        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();

        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $data['roles_show_cabang'] = config('global.roles_show_cabang');
        return view('keuangan.kasbesar.saldoawal.create', $data);
    }

    public function getsaldo(Request $request)
    {
        // Fitur ini tidak lagi digunakan karena Saldo Awal hanya diinput manual via jumlah_saldo.
        return response()->json([
            'success' => true,
            'message' => 'Not used',
            'data'    => []
        ]);
    }

    public function store(Request $request)
    {
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $user = User::findorfail(auth()->user()->id);

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $kode_cabang = $request->kode_cabang;
            } else {
                $kode_cabang = $user->kode_cabang;
            }
        } else {
            $kode_cabang = $request->kode_cabang;
        }
        $kode_saldo_awal = "SA" . $kode_cabang . $request->bulan . substr($request->tahun, 2, 2);
        $tanggal = $request->tahun . "-" . $request->bulan . "-01";
        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }
            //Cek Jika Saldo Sudah Pernah Diinputkan
            $ceksaldo = Saldoawalkasbesar::where('kode_saldo_awal', $kode_saldo_awal)->count();
            if ($ceksaldo > 0) {
                Saldoawalkasbesar::where('kode_saldo_awal', $kode_saldo_awal)->update([
                    'tanggal' => $tanggal,
                    'bulan' => $request->bulan,
                    'tahun' => $request->tahun,
                    'jumlah_saldo' => toNumber($request->jumlah_saldo),
                    'kode_cabang' => $kode_cabang,

                ]);
            } else {
                Saldoawalkasbesar::create([
                    'kode_saldo_awal' => $kode_saldo_awal,
                    'tanggal' => $tanggal,
                    'bulan' => $request->bulan,
                    'tahun' => $request->tahun,
                    'jumlah_saldo' => toNumber($request->jumlah_saldo),
                    'kode_cabang' => $kode_cabang,
                ]);
            }

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {

            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function destroy($kode_saldo_awal)
    {
        $kode_saldo_awal = Crypt::decrypt($kode_saldo_awal);
        DB::beginTransaction();
        try {
            $saldoawalkasbesar = Saldoawalkasbesar::where('kode_saldo_awal', $kode_saldo_awal)->firstOrFail();
            $cektutuplaporan = cektutupLaporan($saldoawalkasbesar->tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            $saldoawalkasbesar->delete();
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
