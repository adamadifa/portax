<?php

namespace App\Http\Controllers;

use App\Models\CoaPortax;
use App\Models\Detailpembelian;
use App\Models\Detailpenjualan;
use App\Models\Detailretur;
use App\Models\Detailsaldoawalbukubesar;
use App\Models\Historibayarpenjualan;
use App\Models\Jurnalkoreksi;
use App\Models\Jurnalumum;
use App\Models\Kaskecil;
use App\Models\Ledger;
use App\Models\Pembelian;
use App\Models\Penjualan;
use App\Models\Saldoawalbukubesar;
use App\Models\Cabang;
use App\Models\Coa;
use App\Models\Pembelianmarketing;
use App\Models\Detailpembelianmarketing;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class SaldoawalbukubesarController extends Controller
{
    public function index(Request $request)
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['nama_bulan'] = config('global.nama_bulan');
        $data['start_year'] = config('global.start_year');

        // Check user's branch
        if (auth()->user()->kode_cabang != 'PST' && !empty(auth()->user()->kode_cabang)) {
            $kode_cabang = auth()->user()->kode_cabang;
            $request->merge(['kode_cabang' => $kode_cabang]);
        } else {
            $kode_cabang = $request->kode_cabang;
        }

        $query = Saldoawalbukubesar::query();
        $query->join('cabang', 'bukubesar_saldoawal.kode_cabang', '=', 'cabang.kode_cabang');
        if ($request->has('bulan')) {
            $query->where('bulan', $request->bulan);
        }
        if ($request->has('tahun')) {
            $query->where('tahun', $request->tahun);
        } else {
            $query->where('tahun', date('Y'));
        }

        if (!empty($kode_cabang)) {
            $query->where('bukubesar_saldoawal.kode_cabang', $kode_cabang);
        }

        $query->orderBy('bulan', 'asc');
        $data['saldoawalbukubesar'] = $query->get();
        $cabang = new Cabang();
        $data['cabang'] = $cabang->getCabang();
        return view('accounting.saldoawalbukubesar.index', $data);
    }

    public function create()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');

        if (auth()->user()->kode_cabang != 'PST' && !empty(auth()->user()->kode_cabang)) {
            $data['cek_saldo_awal'] = Saldoawalbukubesar::where('kode_cabang', auth()->user()->kode_cabang)->count();
        } else {
            $data['cek_saldo_awal'] = Saldoawalbukubesar::count();
        }

        $data['coa'] = CoaPortax::orderby('kode_akun', 'asc')
            ->whereNotIn('kode_akun', ['1', '0-0000'])
            ->get();
        $cabang = new Cabang();
        $data['cabang'] = $cabang->getCabang();
        return view('accounting.saldoawalbukubesar.create', $data);
    }

    public function show($kode_saldo_awal)
    {
        $kode_saldo_awal = Crypt::decrypt($kode_saldo_awal);
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $data['nama_bulan'] = config('global.nama_bulan');
        $data['saldoawalbukubesar'] = Saldoawalbukubesar::join('cabang', 'bukubesar_saldoawal.kode_cabang', '=', 'cabang.kode_cabang')
            ->where('kode_saldo_awal', $kode_saldo_awal)->first();
        $data['detailsaldoawalbukubesar'] = Detailsaldoawalbukubesar::join('coa_portax', 'bukubesar_saldoawal_detail.kode_akun', '=', 'coa_portax.kode_akun')->where('bukubesar_saldoawal_detail.kode_saldo_awal', $kode_saldo_awal)->get();
        return view('accounting.saldoawalbukubesar.show', $data);
    }

    public function edit($kode_saldo_awal)
    {
        $kode_saldo_awal = Crypt::decrypt($kode_saldo_awal);
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $data['nama_bulan'] = config('global.nama_bulan');
        $data['saldoawalbukubesar'] = Saldoawalbukubesar::join('cabang', 'bukubesar_saldoawal.kode_cabang', '=', 'cabang.kode_cabang')
            ->where('kode_saldo_awal', $kode_saldo_awal)->first();
        $data['detailsaldoawalbukubesar'] = Detailsaldoawalbukubesar::join('coa_portax', 'bukubesar_saldoawal_detail.kode_akun', '=', 'coa_portax.kode_akun')->where('bukubesar_saldoawal_detail.kode_saldo_awal', $kode_saldo_awal)->get();
        $data['coa'] = CoaPortax::orderby('kode_akun', 'asc')
            ->whereNotIn('kode_akun', ['1', '0-0000'])
            ->get();
        return view('accounting.saldoawalbukubesar.edit', $data);
    }

    public function store(Request $request)
    {
        if (auth()->user()->kode_cabang != 'PST' && !empty(auth()->user()->kode_cabang)) {
            $request->merge(['kode_cabang' => auth()->user()->kode_cabang]);
        }

        $request->validate([
            'bulan' => 'required',
            'tahun' => 'required',
            'kode_cabang' => 'required',
        ]);

        DB::beginTransaction();

        $kode_saldo_awal = "SA" . $request->kode_cabang . $request->bulan . $request->tahun;
        try {
            Saldoawalbukubesar::create([
                'kode_saldo_awal' => $kode_saldo_awal,
                'tanggal' => $request->tahun . "-" . $request->bulan . "-01",
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'kode_cabang' => $request->kode_cabang,
            ]);

            $kode_akun = $request->kode_akun;
            $jumlah = $request->jumlah;

            foreach ($kode_akun as $key => $value) {
                if ($jumlah[$key] != 0) {
                    Detailsaldoawalbukubesar::create([
                        'kode_saldo_awal' => $kode_saldo_awal,
                        'kode_akun' => $value,
                        'jumlah' => toNumber($jumlah[$key]),
                    ]);
                }
            }
            DB::commit();
            return redirect()->route('saldoawalbukubesar.index')->with(messageSuccess('Data berhasil disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(messageError($e->getMessage()));
        }
    }


    public function getsaldo(Request $request)
    {
        if (auth()->user()->kode_cabang != 'PST' && !empty(auth()->user()->kode_cabang)) {
            $request->merge(['kode_cabang' => auth()->user()->kode_cabang]);
        }

        $bulan_dipilih = $request->bulan;
        $tahun_dipilih = $request->tahun;
        $nama_bulan = config('global.nama_bulan');

        // Mengatur agar $bulan dan $tahun menjadi bulan sebelumnya
        $bulan_sebelumnya = $bulan_dipilih;
        $tahun_sebelumnya = $tahun_dipilih;

        if ($bulan_dipilih == 1 || $bulan_dipilih == "01") {
            // Jika bulan Januari maka mundur ke Desember tahun sebelumnya
            $bulan_sebelumnya = 12;
            $tahun_sebelumnya = $tahun_dipilih - 1;
        } else {
            $bulan_sebelumnya = (int)$bulan_dipilih - 1;
            // Pastikan format tetap dua digit
            $bulan_sebelumnya = str_pad($bulan_sebelumnya, 2, "0", STR_PAD_LEFT);
        }

        // Cek apakah saldo bulan sebelumnya sudah ada
        $cek_saldo_sebelumnya = Saldoawalbukubesar::where('bulan', $bulan_sebelumnya)
            ->where('tahun', $tahun_sebelumnya)
            ->where('kode_cabang', $request->kode_cabang)
            ->count();

        // Cek apakah sudah ada data saldo awal sama sekali untuk cabang ini
        $cek_data_saldo_awal = Saldoawalbukubesar::where('kode_cabang', $request->kode_cabang)->count();

        if ($cek_saldo_sebelumnya == 0 && $cek_data_saldo_awal > 0) {
            $nama_bulan_sebelumnya = $nama_bulan[$bulan_sebelumnya * 1];
            return response()->json([
                'success' => false,
                'message' => "Saldo Awal Bulan $nama_bulan_sebelumnya $tahun_sebelumnya belum dibuat. Silakan buat saldo awal bulan sebelumnya terlebih dahulu."
            ], 400);
        }

        $bulan = $bulan_sebelumnya;
        $tahun = $tahun_sebelumnya;
        $start_date = $tahun . "-" . $bulan . "-01";

        $dari = $tahun . "-" . $bulan . "-01";
        $sampai = date('Y-m-t', strtotime($dari));

        $saldoawal = Detailsaldoawalbukubesar::query();

        $saldoawal->join('bukubesar_saldoawal', 'bukubesar_saldoawal.kode_saldo_awal', '=', 'bukubesar_saldoawal_detail.kode_saldo_awal');
        $saldoawal->join('coa_portax', 'bukubesar_saldoawal_detail.kode_akun', '=', 'coa_portax.kode_akun');
        $saldoawal->select(
            'bukubesar_saldoawal_detail.kode_akun',
            'coa_portax.jenis_akun',
            'nama_akun',

            // Set tanggal 1 pada bulan yang dipilih sebagai default tanggal
            DB::raw("CONCAT('$tahun-$bulan-01') as tanggal"),
            'bukubesar_saldoawal_detail.kode_saldo_awal as no_bukti',
            DB::raw("'SALDO AWAL' AS sumber"),
            DB::raw("'Saldo Awal' as keterangan"),
            // 'bukubesar_saldoawal_detail.jumlah as jml_kredit',


            DB::raw('IF(coa_portax.jenis_akun ="1",bukubesar_saldoawal_detail.jumlah,0) as jml_kredit'),
            DB::raw('IF(coa_portax.jenis_akun !="1" || coa_portax.jenis_akun IS NULL,bukubesar_saldoawal_detail.jumlah,0) as jml_debet'),
            DB::raw('0 as urutan')
        );
        $saldoawal->where('bukubesar_saldoawal.bulan', $bulan);
        $saldoawal->where('bukubesar_saldoawal.tahun', $tahun);
        $saldoawal->where('bukubesar_saldoawal.kode_cabang', $request->kode_cabang);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $saldoawal->whereBetween('bukubesar_saldoawal_detail.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $saldoawal->orderBy('bukubesar_saldoawal_detail.kode_akun');


        // ->get()->toArray();
        // Mengubah $saldo_awal_ledger menjadi koleksi
        $saldoawalCollection = collect($saldoawal);
        // dd($saldoawalCollection);
        //Ledger BANK
        $ledger = Ledger::query();
        $ledger->select(
            'coa.kode_akun_portax as kode_akun',
            'coa_portax.jenis_akun',
            'coa_portax.nama_akun',
            'keuangan_ledger.tanggal',
            'keuangan_ledger.no_bukti',
            DB::raw('CONCAT_WS(" - ", bank.nama_bank, bank.no_rekening) AS sumber'),
            'keuangan_ledger.keterangan',
            DB::raw('IF(debet_kredit="D",jumlah,0) as jml_kredit'),
            DB::raw('IF(debet_kredit="K",jumlah,0) as jml_debet'),
            DB::raw('IF(coa_portax.jenis_akun="1" AND debet_kredit="D",1,2) as urutan')
        );
        $ledger->join('bank', 'keuangan_ledger.kode_bank', '=', 'bank.kode_bank');
        $ledger->join('coa', 'bank.kode_akun', '=', 'coa.kode_akun');
        $ledger->join('coa_portax', 'coa.kode_akun_portax', '=', 'coa_portax.kode_akun');


        $ledger->whereBetween('keuangan_ledger.tanggal', [$start_date, $sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $ledger->whereBetween('coa.kode_akun_portax', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        if (auth()->user()->kode_cabang != "PST") {
            $ledger->where('bank.kode_cabang', auth()->user()->kode_cabang);
        } else {
            $ledger->where('bank.kode_cabang', $request->kode_cabang);
        }
        $ledger->orderBy('coa.kode_akun_portax');
        $ledger->orderBy('tanggal');
        $ledger->orderBy('keuangan_ledger.no_bukti');


        $ledger_transaksi = Ledger::query();
        $ledger_transaksi->select(
            'coa.kode_akun_portax as kode_akun',
            'coa_portax.jenis_akun',
            'coa_portax.nama_akun',
            'keuangan_ledger.tanggal',
            'keuangan_ledger.no_bukti',
            DB::raw('CONCAT_WS(" - ", bank.nama_bank, bank.no_rekening) AS sumber'),
            'keuangan_ledger.keterangan',
            DB::raw('IF(debet_kredit="K",jumlah,0) as jml_kredit'),
            DB::raw('IF(debet_kredit="D",jumlah,0) as jml_debet'),
            DB::raw('IF(debet_kredit="D",1,2) as urutan')
        );
        $ledger_transaksi->join('coa', 'keuangan_ledger.kode_akun', '=', 'coa.kode_akun');
        $ledger_transaksi->join('coa_portax', 'coa.kode_akun_portax', '=', 'coa_portax.kode_akun');
        $ledger_transaksi->join('bank', 'keuangan_ledger.kode_bank', '=', 'bank.kode_bank');
        $ledger_transaksi->whereBetween('keuangan_ledger.tanggal', [$start_date, $sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $ledger_transaksi->whereBetween('coa.kode_akun_portax', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        if (auth()->user()->kode_cabang != "PST") {
            $ledger_transaksi->where('bank.kode_cabang', auth()->user()->kode_cabang);
        } else {
            $ledger_transaksi->where('bank.kode_cabang', $request->kode_cabang);
        }
        $ledger_transaksi->orderBy('coa.kode_akun_portax');
        $ledger_transaksi->orderBy('keuangan_ledger.tanggal');
        $ledger_transaksi->orderBy('keuangan_ledger.no_bukti');


        //JURNAL UMUM

        $jurnalumum = Jurnalumum::query();
        $jurnalumum->select(
            'coa_portax.kode_akun as kode_akun',
            'coa_portax.jenis_akun',
            'coa_portax.nama_akun',
            'accounting_jurnalumum.tanggal',
            'accounting_jurnalumum.kode_ju as no_bukti',
            DB::raw("'JURNAL UMUM' AS sumber"),
            'accounting_jurnalumum.keterangan',
            DB::raw('IF(accounting_jurnalumum.debet_kredit="K",accounting_jurnalumum.jumlah,0) as jml_kredit'),
            DB::raw('IF(accounting_jurnalumum.debet_kredit="D",accounting_jurnalumum.jumlah,0) as jml_debet'),
            DB::raw('IF(accounting_jurnalumum.debet_kredit="D",2,1) as urutan')
        );
        $jurnalumum->join('coa_portax', 'accounting_jurnalumum.kode_akun_portax', '=', 'coa_portax.kode_akun');
        $jurnalumum->whereBetween('accounting_jurnalumum.tanggal', [$start_date, $sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $jurnalumum->whereBetween('coa_portax.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }

        $jurnalumum->orderBy('coa_portax.kode_akun');
        $jurnalumum->orderBy('accounting_jurnalumum.tanggal');
        $jurnalumum->orderBy('accounting_jurnalumum.kode_ju');


        $coa_kas_kecil = Coa::join('coa_portax', 'coa.kode_akun_portax', '=', 'coa_portax.kode_akun')
            ->where('coa.kode_transaksi', 'KKL')
            ->select('coa_portax.kode_akun', 'coa.kode_akun as kode_akun_portal', 'coa_portax.jenis_akun', 'coa_portax.nama_akun', 'coa.kode_cabang_coa');

        //Kas Kecil
        $kaskecil = Kaskecil::query();
        $kaskecil->select(
            'coa_kas_kecil.kode_akun',
            'coa_kas_kecil.jenis_akun',
            'nama_akun',
            'keuangan_kaskecil.tanggal',
            'keuangan_kaskecil.no_bukti',
            DB::raw("CONCAT('KAS KECIL ', keuangan_kaskecil.kode_cabang) AS sumber"),
            'keuangan_kaskecil.keterangan',
            DB::raw('IF(debet_kredit="D",jumlah,0) as jml_kredit'),
            DB::raw('IF(debet_kredit="K",jumlah,0) as jml_debet'),
            DB::raw('IF(debet_kredit="D",2,1) as urutan')
        );
        $kaskecil->leftJoinSub($coa_kas_kecil, 'coa_kas_kecil', function ($join) {
            $join->on('keuangan_kaskecil.kode_cabang', '=', 'coa_kas_kecil.kode_cabang_coa');
        });
        $kaskecil->where(function ($query) {
            $query->where('keuangan_kaskecil.keterangan', '!=', 'Penerimaan Kas Kecil')
                ->orWhere('keuangan_kaskecil.kode_cabang', '=', 'PST');
        });
        if (auth()->user()->kode_cabang != "PST") {
            $kaskecil->where('keuangan_kaskecil.kode_cabang', auth()->user()->kode_cabang);
        } else {
            $kaskecil->where('keuangan_kaskecil.kode_cabang', $request->kode_cabang);
        }

        $kaskecil->whereBetween('keuangan_kaskecil.tanggal', [$start_date, $sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $kaskecil->whereBetween('coa_kas_kecil.kode_akun_portal', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $kaskecil->orderBy('coa_kas_kecil.kode_akun_portal');
        $kaskecil->orderBy('keuangan_kaskecil.tanggal');
        $kaskecil->orderBy('keuangan_kaskecil.no_bukti');


        //dd($kaskecil->get());

        $kaskecil_transaksi = Kaskecil::query();
        $kaskecil_transaksi->select(
            'coa.kode_akun_portax as kode_akun',
            'coa_portax.jenis_akun',
            'coa_portax.nama_akun',
            'keuangan_kaskecil.tanggal',
            'keuangan_kaskecil.no_bukti',
            DB::raw("CONCAT('KAS KECIL ', keuangan_kaskecil.kode_cabang) AS sumber"),
            'keuangan_kaskecil.keterangan',
            DB::raw('IF(debet_kredit="K",jumlah,0) as jml_kredit'),
            DB::raw('IF(debet_kredit="D",jumlah,0) as jml_debet'),
            DB::raw('IF(debet_kredit="D",1,2) as urutan')
        );
        $kaskecil_transaksi->join('coa', 'keuangan_kaskecil.kode_akun', '=', 'coa.kode_akun');
        $kaskecil_transaksi->join('coa_portax', 'coa.kode_akun_portax', '=', 'coa_portax.kode_akun');
        $kaskecil_transaksi->whereBetween('keuangan_kaskecil.tanggal', [$start_date, $sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $kaskecil_transaksi->whereBetween('coa.kode_akun_portax', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        if (auth()->user()->kode_cabang != "PST") {
            $kaskecil_transaksi->where('keuangan_kaskecil.kode_cabang', auth()->user()->kode_cabang);
        } else {
            $kaskecil_transaksi->where('keuangan_kaskecil.kode_cabang', $request->kode_cabang);
        }
        $kaskecil_transaksi->where('keuangan_kaskecil.keterangan', '!=', 'Penerimaan Kas Kecil');
        $kaskecil_transaksi->orderBy('keuangan_kaskecil.kode_akun');
        $kaskecil_transaksi->orderBy('keuangan_kaskecil.tanggal');
        $kaskecil_transaksi->orderBy('keuangan_kaskecil.no_bukti');


        //Retur Penjualan
        $returpenjualan = Detailretur::query();
        $returpenjualan->select('marketing_retur.no_faktur', DB::raw('SUM(subtotal) as jml_retur'));
        $returpenjualan->join('marketing_retur', 'marketing_retur_detail.no_retur', '=', 'marketing_retur.no_retur');
        $returpenjualan->where('jenis_retur', 'PF');
        $returpenjualan->whereBetween('marketing_retur.tanggal', [$start_date, $sampai]);
        $returpenjualan->groupBy('marketing_retur.no_faktur');

        $detailpenjualan = Detailpenjualan::query();
        $detailpenjualan->select('marketing_penjualan.no_faktur', DB::raw('SUM(subtotal) as jml_bruto_penjualan'));
        $detailpenjualan->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $detailpenjualan->whereBetween('marketing_penjualan.tanggal', [$start_date, $sampai]);
        $detailpenjualan->where('status_batal', 0);
        $detailpenjualan->groupBy('marketing_penjualan.no_faktur');

        $penjualannetto = Penjualan::query();
        $penjualannetto->select(
            'coa.kode_akun_portax as kode_akun',
            'coa_portax.jenis_akun',
            'coa_portax.nama_akun',
            'marketing_penjualan.tanggal',
            'marketing_penjualan.no_faktur as no_bukti',
            DB::raw("'PENJUALAN' AS sumber"),
            DB::raw("CONCAT(' Penjualan ',pelanggan.nama_pelanggan) as keterangan"),
            DB::raw('((IFNULL(jml_bruto_penjualan,0) - IFNULL(potongan,0) - IFNULL(potongan_istimewa,0) - IFNULL(penyesuaian,0)) * 100 / 111) as jml_kredit'),
            DB::raw('0 as jml_debet'),

            DB::raw('1 as urutan')
        );
        $penjualannetto->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $penjualannetto->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman');
        $penjualannetto->join('coa', 'marketing_penjualan.kode_akun', '=', 'coa.kode_akun');
        $penjualannetto->join('coa_portax', 'coa.kode_akun_portax', '=', 'coa_portax.kode_akun');
        $penjualannetto->leftJoinSub($returpenjualan, 'returpenjualan', function ($join) {
            $join->on('marketing_penjualan.no_faktur', '=', 'returpenjualan.no_faktur');
        });
        $penjualannetto->leftJoinSub($detailpenjualan, 'detailpenjualan', function ($join) {
            $join->on('marketing_penjualan.no_faktur', '=', 'detailpenjualan.no_faktur');
        });
        $penjualannetto->where('marketing_penjualan.status_batal', 0);
        $penjualannetto->whereBetween('marketing_penjualan.tanggal', [$start_date, $sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $penjualannetto->whereBetween('coa.kode_akun_portax', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        if (auth()->user()->kode_cabang != "PST") {
            $penjualannetto->where('salesman.kode_cabang', auth()->user()->kode_cabang);
        } else {
            $penjualannetto->where('salesman.kode_cabang', $request->kode_cabang);
        }
        $penjualannetto->orderBy('coa.kode_akun_portax');
        $penjualannetto->orderBy('marketing_penjualan.tanggal');

        // PPN Keluaran
        $ppnkeluaran = Penjualan::query();
        $ppnkeluaran->select(
            'marketing_penjualan.kode_akun_ppn as kode_akun',
            'coa_portax.jenis_akun',
            'coa_portax.nama_akun',
            'marketing_penjualan.tanggal',
            'marketing_penjualan.no_faktur as no_bukti',
            DB::raw("'PENJUALAN' AS sumber"),
            DB::raw("CONCAT(' PPN Keluar ', pelanggan.nama_pelanggan) as keterangan"),
            DB::raw('(((IFNULL(jml_bruto_penjualan,0) - IFNULL(potongan,0) - IFNULL(potongan_istimewa,0) - IFNULL(penyesuaian,0)) * 100 / 111) * (11/12) * 0.12) as jml_kredit'),
            DB::raw('0 as jml_debet'),
            DB::raw('1 as urutan')
        );
        $ppnkeluaran->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $ppnkeluaran->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman');
        $ppnkeluaran->join('coa_portax', 'marketing_penjualan.kode_akun_ppn', '=', 'coa_portax.kode_akun');
        $ppnkeluaran->leftJoinSub($returpenjualan, 'returpenjualan', function ($join) {
            $join->on('marketing_penjualan.no_faktur', '=', 'returpenjualan.no_faktur');
        });
        $ppnkeluaran->leftJoinSub($detailpenjualan, 'detailpenjualan', function ($join) {
            $join->on('marketing_penjualan.no_faktur', '=', 'detailpenjualan.no_faktur');
        });
        $ppnkeluaran->where('marketing_penjualan.status_batal', 0);
        $ppnkeluaran->whereBetween('marketing_penjualan.tanggal', [$start_date, $sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $ppnkeluaran->whereBetween('marketing_penjualan.kode_akun_ppn', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        if (auth()->user()->kode_cabang != "PST") {
            $ppnkeluaran->where('salesman.kode_cabang', auth()->user()->kode_cabang);
        } else {
            $ppnkeluaran->where('salesman.kode_cabang', $request->kode_cabang);
        }
        $ppnkeluaran->orderBy('marketing_penjualan.kode_akun_ppn');
        $ppnkeluaran->orderBy('marketing_penjualan.tanggal');

        // Pembelian Marketing Netto
        $detailpembelianmarketing = Detailpembelianmarketing::query();
        $detailpembelianmarketing->select(
            'marketing_pembelian.no_bukti',
            DB::raw('SUM(subtotal + (subtotal * (11/12) * 0.12)) as jml_bruto_pembelian'),
            DB::raw('SUM(subtotal) as subtotal_dpp')
        );
        $detailpembelianmarketing->join('marketing_pembelian', 'marketing_pembelian_detail.no_bukti', '=', 'marketing_pembelian.no_bukti');
        $detailpembelianmarketing->whereBetween('marketing_pembelian.tanggal', [$start_date, $sampai]);
        $detailpembelianmarketing->groupBy('marketing_pembelian.no_bukti');

        $pembelianmarketingnetto = Pembelianmarketing::query();
        $pembelianmarketingnetto->select(
            'marketing_pembelian.kode_akun_portax as kode_akun',
            'coa_portax.jenis_akun',
            'coa_portax.nama_akun',
            'marketing_pembelian.tanggal',
            'marketing_pembelian.no_bukti as no_bukti',
            DB::raw("'PEMBELIAN' AS sumber"),
            DB::raw("CONCAT(' Pembelian ',supplier_marketing.nama_supplier) as keterangan"),
            DB::raw('0 as jml_kredit'),
            DB::raw('(IFNULL(jml_bruto_pembelian,0) * 100 / 111) as jml_debet'),
            DB::raw('1 as urutan')
        );
        $pembelianmarketingnetto->join('supplier_marketing', 'marketing_pembelian.kode_supplier', '=', 'supplier_marketing.kode_supplier');
        $pembelianmarketingnetto->join('coa_portax', 'marketing_pembelian.kode_akun_portax', '=', 'coa_portax.kode_akun');
        $pembelianmarketingnetto->leftJoinSub($detailpembelianmarketing, 'detailpembelianmarketing', function ($join) {
            $join->on('marketing_pembelian.no_bukti', '=', 'detailpembelianmarketing.no_bukti');
        });
        $pembelianmarketingnetto->whereBetween('marketing_pembelian.tanggal', [$start_date, $sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $pembelianmarketingnetto->whereBetween('marketing_pembelian.kode_akun_portax', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        if (auth()->user()->kode_cabang != "PST") {
            $pembelianmarketingnetto->where('marketing_pembelian.kode_cabang', auth()->user()->kode_cabang);
        } else {
            $pembelianmarketingnetto->where('marketing_pembelian.kode_cabang', $request->kode_cabang);
        }
        $pembelianmarketingnetto->orderBy('marketing_pembelian.kode_akun_portax');
        $pembelianmarketingnetto->orderBy('marketing_pembelian.tanggal');

        // PPN Masukan
        $ppnmasukan = Pembelianmarketing::query();
        $ppnmasukan->select(
            'marketing_pembelian.kode_akun_ppn as kode_akun',
            'coa_portax.jenis_akun',
            'coa_portax.nama_akun',
            'marketing_pembelian.tanggal',
            'marketing_pembelian.no_bukti as no_bukti',
            DB::raw("'PEMBELIAN' AS sumber"),
            DB::raw("CONCAT(' PPN Masukan ', supplier_marketing.nama_supplier) as keterangan"),
            DB::raw('0 as jml_kredit'),
            DB::raw('(IFNULL(subtotal_dpp,0) * (11/12) * 0.12) as jml_debet'),
            DB::raw('1 as urutan')
        );
        $ppnmasukan->join('supplier_marketing', 'marketing_pembelian.kode_supplier', '=', 'supplier_marketing.kode_supplier');
        $ppnmasukan->join('coa_portax', 'marketing_pembelian.kode_akun_ppn', '=', 'coa_portax.kode_akun');
        $ppnmasukan->leftJoinSub($detailpembelianmarketing, 'detailpembelianmarketing', function ($join) {
            $join->on('marketing_pembelian.no_bukti', '=', 'detailpembelianmarketing.no_bukti');
        });
        $ppnmasukan->whereBetween('marketing_pembelian.tanggal', [$start_date, $sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $ppnmasukan->whereBetween('marketing_pembelian.kode_akun_ppn', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        if (auth()->user()->kode_cabang != "PST") {
            $ppnmasukan->where('marketing_pembelian.kode_cabang', auth()->user()->kode_cabang);
        } else {
            $ppnmasukan->where('marketing_pembelian.kode_cabang', $request->kode_cabang);
        }
        $ppnmasukan->orderBy('marketing_pembelian.kode_akun_ppn');
        $ppnmasukan->orderBy('marketing_pembelian.tanggal');

        $data['dari'] = $dari;
        $data['sampai'] = $sampai;
        $data['saldoawalCollection'] = $saldoawalCollection;

        $union_data = $ledger->unionAll($saldoawal)
            ->unionAll($penjualannetto)
            ->unionAll($ppnkeluaran)
            ->unionAll($pembelianmarketingnetto)
            ->unionAll($ppnmasukan)
            ->unionAll($kaskecil)
            ->unionAll($kaskecil_transaksi)
            ->unionAll($ledger_transaksi)
            ->unionAll($jurnalumum);

        // 1. Hitung saldo_akhir per kode_akun dari semua data union
        $rekapakun = DB::query()->fromSub($union_data, 'rekap')
            ->selectRaw('kode_akun, SUM(jml_debet - jml_kredit) as saldo_akhir')
            ->groupBy('kode_akun')
            ->get();

        $saldo_map = [];
        foreach ($rekapakun as $r) {
            $saldo_map[$r->kode_akun] = (float) $r->saldo_akhir;
        }

        // 2. Hitung net_profit_loss dari akun laba rugi (4,5,6,7,8,9)
        $net_profit_loss = 0;
        foreach ($saldo_map as $kode => $saldo) {
            $first = substr($kode, 0, 1);
            if (in_array($first, ['4', '5', '6', '7', '8', '9'])) {
                $net_profit_loss -= $saldo;
            }
        }

        // 3. Hitung saldo 33001 (Laba Tahun Berjalan) = saldo_awal_33001 + net_profit_loss
        $saldo_33001 = ($saldo_map['33001'] ?? 0) + $net_profit_loss;

        // 4. Ambil semua coa_portax untuk neraca (1=Aktiva, 2=Kewajiban, 3=Ekuitas)
        $data['neraca'] = CoaPortax::whereRaw('LEFT(kode_akun, 1) IN (1,2,3)')
            ->orderBy('kode_akun')
            ->get()
            ->map(function ($item) use ($saldo_map, $saldo_33001) {
                if ($item->kode_akun == '33001') {
                    $item->saldo_akhir = $saldo_33001;
                } else {
                    $item->saldo_akhir = $saldo_map[$item->kode_akun] ?? 0;
                }
                return $item;
            });

        $data['net_profit_loss'] = $net_profit_loss;

        return view('accounting.saldoawalbukubesar.getsaldo', $data);
    }

    public function destroy($kode_saldo_awal)
    {
        $kode_saldo_awal = Crypt::decrypt($kode_saldo_awal);
        DB::beginTransaction();
        try {
            // Hapus detail terlebih dahulu
            Detailsaldoawalbukubesar::where('kode_saldo_awal', $kode_saldo_awal)->delete();
            // Hapus saldo awal
            Saldoawalbukubesar::where('kode_saldo_awal', $kode_saldo_awal)->delete();
            DB::commit();
            return redirect()->back()->with(messageSuccess('Data berhasil dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(messageError($e->getMessage()));
        }
    }
}
