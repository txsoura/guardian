<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        // Admin
        $adminRole = $this->getRole('admin');
        $adminPermissions = collect($this->adminPermissions());

        $this->createRolePermissions($adminRole, $adminPermissions);

        // User
        $userRole = $this->getRole('user');
        $userPermissions = collect($this->userPermissions());

        $this->createRolePermissions($userRole, $userPermissions);
        //next id=21
    }

    public function getRole($name)
    {
        return Role::firstOrCreate([
            'name' => $name,
            'description' => $name,
        ]);
    }

    public function createRolePermissions(Role $role, $permissions)
    {
        foreach ($permissions as $permission) {
            RolePermission::firstOrCreate([
                'acl_role_id' => $role->id,
                'acl_permission_id' => $permission,
            ]);
        }
    }

    public function adminPermissions()
    {
        return [
            //Permission
            1,
            2,
            3,
            4,
            5,
            //Role
            6,
            7,
            8,
            9,
            10,
            //Role permmissions
            11,
            12,
            13,
            //User
            14,
            15,
            16,
            17,
            18,
            19,
            20
        ];
    }

    public function userPermissions()
    {
        return [
        ];
    }
}
