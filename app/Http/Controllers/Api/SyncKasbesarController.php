<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Historibayarpenjualan;
use App\Models\Penjualan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class SyncKasbesarController extends Controller
{
    /**
     * Sync data kas besar (historibayar) dari aplikasi lain
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function syncBatch(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'data' => 'required|array|min:1',
                'data.*.no_bukti' => 'required|string|max:20',
                'data.*.no_faktur' => 'required|string|max:13',
                'data.*.tanggal' => 'required|date',
                'data.*.jumlah' => 'required|integer',
                'data.*.jenis_bayar' => 'required|string|max:2',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Get User once (Super Admin ID 1)
            $id_user = 1;
            $user = User::find($id_user);
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Super Admin (ID 1) tidak ditemukan'
                ], 404);
            }

            $successCount = 0;
            $failedCount = 0;
            $results = [];

            foreach ($request->data as $bayar) {
                try {
                    DB::beginTransaction();

                    // Cek apakah invoice header ada
                    $penjualan = Penjualan::where('no_faktur', $bayar['no_faktur'])->first();
                    
                    if (!$penjualan) {
                        // Jika invoice belum ada, kita tidak bisa sync pembayarannya
                        throw new Exception("No Faktur {$bayar['no_faktur']} tidak ditemukan. Sync invoice terlebih dahulu.");
                    }

                    // Prepare data historibayar
                    $historibayarData = [
                        'no_faktur' => $bayar['no_faktur'],
                        'tanggal' => $bayar['tanggal'],
                        'kode_salesman' => $bayar['kode_salesman'] ?? $penjualan->kode_salesman,
                        'jenis_bayar' => $bayar['jenis_bayar'],
                        'jumlah' => $bayar['jumlah'],
                        'voucher' => $bayar['voucher'] ?? '0',
                        'jenis_voucher' => $bayar['jenis_voucher'] ?? '0',
                        'kode_lhp' => $bayar['kode_lhp'] ?? null,
                        'kode_akun' => $bayar['kode_akun'] ?? '1-1401',
                        'keterangan' => $bayar['keterangan'] ?? null,
                        'id_user' => $id_user,
                    ];

                    // Upsert Historibayar
                    Historibayarpenjualan::updateOrCreate(
                        ['no_bukti' => $bayar['no_bukti']],
                        $historibayarData
                    );

                    DB::commit();
                    $successCount++;
                    $results[] = [
                        'no_bukti' => $bayar['no_bukti'],
                        'status' => 'success',
                        'message' => 'Berhasil disync (updateOrCreate)'
                    ];
                } catch (Exception $e) {
                    DB::rollBack();
                    $failedCount++;
                    $results[] = [
                        'no_bukti' => $bayar['no_bukti'] ?? 'unknown',
                        'status' => 'failed',
                        'message' => $e->getMessage()
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Sync Kas Besar selesai. Sukses: {$successCount}, Gagal: {$failedCount}",
                'summary' => [
                    'total' => count($request->data),
                    'success' => $successCount,
                    'failed' => $failedCount
                ],
                'results' => $results
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal sync batch kas besar',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
