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
                'name' => fake()->name(),
                'email' => 'user1@gmail.com',
                'roles' => 'user',
                'password' => 'password',
            ],
            [
                'name' => fake()->name(),
                'email' => 'user2@gmail.com',
                'roles' => 'user',
                'password' => 'password',
            ],
            [
                'name' => 'Super Root User',
                'email' => 'admin@gmail.com',
                'roles' => 'admin',
                'password' => 'password',
            ]
        ];

        foreach ($authUsers as $authUser) {
            User::create($authUser);
        }
    }
}
