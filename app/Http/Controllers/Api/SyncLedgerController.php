<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Coa;
use App\Models\Ledger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class SyncLedgerController extends Controller
{
    /**
     * Sync data ledger dari aplikasi lain
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sync(Request $request)
    {
        try {
            // Validasi request
            $validator = Validator::make($request->all(), [
                // no_bukti sebagai key (required)
                'no_bukti' => 'required|string|max:12',
                'tanggal' => 'required|date',
                'pelanggan' => 'nullable|string|max:255',
                'kode_bank' => 'required|string|max:5',
                'kode_akun' => 'required|string|max:6',
                'keterangan' => 'required|string|max:255',
                'jumlah' => 'required|integer',
                'debet_kredit' => 'required|string|max:1|in:D,K',

                // Optional fields
                'kode_peruntukan' => 'nullable|string|max:2',
                'keterangan_peruntukan' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            // Validasi foreign key - cek kode_bank
            $bankExists = Bank::where('kode_bank', $request->kode_bank)->exists();
            if (!$bankExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => [
                        'kode_bank' => ['Kode bank tidak ditemukan di database']
                    ]
                ], 422);
            }

            // Validasi foreign key - cek kode_akun
            $coaExists = Coa::where('kode_akun', $request->kode_akun)->exists();
            if (!$coaExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => [
                        'kode_akun' => ['Kode akun tidak ditemukan di database']
                    ]
                ], 422);
            }

            // Cek apakah data dengan no_bukti sudah ada
            $ledger = Ledger::find($request->no_bukti);
            $isUpdate = $ledger !== null;

            // Prepare data ledger
            $ledgerData = [
                'no_bukti' => $request->no_bukti,
                'tanggal' => $request->tanggal,
                'pelanggan' => $request->pelanggan,
                'kode_bank' => $request->kode_bank,
                'kode_akun' => $request->kode_akun,
                'keterangan' => $request->keterangan,
                'jumlah' => $request->jumlah,
                'debet_kredit' => $request->debet_kredit,
                'kode_peruntukan' => $request->kode_peruntukan,
                'keterangan_peruntukan' => $request->keterangan_peruntukan,
            ];

            if ($isUpdate) {
                // Update data yang sudah ada
                $ledger->update($ledgerData);
            } else {
                // Insert data baru
                $ledger = Ledger::create($ledgerData);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $isUpdate ? 'Data ledger berhasil diupdate' : 'Data ledger berhasil disync',
                'data' => [
                    'no_bukti' => $ledger->no_bukti,
                    'action' => $isUpdate ? 'updated' : 'created',
                    'created_at' => now()->toDateTimeString()
                ]
            ], $isUpdate ? 200 : 201);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();

            // Handle foreign key constraint violation
            if ($e->getCode() == 23000) {
                $errorMessage = 'Foreign key constraint violation';

                // Cek apakah kode_bank tidak ada
                if (strpos($e->getMessage(), 'kode_bank') !== false) {
                    $errorMessage = 'Kode bank tidak ditemukan di database';
                }
                // Cek apakah kode_akun tidak ada
                elseif (strpos($e->getMessage(), 'kode_akun') !== false) {
                    $errorMessage = 'Kode akun tidak ditemukan di database';
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Gagal sync data ledger',
                    'error' => $errorMessage,
                    'details' => config('app.debug') ? $e->getMessage() : null
                ], 422);
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal sync data ledger',
                'error' => 'Database error: ' . $e->getMessage(),
                'details' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal sync data ledger',
                'error' => $e->getMessage(),
                'details' => config('app.debug') ? $e->getTraceAsString() : null
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

        $exists = Ledger::where('no_bukti', $request->no_bukti)->exists();

        return response()->json([
            'success' => true,
            'exists' => $exists,
            'no_bukti' => $request->no_bukti
        ]);
    }

    /**
     * Sync multiple ledger sekaligus (batch)
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
                'data.*.kode_bank' => 'required|string|max:5',
                'data.*.kode_akun' => 'required|string|max:6',
                'data.*.keterangan' => 'required|string|max:255',
                'data.*.jumlah' => 'required|integer',
                'data.*.debet_kredit' => 'required|string|max:1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Validasi duplikasi no_bukti dalam satu request
            $noBuktis = array_column($request->data, 'no_bukti');
            $duplicateNoBuktis = array_diff_assoc($noBuktis, array_unique($noBuktis));

            if (!empty($duplicateNoBuktis)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal: Terdapat duplikasi No Bukti dalam request',
                    'errors' => [
                        'duplicate_no_bukti' => array_values(array_unique($duplicateNoBuktis))
                    ]
                ], 422);
            }

            $successCount = 0;
            $failedCount = 0;
            $results = [];

            foreach ($request->data as $ledgerData) {
                try {
                    DB::beginTransaction();

                    // Cek apakah data dengan no_bukti sudah ada
                    $ledger = Ledger::find($ledgerData['no_bukti']);
                    $isUpdate = $ledger !== null;

                    // Prepare header
                    $header = [
                        'no_bukti' => $ledgerData['no_bukti'],
                        'tanggal' => $ledgerData['tanggal'],
                        'pelanggan' => $ledgerData['pelanggan'] ?? null,
                        'kode_bank' => $ledgerData['kode_bank'],
                        'kode_akun' => $ledgerData['kode_akun'],
                        'keterangan' => $ledgerData['keterangan'],
                        'jumlah' => $ledgerData['jumlah'],
                        'debet_kredit' => $ledgerData['debet_kredit'],
                        'kode_peruntukan' => $ledgerData['kode_peruntukan'] ?? null,
                        'keterangan_peruntukan' => $ledgerData['keterangan_peruntukan'] ?? null,
                    ];

                    if ($isUpdate) {
                        // Update data yang sudah ada
                        $ledger->update($header);
                    } else {
                        // Insert data baru
                        $ledger = Ledger::create($header);
                    }

                    DB::commit();
                    $successCount++;
                    $results[] = [
                        'no_bukti' => $ledgerData['no_bukti'],
                        'status' => 'success',
                        'message' => $isUpdate ? 'Berhasil diupdate' : 'Berhasil disync',
                        'action' => $isUpdate ? 'updated' : 'created'
                    ];
                } catch (Exception $e) {
                    DB::rollBack();
                    $failedCount++;
                    $results[] = [
                        'no_bukti' => $ledgerData['no_bukti'] ?? 'unknown',
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
     * Hapus data ledger berdasarkan no_bukti
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

            // Cek apakah data dengan no_bukti ada
            $ledger = Ledger::find($request->no_bukti);

            if (!$ledger) {
                return response()->json([
                    'success' => false,
                    'message' => 'No bukti tidak ditemukan',
                    'no_bukti' => $request->no_bukti
                ], 404);
            }

            // Hapus header
            $ledger->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data ledger berhasil dihapus',
                'data' => [
                    'no_bukti' => $request->no_bukti,
                    'deleted_at' => now()->toDateTimeString()
                ]
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data ledger',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus multiple ledger sekaligus (batch delete)
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

            // Validasi duplikasi no_bukti dalam satu request
            $duplicateNoBuktis = array_diff_assoc($request->no_bukti, array_unique($request->no_bukti));

            if (!empty($duplicateNoBuktis)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal: Terdapat duplikasi No Bukti dalam request',
                    'errors' => [
                        'duplicate_no_bukti' => array_values(array_unique($duplicateNoBuktis))
                    ]
                ], 422);
            }

            $successCount = 0;
            $failedCount = 0;
            $results = [];

            foreach ($request->no_bukti as $noBukti) {
                try {
                    DB::beginTransaction();

                    // Cek apakah data dengan no_bukti ada
                    $ledger = Ledger::find($noBukti);

                    if (!$ledger) {
                        $failedCount++;
                        $results[] = [
                            'no_bukti' => $noBukti,
                            'status' => 'failed',
                            'message' => 'No bukti tidak ditemukan'
                        ];
                        DB::rollBack();
                        continue;
                    }

                    // Hapus header
                    $ledger->delete();

                    DB::commit();
                    $successCount++;
                    $results[] = [
                        'no_bukti' => $noBukti,
                        'status' => 'success',
                        'message' => 'Berhasil dihapus'
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
