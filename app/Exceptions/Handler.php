<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        // Handle Method Not Allowed (405) untuk API
        if ($e instanceof MethodNotAllowedHttpException && $request->is('api/*')) {
            $method = $request->method();
            $path = $request->path();
            $allowedMethods = $e->getHeaders()['Allow'] ?? '';
            $allowedMethodsArray = $allowedMethods ? explode(', ', $allowedMethods) : [];

            // Daftar semua endpoint yang tersedia
            $availableEndpoints = [
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
                'POST /api/sync/jurnalumum' => 'Sync single jurnal umum',
                'POST /api/sync/jurnalumum/batch' => 'Sync batch jurnal umum',
                'POST /api/sync/jurnalumum/check' => 'Check kode_ju jurnal umum',
                'DELETE /api/sync/jurnalumum' => 'Delete single jurnal umum',
                'DELETE /api/sync/jurnalumum/batch' => 'Delete batch jurnal umum',
            ];

            // Cari endpoint yang sesuai dengan path
            $suggestedEndpoints = [];
            foreach ($availableEndpoints as $endpoint => $description) {
                $endpointPath = explode(' ', $endpoint)[1];
                if (stripos($path, str_replace('/api/', '', $endpointPath)) !== false) {
                    $suggestedEndpoints[$endpoint] = $description;
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'Method tidak diizinkan (405)',
                'error' => [
                    'method_used' => $method,
                    'path' => $path,
                    'requested_url' => $request->fullUrl(),
                    'cause' => "Method '{$method}' tidak diizinkan untuk endpoint ini",
                    'allowed_methods' => $allowedMethodsArray,
                    'suggestion' => !empty($suggestedEndpoints) ? 'Gunakan salah satu method berikut untuk endpoint ini:' : null,
                    'suggested_endpoints' => !empty($suggestedEndpoints) ? $suggestedEndpoints : null,
                    'available_endpoints' => $availableEndpoints,
                    'detail' => "Endpoint '{$path}' tidak menerima method '{$method}'. " . 
                                (!empty($allowedMethodsArray) ? "Method yang diizinkan: " . implode(', ', $allowedMethodsArray) : '')
                ]
            ], 405);
        }

        // Handle Not Found (404) untuk API
        if ($e instanceof NotFoundHttpException && $request->is('api/*')) {
            // Fallback handler di routes/api.php akan menangani ini
        }

        return parent::render($request, $e);
    }
}
