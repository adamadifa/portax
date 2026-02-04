<?php

namespace App\Http\Controllers;

use App\Models\SupplierMarketing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;

class SupplierMarketingController extends Controller
{
    public function index(Request $request)
    {
        $query = SupplierMarketing::query();
        if (!empty($request->nama_supplier)) {
            $query->where('nama_supplier', 'like', '%' . $request->nama_supplier . '%');
        }

        $query->orderBy('kode_supplier', 'desc');
        $supplier = $query->paginate(15);
        $supplier->appends(request()->all());
        return view('datamaster.suppliermarketing.index', compact('supplier'));
    }

    public function create()
    {
        return view('datamaster.suppliermarketing.create');
    }


    public function store(Request $request)
    {

        $request->validate([
            'nama_supplier' => 'required'
        ]);

        try {
            $lastsupplier = SupplierMarketing::orderBy('kode_supplier', 'desc')->first();
            $last_kode_supplier = $lastsupplier != NULL ? $lastsupplier->kode_supplier : '';
            $kode_supplier =  buatkode($last_kode_supplier, "SM", 4);

            SupplierMarketing::create([
                'kode_supplier' => $kode_supplier,
                'nama_supplier' => $request->nama_supplier,
                'contact_person' => $request->contact_person,
                'no_hp_supplier' => $request->no_hp_supplier,
                'alamat_supplier' => $request->alamat_supplier,
                'email_supplier' => $request->email_supplier,
                'no_rekening_supplier' => $request->no_rekening_supplier,
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function edit($kode_supplier)
    {
        $kode_supplier = Crypt::decrypt($kode_supplier);
        $supplier = SupplierMarketing::where('kode_supplier', $kode_supplier)->first();
        return view('datamaster.suppliermarketing.edit', compact('supplier'));
    }

    public function update(Request $request, $kode_supplier)
    {
        $kode_supplier = Crypt::decrypt($kode_supplier);
        $request->validate([
            'nama_supplier' => 'required'
        ]);

        try {
            SupplierMarketing::where('kode_supplier', $kode_supplier)->update([

                'nama_supplier' => $request->nama_supplier,
                'contact_person' => $request->contact_person,
                'no_hp_supplier' => $request->no_hp_supplier,
                'alamat_supplier' => $request->alamat_supplier,
                'email_supplier' => $request->email_supplier,
                'no_rekening_supplier' => $request->no_rekening_supplier,
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Di Update'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_supplier)
    {
        $kode_supplier = Crypt::decrypt($kode_supplier);
        try {
            SupplierMarketing::where('kode_supplier', $kode_supplier)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function getSupplierMarketing(Request $request)
    {
        if ($request->ajax()) {
            $query = SupplierMarketing::query();
            $query->select(
                'supplier_marketing.kode_supplier',
                'supplier_marketing.nama_supplier',
                'supplier_marketing.no_hp_supplier',
                'supplier_marketing.alamat_supplier'
            );

            if ($request->has('search') && !empty($request->search['value'])) {
                $searchValue = $request->search['value'];
                $query->where(function ($q) use ($searchValue) {
                    $q->where('supplier_marketing.nama_supplier', 'like', '%' . $searchValue . '%');
                });
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($item) {
                    return '<a href="#" kode_supplier="' . Crypt::encrypt($item->kode_supplier) . '" class="pilihsupplier"><i class="ti ti-external-link"></i></a>';
                })
                ->make(true);
        }
    }

    public function getSupplier($kode_supplier)
    {
        $kode_supplier = Crypt::decrypt($kode_supplier);
        $supplier = SupplierMarketing::where('kode_supplier', $kode_supplier)->first();

        if (!$supplier) {
            return response()->json([
                'success' => false,
                'message' => 'Supplier tidak ditemukan',
                'data' => null
            ], 404);
        }
        
        // Add default fields expected by JS
        $user = auth()->user();
        $supplier->status_aktif_supplier = '1'; 
        $supplier->kode_cabang = $user->kode_cabang ?? null;
        
        return response()->json([
            'success' => true,
            'message' => 'Detail Supplier',
            'data' => $supplier
        ]);
    }
}
