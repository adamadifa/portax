<?php

use App\Http\Controllers\Api\SyncPenjualanController;
use App\Http\Controllers\Api\SyncKaskecilController;
use App\Http\Controllers\Api\SyncLedgerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::apiResource('/slipgaji', App\Http\Controllers\Api\SlipgajiController::class);
// Route::get('/slipgaji/{bulan}/{tahun}/{nik}', [App\Http\Controllers\Api\SlipgajiController::class, 'show']);
// Route::get('/api/slipgaji', [App\Http\Controllers\Api\SlipgajiController::class, 'index']);

Route::apiResource('/slipgaji', App\Http\Controllers\Api\SlipgajiController::class);
Route::get('/slipgaji/{bulangaji}/{tahungaji}/{nik}', [App\Http\Controllers\Api\SlipgajiController::class, 'show']);

// API Sync Penjualan dari Aplikasi Lain
Route::prefix('sync')->group(function () {
    // Penjualan
    Route::post('/penjualan', [SyncPenjualanController::class, 'sync']);
    Route::post('/penjualan/batch', [SyncPenjualanController::class, 'syncBatch']);
    Route::post('/penjualan/check', [SyncPenjualanController::class, 'check']);
    Route::delete('/penjualan', [SyncPenjualanController::class, 'delete']);
    Route::delete('/penjualan/batch', [SyncPenjualanController::class, 'deleteBatch']);

    // Kas Kecil
    Route::post('/kaskecil', [SyncKaskecilController::class, 'sync']);
    Route::post('/kaskecil/batch', [SyncKaskecilController::class, 'syncBatch']);
    Route::post('/kaskecil/check', [SyncKaskecilController::class, 'check']);
    Route::delete('/kaskecil', [SyncKaskecilController::class, 'delete']);
    Route::delete('/kaskecil/batch', [SyncKaskecilController::class, 'deleteBatch']);

    // Ledger
    Route::post('/ledger', [SyncLedgerController::class, 'sync']);
    Route::post('/ledger/batch', [SyncLedgerController::class, 'syncBatch']);
    Route::post('/ledger/check', [SyncLedgerController::class, 'check']);
    Route::delete('/ledger', [SyncLedgerController::class, 'delete']);
    Route::delete('/ledger/batch', [SyncLedgerController::class, 'deleteBatch']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Fallback untuk route API yang tidak ditemukan (404)
Route::fallback(function (Request $request) {
    // Cek apakah request ke API
    if ($request->is('api/*')) {
        $method = $request->method();
        $path = $request->path();
        $availableRoutes = [
            'POST' => [
                '/api/sync/penjualan',
                '/api/sync/penjualan/batch',
                '/api/sync/penjualan/check',
                '/api/sync/kaskecil',
                '/api/sync/kaskecil/batch',
                '/api/sync/kaskecil/check',
                '/api/sync/ledger',
                '/api/sync/ledger/batch',
                '/api/sync/ledger/check',
            ],
            'DELETE' => [
                '/api/sync/penjualan',
                '/api/sync/penjualan/batch',
                '/api/sync/kaskecil',
                '/api/sync/kaskecil/batch',
                '/api/sync/ledger',
                '/api/sync/ledger/batch',
            ],
        ];

        $suggestions = [];
        if (isset($availableRoutes[$method])) {
            foreach ($availableRoutes[$method] as $route) {
                if (stripos($route, $request->segment(2) ?? '') !== false) {
                    $suggestions[] = $route;
                }
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Endpoint tidak ditemukan (404)',
            'error' => [
                'method' => $method,
                'path' => $path,
                'requested_url' => $request->fullUrl(),
                'cause' => 'Route tidak terdaftar atau endpoint salah',
                'suggestions' => !empty($suggestions) ? $suggestions : null,
                'available_endpoints' => [
                    'POST /api/sync/penjualan' => 'Sync single penjualan',
                    'POST /api/sync/penjualan/batch' => 'Sync batch penjualan',
                    'POST /api/sync/penjualan/check' => 'Check no_faktur penjualan',
                    'DELETE /api/sync/penjualan' => 'Delete single penjualan',
                    'DELETE /api/sync/penjualan/batch' => 'Delete batch penjualan',
                    'POST /api/sync/kaskecil' => 'Sync single kas kecil',
                    'POST /api/sync/kaskecil/batch' => 'Sync batch kas kecil',
                    'POST /api/sync/kaskecil/check' => 'Check id kas kecil',
                    'DELETE /api/sync/kaskecil' => 'Delete single kas kecil',
                    'DELETE /api/sync/kaskecil/batch' => 'Delete batch kas kecil',
                    'POST /api/sync/ledger' => 'Sync single ledger',
                    'POST /api/sync/ledger/batch' => 'Sync batch ledger',
                    'POST /api/sync/ledger/check' => 'Check no_bukti ledger',
                    'DELETE /api/sync/ledger' => 'Delete single ledger',
                    'DELETE /api/sync/ledger/batch' => 'Delete batch ledger',
                ]
            ]
        ], 404);
    }

    // Untuk non-API routes, return default 404
    abort(404);
});
