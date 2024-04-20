<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\{
    Permission,
    Role
};

class RolePermissionsSeeder extends Seeder
{
    protected $permissions = [
        [
            'title' => 'Create role',
            'name' => 'role_create',
            'guard_name' => 'api',
        ],
        [
            'title' => 'See role',
            'name' => 'role_read',
            'guard_name' => 'api',
        ],
        [
            'title' => 'Edit role',
            'name' => 'role_update',
            'guard_name' => 'api',
        ],
        [
            'title' => 'Exclude role',
            'name' => 'role_delete',
            'guard_name' => 'api',
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Permission::insert($this->permissions);

        $role = Role::whereName('admin')->first();
        $role->givePermissionTo(array_column($this->permissions, 'name'));

        $role = Role::whereName('dev')->first();
        $role->givePermissionTo(array_column($this->permissions, 'name'));
    }
}
