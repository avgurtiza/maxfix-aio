<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VinDecoderService
{
    private const NHTSA_API_URL = 'https://vpic.nhtsa.dot.gov/api/vehicles/DecodeVin';

    public function decode(string $vin): array
    {
        try {
            $response = Http::timeout(10)
                ->get(self::NHTSA_API_URL."/{$vin}", [
                    'format' => 'json',
                ]);

            if (! $response->successful()) {
                return [
                    'success' => false,
                    'error' => 'NHTSA API request failed',
                ];
            }

            $data = $response->json();
            $results = collect($data['Results'] ?? []);

            $make = $this->extractValue($results, 'Make');
            $model = $this->extractValue($results, 'Model');
            $year = $this->extractValue($results, 'Model Year');

            if (! $make || ! $model || ! $year) {
                return [
                    'success' => false,
                    'error' => 'Unable to decode VIN - missing required fields',
                ];
            }

            return [
                'success' => true,
                'data' => [
                    'vin' => $vin,
                    'make' => $make,
                    'model' => $model,
                    'year' => (int) $year,
                    'fuel_type' => $this->mapFuelType($this->extractValue($results, 'Fuel Type - Primary')),
                    'body_class' => $this->extractValue($results, 'Body Class'),
                    'drive_type' => $this->extractValue($results, 'Drive Type'),
                    'engine' => $this->extractValue($results, 'Engine Model'),
                ],
            ];
        } catch (\Exception $e) {
            Log::error('VIN decode error', ['vin' => $vin, 'error' => $e->getMessage()]);

            return [
                'success' => false,
                'error' => 'An error occurred while decoding the VIN',
            ];
        }
    }

    private function extractValue($results, string $variable): ?string
    {
        $item = $results->firstWhere('Variable', $variable);
        $value = $item['Value'] ?? null;

        return $value && $value !== 'Not Applicable' ? $value : null;
    }

    private function mapFuelType(?string $nhtsaFuelType): ?string
    {
        if (! $nhtsaFuelType) {
            return null;
        }

        return match (strtolower($nhtsaFuelType)) {
            'gasoline' => 'gasoline',
            'diesel' => 'diesel',
            'electric' => 'electric',
            'hybrid', 'plug-in hybrid' => 'hybrid',
            default => null,
        };
    }
}
