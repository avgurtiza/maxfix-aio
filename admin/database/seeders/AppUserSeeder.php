<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AppUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'owner@example.com'],
            [
                'name' => 'Car Owner',
                'password' => Hash::make('password'),
                'role' => 'car_owner',
                'email_verified_at' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'fleet@example.com'],
            [
                'name' => 'Fleet Manager',
                'password' => Hash::make('password'),
                'role' => 'fleet_manager',
                'email_verified_at' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'service@example.com'],
            [
                'name' => 'Service Personnel',
                'password' => Hash::make('password'),
                'role' => 'service_personnel',
                'email_verified_at' => now(),
            ]
        );
    }
}
