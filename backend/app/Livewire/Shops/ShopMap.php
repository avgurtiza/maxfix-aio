<?php

namespace App\Livewire\Shops;

use App\Models\ServiceShop;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;

class ShopMap extends Component
{
    #[Url]
    public string $city = '';

    #[Url]
    public ?string $serviceType = null;

    public float $mapCenterLat = 14.5995;
    public float $mapCenterLng = 120.9842;

    public int $mapZoom = 11;

    public function mount(): void
    {
        if ($this->city) {
            $this->updateMapCenter();
        }
    }

    public function updatedCity(): void
    {
        $this->updateMapCenter();
        $this->serviceType = null;
    }

    private function updateMapCenter(): void
    {
        $cityCoords = [
            'makati' => ['lat' => 14.5547, 'lng' => 121.0244],
            'quezon city' => ['lat' => 14.6760, 'lng' => 121.0437],
            'manila' => ['lat' => 14.5995, 'lng' => 120.9842],
            'taguig' => ['lat' => 14.5176, 'lng' => 121.0511],
            'pasig' => ['lat' => 14.5764, 'lng' => 121.0851],
            'paranaque' => ['lat' => 14.4793, 'lng' => 121.0195],
            'las pinas' => ['lat' => 14.4379, 'lng' => 120.9767],
            'muntinlupa' => ['lat' => 14.4099, 'lng' => 121.0218],
            'marikina' => ['lat' => 14.6507, 'lng' => 121.1029],
            'pasay' => ['lat' => 14.5378, 'lng' => 121.0015],
            'valenzuela' => ['lat' => 14.7000, 'lng' => 120.9833],
            'caloocan' => ['lat' => 14.6511, 'lng' => 120.9667],
            'malabon' => ['lat' => 14.6600, 'lng' => 120.9667],
            'navotas' => ['lat' => 14.6400, 'lng' => 120.9400],
            'san juan' => ['lat' => 14.6000, 'lng' => 121.0333],
        ];

        $key = strtolower($this->city);
        if (isset($cityCoords[$key])) {
            $this->mapCenterLat = $cityCoords[$key]['lat'];
            $this->mapCenterLng = $cityCoords[$key]['lng'];
            $this->mapZoom = 13;
        }
    }

    public function getShopsProperty(): \Illuminate\Database\Eloquent\Collection
    {
        $query = ServiceShop::query();

        if ($this->city) {
            $query->where('city', 'like', '%' . $this->city . '%');
        }

        if ($this->serviceType) {
            $query->offeringService($this->serviceType);
        }

        return $query->get();
    }

    public function render()
    {
        return view('livewire.shops.shop-map');
    }
}
