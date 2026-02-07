<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => Hash::make('password123'),
                'role' => 'car_owner',
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'password' => Hash::make('password123'),
                'role' => 'car_owner',
            ],
            [
                'name' => 'Fleet Manager',
                'email' => 'fleet@example.com',
                'password' => Hash::make('password123'),
                'role' => 'fleet_manager',
            ],
            [
                'name' => 'Service Personnel',
                'email' => 'service@example.com',
                'password' => Hash::make('password123'),
                'role' => 'service_personnel',
            ],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => $user['password'],
                    'role' => $user['role'],
                ]
            );
        }
    }
}
