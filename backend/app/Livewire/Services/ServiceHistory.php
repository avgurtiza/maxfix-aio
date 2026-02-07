<?php

namespace App\Livewire\Services;

use App\Models\ServiceRecord;
use App\Models\Vehicle;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Service History - MaxFix')]
class ServiceHistory extends Component
{
    public Vehicle $vehicle;

    public function delete(ServiceRecord $service)
    {
        $this->authorize('delete', $service);

        $service->delete();
        session()->flash('message', 'Service record deleted successfully.');
    }

    public function render()
    {
        $this->authorize('view', $this->vehicle);

        $services = $this->vehicle->serviceRecords()
            ->with(['shop'])
            ->get()
            ->groupBy(fn ($record) => $record->service_date->format('Y'));

        return view('livewire.services.service-history', [
            'servicesByYear' => $services,
            'serviceTypes' => ServiceRecord::SERVICE_TYPES,
        ]);
    }
}
