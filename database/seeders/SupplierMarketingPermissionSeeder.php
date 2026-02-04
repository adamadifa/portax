<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SupplierMarketingPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get Permission Group
        $permissionGroup = Permission_group::where('name', 'Data Master')->first();
        
        if (!$permissionGroup) {
            $permissionGroup = Permission_group::create(['name' => 'Data Master']);
        }

        // Create permissions
        $permissions = [
            'suppliermarketing.index',
            'suppliermarketing.create',
            'suppliermarketing.edit',
            'suppliermarketing.delete',
        ];

        foreach ($permissions as $permissionName) {
            $permission = Permission::firstOrCreate([
                'name' => $permissionName,
                'id_permission_group' => $permissionGroup->id
            ]);

            // Assign to roles
            $roles = ['super admin', 'operation manager', 'manager marketing', 'admin marketing'];

            foreach ($roles as $roleName) {
                $role = Role::where('name', $roleName)->first();
                if ($role) {
                    $role->givePermissionTo($permission);
                }
            }
        }
    }
}
