<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'john@example.com')->first();
        $fleetUser = User::where('email', 'fleet@example.com')->first();

        if (!$user || !$fleetUser) {
            $this->command->warn('Users not found. Please run UserSeeder first.');
            return;
        }

        $vehicles = [
            [
                'uuid' => \Illuminate\Support\Str::uuid()->toString(),
                'vin' => '1HGCM82633A004352',
                'make' => 'Honda',
                'model' => 'Civic',
                'year' => 2020,
                'current_plate' => 'ABC 1234',
                'current_mileage' => 45000,
                'color' => 'Silver',
                'fuel_type' => 'gasoline',
                'notes' => 'Personal vehicle',
            ],
            [
                'uuid' => \Illuminate\Support\Str::uuid()->toString(),
                'vin' => 'JM1BK32G091512345',
                'make' => 'Mazda',
                'model' => '3',
                'year' => 2022,
                'current_plate' => 'XYZ 5678',
                'current_mileage' => 12000,
                'color' => 'Blue',
                'fuel_type' => 'gasoline',
                'notes' => 'Family car',
            ],
            [
                'uuid' => \Illuminate\Support\Str::uuid()->toString(),
                'vin' => 'JTDKN3DU5A0000001',
                'make' => 'Toyota',
                'model' => 'Prius',
                'year' => 2021,
                'current_plate' => 'HYB 9999',
                'current_mileage' => 28000,
                'color' => 'White',
                'fuel_type' => 'hybrid',
                'notes' => 'Eco-friendly vehicle',
            ],
        ];

        $fleetVehicles = [
            [
                'uuid' => \Illuminate\Support\Str::uuid()->toString(),
                'vin' => '1F1F15Z54F5123456',
                'make' => 'Ford',
                'model' => 'Ranger',
                'year' => 2019,
                'current_plate' => 'FLT 0001',
                'current_mileage' => 75000,
                'color' => 'Black',
                'fuel_type' => 'diesel',
                'notes' => 'Delivery vehicle 1',
            ],
            [
                'uuid' => \Illuminate\Support\Str::uuid()->toString(),
                'vin' => '1F1F15Z54F5789012',
                'make' => 'Ford',
                'model' => 'Ranger',
                'year' => 2020,
                'current_plate' => 'FLT 0002',
                'current_mileage' => 52000,
                'color' => 'White',
                'fuel_type' => 'diesel',
                'notes' => 'Delivery vehicle 2',
            ],
            [
                'uuid' => \Illuminate\Support\Str::uuid()->toString(),
                'vin' => '1C4RJFBG5KC123456',
                'make' => 'Jeep',
                'model' => 'Wrangler',
                'year' => 2021,
                'current_plate' => 'FLT 0003',
                'current_mileage' => 35000,
                'color' => 'Green',
                'fuel_type' => 'gasoline',
                'notes' => 'Off-road vehicle',
            ],
        ];

        foreach ($vehicles as $vehicle) {
            $v = Vehicle::firstOrCreate(
                ['vin' => $vehicle['vin']],
                $vehicle
            );

            $v->users()->syncWithoutDetaching([
                $user->id => [
                    'relationship' => 'owner',
                    'is_primary' => true,
                ],
            ]);
        }

        foreach ($fleetVehicles as $vehicle) {
            $v = Vehicle::firstOrCreate(
                ['vin' => $vehicle['vin']],
                $vehicle
            );

            $v->users()->syncWithoutDetaching([
                $fleetUser->id => [
                    'relationship' => 'manager',
                    'is_primary' => true,
                ],
            ]);
        }
    }
}
