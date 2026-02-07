<?php

namespace App\Livewire\Vehicles;

use App\Models\Vehicle;
use App\Services\VinDecoderService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Vehicle Form - MaxFix')]
class VehicleForm extends Component
{
    public ?Vehicle $vehicle = null;

    public string $vin = '';

    public string $make = '';

    public string $model = '';

    public int $year = 2024;

    public string $current_plate = '';

    public int $current_mileage = 0;

    public string $color = '';

    public string $fuel_type = '';

    public string $notes = '';

    public bool $showVinDecoder = true;

    public bool $isDecoding = false;

    protected function rules(): array
    {
        return [
            'make' => ['required', 'string', 'max:50'],
            'model' => ['required', 'string', 'max:50'],
            'year' => ['required', 'integer', 'min:1900', 'max:'.(date('Y') + 1)],
            'current_plate' => ['nullable', 'string', 'max:20'],
            'current_mileage' => ['nullable', 'integer', 'min:0'],
            'color' => ['nullable', 'string', 'max:30'],
            'fuel_type' => ['nullable', 'in:gasoline,diesel,electric,hybrid'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function mount($vehicle = null)
    {
        if ($vehicle) {
            $this->vehicle = $vehicle;
            $this->vin = $vehicle->vin ?? '';
            $this->make = $vehicle->make;
            $this->model = $vehicle->model;
            $this->year = $vehicle->year;
            $this->current_plate = $vehicle->current_plate ?? '';
            $this->current_mileage = $vehicle->current_mileage;
            $this->color = $vehicle->color ?? '';
            $this->fuel_type = $vehicle->fuel_type ?? '';
            $this->notes = $vehicle->notes ?? '';
            $this->showVinDecoder = false;
        }
    }

    public function decodeVin()
    {
        $this->validate(['vin' => ['required', 'string', 'size:17']]);

        $this->isDecoding = true;

        $service = new VinDecoderService;
        $result = $service->decode($this->vin);

        if ($result['success']) {
            $this->make = $result['data']['make'];
            $this->model = $result['data']['model'];
            $this->year = $result['data']['year'];
            $this->fuel_type = $result['data']['fuel_type'] ?? '';
            $this->showVinDecoder = false;
            session()->flash('message', 'VIN decoded successfully!');
        } else {
            $this->addError('vin', 'Unable to decode VIN. Please enter vehicle details manually.');
        }

        $this->isDecoding = false;
    }

    public function save()
    {
        $this->validate();

        if (Auth::user()->isFleetManager() && ! $this->vehicle) {
            $vehicleCount = Auth::user()->vehicles()->count();
            if ($vehicleCount >= 10) {
                $this->addError('limit', 'Fleet managers can manage up to 10 vehicles.');

                return;
            }
        }

        $data = [
            'vin' => $this->vin ?: null,
            'make' => $this->make,
            'model' => $this->model,
            'year' => $this->year,
            'current_plate' => $this->current_plate ?: null,
            'current_mileage' => $this->current_mileage,
            'color' => $this->color ?: null,
            'fuel_type' => $this->fuel_type ?: null,
            'notes' => $this->notes ?: null,
        ];

        if ($this->vehicle) {
            $this->vehicle->update($data);
            session()->flash('message', 'Vehicle updated successfully!');
        } else {
            $vehicle = Vehicle::create($data);
            Auth::user()->vehicles()->attach($vehicle->id, [
                'relationship' => 'owner',
                'is_primary' => true,
            ]);
            session()->flash('message', 'Vehicle created successfully!');
        }

        return $this->redirect(route('vehicles.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.vehicles.vehicle-form');
    }
}
