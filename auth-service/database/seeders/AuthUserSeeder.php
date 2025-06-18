<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AuthUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $authUsers = [
            [
                'name' => 'Test User One',
                'email' => 'user1@gmail.com',
                'roles' => 'user',
                'password' => '123456',
            ],
            [
                'name' => 'Test User Two',
                'email' => 'user2@gmail.com',
                'roles' => 'user',
                'password' => '123456',
            ],
            [
                'name' => 'Admin User',
                'email' => 'admin@gmail.com',
                'roles' => 'admin',
                'password' => '123456',
            ]
        ];

        foreach ($authUsers as $authUser) {
            User::create($authUser);
        }
    }
}
