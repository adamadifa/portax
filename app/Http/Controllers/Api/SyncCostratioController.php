<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Costratio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class SyncCostratioController extends Controller
{
    /**
     * Sync data cost ratio dari aplikasi lain
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function syncBatch(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'data' => 'required|array|min:1',
                'data.*.kode_cr' => 'required|string|max:20',
                'data.*.tanggal' => 'required|date',
                'data.*.kode_akun' => 'required|string|max:10',
                'data.*.jumlah' => 'required|integer',
                'data.*.kode_cabang' => 'nullable|string|max:3',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $id_user = 1; // Default to Super Admin
            $successCount = 0;
            $failedCount = 0;
            $results = [];

            foreach ($request->data as $item) {
                try {
                    DB::beginTransaction();

                    $costratioData = [
                        'tanggal' => $item['tanggal'],
                        'kode_akun' => $item['kode_akun'],
                        'keterangan' => $item['keterangan'] ?? null,
                        'kode_cabang' => $item['kode_cabang'] ?? null,
                        'kode_sumber' => $item['kode_sumber'] ?? null,
                        'jumlah' => $item['jumlah'],
                    ];

                    // Menggunakan update atau create secara eksplisit
                    $costratio = Costratio::where('kode_cr', $item['kode_cr'])->first();
                    
                    if ($costratio) {
                        $costratio->update($costratioData);
                    } else {
                        Costratio::create(array_merge(
                            ['kode_cr' => $item['kode_cr']],
                            $costratioData
                        ));
                    }

                    DB::commit();
                    $successCount++;
                    $results[] = [
                        'kode_cr' => $item['kode_cr'],
                        'status' => 'success'
                    ];
                } catch (Exception $e) {
                    DB::rollBack();
                    $failedCount++;
                    $results[] = [
                        'kode_cr' => $item['kode_cr'] ?? 'unknown',
                        'status' => 'failed',
                        'message' => $e->getMessage()
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Sync Cost Ratio selesai. Sukses: {$successCount}, Gagal: {$failedCount}",
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
                'message' => 'Gagal sync batch cost ratio',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete batch cost ratio
     */
    public function deleteBatch(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'kode_cr' => 'required|array|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $deletedCount = Costratio::whereIn('kode_cr', $request->kode_cr)->delete();

            return response()->json([
                'success' => true,
                'message' => "Berhasil menghapus {$deletedCount} data cost ratio."
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus batch cost ratio',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Reset data transaksi (Kas Kecil, Ledger, Jurnal Umum) yang sudah tersinkronisasi
     * berdasarkan periode dan kode cabang
     */
    public function resetBatch(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'dari' => 'required|date',
                'sampai' => 'required|date',
                'kode_cabang' => 'nullable|string|max:3',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $dari = $request->dari;
            $sampai = $request->sampai;
            $kode_cabang = $request->kode_cabang;

            DB::beginTransaction();

            // 1. Reset Kas Kecil
            $queryKk = DB::table('keuangan_kaskecil')
                ->where('is_sync', 1)
                ->whereBetween('tanggal', [$dari, $sampai]);
            if (!empty($kode_cabang)) {
                $queryKk->where('kode_cabang', $kode_cabang);
            }
            $deletedKk = $queryKk->delete();

            // 2. Reset Ledger
            $queryLedger = DB::table('keuangan_ledger')
                ->where('is_sync', 1)
                ->whereBetween('tanggal', [$dari, $sampai]);
            if (!empty($kode_cabang)) {
                $queryLedger->whereIn('kode_bank', function($q) use ($kode_cabang) {
                    $q->select('kode_bank')->from('bank')->where('kode_cabang', $kode_cabang);
                });
            }
            $deletedLedger = $queryLedger->delete();

            // 3. Reset Jurnal Umum
            $queryJu = DB::table('accounting_jurnalumum')
                ->where('is_sync', 1)
                ->whereBetween('tanggal', [$dari, $sampai]);
            if (!empty($kode_cabang)) {
                $queryJu->where('kode_cabang', $kode_cabang);
            }
            $deletedJu = $queryJu->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Reset data sync berhasil.",
                'summary' => [
                    'kaskecil' => $deletedKk,
                    'ledger' => $deletedLedger,
                    'jurnalumum' => $deletedJu,
                ]
            ], 200);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mereset data sync',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
