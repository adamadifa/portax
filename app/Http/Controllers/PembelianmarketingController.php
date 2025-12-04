<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Salesman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

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

        // Query untuk marketing_pembelian (struktur sama dengan penjualan)
        $query = DB::table('marketing_pembelian')
            ->select(
                'marketing_pembelian.*',
                'supplier.nama_supplier',
                'salesman.nama_salesman',
                'cabang.nama_cabang'
            )
            ->selectRaw('(SELECT SUM(subtotal) FROM marketing_pembelian_detail WHERE no_bukti = marketing_pembelian.no_bukti) as total_bruto')
            ->leftJoin('supplier', 'marketing_pembelian.kode_supplier', '=', 'supplier.kode_supplier')
            ->leftJoin('salesman', 'marketing_pembelian.kode_salesman', '=', 'salesman.kode_salesman')
            ->leftJoin('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang');

        // Filter berdasarkan role dan cabang
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('salesman.kode_cabang', auth()->user()->kode_cabang);
            }
        }

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

        // Filter cabang
        if (!empty($request->kode_cabang_search)) {
            $query->where('salesman.kode_cabang', $request->kode_cabang_search);
        }

        // Filter salesman
        if (!empty($request->kode_salesman_search)) {
            $query->where('marketing_pembelian.kode_salesman', $request->kode_salesman_search);
        }

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

    public function create()
    {
        // TODO: Implement create method
        return view('marketing.pembelian.create');
    }

    public function store(Request $request)
    {
        // TODO: Implement store method
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
        // TODO: Implement destroy method
    }

    public function show($no_bukti)
    {
        // TODO: Implement show method
    }
}
