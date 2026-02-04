<?php

namespace App\Http\Controllers;

use App\Models\HargaSupplier;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

class HargaSupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = HargaSupplier::query();
        $query->select('harga_supplier.*', 'produk.nama_produk');
        $query->join('produk', 'harga_supplier.kode_produk', '=', 'produk.kode_produk');
        $query->orderBy('produk.nama_produk', 'asc');
        
        if ($request->has('nama_produk') && !empty($request->nama_produk)) {
            $query->where('produk.nama_produk', 'like', '%' . $request->nama_produk . '%');
        }

        $hargasupplier = $query->paginate(15);
        $hargasupplier->appends($request->all());

        return view('datamaster.hargasupplier.index', compact('hargasupplier'));
    }

    public function create()
    {
        $produk = Produk::orderBy('nama_produk')->get();
        return view('datamaster.hargasupplier.create', compact('produk'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_produk' => 'required|unique:harga_supplier,kode_produk',
            'harga' => 'required|numeric'
        ]);

        try {
            HargaSupplier::create([
                'kode_produk' => $request->kode_produk,
                'harga' => toNumber($request->harga)
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($kode_produk)
    {
        $kode_produk = Crypt::decrypt($kode_produk);
        $hargasupplier = HargaSupplier::findOrFail($kode_produk);
        $produk = Produk::orderBy('nama_produk')->get();
        return view('datamaster.hargasupplier.edit', compact('hargasupplier', 'produk'));
    }

    public function update(Request $request, $kode_produk)
    {
        $kode_produk = Crypt::decrypt($kode_produk);
        $request->validate([
            'harga' => 'required|numeric'
        ]);

        try {
            HargaSupplier::where('kode_produk', $kode_produk)->update([
                'harga' => toNumber($request->harga)
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Diupdate'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_produk)
    {
        $kode_produk = Crypt::decrypt($kode_produk);
        try {
            $hargasupplier = HargaSupplier::where('kode_produk', $kode_produk)->first();
            $hargasupplier->delete();
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Data Gagal Dihapus ' . $e->getMessage()]);
        }
    }

    public function getHarga($kode_produk)
    {
        $harga = HargaSupplier::where('kode_produk', $kode_produk)->first();
        return response()->json([
            'harga' => $harga ? $harga->harga : 0
        ]);
    }
}
