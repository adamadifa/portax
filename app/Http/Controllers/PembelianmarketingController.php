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
                'supplier_marketing.nama_supplier'
            )
            ->selectRaw('(SELECT SUM(subtotal + (subtotal * (11/12) * 0.12)) FROM marketing_pembelian_detail WHERE no_bukti = marketing_pembelian.no_bukti) as total_bruto')
            ->leftJoin('supplier_marketing', 'marketing_pembelian.kode_supplier', '=', 'supplier_marketing.kode_supplier');

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
            $query->where('supplier_marketing.nama_supplier', 'like', '%' . $request->nama_supplier_search . '%');
        }

        // Filter Cabang based on roles
        if (in_array($user->roles, $roles_access_all_cabang)) {
            if (!empty($request->kode_cabang_search)) {
                $query->where('marketing_pembelian.kode_cabang', $request->kode_cabang_search);
            }
        } else {
            $query->where('marketing_pembelian.kode_cabang', $user->kode_cabang);
        }

        $query->orderBy('marketing_pembelian.tanggal', 'desc');
        $query->orderBy('marketing_pembelian.no_bukti', 'desc');

        $pembelian = $query->paginate(15);
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
        $jenis_bayar = $jenis_transaksi == 'T' ? $request->jenis_bayar : 'TP';
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
                'kode_cabang' => auth()->user()->kode_cabang,
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
            return Redirect::route('pembelianmarketing.show', Crypt::encrypt($no_bukti))->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($no_bukti)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        $user = User::findOrFail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');

        // Check Access
        if (!in_array($user->roles, $roles_access_all_cabang)) {
            $pembelian = Pembelianmarketing::where('no_bukti', $no_bukti)->where('kode_cabang', $user->kode_cabang)->firstOrFail();
        } else {
            $pembelian = Pembelianmarketing::where('no_bukti', $no_bukti)->firstOrFail();
        }

        // Get pembelian header with supplier name
        $pembelianData = Pembelianmarketing::select(
            'marketing_pembelian.*',
            'supplier_marketing.nama_supplier'
        )
        ->leftJoin('supplier_marketing', 'marketing_pembelian.kode_supplier', '=', 'supplier_marketing.kode_supplier')
        ->where('marketing_pembelian.no_bukti', $no_bukti)
        ->firstOrFail();

        // Get detail pembelian products
        $detail = Detailpembelianmarketing::select(
            'marketing_pembelian_detail.*',
            'produk.nama_produk',
            'produk.isi_pcs_dus',
            'produk.isi_pcs_pack'
        )
        ->join('produk', 'marketing_pembelian_detail.kode_produk', '=', 'produk.kode_produk')
        ->where('marketing_pembelian_detail.no_bukti', $no_bukti)
        ->get();

        $data['pembelian'] = $pembelianData;
        $data['detail'] = $detail;
        
        return view('marketing.pembelian.edit', $data);
    }

    public function update(Request $request, $no_bukti)
    {
        $no_bukti_lama = Crypt::decrypt($no_bukti);

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

        $no_bukti_baru = $request->no_bukti;
        $tanggal = $request->tanggal;
        $kode_supplier = $request->kode_supplier;
        $jenis_transaksi = $request->jenis_transaksi;
        $jenis_bayar = $jenis_transaksi == 'T' ? $request->jenis_bayar : 'TP';
        $kode_akun = $request->kode_akun ?? '1-1401';
        
        // Detail produk
        $kode_produk = $request->kode_produk;
        $harga_dus = $request->harga_dus_produk;
        $jumlah = $request->jumlah_produk;
        $subtotal = $request->subtotal;

        DB::beginTransaction();
        try {
            $pembelian = Pembelianmarketing::where('no_bukti', $no_bukti_lama)->firstOrFail();
            
            // Jika no_bukti diganti, cek ketersediaan no_bukti_baru
            if ($no_bukti_baru != $no_bukti_lama) {
                $cekNoBukti = Pembelianmarketing::where('no_bukti', $no_bukti_baru)->count();
                if ($cekNoBukti > 0) {
                    DB::rollBack();
                    return Redirect::back()->with(messageError('No. Bukti Sudah Digunakan'));
                }
            }

            // Cek tutup laporan inputan baru
            $cektutuplaporan = cektutupLaporan($tanggal, "pembelian");
            if ($cektutuplaporan == "error") {
                DB::rollBack();
                return Redirect::back()->with(messageError('Periode Laporan Tanggal Input Sudah Ditutup'));
            }

            // Cek tutup laporan transaksi lama
            $cektutuplaporanlama = cektutupLaporan($pembelian->tanggal, "pembelian");
            if ($cektutuplaporanlama == "error") {
                DB::rollBack();
                return Redirect::back()->with(messageError('Periode Laporan Transaksi Lama Sudah Ditutup'));
            }

            // Siapkan data detail
            $detail = [];
            for ($i = 0; $i < count($kode_produk); $i++) {
                $detail[] = [
                    'no_bukti' => $no_bukti_baru,
                    'kode_produk' => $kode_produk[$i],
                    'harga_dus' => toNumber($harga_dus[$i]),
                    'jumlah' => toNumber($jumlah[$i]),
                    'subtotal' => toNumber($subtotal[$i]),
                    'created_at' => $pembelian->created_at,
                    'updated_at' => now()
                ];
            }

            // Hapus detail pembelian lama
            Detailpembelianmarketing::where('no_bukti', $no_bukti_lama)->delete();

            // Update header pembelian terlebih dahulu agar constraint FK detail tidak gagal
            Pembelianmarketing::where('no_bukti', $no_bukti_lama)->update([
                'no_bukti' => $no_bukti_baru,
                'tanggal' => $tanggal,
                'kode_supplier' => $kode_supplier,
                'kode_akun' => $kode_akun,
                'jenis_transaksi' => $jenis_transaksi,
                'jenis_bayar' => $jenis_bayar,
                'id_user' => auth()->user()->id
            ]);

            // Insert detail pembelian baru dengan no_bukti yang sudah di-update di header
            Detailpembelianmarketing::insert($detail);

            // Hitung total pembelian
            $total_pembelian = array_sum(array_map(function($sub) {
                return toNumber($sub);
            }, $subtotal));

            // Jika ubah no_bukti, pastikan riwayat update ke no_bukti yang baru
            if ($no_bukti_baru != $no_bukti_lama) {
                Historibayarpembelianmarketing::where('no_bukti_pembelian', $no_bukti_lama)->update([
                    'no_bukti_pembelian' => $no_bukti_baru
                ]);
            }

            // Perbarui Histori Bayar Cash Tunai
            $cekhistoribayar = Historibayarpembelianmarketing::where('no_bukti_pembelian', $no_bukti_baru)->orderBy('no_bukti')->first();
            
            if ($jenis_transaksi == "T") {
                if ($cekhistoribayar != null) {
                    // Update yang sudah ada
                    Historibayarpembelianmarketing::where('no_bukti', $cekhistoribayar->no_bukti)->update([
                        'tanggal' => $tanggal,
                        'jumlah' => $total_pembelian,
                        'jenis_bayar' => $jenis_bayar,
                        'kode_akun' => $jenis_bayar == 'TN' ? '1-1100' : '1-1200', 
                        'id_user' => auth()->user()->id
                    ]);
                } else {
                    // Generate histori bayar baru karena tadinya kredit
                    $kode_cabang = auth()->user()->kode_cabang;
                    $tahun = date('y', strtotime($tanggal));
                    
                    $lasthistoribayar = Historibayarpembelianmarketing::select('no_bukti')
                        ->whereRaw('LEFT(no_bukti,6) = "' . $kode_cabang . $tahun . '-"')
                        ->orderBy("no_bukti", "desc")
                        ->first();

                    $last_no_bukti = $lasthistoribayar != null ? $lasthistoribayar->no_bukti : '';
                    $no_bukti_bayar = buatkode($last_no_bukti, $kode_cabang . $tahun . "-", 6);

                    Historibayarpembelianmarketing::create([
                        'no_bukti' => $no_bukti_bayar,
                        'tanggal' => $tanggal,
                        'no_bukti_pembelian' => $no_bukti_baru,
                        'jenis_bayar' => $jenis_bayar,
                        'jumlah' => $total_pembelian,
                        'voucher' => 0,
                        'jenis_voucher' => '0',
                        'kode_akun' => $jenis_bayar == 'TN' ? '1-1100' : '1-1200', // Kas atau Bank
                        'id_user' => auth()->user()->id
                    ]);
                }
            } else {
                // KREDIT - Hapus
                Historibayarpembelianmarketing::where('no_bukti_pembelian', $no_bukti_baru)->delete();
            }

            DB::commit();
            
            return Redirect::route('pembelianmarketing.show', Crypt::encrypt($no_bukti_baru))->with(messageSuccess('Data Berhasil Diperbarui'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
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
            'supplier_marketing.nama_supplier'
        )
        ->leftJoin('supplier_marketing', 'marketing_pembelian.kode_supplier', '=', 'supplier_marketing.kode_supplier')
        ->where('marketing_pembelian.no_bukti', $no_bukti)
        ->firstOrFail();
        
        // Get detail pembelian
        $detail = Detailpembelianmarketing::select(
            'marketing_pembelian_detail.*',
            'produk.nama_produk',
            'produk.isi_pcs_dus',
            'produk.isi_pcs_pack'
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
