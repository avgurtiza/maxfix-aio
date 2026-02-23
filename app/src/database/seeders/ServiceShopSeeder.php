<?php

namespace Database\Seeders;

use App\Models\ServiceShop;
use Illuminate\Database\Seeder;

class ServiceShopSeeder extends Seeder
{
    private array $services = [
        'oil_change',
        'brake_service',
        'tire_service',
        'engine_repair',
        'transmission',
        'battery_service',
        'ac_service',
        'detailing',
        'inspection',
        'alignment',
    ];

    private array $philippinesLocations = [
        [
            'name' => 'AutoPro Makati',
            'address' => '123 Senator Gil Puyat Avenue, Makati City',
            'city' => 'Makati',
            'postal_code' => '1200',
            'lat' => 14.5547,
            'lng' => 121.0244,
            'phone' => '+63 2 8123 4567',
            'email' => 'autopromakati@example.com',
            'services' => ['oil_change', 'brake_service', 'tire_service', 'inspection'],
        ],
        [
            'name' => 'Mandaluyong Car Care Center',
            'address' => '45 EDSA Corner Shaw Boulevard, Mandaluyong City',
            'city' => 'Mandaluyong',
            'postal_code' => '1550',
            'lat' => 14.5794,
            'lng' => 121.0359,
            'phone' => '+63 2 8534 7890',
            'email' => 'mandaluyongcare@example.com',
            'services' => ['engine_repair', 'transmission', 'ac_service', 'battery_service'],
        ],
        [
            'name' => 'Quezon City Auto Service',
            'address' => '789 Commonwealth Avenue, Quezon City',
            'city' => 'Quezon City',
            'postal_code' => '1119',
            'lat' => 14.6760,
            'lng' => 121.0437,
            'phone' => '+63 2 8923 4567',
            'email' => 'qcservice@example.com',
            'services' => ['oil_change', 'alignment', 'tire_service', 'detailing'],
        ],
        [
            'name' => 'Pasig Express Garage',
            'address' => '321 Ortigas Avenue, Pasig City',
            'city' => 'Pasig',
            'postal_code' => '1605',
            'lat' => 14.5764,
            'lng' => 121.0851,
            'phone' => '+63 2 8634 5678',
            'email' => 'pasigexpress@example.com',
            'services' => ['brake_service', 'engine_repair', 'inspection', 'oil_change'],
        ],
        [
            'name' => 'Taguig Premium Motors',
            'address' => '654 McKinley Road, Taguig City',
            'city' => 'Taguig',
            'postal_code' => '1630',
            'lat' => 14.5176,
            'lng' => 121.0509,
            'phone' => '+63 2 8778 9012',
            'email' => 'taguigpremium@example.com',
            'services' => ['detailing', 'ac_service', 'transmission', 'alignment'],
        ],
        [
            'name' => 'Manila Downtown Garage',
            'address' => '99 Recto Avenue, Manila',
            'city' => 'Manila',
            'postal_code' => '1008',
            'lat' => 14.5995,
            'lng' => 120.9842,
            'phone' => '+63 2 8241 5678',
            'email' => 'manilagarage@example.com',
            'services' => ['oil_change', 'battery_service', 'tire_service'],
        ],
        [
            'name' => 'Cebu Southside Auto Shop',
            'address' => '88 Natalio B. Bacalso Avenue, Cebu City',
            'city' => 'Cebu City',
            'postal_code' => '6000',
            'lat' => 10.3157,
            'lng' => 123.8854,
            'phone' => '+63 32 234 5678',
            'email' => 'cebuauto@example.com',
            'services' => ['engine_repair', 'brake_service', 'transmission', 'inspection'],
        ],
        [
            'name' => 'Davao Car Doctor',
            'address' => '456 Quimpo Boulevard, Davao City',
            'city' => 'Davao City',
            'postal_code' => '8000',
            'lat' => 7.1907,
            'lng' => 125.4553,
            'phone' => '+63 82 234 5678',
            'email' => 'davaocardoctor@example.com',
            'services' => ['oil_change', 'ac_service', 'tire_service', 'battery_service'],
        ],
        [
            'name' => 'Caloocan North Auto Repair',
            'address' => '77 Samson Road, Caloocan City',
            'city' => 'Caloocan',
            'postal_code' => '1400',
            'lat' => 14.6570,
            'lng' => 120.9841,
            'phone' => '+63 2 8365 7890',
            'email' => 'caloocanauto@example.com',
            'services' => ['alignment', 'detailing', 'engine_repair', 'brake_service'],
        ],
        [
            'name' => 'Las Pinas Quick Service',
            'address' => '234 Alabang-Zapote Road, Las Pinas City',
            'city' => 'Las Pinas',
            'postal_code' => '1740',
            'lat' => 14.4445,
            'lng' => 120.9938,
            'phone' => '+63 2 8871 2345',
            'email' => 'laspinasquick@example.com',
            'services' => ['oil_change', 'inspection', 'tire_service'],
        ],
        [
            'name' => 'Paranaque Speed Shop',
            'address' => '567 Dr. A. Santos Avenue, Paranaque City',
            'city' => 'Paranaque',
            'postal_code' => '1711',
            'lat' => 14.4793,
            'lng' => 121.0198,
            'phone' => '+63 2 8825 6789',
            'email' => 'paranaquespeed@example.com',
            'services' => ['transmission', 'engine_repair', 'ac_service', 'alignment'],
        ],
        [
            'name' => 'Muntinlupa Total Car Care',
            'address' => '890 Commerce Avenue, Muntinlupa City',
            'city' => 'Muntinlupa',
            'postal_code' => '1770',
            'lat' => 14.4081,
            'lng' => 121.0415,
            'phone' => '+63 2 8862 3456',
            'email' => 'muntinlupacare@example.com',
            'services' => ['detailing', 'battery_service', 'brake_service', 'oil_change'],
        ],
        [
            'name' => 'Valenzuela Motor Works',
            'address' => '12 MacArthur Highway, Valenzuela City',
            'city' => 'Valenzuela',
            'postal_code' => '1440',
            'lat' => 14.7011,
            'lng' => 120.9650,
            'phone' => '+63 2 8291 5678',
            'email' => 'valmotorworks@example.com',
            'services' => ['tire_service', 'transmission', 'inspection', 'engine_repair'],
        ],
        [
            'name' => 'Marikina Auto Specialists',
            'address' => '345 J.P. Rizal Street, Marikina City',
            'city' => 'Marikina',
            'postal_code' => '1800',
            'lat' => 14.6507,
            'lng' => 121.1029,
            'phone' => '+63 2 8942 7890',
            'email' => 'marikinaspecialists@example.com',
            'services' => ['ac_service', 'alignment', 'battery_service', 'tire_service'],
        ],
        [
            'name' => 'Pasay Highway Garage',
            'address' => '678 Roxas Boulevard, Pasay City',
            'city' => 'Pasay',
            'postal_code' => '1300',
            'lat' => 14.5378,
            'lng' => 120.9900,
            'phone' => '+63 2 8851 2345',
            'email' => 'pasaygarage@example.com',
            'services' => ['oil_change', 'detailing', 'brake_service', 'inspection'],
        ],
    ];

    public function run(): void
    {
        foreach ($this->philippinesLocations as $index => $location) {
            ServiceShop::create([
                'name' => $location['name'],
                'address' => $location['address'],
                'city' => $location['city'],
                'postal_code' => $location['postal_code'],
                'latitude' => $location['lat'],
                'longitude' => $location['lng'],
                'phone' => $location['phone'],
                'email' => $location['email'],
                'website' => 'https://example.com/'.strtolower(str_replace(' ', '-', $location['name'])),
                'services_offered' => $location['services'],
                'operating_hours' => $this->generateOperatingHours(),
                'is_verified' => $index < 10,
            ]);
        }
    }

    private function generateOperatingHours(): string
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $hours = [];

        foreach ($days as $day) {
            if ($day === 'Sunday') {
                $hours[] = "$day: Closed";
            } elseif ($day === 'Saturday') {
                $hours[] = "$day: 8:00 AM - 5:00 PM";
            } else {
                $hours[] = "$day: 8:00 AM - 6:00 PM";
            }
        }

        return implode("\n", $hours);
    }
}
