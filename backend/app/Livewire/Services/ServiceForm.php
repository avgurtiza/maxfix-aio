<?php

namespace App\Livewire\Services;

use App\Models\ServiceRecord;
use App\Models\ServiceShop;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
#[Title('Service Form - MaxFix')]
class ServiceForm extends Component
{
    use WithFileUploads;

    public Vehicle $vehicle;

    public ?ServiceRecord $service = null;

    public $shop_id = '';

    public string $service_date = '';

    public ?int $mileage = null;

    public string $service_type = '';

    public string $description = '';

    public ?float $cost = null;

    public $receipt;

    public bool $deleteExistingReceipt = false;

    protected function rules(): array
    {
        return [
            'shop_id' => ['nullable', 'exists:service_shops,id'],
            'service_date' => ['required', 'date'],
            'mileage' => ['nullable', 'integer', 'min:0'],
            'service_type' => ['required', 'in:oil_change,tire_rotation,brake_service,transmission,engine,electrical,air_conditioning,suspension,inspection,other'],
            'description' => ['nullable', 'string', 'max:2000'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'receipt' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ];
    }

    public function mount($vehicle, $service = null)
    {
        $this->vehicle = $vehicle;

        if ($service) {
            $this->service = $service;
            $this->shop_id = $service->shop_id ?? '';
            $this->service_date = $service->service_date->format('Y-m-d');
            $this->mileage = $service->mileage;
            $this->service_type = $service->service_type;
            $this->description = $service->description ?? '';
            $this->cost = $service->cost;
        } else {
            $this->service_date = now()->format('Y-m-d');
            $this->mileage = $this->vehicle->current_mileage;
        }
    }

    public function save()
    {
        $this->authorize('update', $this->vehicle);

        $validated = $this->validate();

        $data = [
            'shop_id' => $validated['shop_id'] ?: null,
            'service_date' => $validated['service_date'],
            'mileage' => $validated['mileage'],
            'service_type' => $validated['service_type'],
            'description' => $validated['description'] ?: null,
            'cost' => $validated['cost'],
        ];

        if ($this->service) {
            if ($this->deleteExistingReceipt && $this->service->receipt_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($this->service->receipt_path);
                $data['receipt_path'] = null;
            }

            if ($this->receipt) {
                if ($this->service->receipt_path) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($this->service->receipt_path);
                }
                $data['receipt_path'] = $this->receipt->store('receipts/'.$this->vehicle->uuid, 'public');
            }

            $this->service->update($data);

            if ($data['mileage']) {
                $this->vehicle->updateMileage($data['mileage']);
            }

            session()->flash('message', 'Service record updated successfully!');
        } else {
            $data['vehicle_id'] = $this->vehicle->id;
            $data['created_by'] = Auth::id();

            if ($this->receipt) {
                $data['receipt_path'] = $this->receipt->store('receipts/'.$this->vehicle->uuid, 'public');
            }

            $service = ServiceRecord::create($data);

            if ($data['mileage']) {
                $this->vehicle->updateMileage($data['mileage']);
            }

            session()->flash('message', 'Service record created successfully!');
        }

        return $this->redirect(route('services.history', $this->vehicle), navigate: true);
    }

    public function render()
    {
        $shops = ServiceShop::orderBy('name')->get();

        return view('livewire.services.service-form', [
            'shops' => $shops,
            'serviceTypes' => ServiceRecord::SERVICE_TYPES,
        ]);
    }
}
