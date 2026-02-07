<?php

namespace App\Livewire\Shops;

use App\Models\ServiceShop;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Find Service Shops - MaxFix')]
class ShopSearch extends Component
{
    use WithPagination;

    public string $search = '';

    public string $city = '';

    public string $service = '';

    public ?float $userLat = null;

    public ?float $userLng = null;

    public int $radius = 25;

    public bool $verifiedOnly = false;

    public ?int $selectedShopId = null;

    public bool $showDetailsModal = false;

    protected $queryString = [
        'search',
        'city',
        'service',
        'radius',
        'verifiedOnly',
    ];

    public function mount(): void
    {
        $this->service = request()->query('service', '');
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCity(): void
    {
        $this->resetPage();
    }

    public function updatedService(): void
    {
        $this->resetPage();
    }

    public function updatedRadius(): void
    {
        $this->resetPage();
    }

    public function updatedVerifiedOnly(): void
    {
        $this->resetPage();
    }

    public function locateUser(): void
    {
        $this->dispatch('getUserLocation');
    }

    public function setLocation($lat, $lng): void
    {
        $this->userLat = $lat;
        $this->userLng = $lng;
        $this->resetPage();
    }

    public function clearLocation(): void
    {
        $this->userLat = null;
        $this->userLng = null;
        $this->resetPage();
    }

    public function showDetails(int $shopId): void
    {
        $this->selectedShopId = $shopId;
        $this->showDetailsModal = true;
    }

    public function closeDetails(): void
    {
        $this->showDetailsModal = false;
        $this->selectedShopId = null;
    }

    public function toggleFavorite(int $shopId): void
    {
        $shop = ServiceShop::findOrFail($shopId);
        $user = Auth::user();

        if ($user->favoriteShops()->where('shop_id', $shopId)->exists()) {
            $user->favoriteShops()->detach($shopId);
            session()->flash('message', 'Removed from favorites');
        } else {
            $user->favoriteShops()->attach($shopId);
            session()->flash('message', 'Added to favorites');
        }
    }

    public function render()
    {
        $query = ServiceShop::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('address', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->city) {
            $query->where('city', 'like', '%'.$this->city.'%');
        }

        if ($this->service) {
            $query->offeringService($this->service);
        }

        if ($this->userLat && $this->userLng) {
            $query->nearby($this->userLat, $this->userLng, $this->radius);
        }

        if ($this->verifiedOnly) {
            $query->where('is_verified', true);
        }

        $shops = $query->paginate(12);

        $favoriteIds = Auth::user()
            ->favoriteShops()
            ->pluck('shop_id')
            ->toArray();

        $serviceTypes = [
            'oil_change' => 'Oil Change',
            'brake_service' => 'Brake Service',
            'tire_service' => 'Tire Service',
            'engine_repair' => 'Engine Repair',
            'transmission' => 'Transmission',
            'battery_service' => 'Battery Service',
            'ac_service' => 'AC Service',
            'detailing' => 'Detailing',
            'inspection' => 'Inspection',
            'alignment' => 'Wheel Alignment',
        ];

        return view('livewire.shops.shop-search', [
            'shops' => $shops,
            'favoriteIds' => $favoriteIds,
            'serviceTypes' => $serviceTypes,
        ]);
    }
}
