<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    protected $roles = 
    [
        [
            'title' => 'Administrator',
            'name' => 'admin',
            'guard_name' => 'api',
        ],
        [
            'title' => 'Writer',
            'name' => 'writer',
            'guard_name' => 'api',
        ],
        [
            'title' => 'Reader',
            'name' => 'reader',
            'guard_name' => 'api',
        ],
        [
            'title' => 'Developer',
            'name' => 'dev',
            'guard_name' => 'api',
        ],
    ];

    protected $permissions = [
        'User' => [
            [
                'title' => 'Create user',
                'name' => 'user_create',
                'guard_name' => 'api',
            ],
            [
                'title' => 'See user',
                'name' => 'user_read',
                'guard_name' => 'api',
            ],
            [
                'title' => 'Edit user',
                'name' => 'user_update',
                'guard_name' => 'api',
            ],
            [
                'title' => 'Exclude user',
                'name' => 'user_delete',
                'guard_name' => 'api',
            ],
        ],
        'Topic' => [
            [
                'title' => 'Create topic',
                'name' => 'topic_create',
                'guard_name' => 'api',
            ],
            [
                'title' => 'See topic',
                'name' => 'topic_read',
                'guard_name' => 'api',
            ],
            [
                'title' => 'Edit topic',
                'name' => 'topic_update',
                'guard_name' => 'api',
            ],
            [
                'title' => 'Exclude topic',
                'name' => 'topic_delete',
                'guard_name' => 'api',
            ],
        ],
        'Article' => [
            [
                'title' => 'Create article',
                'name' => 'article_create',
                'guard_name' => 'api',
            ],
            [
                'title' => 'See article',
                'name' => 'article_read',
                'guard_name' => 'api',
            ],
            [
                'title' => 'Edit article',
                'name' => 'article_update',
                'guard_name' => 'api',
            ],
            [
                'title' => 'Exclude article',
                'name' => 'article_delete',
                'guard_name' => 'api',
            ],
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Role::insert($this->roles);
        foreach ($this->permissions as $key => $value)
            Permission::insert($value);

        $role = Role::whereName('dev')->first();
        foreach ($this->permissions as $key => $value)
            $role->givePermissionTo(array_column($value, 'name'));

        $role = Role::whereName('admin')->first();
        $role->givePermissionTo(array_column(
            array_filter($this->permissions['Article'], function($item) {
                return ! in_array($item['name'], ['article_create', 'article_update']); 
            }), 
            'name')
        );

        // As long as the resources were created by them.
        $role = Role::whereName('writer')->first();
        $role->givePermissionTo(array_column($this->permissions['Article'], 'name'));
        $role->givePermissionTo(array_column($this->permissions['Topic'], 'name'));

        $role = Role::whereName('reader')->first();
        $role->givePermissionTo(['article_read', 'topic_read', 'user_read']);
    }
}