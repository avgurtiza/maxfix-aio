<?php

namespace App\Livewire\Vehicles;

use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('My Vehicles - MaxFix')]
class VehicleList extends Component
{
    public function delete(Vehicle $vehicle)
    {
        $this->authorize('delete', $vehicle);
        $vehicle->delete();
        session()->flash('message', 'Vehicle deleted successfully.');
    }

    public function render()
    {
        $vehicles = Auth::user()
            ->vehicles()
            ->withCount('serviceRecords')
            ->with('activeReminders')
            ->get();

        return view('livewire.vehicles.vehicle-list', [
            'vehicles' => $vehicles,
        ]);
    }
}
