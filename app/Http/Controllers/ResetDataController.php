<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class ResetDataController extends Controller
{
    /**
     * Tabel yang TIDAK akan dihapus datanya
     */
    protected $protectedTables = [
        'migrations',
        'roles',
        'permissions',
        'role_has_permissions',
        'model_has_permissions',
        'model_has_roles',
        'permission_groups',
        'failed_jobs',
        'password_reset_tokens',
        'personal_access_tokens',
        'jobs',
        'job_batches',
        'pelanggan',
        'produk',
        'produk_diskon',
        'produk_diskon_kategori',
        'produk_harga',
        'produk_jenis',
        'produk_kategori',
        'salesman',
        'supplier',
        'cabang',
        'wilayah',
        'regional',
        'salesman_kategori',
    ];

    public function index()
    {
        // Get table count
        $database = DB::getDatabaseName();
        $tables = DB::select("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = '{$database}'");
        $allTables = count($tables);
        $protectedCount = count($this->protectedTables);
        $toBeResetCount = $allTables - $protectedCount - 1; // -1 for users table (partial reset)

        $data = [
            'total_tables' => $allTables,
            'protected_tables' => $protectedCount,
            'reset_tables' => $toBeResetCount,
            'protected_list' => $this->protectedTables,
        ];

        return view('resetdata.index', $data);
    }

    public function reset(Request $request)
    {
        // Validasi konfirmasi
        $request->validate([
            'confirmation' => 'required|in:RESET',
        ], [
            'confirmation.required' => 'Anda harus mengetik RESET untuk konfirmasi',
            'confirmation.in' => 'Konfirmasi tidak valid. Ketik RESET (huruf besar)',
        ]);

        try {
            DB::beginTransaction();

            // Disable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            // Get all tables
            $database = DB::getDatabaseName();
            $tables = DB::select("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = '{$database}'");

            $deletedTables = 0;
            $skippedTables = 0;
            $log = [];

            foreach ($tables as $tableObj) {
                $table = $tableObj->TABLE_NAME;

                // Skip protected tables
                if (in_array($table, $this->protectedTables)) {
                    $skippedTables++;
                    $log[] = "⚪ {$table} - Dilindungi";
                    continue;
                }

                // Special handling for users table
                if ($table === 'users') {
                    $deleted = DB::table('users')->where('id', '!=', 1)->delete();
                    $log[] = "✅ {$table} - Dihapus {$deleted} user(s), keep user ID=1";
                    $deletedTables++;
                    continue;
                }

                // Truncate other tables
                try {
                    $count = DB::table($table)->count();
                    DB::table($table)->truncate();
                    $log[] = "✅ {$table} - Dihapus {$count} baris";
                    $deletedTables++;
                } catch (\Exception $e) {
                    $log[] = "❌ {$table} - Gagal: " . $e->getMessage();
                }
            }

            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            DB::commit();

            return redirect()->back()->with([
                'success' => 'Reset data berhasil!',
                'reset_count' => $deletedTables,
                'protected_count' => $skippedTables,
                'log' => $log,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            return redirect()->back()->with([
                'error' => 'Error saat reset data: ' . $e->getMessage()
            ]);
        }
    }
}
