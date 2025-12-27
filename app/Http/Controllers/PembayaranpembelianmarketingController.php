<?php

namespace App\Http\Controllers;

use App\Models\Historibayarpembelianmarketing;
use App\Models\Pembelianmarketing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class PembayaranpembelianmarketingController extends Controller
{
    public function create($no_bukti)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        $pembelian = Pembelianmarketing::where('no_bukti', $no_bukti)->first();
        
        $data['pembelian'] = $pembelian;
        $data['no_bukti'] = $no_bukti;
        return view('marketing.pembayaranpembelianmarketing.create', $data);
    }

    public function store(Request $request, $no_bukti)
    {
        $request->validate([
            'tanggal' => 'required',
            'jumlah' => 'required',
            'jenis_bayar' => 'required|in:TN,TR'
        ]);

        $no_bukti = Crypt::decrypt($no_bukti);
        $pembelian = Pembelianmarketing::where('no_bukti', $no_bukti)->firstOrFail();

        $kode_cabang = auth()->user()->kode_cabang;
        $tahun = date('y', strtotime($request->tanggal));

        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($request->tanggal, "pembelian");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            $lasthistoribayar = Historibayarpembelianmarketing::select('no_bukti')
                ->whereRaw('LEFT(no_bukti,6) = "' . $kode_cabang . $tahun . '-"')
                ->orderBy("no_bukti", "desc")
                ->first();

            $last_no_bukti = $lasthistoribayar != null ? $lasthistoribayar->no_bukti : '';
            $no_bukti_bayar = buatkode($last_no_bukti, $kode_cabang . $tahun . "-", 6);

            Historibayarpembelianmarketing::create([
                'no_bukti' => $no_bukti_bayar,
                'tanggal' => $request->tanggal,
                'no_bukti_pembelian' => $no_bukti,
                'jenis_bayar' => $request->jenis_bayar,
                'jumlah' => toNumber($request->jumlah),
                'voucher' => 0,
                'jenis_voucher' => '0',
                'kode_akun' => $request->jenis_bayar == 'TN' ? '1-1100' : '1-1200', // Kas atau Bank
                'id_user' => auth()->user()->id
            ]);

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($no_bukti)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        $historibayar = Historibayarpembelianmarketing::where('no_bukti', $no_bukti)->firstOrFail();
        
        $pembelian = Pembelianmarketing::where('no_bukti', $historibayar->no_bukti_pembelian)->first();
        
        $data['historibayar'] = $historibayar;
        $data['pembelian'] = $pembelian;
        return view('marketing.pembayaranpembelianmarketing.edit', $data);
    }

    public function update(Request $request, $no_bukti)
    {
        $request->validate([
            'tanggal' => 'required',
            'jumlah' => 'required',
            'jenis_bayar' => 'required|in:TN,TR'
        ]);

        $no_bukti = Crypt::decrypt($no_bukti);

        DB::beginTransaction();
        try {
            $historibayar = Historibayarpembelianmarketing::where('no_bukti', $no_bukti)->firstOrFail();

            $cektutuplaporanpembayaran = cektutupLaporan($historibayar->tanggal, "pembelian");
            if ($cektutuplaporanpembayaran > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }
            
            $cektutuplaporan = cektutupLaporan($request->tanggal, "pembelian");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            Historibayarpembelianmarketing::where('no_bukti', $no_bukti)->update([
                'tanggal' => $request->tanggal,
                'jenis_bayar' => $request->jenis_bayar,
                'jumlah' => toNumber($request->jumlah),
                'kode_akun' => $request->jenis_bayar == 'TN' ? '1-1100' : '1-1200', // Kas atau Bank
                'id_user' => auth()->user()->id
            ]);

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Diupdate'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($no_bukti)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        
        DB::beginTransaction();
        try {
            $historibayar = Historibayarpembelianmarketing::where('no_bukti', $no_bukti)->firstOrFail();

            $cektutuplaporan = cektutupLaporan($historibayar->tanggal, "pembelian");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            Historibayarpembelianmarketing::where('no_bukti', $no_bukti)->delete();

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
