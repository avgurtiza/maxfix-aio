<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceShopResource;
use App\Models\ServiceShop;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ShopController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = ServiceShop::query();

        if ($request->has('service')) {
            $query->offeringService($request->input('service'));
        }

        if ($request->has('city')) {
            $query->where('city', 'like', '%'.$request->input('city').'%');
        }

        if ($request->has(['lat', 'lng'])) {
            $radius = $request->input('radius', 25);
            $query->nearby(
                (float) $request->input('lat'),
                (float) $request->input('lng'),
                (int) $radius
            );
        }

        if ($request->has('verified')) {
            $query->where('is_verified', $request->boolean('verified'));
        }

        $shops = $query->paginate($request->input('per_page', 15));

        return ServiceShopResource::collection($shops);
    }

    public function show(Request $request, ServiceShop $shop): ServiceShopResource
    {
        $shop->loadCount('serviceRecords');
        $shop->load('favoritedBy');

        if ($request->user()) {
            $shop->is_favorited = $shop->favoritedBy->contains($request->user()->id);
        }

        return new ServiceShopResource($shop);
    }

    public function addFavorite(Request $request, ServiceShop $shop): JsonResponse
    {
        $request->user()->favoriteShops()->syncWithoutDetaching($shop->id);

        return response()->json([
            'message' => 'Shop added to favorites',
            'is_favorited' => true,
        ]);
    }

    public function removeFavorite(Request $request, ServiceShop $shop): JsonResponse
    {
        $request->user()->favoriteShops()->detach($shop->id);

        return response()->json([
            'message' => 'Shop removed from favorites',
            'is_favorited' => false,
        ]);
    }

    public function favorites(Request $request): AnonymousResourceCollection
    {
        $shops = $request->user()
            ->favoriteShops()
            ->withCount('serviceRecords')
            ->get();

        return ServiceShopResource::collection($shops);
    }
}
