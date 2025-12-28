<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coa;
use App\Models\Jurnalumum;
use App\Models\Departemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class SyncJurnalumumController extends Controller
{
    /**
     * Sync data jurnal umum dari aplikasi lain
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sync(Request $request)
    {
        try {

            // Validasi request
            $validator = Validator::make($request->all(), [
                // kode_ju sebagai key (required)
                'kode_ju' => 'required|string|max:9',
                'tanggal' => 'required|date',
                'keterangan' => 'required|string|max:255',
                'jumlah' => 'required|integer',
                'debet_kredit' => 'required|string|max:1|in:D,K',
                'kode_akun' => 'required|string|max:6',
                'kode_dept' => 'required|string|max:3',
                'kode_peruntukan' => 'required|string|max:3',
                'id_user' => 'required|integer',

                // Optional fields
                'kode_cabang' => 'nullable|string|max:3',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

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

            // Validasi foreign key - cek kode_dept
            $deptExists = Departemen::where('kode_dept', $request->kode_dept)->exists();
            if (!$deptExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => [
                        'kode_dept' => ['Kode departemen tidak ditemukan di database']
                    ]
                ], 422);
            }

            // Cek apakah data dengan kode_ju sudah ada
            $jurnalumum = Jurnalumum::find($request->kode_ju);
            $isUpdate = $jurnalumum !== null;

            // Prepare data jurnal umum
            $jurnalumumData = [
                'kode_ju' => $request->kode_ju,
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan,
                'jumlah' => $request->jumlah,
                'debet_kredit' => $request->debet_kredit,
                'kode_akun' => $request->kode_akun,
                'kode_dept' => $request->kode_dept,
                'kode_peruntukan' => $request->kode_peruntukan, // Note: typo di migration menggunakan kode_pruntukan
                'kode_cabang' => $request->kode_cabang,
                'id_user' => $request->id_user,
            ];

            if ($isUpdate) {
                // Update data yang sudah ada
                $jurnalumum->update($jurnalumumData);
            } else {
                // Insert data baru
                $jurnalumum = Jurnalumum::create($jurnalumumData);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $isUpdate ? 'Data jurnal umum berhasil diupdate' : 'Data jurnal umum berhasil disync',
                'data' => [
                    'kode_ju' => $jurnalumum->kode_ju,
                    'action' => $isUpdate ? 'updated' : 'created',
                    'created_at' => now()->toDateTimeString()
                ]
            ], $isUpdate ? 200 : 201);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();

            // Handle foreign key constraint violation
            if ($e->getCode() == 23000) {
                $errorMessage = 'Foreign key constraint violation';

                // Cek apakah kode_akun tidak ada
                if (strpos($e->getMessage(), 'kode_akun') !== false) {
                    $errorMessage = 'Kode akun tidak ditemukan di database';
                }
                // Cek apakah kode_dept tidak ada
                elseif (strpos($e->getMessage(), 'kode_dept') !== false) {
                    $errorMessage = 'Kode departemen tidak ditemukan di database';
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Gagal sync data jurnal umum',
                    'error' => $errorMessage,
                    'sql_error' => config('app.debug') ? $e->getMessage() : null,
                    'sql_code' => $e->getCode(),
                    'details' => config('app.debug') ? $e->getTraceAsString() : null
                ], 422);
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal sync data jurnal umum',
                'error' => 'Database error: ' . $e->getMessage(),
                'sql_error' => $e->getMessage(),
                'sql_code' => $e->getCode(),
                'file' => config('app.debug') ? $e->getFile() . ':' . $e->getLine() : null,
                'details' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        } catch (\Error $e) {
            DB::rollBack();

            // Handle PHP errors seperti undefined variable, undefined method, dll
            return response()->json([
                'success' => false,
                'message' => 'Gagal sync data jurnal umum',
                'error' => $e->getMessage(),
                'error_type' => get_class($e),
                'file' => $e->getFile() . ':' . $e->getLine(),
                'details' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal sync data jurnal umum',
                'error' => $e->getMessage(),
                'error_type' => get_class($e),
                'file' => config('app.debug') ? $e->getFile() . ':' . $e->getLine() : null,
                'details' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    /**
     * Cek apakah kode_ju sudah ada
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function check(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_ju' => 'required|string|max:9'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $exists = Jurnalumum::where('kode_ju', $request->kode_ju)->exists();

        return response()->json([
            'success' => true,
            'exists' => $exists,
            'kode_ju' => $request->kode_ju
        ]);
    }

    /**
     * Sync multiple jurnal umum sekaligus (batch)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function syncBatch(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'data' => 'required|array|min:1',
                'data.*.kode_ju' => 'required|string|max:9',
                'data.*.tanggal' => 'required|date',
                'data.*.keterangan' => 'required|string|max:255',
                'data.*.jumlah' => 'required|integer',
                'data.*.debet_kredit' => 'required|string|max:1|in:D,K',
                'data.*.kode_akun' => 'required|string|max:6',
                'data.*.kode_dept' => 'required|string|max:3',
                'data.*.kode_peruntukan' => 'required|string|max:2',
                'data.*.id_user' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Validasi duplikasi kode_ju dalam satu request
            $kodeJus = array_column($request->data, 'kode_ju');
            $duplicateKodeJus = array_diff_assoc($kodeJus, array_unique($kodeJus));

            if (!empty($duplicateKodeJus)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal: Terdapat duplikasi Kode JU dalam request',
                    'errors' => [
                        'duplicate_kode_ju' => array_values(array_unique($duplicateKodeJus))
                    ]
                ], 422);
            }

            $successCount = 0;
            $failedCount = 0;
            $results = [];

            foreach ($request->data as $jurnalumumData) {
                try {
                    DB::beginTransaction();

                    // Validasi foreign key - cek kode_akun
                    $coaExists = Coa::where('kode_akun', $jurnalumumData['kode_akun'])->exists();
                    if (!$coaExists) {
                        throw new Exception('Kode akun tidak ditemukan di database');
                    }

                    // Validasi foreign key - cek kode_dept
                    $deptExists = Departemen::where('kode_dept', $jurnalumumData['kode_dept'])->exists();
                    if (!$deptExists) {
                        throw new Exception('Kode departemen tidak ditemukan di database');
                    }

                    // Cek apakah data dengan kode_ju sudah ada
                    $jurnalumum = Jurnalumum::find($jurnalumumData['kode_ju']);
                    $isUpdate = $jurnalumum !== null;

                    // Prepare header
                    $header = [
                        'kode_ju' => $jurnalumumData['kode_ju'],
                        'tanggal' => $jurnalumumData['tanggal'],
                        'keterangan' => $jurnalumumData['keterangan'],
                        'jumlah' => $jurnalumumData['jumlah'],
                        'debet_kredit' => $jurnalumumData['debet_kredit'],
                        'kode_akun' => $jurnalumumData['kode_akun'],
                        'kode_dept' => $jurnalumumData['kode_dept'],
                        'kode_pruntukan' => $jurnalumumData['kode_peruntukan'], // Note: typo di migration
                        'kode_cabang' => $jurnalumumData['kode_cabang'] ?? null,
                        'id_user' => $jurnalumumData['id_user'],
                    ];

                    if ($isUpdate) {
                        // Update data yang sudah ada
                        $jurnalumum->update($header);
                    } else {
                        // Insert data baru
                        $jurnalumum = Jurnalumum::create($header);
                    }

                    DB::commit();
                    $successCount++;
                    $results[] = [
                        'kode_ju' => $jurnalumumData['kode_ju'],
                        'status' => 'success',
                        'message' => $isUpdate ? 'Berhasil diupdate' : 'Berhasil disync',
                        'action' => $isUpdate ? 'updated' : 'created'
                    ];
                } catch (\Illuminate\Database\QueryException $e) {
                    DB::rollBack();
                    $failedCount++;
                    $results[] = [
                        'kode_ju' => $jurnalumumData['kode_ju'] ?? 'unknown',
                        'status' => 'failed',
                        'message' => $e->getMessage(),
                        'sql_error' => config('app.debug') ? $e->getMessage() : null,
                        'sql_code' => $e->getCode()
                    ];
                } catch (\Error $e) {
                    DB::rollBack();
                    $failedCount++;
                    $results[] = [
                        'kode_ju' => $jurnalumumData['kode_ju'] ?? 'unknown',
                        'status' => 'failed',
                        'message' => $e->getMessage(),
                        'error_type' => get_class($e),
                        'file' => config('app.debug') ? $e->getFile() . ':' . $e->getLine() : null
                    ];
                } catch (Exception $e) {
                    DB::rollBack();
                    $failedCount++;
                    $results[] = [
                        'kode_ju' => $jurnalumumData['kode_ju'] ?? 'unknown',
                        'status' => 'failed',
                        'message' => $e->getMessage(),
                        'error_type' => config('app.debug') ? get_class($e) : null
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
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal sync batch',
                'error' => 'Database error: ' . $e->getMessage(),
                'sql_error' => $e->getMessage(),
                'sql_code' => $e->getCode(),
                'details' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        } catch (\Error $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal sync batch',
                'error' => $e->getMessage(),
                'error_type' => get_class($e),
                'file' => $e->getFile() . ':' . $e->getLine(),
                'details' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal sync batch',
                'error' => $e->getMessage(),
                'error_type' => config('app.debug') ? get_class($e) : null,
                'details' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    /**
     * Hapus data jurnal umum berdasarkan kode_ju
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'kode_ju' => 'required|string|max:9'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            // Cek apakah data dengan kode_ju ada
            $jurnalumum = Jurnalumum::find($request->kode_ju);

            if (!$jurnalumum) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode JU tidak ditemukan',
                    'kode_ju' => $request->kode_ju
                ], 404);
            }

            // Hapus data
            $jurnalumum->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data jurnal umum berhasil dihapus',
                'data' => [
                    'kode_ju' => $request->kode_ju,
                    'deleted_at' => now()->toDateTimeString()
                ]
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data jurnal umum',
                'error' => 'Database error: ' . $e->getMessage(),
                'sql_error' => $e->getMessage(),
                'sql_code' => $e->getCode(),
                'details' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        } catch (\Error $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data jurnal umum',
                'error' => $e->getMessage(),
                'error_type' => get_class($e),
                'file' => $e->getFile() . ':' . $e->getLine(),
                'details' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data jurnal umum',
                'error' => $e->getMessage(),
                'error_type' => config('app.debug') ? get_class($e) : null,
                'details' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    /**
     * Hapus multiple jurnal umum sekaligus (batch delete)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteBatch(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'kode_ju' => 'required|array|min:1',
                'kode_ju.*' => 'required|string|max:9'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Validasi duplikasi kode_ju dalam satu request
            $duplicateKodeJus = array_diff_assoc($request->kode_ju, array_unique($request->kode_ju));

            if (!empty($duplicateKodeJus)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal: Terdapat duplikasi Kode JU dalam request',
                    'errors' => [
                        'duplicate_kode_ju' => array_values(array_unique($duplicateKodeJus))
                    ]
                ], 422);
            }

            $successCount = 0;
            $failedCount = 0;
            $results = [];

            foreach ($request->kode_ju as $kodeJu) {
                try {
                    DB::beginTransaction();

                    // Cek apakah data dengan kode_ju ada
                    $jurnalumum = Jurnalumum::find($kodeJu);

                    if (!$jurnalumum) {
                        $failedCount++;
                        $results[] = [
                            'kode_ju' => $kodeJu,
                            'status' => 'failed',
                            'message' => 'Kode JU tidak ditemukan'
                        ];
                        DB::rollBack();
                        continue;
                    }

                    // Hapus data
                    $jurnalumum->delete();

                    DB::commit();
                    $successCount++;
                    $results[] = [
                        'kode_ju' => $kodeJu,
                        'status' => 'success',
                        'message' => 'Berhasil dihapus'
                    ];
                } catch (\Illuminate\Database\QueryException $e) {
                    DB::rollBack();
                    $failedCount++;
                    $results[] = [
                        'kode_ju' => $kodeJu,
                        'status' => 'failed',
                        'message' => 'Database error: ' . $e->getMessage(),
                        'sql_error' => config('app.debug') ? $e->getMessage() : null,
                        'sql_code' => $e->getCode()
                    ];
                } catch (\Error $e) {
                    DB::rollBack();
                    $failedCount++;
                    $results[] = [
                        'kode_ju' => $kodeJu,
                        'status' => 'failed',
                        'message' => $e->getMessage(),
                        'error_type' => get_class($e),
                        'file' => config('app.debug') ? $e->getFile() . ':' . $e->getLine() : null
                    ];
                } catch (Exception $e) {
                    DB::rollBack();
                    $failedCount++;
                    $results[] = [
                        'kode_ju' => $kodeJu,
                        'status' => 'failed',
                        'message' => $e->getMessage(),
                        'error_type' => config('app.debug') ? get_class($e) : null
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Hapus batch selesai. Sukses: {$successCount}, Gagal: {$failedCount}",
                'summary' => [
                    'total' => count($request->kode_ju),
                    'success' => $successCount,
                    'failed' => $failedCount
                ],
                'results' => $results
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal hapus batch',
                'error' => 'Database error: ' . $e->getMessage(),
                'sql_error' => $e->getMessage(),
                'sql_code' => $e->getCode(),
                'details' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        } catch (\Error $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal hapus batch',
                'error' => $e->getMessage(),
                'error_type' => get_class($e),
                'file' => $e->getFile() . ':' . $e->getLine(),
                'details' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal hapus batch',
                'error' => $e->getMessage(),
                'error_type' => config('app.debug') ? get_class($e) : null,
                'details' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }
}
