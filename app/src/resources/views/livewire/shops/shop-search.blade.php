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
    <div class="h-[calc(100vh-8rem)] flex flex-col md:flex-row gap-6">
        <!-- Filters Panel -->
        <div class="w-full md:w-1/3 flex flex-col h-full min-w-[320px]">
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm z-20 relative flex flex-col gap-5">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-3">
                    <span class="bg-brand-orange/10 text-brand-orange p-2 rounded-lg border border-brand-orange/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    Tuning Shops
                </h1>
                
                <!-- Search -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Shop name or address..."
                        class="block w-full pl-10 pr-3 py-2 border border-slate-300 rounded-lg focus:ring-brand-blue focus:border-brand-blue text-sm"
                    >
                </div>

                <!-- City -->
                <div>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="city" 
                        placeholder="Filter by city..."
                        class="block w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-brand-blue focus:border-brand-blue text-sm"
                    >
                </div>

                <!-- Service Type -->
                <div class="relative">
                    <select 
                        wire:model.live="service"
                        class="block w-full pl-3 pr-10 py-2 border border-slate-300 rounded-lg focus:ring-brand-blue focus:border-brand-blue text-sm appearance-none bg-white"
                    >
                        <option value="">All Services</option>
                        @foreach($serviceTypes as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                <!-- Location & Distance -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <button 
                        @click="$dispatch('get-user-location')"
                        class="flex-1 bg-white border border-slate-300 text-slate-700 py-2 px-3 rounded-lg text-sm font-semibold hover:bg-slate-50 transition flex items-center justify-center gap-2"
                        :disabled="gettingLocation"
                    >
                        <svg x-show="!gettingLocation" class="w-4 h-4 text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <svg x-show="gettingLocation" class="animate-spin w-4 h-4 text-brand-blue" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span x-text="gettingLocation ? 'Locating...' : 'Use My GPS'"></span>
                    </button>
                    
                    <div class="relative w-full sm:w-28 shrink-0">
                         <select wire:model.live="radius" class="block w-full pl-3 pr-8 py-2 border border-slate-300 rounded-lg focus:ring-brand-blue focus:border-brand-blue text-sm appearance-none bg-white">
                            <option value="5">5 km</option>
                            <option value="10">10 km</option>
                            <option value="25">25 km</option>
                            <option value="50">50 km</option>
                            <option value="100">100 km</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>

                <!-- Toggles -->
                <div class="flex flex-wrap gap-2 pt-4 border-t border-slate-100">
                    <label class="flex items-center space-x-2 text-sm font-medium text-slate-700 bg-slate-50 px-3 py-2 rounded-lg border border-slate-200 cursor-pointer hover:bg-slate-100 transition-colors {{ $verifiedOnly ? 'border-brand-blue bg-brand-blue/5 text-brand-blue' : '' }}">
                        <input type="checkbox" wire:model.live="verifiedOnly" class="rounded text-brand-blue focus:ring-brand-blue border-slate-300">
                        <span>Verified Shops</span>
                    </label>
                </div>
                
                <div class="mt-auto pt-4 flex justify-between items-center text-sm text-slate-500 border-t border-slate-100">
                    <a href="{{ route('shops.map') }}" class="flex items-center gap-1.5 hover:text-brand-blue transition-colors font-semibold" wire:navigate>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                        Global Map View
                    </a>
                </div>
            </div>
        </div>

        <!-- Shop List -->
        <div class="flex-1 bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="p-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <h2 class="font-bold text-slate-800 uppercase tracking-wider text-sm">Search Results</h2>
                <span class="text-xs text-slate-500 font-medium bg-slate-200 px-2 py-0.5 rounded-full">{{ $shops->total() }} FOUND</span>
            </div>
            
            <div class="flex-1 overflow-y-auto w-full p-4 space-y-3 relative">
                @if($shops->isEmpty())
                    <div class="absolute inset-0 flex flex-col items-center justify-center p-8 bg-slate-50/50">
                        <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mb-6 border border-slate-200">
                            <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                        </div>
                        <h3 class="text-slate-900 font-bold text-lg mb-2">No Shops Found in Area</h3>
                        <p class="text-slate-500 text-sm max-w-sm text-center">Try adjusting your search parameters or increasing the scanning radius to find more options.</p>
                    </div>
                @else
                    @foreach($shops as $shop)
                        <div 
                            class="bg-white rounded-lg p-4 cursor-pointer border hover:shadow-md transition-all duration-200 {{ $selectedShopId === $shop->id ? 'border-brand-blue ring-1 ring-brand-blue bg-blue-50/30' : 'border-slate-200 hover:border-slate-300' }}"
                            wire:click="selectShop({{ $shop->id }})"
                        >
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-bold text-slate-900 flex items-center gap-2 text-lg">
                                        {{ $shop->name }}
                                        @if($shop->is_verified)
                                            <svg class="w-5 h-5 text-brand-blue" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                        @endif
                                    </h3>
                                    <p class="text-sm text-slate-500 mt-1 flex items-start gap-1.5">
                                        <svg class="w-4 h-4 text-slate-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        {{ $shop->address }}
                                    </p>
                                    @if(isset($shop->distance))
                                        <div class="mt-3 flex items-center gap-2">
                                            <span class="text-xs font-semibold text-slate-600 bg-slate-100 px-2 py-1 rounded-md border border-slate-200">
                                                {{ number_format($shop->distance, 1) }} KM away
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                
                                <button
                                    wire:click.stop="toggleFavorite({{ $shop->id }})"
                                    class="p-2 rounded-full transition-all duration-200 {{ in_array($shop->id, $favoriteIds) ? 'text-red-500 bg-red-50 hover:bg-red-100' : 'text-slate-400 hover:text-red-500 hover:bg-slate-100' }}"
                                    title="{{ in_array($shop->id, $favoriteIds) ? 'Remove from favorites' : 'Add to favorites' }}"
                                >
                                    <svg class="w-6 h-6 {{ in_array($shop->id, $favoriteIds) ? 'fill-current' : 'fill-none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="p-4 border-t border-slate-100 bg-white">
                {{ $shops->links() }}
            </div>
        </div>
    </div>

    @if($showDetailsModal && $selectedShopId)
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center z-50 p-4 sm:p-6 transition-opacity">
            <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto" @click.away="closeDetails">
                @livewire('shops.shop-details', ['shop' => \App\Models\ServiceShop::find($selectedShopId)], key($selectedShopId))
                
                <div class="p-4 sm:p-6 border-t border-slate-100 bg-slate-50 flex justify-end gap-3 rounded-b-2xl">
                    <button 
                        wire:click="closeDetails"
                        class="px-5 py-2.5 bg-white border border-slate-300 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-50 transition"
                    >
                        Close Details
                    </button>
                    <a href="https://maps.google.com/?q={{ urlencode(\App\Models\ServiceShop::find($selectedShopId)->address) }}" target="_blank" class="px-5 py-2.5 bg-brand-blue text-white rounded-lg text-sm font-semibold hover:bg-blue-600 transition shadow-sm">
                        Get Directions
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
