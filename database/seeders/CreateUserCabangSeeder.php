<?php

namespace Database\Seeders;

use App\Models\Cabang;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class CreateUserCabangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "=== Create User Cabang Seeder ===\n\n";

        // Password default: 12345
        $passwordHash = Hash::make('12345');
        
        // Ambil semua cabang
        $cabangs = Cabang::orderBy('kode_cabang')->get();
        
        if ($cabangs->isEmpty()) {
            echo "⚠ Tidak ada data cabang ditemukan.\n";
            return;
        }

        // Cek apakah role operation manager ada
        $roleOperationManager = Role::where('name', 'operation manager')->first();
        if (!$roleOperationManager) {
            echo "⚠ Role 'operation manager' tidak ditemukan. Pastikan role sudah dibuat terlebih dahulu.\n";
            return;
        }

        $created = 0;
        $skipped = 0;
        $errors = [];

        foreach ($cabangs as $cabang) {
            // Buat username dari nama cabang (lowercase, tanpa spasi)
            // Contoh: "Tasikmalaya" -> "omtasikmalaya"
            $namaCabangSlug = strtolower(str_replace(' ', '', $cabang->nama_cabang));
            $username = 'om' . $namaCabangSlug;
            $email = $username . '@pedasalami.com';
            $name = 'OM ' . $cabang->nama_cabang;

            // Cek apakah user sudah ada
            $existingUser = User::where('username', $username)
                ->orWhere('email', $email)
                ->first();

            if ($existingUser) {
                echo "⏭ Skip: User dengan username '{$username}' atau email '{$email}' sudah ada.\n";
                $skipped++;
                continue;
            }

            try {
                // Buat user baru
                $user = User::create([
                    'name' => $name,
                    'username' => $username,
                    'email' => $email,
                    'password' => $passwordHash,
                    'kode_cabang' => $cabang->kode_cabang,
                    'kode_dept' => 'MKT',
                    'kode_regional' => $cabang->kode_regional ?? 'R00',
                    'dept_access' => json_encode(['MKT']),
                    'status' => '1',
                    'remember_token' => Str::random(60),
                ]);

                // Assign role operation manager
                $user->assignRole($roleOperationManager);

                echo "✓ Created: {$name} ({$username}) - {$cabang->kode_cabang}\n";
                $created++;
            } catch (\Exception $e) {
                $errorMsg = "Error creating user for {$cabang->kode_cabang}: " . $e->getMessage();
                echo "✗ {$errorMsg}\n";
                $errors[] = $errorMsg;
            }
        }

        echo "\n";
        echo "=== Summary ===\n";
        echo "Total cabang: {$cabangs->count()}\n";
        echo "User created: {$created}\n";
        echo "User skipped: {$skipped}\n";
        
        if (!empty($errors)) {
            echo "\nErrors:\n";
            foreach ($errors as $error) {
                echo "  - {$error}\n";
            }
        }
    }
}
