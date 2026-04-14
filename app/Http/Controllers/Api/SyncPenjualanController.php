<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Penjualan;
use App\Models\Detailpenjualan;
use App\Models\Salesman;
use App\Models\Pelanggan;
use App\Models\User;
use App\Models\Kategorisalesman;
use App\Models\Historibayarpenjualan;
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
            // Validasi request
            $validator = Validator::make($request->all(), [
                'no_faktur' => 'required|string|max:13', // Removed unique
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
                // 'id_user' => 'required|integer', // Auto set to 1
                'keterangan' => 'nullable|string|max:255',
                'status_batal' => 'nullable|string|max:1',
                'lock_print' => 'nullable|string|max:1',

                // Data Master Optional (Salesman)
                'salesman' => 'nullable|array',

                // Data Master Optional (Pelanggan)
                'pelanggan' => 'nullable|array',

                // Detail penjualan (array)
                'detail' => 'required|array|min:1',
                'detail.*.kode_harga' => 'required|string|max:7',
                'detail.*.harga_dus' => 'required|integer',
                'detail.*.harga_pack' => 'required|integer',
                'detail.*.harga_pcs' => 'required|integer',
                'detail.*.jumlah' => 'required|integer',
                'detail.*.subtotal' => 'required|integer',

                'detail.*.status_promosi' => 'nullable|string|max:1',

                // Historibayar (optional array)
                'historibayar' => 'nullable|array',
                'historibayar.*.no_bukti' => 'required|string|max:20',
                'historibayar.*.tanggal' => 'required|date',
                'historibayar.*.kode_salesman' => 'nullable|string|max:7',
                'historibayar.*.jenis_bayar' => 'required|string|max:2',
                'historibayar.*.jumlah' => 'required|integer',
                'historibayar.*.voucher' => 'nullable|string',
                'historibayar.*.jenis_voucher' => 'nullable|string',
                'historibayar.*.kode_lhp' => 'nullable|string',
                'historibayar.*.kode_akun' => 'nullable|string',
                'historibayar.*.keterangan' => 'nullable|string',
                // 'historibayar.*.id_user' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $id_user = 1; // Default Super Admin
            $user = User::find($id_user);
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Super Admin (ID 1) tidak ditemukan'
                ], 404);
            }
            $kode_cabang = $user->kode_cabang;

            // Check & Create Salesman
            $cekSalesman = Salesman::where('kode_salesman', $request->kode_salesman)->first();
            if (!$cekSalesman && $request->has('salesman')) {
                $salesmanData = $request->salesman;
                if (!isset($salesmanData['kode_cabang'])) {
                    $salesmanData['kode_cabang'] = $kode_cabang;
                }

                // Filter columns to prevent "Column not found" error
                $tableColumns = \Illuminate\Support\Facades\Schema::getColumnListing('salesman');
                $filteredData = array_intersect_key($salesmanData, array_flip($tableColumns));

                Salesman::create($filteredData);
            }

            // Check & Create Pelanggan
            $cekPelanggan = Pelanggan::where('kode_pelanggan', $request->kode_pelanggan)->first();
            if (!$cekPelanggan && $request->has('pelanggan')) {
                $pelangganData = $request->pelanggan;
                if (!isset($pelangganData['kode_cabang'])) {
                    $pelangganData['kode_cabang'] = $kode_cabang;
                }
                if (!isset($pelangganData['kode_salesman'])) {
                    $pelangganData['kode_salesman'] = $request->kode_salesman;
                }

                // Filter columns
                $tableColumns = \Illuminate\Support\Facades\Schema::getColumnListing('pelanggan');
                $filteredData = array_intersect_key($pelangganData, array_flip($tableColumns));

                Pelanggan::create($filteredData);
            }


            // Get Salesman for Auto Numbering
            $salesman = Salesman::join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang')
                ->where('kode_salesman', $request->kode_salesman)->first();
            $no_fak_new = null;
            if ($salesman) {
                $tahun = date('y', strtotime($request->tanggal));
                $thn = date('Y', strtotime($request->tanggal));
                $start_date = "2024-03-01";

                if ($request->tanggal >= '2024-03-01' && $salesman->kode_cabang != "PST") {
                    $lastransaksi = Penjualan::join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
                        ->where('tanggal', '>=', $start_date)
                        ->whereRaw('MID(no_fak_new,6,1)="' . $salesman->kode_sales . '"')
                        ->where('salesman.kode_cabang', $salesman->kode_cabang)
                        ->whereRaw('YEAR(tanggal)="' . $thn . '"')
                        ->whereRaw('LEFT(no_fak_new,3)="' . $salesman->kode_pt . '"')
                        ->orderBy('no_fak_new', 'desc')
                        ->first();

                    $last_no_fak_new = $lastransaksi != NULL ? $lastransaksi->no_fak_new : "";
                    $no_fak_new = buatkode($last_no_fak_new, $salesman->kode_pt . $tahun . $salesman->kode_sales, 6);
                }
            }

            // Prepare data penjualan
            $penjualanData = [
                'no_faktur' => $request->no_faktur,
                'no_fak_new' => $no_fak_new,
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
                'id_user' => 1, // Force Super Admin
                'keterangan' => $request->keterangan,
                'status_batal' => $request->status_batal ?? '0',
                'lock_print' => $request->lock_print ?? '0',
            ];

            // 1. Header: Upsert
            //$penjualanData already prepared above

            // Remove unneeded fields for update check
            $updateData = $penjualanData;
            unset($updateData['no_faktur']); // Don't update the key itself
            unset($updateData['no_fak_new']); // usually auto-generated, maybe don't update if exists? User said "update data", assuming all data.

            // Note: no_fak_new logic is complex. If record exists, we might want to keep existing no_fak_new or update it?
            // The requirement says "UPDATE the existing record with the new data".
            // However, no_fak_new generation depends on creation time usually.
            // Let's assume we keep existing no_fak_new if it exists, or update if we really want to sync it from client (but client doesn't seem to send no_fak_new).
            // Actually, the code generates no_fak_new. If updating, we probably shouldn't regenerate it if it's already there, or maybe we accept it doesn't change.
            // But lets follow "updateOrCreate".

            $penjualan = Penjualan::updateOrCreate(
                ['no_faktur' => $request->no_faktur],
                $penjualanData
            );

            // 2. Detail: Replace
            Detailpenjualan::where('no_faktur', $request->no_faktur)->delete();

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

            // 3. History: Upsert
            if ($request->has('historibayar')) {
                foreach ($request->historibayar as $bayar) {
                    Historibayarpenjualan::updateOrCreate(
                        ['no_bukti' => $bayar['no_bukti']],
                        [
                            'no_faktur' => $request->no_faktur,
                            'tanggal' => $bayar['tanggal'],
                            'kode_salesman' => $bayar['kode_salesman'] ?? $request->kode_salesman,
                            'jenis_bayar' => $bayar['jenis_bayar'],
                            'jumlah' => $bayar['jumlah'],
                            'voucher' => $bayar['voucher'] ?? '0',
                            'jenis_voucher' => $bayar['jenis_voucher'] ?? '0',
                            'kode_lhp' => $bayar['kode_lhp'] ?? null,
                            'kode_akun' => $bayar['kode_akun'] ?? '1-1401',
                            'keterangan' => $bayar['keterangan'] ?? null,
                            'id_user' => $bayar['id_user'] ?? $id_user,
                        ]
                    );
                }
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
            \Illuminate\Support\Facades\Log::error('Sync Penjualan Error: ' . $e->getMessage());
            \Illuminate\Support\Facades\Log::error('Trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Gagal sync data penjualan' + $e->getMessage(),
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
            //dd('error');
            $validator = Validator::make($request->all(), [
                'data' => 'required|array|min:1',
                'data.*.no_faktur' => 'required|string|max:13',
                'data.*.tanggal' => 'required|date',
                'data.*.kode_pelanggan' => 'required|string|max:13',
                'data.*.kode_salesman' => 'required|string|max:7',
                'data.*.jenis_transaksi' => 'required|string|max:1',
                'data.*.jenis_bayar' => 'required|string|max:2',
                'data.*.status' => 'required|string|max:1',
                // 'data.*.id_user' => 'required|integer',

                // Data Master Optional Batch
                'data.*.salesman' => 'nullable|array',
                'data.*.pelanggan' => 'nullable|array',

                'data.*.detail' => 'required|array|min:1',

                // Historibayar batch
                'data.*.historibayar' => 'nullable|array',
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
            $kode_cabang = $user->kode_cabang;

            $data = $request->data;

            // Sort data by tanggal ASC so that insertion order matches date order (important for auto-numbering)
            usort($data, function ($a, $b) {
                return strtotime($a['tanggal']) - strtotime($b['tanggal']);
            });

            // Extract valid no_fakturs to delete first
            $no_fakturs = array_column($data, 'no_faktur');

            // ============================================================
            // PHASE 1: DELETE all existing batch data (committed separately)
            // This MUST be committed first so that the auto-numbering query
            // in Phase 2 does not see the old records (MySQL REPEATABLE READ
            // snapshot isolation would otherwise still show deleted rows).
            // ============================================================
            DB::beginTransaction();
            try {
                $deleted_detail = Detailpenjualan::whereIn('no_faktur', $no_fakturs)->delete();
                $deleted_histori = Historibayarpenjualan::whereIn('no_faktur', $no_fakturs)->delete();
                $deleted_header = Penjualan::whereIn('no_faktur', $no_fakturs)->delete();
                DB::commit(); // Commit DELETE so it's visible to next queries
            } catch (Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus data lama batch',
                    'error' => $e->getMessage()
                ], 500);
            }

            // DEBUG: Check what remains in DB after delete commit
            $debug_info = [
                'deleted_header' => $deleted_header,
                'deleted_detail' => $deleted_detail,
                'deleted_histori' => $deleted_histori,
                'batch_no_fakturs_count' => count($no_fakturs),
                'first_record_debug' => null, // Will be filled for the first record
            ];

            // ============================================================
            // PHASE 2: INSERT new data (in a new transaction)
            // Now the old records are truly gone, so buatkode() will find
            // the correct last number (or empty string if all were deleted).
            // ============================================================
            DB::beginTransaction();
            try {
                $successCount = 0;
                $results = [];
                $isFirstRecord = true;

                foreach ($data as $penjualanData) {

                    // Force User ID
                    $penjualanData['id_user'] = $id_user;

                    // Auto Numbering Logic
                    // Since old records are committed-deleted, query sees clean state
                    $no_fak_new = null;

                    $salesmanBatch = Salesman::join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang')
                        ->where('kode_salesman', $penjualanData['kode_salesman'])->first();

                    if ($salesmanBatch) {
                        $tahun = date('y', strtotime($penjualanData['tanggal']));
                        $thn = date('Y', strtotime($penjualanData['tanggal']));
                        $start_date = "2024-03-01";

                        if ($penjualanData['tanggal'] >= '2024-03-01' && $salesmanBatch->kode_cabang != "PST") {
                            $lastransaksi = Penjualan::join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
                                ->where('tanggal', '>=', $start_date)
                                ->whereRaw('MID(no_fak_new,6,1)="' . $salesmanBatch->kode_sales . '"')
                                ->where('salesman.kode_cabang', $salesmanBatch->kode_cabang)
                                ->whereRaw('YEAR(tanggal)="' . $thn . '"')
                                ->whereRaw('LEFT(no_fak_new,3)="' . $salesmanBatch->kode_pt . '"')
                                ->orderBy('no_fak_new', 'desc')
                                ->first();

                            $last_no_fak_new = $lastransaksi != NULL ? $lastransaksi->no_fak_new : "";
                            $no_fak_new = buatkode($last_no_fak_new, $salesmanBatch->kode_pt . $tahun . $salesmanBatch->kode_sales, 6);

                            // DEBUG: Capture info for the first record to diagnose
                            if ($isFirstRecord) {
                                $remaining_count = Penjualan::join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
                                    ->where('tanggal', '>=', $start_date)
                                    ->whereRaw('MID(no_fak_new,6,1)="' . $salesmanBatch->kode_sales . '"')
                                    ->where('salesman.kode_cabang', $salesmanBatch->kode_cabang)
                                    ->whereRaw('YEAR(tanggal)="' . $thn . '"')
                                    ->whereRaw('LEFT(no_fak_new,3)="' . $salesmanBatch->kode_pt . '"')
                                    ->count();

                                $debug_info['first_record_debug'] = [
                                    'kode_salesman' => $penjualanData['kode_salesman'],
                                    'kode_sales' => $salesmanBatch->kode_sales,
                                    'kode_cabang' => $salesmanBatch->kode_cabang,
                                    'kode_pt' => $salesmanBatch->kode_pt,
                                    'tahun' => $tahun,
                                    'thn' => $thn,
                                    'remaining_matching_records_after_delete' => $remaining_count,
                                    'lastransaksi_found' => $lastransaksi != NULL ? $lastransaksi->no_fak_new : 'NULL (empty)',
                                    'lastransaksi_no_faktur' => $lastransaksi != NULL ? $lastransaksi->no_faktur : 'NULL',
                                    'generated_no_fak_new' => $no_fak_new,
                                ];
                                $isFirstRecord = false;
                            }
                        }
                    }

                    // Prepare Header Data
                    $header = array_merge($penjualanData, [
                        'no_fak_new' => $no_fak_new,
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

                    // Remove non-table columns
                    unset($header['detail']);
                    unset($header['historibayar']);
                    unset($header['salesman']);
                    unset($header['pelanggan']);

                    // Create New Record
                    Penjualan::create($header);

                    // Insert Details
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

                    // Insert History
                    if (isset($penjualanData['historibayar']) && is_array($penjualanData['historibayar'])) {
                        foreach ($penjualanData['historibayar'] as $bayar) {
                            Historibayarpenjualan::create(
                                [
                                    'no_bukti' => $bayar['no_bukti'],
                                    'no_faktur' => $penjualanData['no_faktur'],
                                    'tanggal' => $bayar['tanggal'],
                                    'kode_salesman' => $bayar['kode_salesman'] ?? $penjualanData['kode_salesman'],
                                    'jenis_bayar' => $bayar['jenis_bayar'],
                                    'jumlah' => $bayar['jumlah'],
                                    'voucher' => $bayar['voucher'] ?? '0',
                                    'jenis_voucher' => $bayar['jenis_voucher'] ?? '0',
                                    'kode_lhp' => $bayar['kode_lhp'] ?? null,
                                    'kode_akun' => $bayar['kode_akun'] ?? '1-1401',
                                    'keterangan' => $bayar['keterangan'] ?? null,
                                    'id_user' => $bayar['id_user'] ?? $id_user,
                                ]
                            );
                        }
                    }

                    $successCount++;
                    $results[] = [
                        'no_faktur' => $penjualanData['no_faktur'],
                        'status' => 'success',
                        'message' => 'Berhasil disync (re-inserted)',
                        'no_fak_new' => $no_fak_new
                    ];
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => "Sync batch selesai. Total Processed: {$successCount}",
                    'summary' => [
                        'total' => count($request->data),
                        'success' => $successCount,
                        'failed' => 0
                    ],
                    'debug' => $debug_info,
                    'results' => $results
                ], 200);

            } catch (Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal sync batch (Insert Rollback). Data lama sudah terhapus, silakan ulangi sync.',
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ], 500);
            }

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

            // Hapus histori bayar juga jika ada
            Historibayarpenjualan::where('no_faktur', $request->no_faktur)->delete();

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
                    Historibayarpenjualan::where('no_faktur', $noFaktur)->delete();
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

    /**
     * Reset nomor faktur new berdasarkan periode
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetNoFakNew(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'periode' => 'required|date_format:Y-m',
                'kode_cabang' => 'nullable|string',
                'kode_salesman' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $periode = $request->periode;
            $bulan = date('m', strtotime($periode));
            $tahun = date('Y', strtotime($periode));

            $query = Penjualan::query()
                ->select('marketing_penjualan.*', 'salesman.kode_pt', 'salesman.kode_sales', 'salesman.kode_cabang as salesman_cabang')
                ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
                ->whereMonth('marketing_penjualan.tanggal', $bulan)
                ->whereYear('marketing_penjualan.tanggal', $tahun);

            if ($request->has('kode_cabang') && !empty($request->kode_cabang)) {
                $query->where('salesman.kode_cabang', $request->kode_cabang);
            }

            if ($request->has('kode_salesman') && !empty($request->kode_salesman)) {
                $query->where('marketing_penjualan.kode_salesman', $request->kode_salesman);
            }

            // Order by tanggal and created_at to ensure sequence
            $penjualanList = $query->orderBy('marketing_penjualan.tanggal', 'asc')
                ->orderBy('marketing_penjualan.created_at', 'asc')
                ->get();

            if ($penjualanList->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tidak ada data penjualan pada periode tersebut',
                    'updated_count' => 0
                ]);
            }

            DB::beginTransaction();

            $updatedCount = 0;
            $lastCodes = []; // Cache for last codes per prefix

            // Start date of the period for finding previous last code
            $periodStartDate = $tahun . '-' . $bulan . '-01';

            foreach ($penjualanList as $penjualan) {
                $thn = date('Y', strtotime($penjualan->tanggal));
                $thn_short = date('y', strtotime($penjualan->tanggal));

                // Construct Prefix: PT + YY + SalesCode
                // Example: PST + 24 + A = PST24A
                $prefix = $penjualan->kode_pt . $thn_short . $penjualan->kode_sales;

                // Initialize last code for this prefix if not yet known in this session
                if (!isset($lastCodes[$prefix])) {
                    // Find the last no_fak_new from DB *before* this period
                    // Logic similar to existing implementation but strict on time
                    $lastTx = Penjualan::join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
                        ->where('salesman.kode_cabang', $penjualan->salesman_cabang)
                        ->whereRaw('LEFT(no_fak_new,3)="' . $penjualan->kode_pt . '"')
                        ->whereRaw('MID(no_fak_new,6,1)="' . $penjualan->kode_sales . '"')
                        ->whereRaw('YEAR(tanggal)="' . $thn . '"')
                        ->where('tanggal', '<', $periodStartDate)
                        ->orderBy('no_fak_new', 'desc')
                        ->first();

                    if ($lastTx && $lastTx->no_fak_new) {
                        $lastCodes[$prefix] = $lastTx->no_fak_new;
                    } else {
                        // If no previous transaction in this year, start from 0
                        // The format expects 6 digits number at the end.
                        // buatkode expects (last_code, prefix, length)
                        // effective last code would be prefix . '000000' so next is ...1
                        $lastCodes[$prefix] = $prefix . '000000';
                    }
                }

                // Generate new code
                $newCode = buatkode($lastCodes[$prefix], $prefix, 6);

                // Update if different
                if ($penjualan->no_fak_new !== $newCode) {
                    // Update straight to DB to avoid model events if any, or use model update
                    // Using query builder update to be faster and safe
                    Penjualan::where('no_faktur', $penjualan->no_faktur)->update(['no_fak_new' => $newCode]);
                    $updatedCount++;
                }

                // Update local cache for next iteration
                $lastCodes[$prefix] = $newCode;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Reset no_fak_new berhasil',
                'updated_count' => $updatedCount,
                'periode' => $periode
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal reset no_fak_new',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Pre-delete data penjualan sebelum sync batch dimulai.
     * Menghapus semua data yang match filter agar no_fak_new bisa reset.
     * Panggil endpoint ini SEBELUM mengirim batch pertama.
     *
     * Parameter (semua opsional, kirim yang diperlukan):
     * - kode_cabang: string
     * - kode_salesman: string  
     * - dari: date (awal periode)
     * - sampai: date (akhir periode)
     */
    public function preDeleteSync(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'kode_cabang' => 'nullable|string',
                'kode_salesman' => 'nullable|string',
                'dari' => 'nullable|date',
                'sampai' => 'nullable|date',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Minimal harus ada 1 filter agar tidak hapus semua data
            if (!$request->kode_cabang && !$request->kode_salesman && !$request->dari && !$request->sampai) {
                return response()->json([
                    'success' => false,
                    'message' => 'Minimal harus ada 1 parameter filter (kode_cabang / kode_salesman / dari / sampai)'
                ], 422);
            }

            // Build query to find matching no_fakturs
            $query = Penjualan::query();
            $query->select('marketing_penjualan.no_faktur');

            if ($request->kode_cabang) {
                $query->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
                    ->where('salesman.kode_cabang', $request->kode_cabang);
            }

            if ($request->kode_salesman) {
                $query->where('marketing_penjualan.kode_salesman', $request->kode_salesman);
            }

            if ($request->dari && $request->sampai) {
                $query->whereBetween('marketing_penjualan.tanggal', [$request->dari, $request->sampai]);
            } elseif ($request->dari) {
                $query->where('marketing_penjualan.tanggal', '>=', $request->dari);
            } elseif ($request->sampai) {
                $query->where('marketing_penjualan.tanggal', '<=', $request->sampai);
            }

            $no_fakturs = $query->pluck('marketing_penjualan.no_faktur')->toArray();

            if (empty($no_fakturs)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tidak ada data yang perlu dihapus',
                    'deleted_count' => 0
                ]);
            }

            DB::beginTransaction();
            try {
                $deleted_detail = Detailpenjualan::whereIn('no_faktur', $no_fakturs)->delete();
                $deleted_histori = Historibayarpenjualan::whereIn('no_faktur', $no_fakturs)->delete();
                $deleted_header = Penjualan::whereIn('no_faktur', $no_fakturs)->delete();
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => "Pre-delete selesai. {$deleted_header} faktur dihapus.",
                    'deleted' => [
                        'header' => $deleted_header,
                        'detail' => $deleted_detail,
                        'histori_bayar' => $deleted_histori,
                    ],
                    'filter' => [
                        'kode_cabang' => $request->kode_cabang,
                        'kode_salesman' => $request->kode_salesman,
                        'dari' => $request->dari,
                        'sampai' => $request->sampai,
                    ]
                ]);

            } catch (Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus data',
                    'error' => $e->getMessage()
                ], 500);
            }

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal pre-delete sync',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
