<?php

namespace Database\Seeders;


use App\Models\Permission_group;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class KartuhutangMarketingPermissionSeeder extends Seeder

{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get Permission Group
        $permissionGroup = Permission_group::where('name', 'Laporan Marketing')->first();
        
        if (!$permissionGroup) {
            $permissionGroup = Permission_group::create(['name' => 'Laporan Marketing']);
        }

        // Create permission
        $permission = Permission::firstOrCreate([
            'name' => 'mkt.kartuhutang',
            'id_permission_group' => $permissionGroup->id
        ]);

        // Assign to roles
        $roles = ['super admin', 'operation manager'];

        foreach ($roles as $roleName) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $role->givePermissionTo($permission);
            }
        }
    }
}
