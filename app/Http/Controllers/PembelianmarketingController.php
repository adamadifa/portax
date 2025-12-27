<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Salesman;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Pembelianmarketing;
use App\Models\Detailpembelianmarketing;
use App\Models\Historibayarpembelianmarketing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class PembelianmarketingController extends Controller
{
    public function index(Request $request)
    {
        $start_date = config('global.start_date');
        $end_date = config('global.end_date');
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');

        if (!empty($request->dari) && !empty($request->sampai)) {
            if (lockreport($request->dari) == "error") {
                return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
            }
        }

        // Query untuk marketing_pembelian
        $query = DB::table('marketing_pembelian')
            ->select(
                'marketing_pembelian.*',
                'supplier.nama_supplier'
            )
            ->selectRaw('(SELECT SUM(subtotal) FROM marketing_pembelian_detail WHERE no_bukti = marketing_pembelian.no_bukti) as total_bruto')
            ->leftJoin('supplier', 'marketing_pembelian.kode_supplier', '=', 'supplier.kode_supplier');

        // Filter tanggal
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('marketing_pembelian.tanggal', [$request->dari, $request->sampai]);
        } else {
            $query->whereBetween('marketing_pembelian.tanggal', [$start_date, $end_date]);
        }

        // Filter no bukti
        if (!empty($request->no_bukti_search)) {
            $query->where('marketing_pembelian.no_bukti', $request->no_bukti_search);
        }

        // Filter cabang - removed karena supplier tidak punya kode_cabang

        // Filter supplier
        if (!empty($request->kode_supplier_search)) {
            $query->where('marketing_pembelian.kode_supplier', $request->kode_supplier_search);
        }

        // Filter nama supplier
        if (!empty($request->nama_supplier_search)) {
            $query->where('supplier.nama_supplier', 'like', '%' . $request->nama_supplier_search . '%');
        }

        $query->orderBy('marketing_pembelian.tanggal', 'desc');
        $query->orderBy('marketing_pembelian.no_bukti', 'desc');

        $pembelian = $query->cursorPaginate(15);
        $pembelian->appends(request()->all());

        $data['pembelian'] = $pembelian;
        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();

        return view('marketing.pembelian.index', $data);
    }

    public function create(Request $request)
    {
        $user = User::findOrFail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');

        // Ajax request for supplier list (DataTables)
        if ($request->ajax()) {
            $query = Supplier::query();
            $query->select(
                'supplier.kode_supplier',
                'supplier.nama_supplier',
                'supplier.no_hp_supplier',
                'supplier.alamat_supplier'
            );

            // Filter by search
            if ($request->has('search') && !empty($request->search['value'])) {
                $searchValue = $request->search['value'];
                $query->where(function ($q) use ($searchValue) {
                    $q->where('supplier.nama_supplier', 'like', '%' . $searchValue . '%');
                });
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($item) {
                    return '<a href="#" kode_supplier="' . Crypt::encrypt($item->kode_supplier) . '" class="pilihsupplier"><i class="ti ti-external-link"></i></a>';
                })
                ->make();
        }

        return view('marketing.pembelian.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_bukti' => 'required',
            'tanggal' => 'required',
            'kode_supplier' => 'required',
            'jenis_transaksi' => 'required',
            'jenis_bayar' => 'required_if:jenis_transaksi,T',
            'kode_produk' => 'required|array|min:1',
            'kode_produk.*' => 'required',
            'harga_dus_produk' => 'required|array',
            'harga_dus_produk.*' => 'required',
            'jumlah_produk' => 'required|array',
            'jumlah_produk.*' => 'required',
            'subtotal' => 'required|array',
            'subtotal.*' => 'required'
        ]);

        $no_bukti = $request->no_bukti;
        $tanggal = $request->tanggal;
        $kode_supplier = $request->kode_supplier;
        $jenis_transaksi = $request->jenis_transaksi;
        $jenis_bayar = $request->jenis_bayar;
        $kode_akun = $request->kode_akun ?? '1-1401';
        
        // Detail produk
        $kode_produk = $request->kode_produk;
        $harga_dus = $request->harga_dus_produk;
        $jumlah = $request->jumlah_produk;
        $subtotal = $request->subtotal;

        DB::beginTransaction();
        try {
            // Cek tutup laporan
            $cektutuplaporan = cektutupLaporan($tanggal, "pembelian");
            if ($cektutuplaporan == "error") {
                DB::rollBack();
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            // Cek duplikat no_bukti
            $cekNoBukti = Pembelianmarketing::where('no_bukti', $no_bukti)->count();
            if ($cekNoBukti > 0) {
                DB::rollBack();
                return Redirect::back()->with(messageError('No. Bukti Sudah Ada'));
            }

            // Siapkan data detail
            $detail = [];
            for ($i = 0; $i < count($kode_produk); $i++) {
                $detail[] = [
                    'no_bukti' => $no_bukti,
                    'kode_produk' => $kode_produk[$i],
                    'harga_dus' => toNumber($harga_dus[$i]),
                    'jumlah' => toNumber($jumlah[$i]),
                    'subtotal' => toNumber($subtotal[$i]),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            // Insert header pembelian
            Pembelianmarketing::create([
                'no_bukti' => $no_bukti,
                'tanggal' => $tanggal,
                'kode_supplier' => $kode_supplier,
                'kode_akun' => $kode_akun,
                'jenis_transaksi' => $jenis_transaksi,
                'jenis_bayar' => $jenis_bayar,
                'status' => '0',
                'id_user' => auth()->user()->id
            ]);

            // Insert detail pembelian
            Detailpembelianmarketing::insert($detail);

            // Jika transaksi TUNAI, buat histori bayar otomatis
            if ($jenis_transaksi == "T") {
                // Hitung total pembelian
                $total_pembelian = array_sum(array_map(function($subtotal) {
                    return toNumber($subtotal);
                }, $subtotal));

                // Generate no_bukti untuk histori bayar
                $kode_cabang = auth()->user()->kode_cabang;
                $tahun = date('y', strtotime($tanggal));
                
                $lasthistoribayar = Historibayarpembelianmarketing::select('no_bukti')
                    ->whereRaw('LEFT(no_bukti,6) = "' . $kode_cabang . $tahun . '-"')
                    ->orderBy("no_bukti", "desc")
                    ->first();

                $last_no_bukti = $lasthistoribayar != null ? $lasthistoribayar->no_bukti : '';
                $no_bukti_bayar = buatkode($last_no_bukti, $kode_cabang . $tahun . "-", 6);

                // Insert histori bayar
                Historibayarpembelianmarketing::create([
                    'no_bukti' => $no_bukti_bayar,
                    'tanggal' => $tanggal,
                    'no_bukti_pembelian' => $no_bukti,
                    'jenis_bayar' => $jenis_bayar,
                    'jumlah' => $total_pembelian,
                    'voucher' => 0,
                    'jenis_voucher' => '0',
                    'kode_akun' => $jenis_bayar == 'TN' ? '1-1100' : '1-1200', // Kas atau Bank
                    'id_user' => auth()->user()->id
                ]);
            }

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($no_bukti)
    {
        // TODO: Implement edit method
    }

    public function update(Request $request, $no_bukti)
    {
        // TODO: Implement update method
    }

    public function destroy($no_bukti)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        $pembelian = Pembelianmarketing::where('no_bukti', $no_bukti)->firstOrFail();
        
        DB::beginTransaction();
        try {
            // Cek tutup laporan
            $cektutuplaporan = cektutupLaporan($pembelian->tanggal, "pembelian");
            if ($cektutuplaporan == "error") {
                DB::rollBack();
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }

            // Hapus histori bayar terlebih dahulu (karena ada foreign key)
            Historibayarpembelianmarketing::where('no_bukti_pembelian', $no_bukti)->delete();
            
            // Hapus detail pembelian
            Detailpembelianmarketing::where('no_bukti', $no_bukti)->delete();
            
            // Hapus header pembelian
            Pembelianmarketing::where('no_bukti', $no_bukti)->delete();

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function show($no_bukti)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        
        // Get pembelian data
        $pembelian = Pembelianmarketing::select(
            'marketing_pembelian.*',
            'supplier.nama_supplier'
        )
        ->leftJoin('supplier', 'marketing_pembelian.kode_supplier', '=', 'supplier.kode_supplier')
        ->where('marketing_pembelian.no_bukti', $no_bukti)
        ->firstOrFail();
        
        // Get detail pembelian
        $detail = Detailpembelianmarketing::select(
            'marketing_pembelian_detail.*',
            'produk.nama_produk'
        )
        ->join('produk', 'marketing_pembelian_detail.kode_produk', '=', 'produk.kode_produk')
        ->where('marketing_pembelian_detail.no_bukti', $no_bukti)
        ->get();
        
        // Calculate total bruto
        $total_bruto = $detail->sum('subtotal');
        
        // Get histori bayar
        $historibayar = Historibayarpembelianmarketing::where('no_bukti_pembelian', $no_bukti)
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Calculate total bayar
        $total_bayar = $historibayar->sum('jumlah');
        
        $data['pembelian'] = $pembelian;
        $data['detail'] = $detail;
        $data['total_bruto'] = $total_bruto;
        $data['historibayar'] = $historibayar;
        $data['total_bayar'] = $total_bayar;
        $data['jenis_bayar'] = config('penjualan.jenis_bayar') ?? ['TN' => 'CASH', 'TR' => 'TRANSFER'];
        
        return view('marketing.pembelian.show', $data);
    }
}
