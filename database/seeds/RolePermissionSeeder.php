<?php

use App\Models\Role;
use App\Models\Permission;
use App\Models\RolePermission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'description' => 'Super admin',
        ]);

        $adminPermissions = collect($this->adminPermissions());
        $this->createRolePermissions($adminRole, $adminPermissions);

        $customerRole = Role::firstOrCreate([
            'name' => 'customer',
            'description' => 'Super customer',
        ]);

        $customerPermissions = collect($this->customerPermissions());
        $this->createRolePermissions($customerRole, $customerPermissions);
    }

    public function createRolePermissions(Role $role, $permissions)
    {
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'id' => $permission['id'],
                'name' => $permission['name'],
                'model' => $permission['model'],
                'description' => $permission['description'],
            ]);

            RolePermission::firstOrCreate([
                'acl_role_id' => $role->id,
                'acl_permission_id' => $permission['id'],
            ]);
        }
    }

    public function adminPermissions()
    {
        return [
            //Permission
            [
                'id' => 1,
                'name' => 'permissions.index',
                'model' => 'permission',
                'description' => 'Show all permissions'
            ],
            [
                'id' => 2,
                'name' => 'permissions.store',
                'model' => 'permission',
                'description' => 'Create permission'
            ],
            [
                'id' => 3,
                'name' => 'permissions.show',
                'model' => 'permission',
                'description' => 'Show permission by id'
            ],
            [
                'id' => 4,
                'name' => 'permissions.update',
                'model' => 'permission',
                'description' => 'Update permission'
            ],
            [
                'id' => 5,
                'name' => 'permissions.destroy',
                'model' => 'permission',
                'description' => 'Delete permission'
            ],
            //Role
            [
                'id' => 6,
                'name' => 'roles.index',
                'model' => 'role',
                'description' => 'Show all roles'
            ],
            [
                'id' => 7,
                'name' => 'roles.store',
                'model' => 'role',
                'description' => 'Create role'
            ],
            [
                'id' => 8,
                'name' => 'roles.show',
                'model' => 'role',
                'description' => 'Show role by id'
            ],
            [
                'id' => 9,
                'name' => 'roles.update',
                'model' => 'role',
                'description' => 'Update role'
            ],
            [
                'id' => 10,
                'name' => 'roles.destroy',
                'model' => 'role',
                'description' => 'Delete role'
            ],
            //Role permmissions
            [
                'id' => 11,
                'name' => 'roles.permissions.index',
                'model' => 'rolePermission',
                'description' => 'Show all role permissions by role id'
            ],
            [
                'id' => 12,
                'name' => 'roles.permissions.store',
                'model' => 'rolePermission',
                'description' => 'Create role permission'
            ],
            [
                'id' => 13,
                'name' => 'roles.permissions.destroy',
                'model' => 'rolePermission',
                'description' => 'Delete role permission'
            ],
            //User
            [
                'id' => 14,
                'name' => 'users.index',
                'model' => 'user',
                'description' => 'Show all users'
            ],
            [
                'id' => 15,
                'name' => 'users.store',
                'model' => 'user',
                'description' => 'Create user'
            ],
            [
                'id' => 16,
                'name' => 'users.show',
                'model' => 'user',
                'description' => 'Show user by id'
            ],
            [
                'id' => 17,
                'name' => 'users.update',
                'model' => 'user',
                'description' => 'Update user'
            ],
            [
                'id' => 18,
                'name' => 'users.destroy',
                'model' => 'user',
                'description' => 'Delete user'
            ],
            [
                'id' => 19,
                'name' => 'users.approve',
                'model' => 'user',
                'description' => 'Approve user'
            ],
            [
                'id' => 20,
                'name' => 'users.block',
                'model' => 'user',
                'description' => 'Block user'
            ],
        ];
    }

    public function customerPermissions()
    {
        return [
        ];
    }
}
