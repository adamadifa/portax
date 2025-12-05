<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Penjualan;
use App\Models\Detailpenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class SyncPenjualanController extends Controller
{
    /**
     * Sync data penjualan dari aplikasi lain
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sync(Request $request)
    {
        try {
            // Validasi request
            $validator = Validator::make($request->all(), [
                // Header penjualan (required)
                'no_faktur' => 'required|string|max:13|unique:marketing_penjualan,no_faktur',
                'tanggal' => 'required|date',
                'kode_pelanggan' => 'required|string|max:13',
                'kode_salesman' => 'required|string|max:7',
                'jenis_transaksi' => 'required|string|max:1|in:T,K',
                'jenis_bayar' => 'required|string|max:2',
                'status' => 'required|string|max:1',

                // Optional fields dengan default value
                'kode_akun' => 'nullable|string|max:6',
                'kode_akun_potongan' => 'nullable|string|max:6',
                'kode_akun_penyesuaian' => 'nullable|string|max:6',
                'potongan_aida' => 'nullable|integer',
                'potongan_swan' => 'nullable|integer',
                'potongan_stick' => 'nullable|integer',
                'potongan_sp' => 'nullable|integer',
                'potongan_sambal' => 'nullable|integer',
                'potongan' => 'nullable|integer',
                'potis_aida' => 'nullable|integer',
                'potis_swan' => 'nullable|integer',
                'potis_stick' => 'nullable|integer',
                'potongan_istimewa' => 'nullable|integer',
                'peny_aida' => 'nullable|integer',
                'peny_swan' => 'nullable|integer',
                'peny_stick' => 'nullable|integer',
                'penyesuaian' => 'nullable|integer',
                'ppn' => 'nullable|integer',
                'jatuh_tempo' => 'nullable|date',
                'routing' => 'nullable|string|max:255',
                'signature' => 'nullable|string|max:255',
                'tanggal_pelunasan' => 'nullable|date',
                'print' => 'nullable|integer',
                'id_user' => 'required|integer',
                'keterangan' => 'nullable|string|max:255',
                'status_batal' => 'nullable|string|max:1',
                'lock_print' => 'nullable|string|max:1',

                // Detail penjualan (array)
                'detail' => 'required|array|min:1',
                'detail.*.kode_harga' => 'required|string|max:7',
                'detail.*.harga_dus' => 'required|integer',
                'detail.*.harga_pack' => 'required|integer',
                'detail.*.harga_pcs' => 'required|integer',
                'detail.*.jumlah' => 'required|integer',
                'detail.*.subtotal' => 'required|integer',
                'detail.*.status_promosi' => 'nullable|string|max:1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            // Prepare data penjualan
            $penjualanData = [
                'no_faktur' => $request->no_faktur,
                'tanggal' => $request->tanggal,
                'kode_pelanggan' => $request->kode_pelanggan,
                'kode_salesman' => $request->kode_salesman,
                'kode_akun' => $request->kode_akun ?? '1-1401',
                'kode_akun_potongan' => $request->kode_akun_potongan ?? '4-2201',
                'kode_akun_penyesuaian' => $request->kode_akun_penyesuaian ?? '4-2202',
                'potongan_aida' => $request->potongan_aida ?? 0,
                'potongan_swan' => $request->potongan_swan ?? 0,
                'potongan_stick' => $request->potongan_stick ?? 0,
                'potongan_sp' => $request->potongan_sp ?? 0,
                'potongan_sambal' => $request->potongan_sambal ?? 0,
                'potongan' => $request->potongan ?? 0,
                'potis_aida' => $request->potis_aida ?? 0,
                'potis_swan' => $request->potis_swan ?? 0,
                'potis_stick' => $request->potis_stick ?? 0,
                'potongan_istimewa' => $request->potongan_istimewa ?? 0,
                'peny_aida' => $request->peny_aida ?? 0,
                'peny_swan' => $request->peny_swan ?? 0,
                'peny_stick' => $request->peny_stick ?? 0,
                'penyesuaian' => $request->penyesuaian ?? 0,
                'ppn' => $request->ppn ?? 0,
                'jenis_transaksi' => $request->jenis_transaksi,
                'jenis_bayar' => $request->jenis_bayar,
                'jatuh_tempo' => $request->jatuh_tempo,
                'status' => $request->status ?? '0',
                'routing' => $request->routing,
                'signature' => $request->signature,
                'tanggal_pelunasan' => $request->tanggal_pelunasan,
                'print' => $request->print ?? 0,
                'id_user' => $request->id_user,
                'keterangan' => $request->keterangan,
                'status_batal' => $request->status_batal ?? '0',
                'lock_print' => $request->lock_print ?? '0',
            ];

            // Insert penjualan
            $penjualan = Penjualan::create($penjualanData);

            // Insert detail penjualan
            $detailCount = 0;
            foreach ($request->detail as $detail) {
                Detailpenjualan::create([
                    'no_faktur' => $request->no_faktur,
                    'kode_harga' => $detail['kode_harga'],
                    'harga_dus' => $detail['harga_dus'],
                    'harga_pack' => $detail['harga_pack'],
                    'harga_pcs' => $detail['harga_pcs'],
                    'jumlah' => $detail['jumlah'],
                    'subtotal' => $detail['subtotal'],
                    'status_promosi' => $detail['status_promosi'] ?? '0',
                ]);
                $detailCount++;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data penjualan berhasil disync',
                'data' => [
                    'no_faktur' => $request->no_faktur,
                    'total_detail' => $detailCount,
                    'created_at' => now()->toDateTimeString()
                ]
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal sync data penjualan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cek apakah no_faktur sudah ada
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function check(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'no_faktur' => 'required|string|max:13'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $exists = Penjualan::where('no_faktur', $request->no_faktur)->exists();

        return response()->json([
            'success' => true,
            'exists' => $exists,
            'no_faktur' => $request->no_faktur
        ]);
    }

    /**
     * Sync multiple penjualan sekaligus (batch)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function syncBatch(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'data' => 'required|array|min:1',
                'data.*.no_faktur' => 'required|string|max:13',
                'data.*.tanggal' => 'required|date',
                'data.*.kode_pelanggan' => 'required|string|max:13',
                'data.*.kode_salesman' => 'required|string|max:7',
                'data.*.jenis_transaksi' => 'required|string|max:1',
                'data.*.jenis_bayar' => 'required|string|max:2',
                'data.*.status' => 'required|string|max:1',
                'data.*.id_user' => 'required|integer',
                'data.*.detail' => 'required|array|min:1',
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

            foreach ($request->data as $penjualanData) {
                try {
                    DB::beginTransaction();

                    // Cek duplikat
                    if (Penjualan::where('no_faktur', $penjualanData['no_faktur'])->exists()) {
                        $failedCount++;
                        $results[] = [
                            'no_faktur' => $penjualanData['no_faktur'],
                            'status' => 'failed',
                            'message' => 'No faktur sudah ada'
                        ];
                        DB::rollBack();
                        continue;
                    }

                    // Insert header
                    $header = array_merge($penjualanData, [
                        'kode_akun' => $penjualanData['kode_akun'] ?? '1-1401',
                        'kode_akun_potongan' => $penjualanData['kode_akun_potongan'] ?? '4-2201',
                        'kode_akun_penyesuaian' => $penjualanData['kode_akun_penyesuaian'] ?? '4-2202',
                        'potongan_aida' => $penjualanData['potongan_aida'] ?? 0,
                        'potongan_swan' => $penjualanData['potongan_swan'] ?? 0,
                        'potongan_stick' => $penjualanData['potongan_stick'] ?? 0,
                        'potongan_sp' => $penjualanData['potongan_sp'] ?? 0,
                        'potongan_sambal' => $penjualanData['potongan_sambal'] ?? 0,
                        'potongan' => $penjualanData['potongan'] ?? 0,
                        'potis_aida' => $penjualanData['potis_aida'] ?? 0,
                        'potis_swan' => $penjualanData['potis_swan'] ?? 0,
                        'potis_stick' => $penjualanData['potis_stick'] ?? 0,
                        'potongan_istimewa' => $penjualanData['potongan_istimewa'] ?? 0,
                        'peny_aida' => $penjualanData['peny_aida'] ?? 0,
                        'peny_swan' => $penjualanData['peny_swan'] ?? 0,
                        'peny_stick' => $penjualanData['peny_stick'] ?? 0,
                        'penyesuaian' => $penjualanData['penyesuaian'] ?? 0,
                        'ppn' => $penjualanData['ppn'] ?? 0,
                        'status' => $penjualanData['status'] ?? '0',
                        'print' => $penjualanData['print'] ?? 0,
                        'status_batal' => $penjualanData['status_batal'] ?? '0',
                        'lock_print' => $penjualanData['lock_print'] ?? '0',
                    ]);

                    unset($header['detail']);
                    Penjualan::create($header);

                    // Insert detail
                    foreach ($penjualanData['detail'] as $detail) {
                        Detailpenjualan::create([
                            'no_faktur' => $penjualanData['no_faktur'],
                            'kode_harga' => $detail['kode_harga'],
                            'harga_dus' => $detail['harga_dus'],
                            'harga_pack' => $detail['harga_pack'],
                            'harga_pcs' => $detail['harga_pcs'],
                            'jumlah' => $detail['jumlah'],
                            'subtotal' => $detail['subtotal'],
                            'status_promosi' => $detail['status_promosi'] ?? '0',
                        ]);
                    }

                    DB::commit();
                    $successCount++;
                    $results[] = [
                        'no_faktur' => $penjualanData['no_faktur'],
                        'status' => 'success',
                        'message' => 'Berhasil disync'
                    ];
                } catch (Exception $e) {
                    DB::rollBack();
                    $failedCount++;
                    $results[] = [
                        'no_faktur' => $penjualanData['no_faktur'] ?? 'unknown',
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
     * Hapus data penjualan beserta detailnya berdasarkan no_faktur
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'no_faktur' => 'required|string|max:13'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            // Cek apakah faktur ada
            $penjualan = Penjualan::where('no_faktur', $request->no_faktur)->first();

            if (!$penjualan) {
                return response()->json([
                    'success' => false,
                    'message' => 'No faktur tidak ditemukan',
                    'no_faktur' => $request->no_faktur
                ], 404);
            }

            // Hitung detail sebelum dihapus
            $detailCount = Detailpenjualan::where('no_faktur', $request->no_faktur)->count();

            // Hapus detail terlebih dahulu (karena ada foreign key)
            Detailpenjualan::where('no_faktur', $request->no_faktur)->delete();

            // Hapus header
            $penjualan->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data penjualan berhasil dihapus',
                'data' => [
                    'no_faktur' => $request->no_faktur,
                    'deleted_detail_count' => $detailCount,
                    'deleted_at' => now()->toDateTimeString()
                ]
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data penjualan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus multiple penjualan sekaligus (batch delete)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteBatch(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'no_faktur' => 'required|array|min:1',
                'no_faktur.*' => 'required|string|max:13'
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

            foreach ($request->no_faktur as $noFaktur) {
                try {
                    DB::beginTransaction();

                    // Cek apakah faktur ada
                    $penjualan = Penjualan::where('no_faktur', $noFaktur)->first();

                    if (!$penjualan) {
                        $failedCount++;
                        $results[] = [
                            'no_faktur' => $noFaktur,
                            'status' => 'failed',
                            'message' => 'No faktur tidak ditemukan'
                        ];
                        DB::rollBack();
                        continue;
                    }

                    // Hitung detail
                    $detailCount = Detailpenjualan::where('no_faktur', $noFaktur)->count();

                    // Hapus detail dan header
                    Detailpenjualan::where('no_faktur', $noFaktur)->delete();
                    $penjualan->delete();

                    DB::commit();
                    $successCount++;
                    $results[] = [
                        'no_faktur' => $noFaktur,
                        'status' => 'success',
                        'message' => 'Berhasil dihapus',
                        'deleted_detail_count' => $detailCount
                    ];
                } catch (Exception $e) {
                    DB::rollBack();
                    $failedCount++;
                    $results[] = [
                        'no_faktur' => $noFaktur,
                        'status' => 'failed',
                        'message' => $e->getMessage()
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Hapus batch selesai. Sukses: {$successCount}, Gagal: {$failedCount}",
                'summary' => [
                    'total' => count($request->no_faktur),
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
