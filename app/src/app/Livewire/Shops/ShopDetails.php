<?php

namespace App\Livewire\Shops;

use App\Models\ServiceShop;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ShopDetails extends Component
{
    public ServiceShop $shop;

    public bool $isFavorited = false;

    public bool $showContactInfo = false;

    public function mount(ServiceShop $shop): void
    {
        $this->shop = $shop;
        $this->isFavorited = Auth::user()
            ->favoriteShops()
            ->where('shop_id', $shop->id)
            ->exists();
    }

    public function toggleFavorite(): void
    {
        $user = Auth::user();

        if ($this->isFavorited) {
            $user->favoriteShops()->detach($this->shop->id);
            $this->isFavorited = false;
            $this->dispatch('shopUnfavorited', shopId: $this->shop->id);
        } else {
            $user->favoriteShops()->attach($this->shop->id);
            $this->isFavorited = true;
            $this->dispatch('shopFavorited', shopId: $this->shop->id);
        }
    }

    public function toggleContactInfo(): void
    {
        $this->showContactInfo = ! $this->showContactInfo;
    }

    public function render()
    {
        $this->shop->loadCount('serviceRecords');

        return view('livewire.shops.shop-details');
    }
}
