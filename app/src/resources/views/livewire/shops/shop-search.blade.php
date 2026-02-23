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
            <div class="glass-panel p-6 rounded-xl z-20 relative flex flex-col gap-4">
                <h1 class="text-2xl font-bold text-white italic tracking-wide uppercase flex items-center gap-3">
                    <span class="bg-gt-accent-orange/20 text-gt-accent-orange p-2 rounded-lg border border-gt-accent-orange/30">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    Tuning Shops
                </h1>
                
                <!-- Search -->
                <div class="relative">
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Shop name or address..."
                        class="input-gt w-full rounded pl-10"
                    >
                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gt-text-muted">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>

                <!-- City -->
                <div>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="city" 
                        placeholder="Filter by city..."
                        class="input-gt w-full rounded"
                    >
                </div>

                <!-- Service Type -->
                <div class="relative">
                    <select 
                        wire:model.live="service"
                        class="input-gt w-full rounded appearance-none"
                    >
                        <option value="">All Services</option>
                        @foreach($serviceTypes as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gt-text-muted pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                <!-- Location & Distance -->
                <div class="flex gap-2">
                    <button 
                        @click="$dispatch('get-user-location')"
                        class="flex-1 btn-gt-secondary py-2 rounded text-xs font-bold uppercase tracking-wide flex items-center justify-center gap-2"
                        :disabled="gettingLocation"
                    >
                        <svg x-show="!gettingLocation" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <svg x-show="gettingLocation" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span x-text="gettingLocation ? 'Locating...' : 'Use My GPS'"></span>
                    </button>
                    
                     <select wire:model.live="radius" class="input-gt rounded w-24 text-center appearance-none cursor-pointer font-mono text-sm">
                        <option value="5">5 km</option>
                        <option value="10">10 km</option>
                        <option value="25">25 km</option>
                        <option value="50">50 km</option>
                        <option value="100">100 km</option>
                    </select>
                </div>

                <!-- Toggles -->
                <div class="flex flex-wrap gap-2 pt-2 border-t border-white/5">
                    <label class="flex items-center space-x-2 text-xs text-gt-text-secondary uppercase tracking-wider bg-gt-bg-900 px-3 py-1.5 rounded border border-white/5 cursor-pointer hover:border-gt-accent-orange/50 transition-colors {{ $verifiedOnly ? 'border-gt-accent-cyan/50 text-white' : '' }}">
                        <input type="checkbox" wire:model.live="verifiedOnly" class="rounded bg-gt-bg-800 border-gt-bg-700 text-gt-accent-cyan focus:ring-gt-accent-cyan/50">
                        <span>Gt Certified</span>
                    </label>
                </div>
                
                <div class="mt-auto pt-4 flex justify-between items-center text-xs text-gt-text-muted">
                    <a href="{{ route('shops.map') }}" class="flex items-center gap-1 hover:text-white transition-colors" wire:navigate>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                        Global Map View
                    </a>
                </div>
            </div>
        </div>

        <!-- Shop List -->
        <div class="flex-1 glass-panel rounded-xl overflow-hidden flex flex-col border border-white/5">
            <div class="p-4 border-b border-white/5 bg-white/5 flex justify-between items-center">
                <h2 class="font-bold text-white uppercase tracking-wider text-sm">Search Results</h2>
                <span class="text-xs text-gt-text-muted font-mono">{{ $shops->total() }} FOUND</span>
            </div>
            
            <div class="flex-1 overflow-y-auto no-scrollbar p-4 space-y-3">
                @if($shops->isEmpty())
                    <div class="text-center py-20 flex flex-col items-center">
                        <div class="w-20 h-20 bg-gt-bg-800 rounded-full flex items-center justify-center mb-6 border border-white/5">
                            <svg class="w-10 h-10 text-gt-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                        </div>
                        <h3 class="text-white font-bold text-lg mb-2">No Shops Found in Sector</h3>
                        <p class="text-gt-text-secondary text-sm max-w-xs">Try adjusting your search parameters or increasing the scan radius.</p>
                    </div>
                @else
                    @foreach($shops as $shop)
                        <div 
                            class="glass-card rounded-lg p-4 cursor-pointer group hover:bg-white/10 border-l-4 transition-all duration-200 {{ $selectedShopId === $shop->id ? 'border-l-gt-accent-orange bg-white/10' : 'border-l-transparent hover:border-l-white/30' }}"
                            wire:click="selectShop({{ $shop->id }})"
                        >
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-bold text-white group-hover:text-gt-accent-orange transition-colors flex items-center gap-2 text-lg">
                                        {{ $shop->name }}
                                        @if($shop->is_verified)
                                            <svg class="w-5 h-5 text-gt-accent-cyan drop-shadow-[0_0_8px_rgba(0,212,255,0.5)]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                        @endif
                                    </h3>
                                    <p class="text-sm text-gt-text-secondary mt-1">{{ $shop->address }}</p>
                                    @if(isset($shop->distance))
                                        <div class="mt-2 flex items-center gap-2">
                                            <span class="text-xs font-mono text-gt-accent-cyan bg-gt-accent-cyan/10 px-2 py-0.5 rounded border border-gt-accent-cyan/20">
                                                {{ number_format($shop->distance, 1) }} KM
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                
                                <button
                                    wire:click.stop="toggleFavorite({{ $shop->id }})"
                                    class="p-2 rounded-full transition-all duration-300 {{ in_array($shop->id, $favoriteIds) ? 'text-red-500 bg-red-500/10 hover:bg-red-500/20' : 'text-gt-text-muted hover:text-red-500 hover:bg-white/5' }}"
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

            <div class="p-4 border-t border-white/5 bg-white/5">
                {{ $shops->links() }}
            </div>
        </div>
    </div>

    @if($showDetailsModal && $selectedShopId)
        <div class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50 p-4 transition-opacity">
            <div class="bg-gt-bg-900 rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto border border-white/10" @click.away="closeDetails">
                @livewire('shops.shop-details', ['shop' => \App\Models\ServiceShop::find($selectedShopId)], key($selectedShopId))
                
                <div class="p-4 border-t border-white/10 bg-white/5 flex justify-end">
                    <button 
                        wire:click="closeDetails"
                        class="btn-gt-secondary px-6 py-2 rounded text-sm font-bold uppercase tracking-wide"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
