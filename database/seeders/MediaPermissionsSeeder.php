<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class MediaPermissionsSeeder extends Seeder
{
    protected $permissions = [
        [
            'title' => 'Create media',
            'name' => 'media_create',
            'guard_name' => 'api',
        ],
        [
            'title' => 'See media',
            'name' => 'media_read',
            'guard_name' => 'api',
        ],
        [
            'title' => 'Edit media',
            'name' => 'media_update',
            'guard_name' => 'api',
        ],
        [
            'title' => 'Exclude media',
            'name' => 'media_delete',
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
