<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ResetDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:reset {--force : Skip confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset all data except important tables (users, roles, permissions). Keep only user ID 1.';

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
        'salesman_kategori'

    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Warning message
        $this->error('╔════════════════════════════════════════════════════════════╗');
        $this->error('║           ⚠️  PERINGATAN: RESET DATA  ⚠️                    ║');
        $this->error('╚════════════════════════════════════════════════════════════╝');
        $this->newLine();
        $this->warn('Fitur ini akan:');
        $this->warn('1. Menghapus SEMUA DATA dari database');
        $this->warn('2. Kecuali tabel: ' . implode(', ', $this->protectedTables));
        $this->warn('3. Menghapus semua users KECUALI user dengan ID = 1');
        $this->newLine();

        // Confirm
        if (!$this->option('force')) {
            if (!$this->confirm('Apakah Anda YAKIN ingin melanjutkan?', false)) {
                $this->info('Reset data dibatalkan.');
                return 0;
            }

            // Double confirmation
            if (!$this->confirm('KONFIRMASI LAGI: Data akan hilang permanen. Lanjutkan?', false)) {
                $this->info('Reset data dibatalkan.');
                return 0;
            }
        }

        $this->info('Memulai proses reset data...');
        $this->newLine();

        try {
            DB::beginTransaction();

            // Disable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            // Get all tables
            $tables = $this->getAllTables();

            $deletedTables = 0;
            $skippedTables = 0;

            $progressBar = $this->output->createProgressBar(count($tables));
            $progressBar->start();

            foreach ($tables as $table) {
                $progressBar->advance();

                // Skip protected tables
                if (in_array($table, $this->protectedTables)) {
                    $skippedTables++;
                    continue;
                }

                // Special handling for users table
                if ($table === 'users') {
                    $deleted = DB::table('users')->where('id', '!=', 1)->delete();
                    $this->newLine();
                    $this->info("✓ Users: Dihapus {$deleted} user(s), keep user ID=1");
                    $deletedTables++;
                    continue;
                }

                // Truncate other tables
                try {
                    DB::table($table)->truncate();
                    $deletedTables++;
                } catch (\Exception $e) {
                    $this->newLine();
                    $this->warn("⚠ Gagal reset tabel: {$table} - " . $e->getMessage());
                }
            }

            $progressBar->finish();
            $this->newLine(2);

            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            DB::commit();

            // Success message
            $this->info('╔════════════════════════════════════════════════════════════╗');
            $this->info('║              ✅  RESET DATA BERHASIL!  ✅                   ║');
            $this->info('╚════════════════════════════════════════════════════════════╝');
            $this->newLine();
            $this->table(
                ['Keterangan', 'Jumlah'],
                [
                    ['Tabel di-reset', $deletedTables],
                    ['Tabel dilindungi', $skippedTables],
                    ['Total tabel', count($tables)],
                ]
            );

            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            $this->error('❌ Error saat reset data: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Get all tables from database
     */
    protected function getAllTables()
    {
        $database = DB::getDatabaseName();
        $tables = DB::select("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = '{$database}'");

        return array_map(function ($table) {
            return $table->TABLE_NAME;
        }, $tables);
    }
}
