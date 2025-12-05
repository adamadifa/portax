<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kaskecil;
use App\Models\Kaskecilcostratio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class SyncKaskecilController extends Controller
{
    /**
     * Sync data kas kecil dari aplikasi lain
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sync(Request $request)
    {
        try {
            // Validasi request
            $validator = Validator::make($request->all(), [
                // Header kas kecil (required)
                'no_bukti' => 'required|string|max:12|unique:keuangan_kaskecil,no_bukti',
                'tanggal' => 'required|date',
                'jumlah' => 'required|integer',
                'debet_kredit' => 'required|string|max:1|in:D,K',
                'kode_akun' => 'required|string|max:6',
                'kode_cabang' => 'required|string|max:3',
                
                // Optional fields
                'keterangan' => 'nullable|string|max:255',
                'status_pajak' => 'nullable|integer',
                'kode_peruntukan' => 'nullable|string|max:3',
                
                // Cost Ratio (optional array)
                'cost_ratio' => 'nullable|array',
                'cost_ratio.*' => 'string|max:10',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            // Prepare data kas kecil
            $kaskecilData = [
                'no_bukti' => $request->no_bukti,
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan,
                'jumlah' => $request->jumlah,
                'debet_kredit' => $request->debet_kredit,
                'status_pajak' => $request->status_pajak ?? 0,
                'kode_akun' => $request->kode_akun,
                'kode_cabang' => $request->kode_cabang,
                'kode_peruntukan' => $request->kode_peruntukan,
            ];

            // Insert kas kecil
            $kaskecil = Kaskecil::create($kaskecilData);

            // Insert cost ratio jika ada
            $costRatioCount = 0;
            if ($request->has('cost_ratio') && is_array($request->cost_ratio)) {
                foreach ($request->cost_ratio as $kodeCr) {
                    Kaskecilcostratio::create([
                        'kode_cr' => $kodeCr,
                        'id' => $kaskecil->id,
                    ]);
                    $costRatioCount++;
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data kas kecil berhasil disync',
                'data' => [
                    'id' => $kaskecil->id,
                    'no_bukti' => $request->no_bukti,
                    'total_cost_ratio' => $costRatioCount,
                    'created_at' => now()->toDateTimeString()
                ]
            ], 201);

        } catch (Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal sync data kas kecil',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cek apakah no_bukti sudah ada
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function check(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'no_bukti' => 'required|string|max:12'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $exists = Kaskecil::where('no_bukti', $request->no_bukti)->exists();

        return response()->json([
            'success' => true,
            'exists' => $exists,
            'no_bukti' => $request->no_bukti
        ]);
    }

    /**
     * Sync multiple kas kecil sekaligus (batch)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function syncBatch(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'data' => 'required|array|min:1',
                'data.*.no_bukti' => 'required|string|max:12',
                'data.*.tanggal' => 'required|date',
                'data.*.jumlah' => 'required|integer',
                'data.*.debet_kredit' => 'required|string|max:1',
                'data.*.kode_akun' => 'required|string|max:6',
                'data.*.kode_cabang' => 'required|string|max:3',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $successCount = 0;
            $failedCount = 0;
            $results = [];

            foreach ($request->data as $kaskecilData) {
                try {
                    DB::beginTransaction();

                    // Cek duplikat
                    if (Kaskecil::where('no_bukti', $kaskecilData['no_bukti'])->exists()) {
                        $failedCount++;
                        $results[] = [
                            'no_bukti' => $kaskecilData['no_bukti'],
                            'status' => 'failed',
                            'message' => 'No bukti sudah ada'
                        ];
                        DB::rollBack();
                        continue;
                    }

                    // Insert header
                    $header = [
                        'no_bukti' => $kaskecilData['no_bukti'],
                        'tanggal' => $kaskecilData['tanggal'],
                        'keterangan' => $kaskecilData['keterangan'] ?? null,
                        'jumlah' => $kaskecilData['jumlah'],
                        'debet_kredit' => $kaskecilData['debet_kredit'],
                        'status_pajak' => $kaskecilData['status_pajak'] ?? 0,
                        'kode_akun' => $kaskecilData['kode_akun'],
                        'kode_cabang' => $kaskecilData['kode_cabang'],
                        'kode_peruntukan' => $kaskecilData['kode_peruntukan'] ?? null,
                    ];
                    
                    $kaskecil = Kaskecil::create($header);

                    // Insert cost ratio jika ada
                    $costRatioCount = 0;
                    if (isset($kaskecilData['cost_ratio']) && is_array($kaskecilData['cost_ratio'])) {
                        foreach ($kaskecilData['cost_ratio'] as $kodeCr) {
                            Kaskecilcostratio::create([
                                'kode_cr' => $kodeCr,
                                'id' => $kaskecil->id,
                            ]);
                            $costRatioCount++;
                        }
                    }

                    DB::commit();
                    $successCount++;
                    $results[] = [
                        'no_bukti' => $kaskecilData['no_bukti'],
                        'status' => 'success',
                        'message' => 'Berhasil disync',
                        'cost_ratio_count' => $costRatioCount
                    ];

                } catch (Exception $e) {
                    DB::rollBack();
                    $failedCount++;
                    $results[] = [
                        'no_bukti' => $kaskecilData['no_bukti'] ?? 'unknown',
                        'status' => 'failed',
                        'message' => $e->getMessage()
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Sync batch selesai. Sukses: {$successCount}, Gagal: {$failedCount}",
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
                'message' => 'Gagal sync batch',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus data kas kecil beserta cost ratio berdasarkan no_bukti
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'no_bukti' => 'required|string|max:12'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            // Cek apakah bukti ada
            $kaskecil = Kaskecil::where('no_bukti', $request->no_bukti)->first();

            if (!$kaskecil) {
                return response()->json([
                    'success' => false,
                    'message' => 'No bukti tidak ditemukan',
                    'no_bukti' => $request->no_bukti
                ], 404);
            }

            // Hitung cost ratio sebelum dihapus
            $costRatioCount = Kaskecilcostratio::where('id', $kaskecil->id)->count();

            // Hapus cost ratio terlebih dahulu (karena ada foreign key)
            Kaskecilcostratio::where('id', $kaskecil->id)->delete();

            // Hapus header
            $kaskecil->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data kas kecil berhasil dihapus',
                'data' => [
                    'no_bukti' => $request->no_bukti,
                    'deleted_cost_ratio_count' => $costRatioCount,
                    'deleted_at' => now()->toDateTimeString()
                ]
            ], 200);

        } catch (Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data kas kecil',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus multiple kas kecil sekaligus (batch delete)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteBatch(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'no_bukti' => 'required|array|min:1',
                'no_bukti.*' => 'required|string|max:12'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $successCount = 0;
            $failedCount = 0;
            $results = [];

            foreach ($request->no_bukti as $noBukti) {
                try {
                    DB::beginTransaction();

                    // Cek apakah bukti ada
                    $kaskecil = Kaskecil::where('no_bukti', $noBukti)->first();

                    if (!$kaskecil) {
                        $failedCount++;
                        $results[] = [
                            'no_bukti' => $noBukti,
                            'status' => 'failed',
                            'message' => 'No bukti tidak ditemukan'
                        ];
                        DB::rollBack();
                        continue;
                    }

                    // Hitung cost ratio
                    $costRatioCount = Kaskecilcostratio::where('id', $kaskecil->id)->count();

                    // Hapus cost ratio dan header
                    Kaskecilcostratio::where('id', $kaskecil->id)->delete();
                    $kaskecil->delete();

                    DB::commit();
                    $successCount++;
                    $results[] = [
                        'no_bukti' => $noBukti,
                        'status' => 'success',
                        'message' => 'Berhasil dihapus',
                        'deleted_cost_ratio_count' => $costRatioCount
                    ];

                } catch (Exception $e) {
                    DB::rollBack();
                    $failedCount++;
                    $results[] = [
                        'no_bukti' => $noBukti,
                        'status' => 'failed',
                        'message' => $e->getMessage()
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Hapus batch selesai. Sukses: {$successCount}, Gagal: {$failedCount}",
                'summary' => [
                    'total' => count($request->no_bukti),
                    'success' => $successCount,
                    'failed' => $failedCount
                ],
                'results' => $results
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal hapus batch',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
