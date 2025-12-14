<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Salesman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query();
        if (!empty($request->nama_supplier)) {
            $query->where('nama_supplier', 'like', '%' . $request->nama_supplier . '%');
        }

        $query->orderBy('kode_supplier', 'desc');
        $supplier = $query->paginate(15);
        $supplier->appends(request()->all());
        return view('datamaster.supplier.index', compact('supplier'));
    }

    public function create()
    {
        return view('datamaster.supplier.create');
    }


    public function store(Request $request)
    {

        $request->validate([
            'nama_supplier' => 'required'
        ]);

        try {
            $lastsupplier = Supplier::orderBy('kode_supplier', 'desc')->first();
            $last_kode_supplier = $lastsupplier != NULL ? $lastsupplier->kode_supplier : '';
            $kode_supplier =  buatkode($last_kode_supplier, "SP", 4);

            Supplier::create([
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
        $supplier = Supplier::where('kode_supplier', $kode_supplier)->first();
        return view('datamaster.supplier.edit', compact('supplier'));
    }

    public function update(Request $request, $kode_supplier)
    {
        $kode_supplier = Crypt::decrypt($kode_supplier);
        $request->validate([
            'nama_supplier' => 'required'
        ]);

        try {
            Supplier::where('kode_supplier', $kode_supplier)->update([

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
            Supplier::where('kode_supplier', $kode_supplier)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function getSupplier($kode_supplier)
    {
        $kode_supplier = Crypt::decrypt($kode_supplier);
        $supplier = Supplier::where('kode_supplier', $kode_supplier)->first();

        if (!$supplier) {
            return response()->json([
                'success' => false,
                'message' => 'Supplier tidak ditemukan',
                'data' => null
            ], 404);
        }

        // Menambahkan field-field yang diharapkan JavaScript dengan nilai default
        $user = auth()->user();
        $supplier->status_aktif_supplier = '1'; // Default aktif
        $supplier->kode_cabang = $user->kode_cabang ?? null;
        $supplier->kode_salesman = $user->kode_salesman ?? null;
        
        // Get nama_salesman from salesman table if kode_salesman exists
        $nama_salesman = null;
        if ($user->kode_salesman) {
            $salesman = Salesman::where('kode_salesman', $user->kode_salesman)->first();
            $nama_salesman = $salesman ? $salesman->nama_salesman : null;
        }
        $supplier->nama_salesman = $nama_salesman;
        $supplier->limit_supplier = 0; // Default limit
        $supplier->latitude = null;
        $supplier->longitude = null;
        $supplier->foto = null;

        return response()->json([
            'success' => true,
            'message' => 'Detail Supplier',
            'data' => $supplier,
            'saldo_voucher' => 0 // Default saldo voucher
        ]);
    }

    public function getPiutangsupplier($kode_supplier)
    {
        $kode_supplier = Crypt::decrypt($kode_supplier);
        
        // Untuk pembelian, piutang supplier biasanya adalah hutang yang belum dibayar
        // Untuk saat ini, return 0 karena belum ada implementasi hutang supplier
        $sisa_piutang = 0;

        return response()->json([
            'success' => true,
            'message' => 'Sisa Piutang Supplier',
            'data' => $sisa_piutang
        ]);
    }

    public function getFakturkredit($kode_supplier)
    {
        $kode_supplier = Crypt::decrypt($kode_supplier);
        
        // Untuk pembelian, faktur kredit supplier biasanya adalah faktur pembelian yang belum dibayar
        // Untuk saat ini, return 0 karena belum ada implementasi faktur kredit supplier
        $faktur_kredit = 0;

        return response()->json([
            'success' => true,
            'message' => 'Jumlah Faktur Kredit Belum Lunas',
            'data' => $faktur_kredit
        ]);
    }

    public function cekfotosupplier(Request $request)
    {
        $file = $request->get('file');
        if ($file) {
            // Remove leading slash if present
            $file = ltrim($file, '/');
            $exists = Storage::disk('public')->exists($file);
        } else {
            $exists = false;
        }

        return response()->json([
            'exists' => $exists
        ]);
    }
}
