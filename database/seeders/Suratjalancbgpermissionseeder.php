<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Suratjalancbgpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Surat Jalan Gudang Cabang'
        ]);

        Permission::create([
            'name' => 'suratjalancbg.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'suratjalancbg.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'suratjalancbg.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'suratjalancbg.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'suratjalancbg.update',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'suratjalancbg.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'suratjalancbg.delete',
            'id_permission_group' => $permissiongroup->id
        ]);

        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        
        // Assign to Super Admin
        $superadmin = Role::findById(1);
        $superadmin->givePermissionTo($permissions);

        // Assign to Operation Manager
        $operationmanager = Role::findById(4);
        $operationmanager->givePermissionTo($permissions);
    }
}
