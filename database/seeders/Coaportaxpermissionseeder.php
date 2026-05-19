<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Coaportaxpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::firstOrCreate([
            'name' => 'Chart of Account Portax'
        ]);

        $permissions = [
            'coa_portax.index',
            'coa_portax.create',
            'coa_portax.edit',
            'coa_portax.store',
            'coa_portax.update',
            'coa_portax.delete',
        ];

        $createdPermissions = [];
        foreach ($permissions as $permissionName) {
            $createdPermissions[] = Permission::firstOrCreate([
                'name' => $permissionName,
                'id_permission_group' => $permissiongroup->id
            ]);
        }

        // Assign to Super Admin (ID 1)
        $superAdmin = Role::where('name', 'super admin')->orWhere('id', 1)->first();
        if ($superAdmin) {
            $superAdmin->givePermissionTo($createdPermissions);
        }

        // Assign to Operation Manager (ID 4)
        $operationManager = Role::where('name', 'operation manager')->orWhere('id', 4)->first();
        if ($operationManager) {
            $operationManager->givePermissionTo($createdPermissions);
        }
    }
}
