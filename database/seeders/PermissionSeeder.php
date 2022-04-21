<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $permissions = collect($this->permissions());

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'id' => $permission['id'],
                'name' => $permission['name'],
                'model' => $permission['model'],
                'description' => $permission['description'],
            ]);
        }

        //next id=147
    }

    public function permissions()
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
            ]
        ];
    }
}
