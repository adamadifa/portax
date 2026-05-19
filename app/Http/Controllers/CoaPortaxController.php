<?php

namespace App\Http\Controllers;

use App\Models\CoaPortax;
use App\Models\Coakategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class CoaPortaxController extends Controller
{
    public function index(Request $request)
    {
        $query = CoaPortax::query();
        if (!empty($request->nama_akun)) {
            $query->where(function($q) use ($request) {
                $q->where('nama_akun', 'like', '%' . $request->nama_akun . '%')
                  ->orWhere('kode_akun', 'like', '%' . $request->nama_akun . '%');
            });
        }
        $allAccounts = $query->orderBy('kode_akun')->paginate(15);
        $allAccounts->appends(request()->all());
        return view('accounting.coa_portax.index', compact('allAccounts'));
    }

    public function create()
    {
        $data['coa'] = CoaPortax::orderBy('kode_akun')->get();
        $data['kategori'] = Coakategori::orderBy('kode_kategori')->get();
        return view('accounting.coa_portax.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_akun' => 'required',
            'nama_akun' => 'required',
            'sub_akun' => 'required',
            'kode_kategori' => 'required',
        ]);

        try {
            CoaPortax::create([
                'kode_akun' => $request->kode_akun,
                'nama_akun' => $request->nama_akun,
                'sub_akun' => $request->sub_akun,
                'kode_kategori' => $request->kode_kategori
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($kode_akun)
    {
        $kode_akun = Crypt::decrypt($kode_akun);
        $coa = CoaPortax::where('kode_akun', $kode_akun)->first();
        if (!$coa) {
            return Redirect::back()->with(messageError('Data tidak ditemukan'));
        }

        $data['coa'] = $coa;
        $data['sub_akun'] = CoaPortax::orderBy('kode_akun')->get();
        $data['kategori'] = Coakategori::orderBy('kode_kategori')->get();
        return view('accounting.coa_portax.edit', $data);
    }

    public function update(Request $request, $kode_akun)
    {
        $kode_akun = Crypt::decrypt($kode_akun);
        $coa = CoaPortax::where('kode_akun', $kode_akun)->first();
        if (!$coa) {
            return Redirect::back()->with(messageError('Data tidak ditemukan'));
        }

        $request->validate([
            'kode_akun' => 'required',
            'nama_akun' => 'required',
            'sub_akun' => 'required',
            'kode_kategori' => 'required',
        ]);

        try {
            $coa->update([
                'kode_akun' => $request->kode_akun,
                'nama_akun' => $request->nama_akun,
                'sub_akun' => $request->sub_akun,
                'kode_kategori' => $request->kode_kategori
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Diupdate'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_akun)
    {
        $kode_akun = Crypt::decrypt($kode_akun);
        $coa = CoaPortax::where('kode_akun', $kode_akun)->first();
        if (!$coa) {
            return Redirect::back()->with(messageError('Data tidak ditemukan'));
        }

        try {
            $coa->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
