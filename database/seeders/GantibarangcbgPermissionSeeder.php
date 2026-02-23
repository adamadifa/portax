<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class GantibarangcbgPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get Permission Group
        $permissionGroup = Permission_group::where('name', 'Gudang Cabang')->first();
        
        if (!$permissionGroup) {
            $permissionGroup = Permission_group::create(['name' => 'Gudang Cabang']);
        }

        // Create permissions
        $permissions = [
            'gantibarangcbg.index',
            'gantibarangcbg.create',
            'gantibarangcbg.store',
            'gantibarangcbg.show',
            'gantibarangcbg.edit',
            'gantibarangcbg.update',
            'gantibarangcbg.delete',
        ];

        foreach ($permissions as $permissionName) {
            $permission = Permission::firstOrCreate([
                'name' => $permissionName,
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
}
