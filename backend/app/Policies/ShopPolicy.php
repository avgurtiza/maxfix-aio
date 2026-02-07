<?php

namespace App\Policies;

use App\Models\ServiceShop;
use App\Models\User;

class ShopPolicy
{
    public function view(User $user, ServiceShop $shop): bool
    {
        return true;
    }

    public function favorite(User $user, ServiceShop $shop): bool
    {
        return true;
    }

    public function unfavorite(User $user, ServiceShop $shop): bool
    {
        return $user->favoriteShops()->where('shop_id', $shop->id)->exists();
    }
}
