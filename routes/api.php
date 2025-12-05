<?php

use App\Http\Controllers\Api\SyncPenjualanController;
use App\Http\Controllers\Api\SyncKaskecilController;
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
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
