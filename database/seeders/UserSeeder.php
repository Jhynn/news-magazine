<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            'admin' => [
                'name' => 'Admin',
                'surname' => 'System',
                'username' => 'admin',
                'email' => 'admin@develop.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'status' => 'active',
            ],
            'writer' => [
                'name' => 'Writer',
                'surname' => 'System',
                'username' => 'writer',
                'email' => 'writer@develop.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'status' => 'active',
            ],
            'reader' => [
                'name' => 'Reader',
                'surname' => 'System',
                'username' => 'reader',
                'email' => 'reader@develop.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'status' => 'active',
            ],
            'dev' => [
                'name' => 'Developer',
                'surname' => 'System',
                'username' => 'dev',
                'email' => 'dev@develop.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'status' => 'active',
            ],
        ];

        foreach ($users as $role => $userData) {
            $user = User::create($userData);
            $user->assignRole($role);
        }
    }
}
