<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class SaldoawalpiutangPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Permission Group
        $group_id = DB::table('permission_groups')->insertGetId([
            'name' => 'Saldo Awal Piutang',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $permissions = [
            'sapiutang.index',
            'sapiutang.create',
            'sapiutang.show',
            'sapiutang.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ], [
                'id_permission_group' => $group_id
            ]);
        }

        $role = Role::where('name', 'super admin')->first();
        if ($role) {
            $role->givePermissionTo($permissions);
        }
    }
}
