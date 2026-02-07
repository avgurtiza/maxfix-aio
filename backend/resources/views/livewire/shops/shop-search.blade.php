<div x-data="{ gettingLocation: false }" @get-user-location.window="
    gettingLocation = true;
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                $wire.setLocation(position.coords.latitude, position.coords.longitude);
                gettingLocation = false;
            },
            (error) => {
                alert('Unable to get your location. Please allow location access.');
                gettingLocation = false;
            }
        );
    } else {
        alert('Geolocation is not supported by your browser.');
        gettingLocation = false;
    }
">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Find Service Shops</h1>
            <p class="mt-2 text-gray-600">Search for trusted auto service shops near you</p>
        </div>

        @if(session('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
                {{ session('message') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search"
                        placeholder="Shop name or address..."
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="city"
                        placeholder="Enter city..."
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Service Type</label>
                    <select 
                        wire:model.live="service"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                        <option value="">All Services</option>
                        @foreach($serviceTypes as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Distance</label>
                    <div class="flex items-center space-x-2">
                        <select 
                            wire:model.live="radius"
                            class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="5">5 km</option>
                            <option value="10">10 km</option>
                            <option value="25">25 km</option>
                            <option value="50">50 km</option>
                            <option value="100">100 km</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mt-4 flex flex-wrap items-center gap-4">
                <label class="inline-flex items-center">
                    <input 
                        type="checkbox" 
                        wire:model.live="verifiedOnly"
                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                    <span class="ml-2 text-sm text-gray-700">Verified shops only</span>
                </label>

                @if($userLat && $userLng)
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-green-600">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Using your location
                        </span>
                        <button 
                            wire:click="clearLocation"
                            class="text-sm text-gray-500 hover:text-gray-700 underline"
                        >
                            Clear
                        </button>
                    </div>
                @else
                    <button 
                        @click="locateUser"
                        :disabled="gettingLocation"
                        class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800"
                    >
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span x-show="!gettingLocation">Use my location</span>
                        <span x-show="gettingLocation">Getting location...</span>
                    </button>
                @endif
            </div>
        </div>

        @if($shops->isEmpty())
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No shops found</h3>
                <p class="mt-2 text-gray-500">Try adjusting your search criteria or expanding your search radius.</p>
            </div>
        @else
            <div class="mb-4 text-sm text-gray-600">
                Found {{ $shops->total() }} shop(s)
            </div>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($shops as $shop)
                    <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow">
                        <div class="p-6">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $shop->name }}</h3>
                                    <p class="text-sm text-gray-500 mt-1">{{ $shop->city }}</p>
                                </div>
                                @if($shop->is_verified)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Verified
                                    </span>
                                @endif
                            </div>

                            <p class="mt-3 text-sm text-gray-600 line-clamp-2">{{ $shop->address }}</p>

                            @if(isset($shop->distance))
                                <p class="mt-2 text-sm text-gray-500">
                                    <span class="font-medium">{{ number_format($shop->distance, 1) }} km</span> away
                                </p>
                            @endif

                            @if($shop->services_offered)
                                <div class="mt-3 flex flex-wrap gap-1">
                                    @foreach(array_slice($shop->services_offered, 0, 3) as $svc)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                            {{ $serviceTypes[$svc] ?? ucwords(str_replace('_', ' ', $svc)) }}
                                        </span>
                                    @endforeach
                                    @if(count($shop->services_offered) > 3)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                            +{{ count($shop->services_offered) - 3 }} more
                                        </span>
                                    @endif
                                </div>
                            @endif

                            <div class="mt-4 flex space-x-2">
                                <button 
                                    wire:click="showDetails({{ $shop->id }})"
                                    class="flex-1 text-center bg-blue-100 text-blue-700 px-3 py-2 rounded hover:bg-blue-200 transition-colors"
                                >
                                    View Details
                                </button>
                                <button 
                                    wire:click="toggleFavorite({{ $shop->id }})"
                                    class="px-3 py-2 rounded transition-colors {{ in_array($shop->id, $favoriteIds) ? 'text-red-600 bg-red-100 hover:bg-red-200' : 'text-gray-400 hover:text-red-600 hover:bg-red-50' }}"
                                    title="{{ in_array($shop->id, $favoriteIds) ? 'Remove from favorites' : 'Add to favorites' }}"
                                >
                                    <svg class="w-5 h-5" fill="{{ in_array($shop->id, $favoriteIds) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $shops->links() }}
            </div>
        @endif
    </div>

    @if($showDetailsModal && $selectedShopId)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                @livewire('shops.shop-details', ['shop' => ServiceShop::find($selectedShopId)], key($selectedShopId))
                
                <div class="p-4 border-t border-gray-200 bg-gray-50">
                    <button 
                        wire:click="closeDetails"
                        class="w-full bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300 transition-colors"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
