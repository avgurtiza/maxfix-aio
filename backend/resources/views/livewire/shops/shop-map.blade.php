<div class="bg-white rounded-lg shadow-lg overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Service Shops Map</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                <select 
                    id="city" 
                    wire:model.live="city" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                >
                    <option value="">All Cities</option>
                    <option value="Makati">Makati</option>
                    <option value="Quezon City">Quezon City</option>
                    <option value="Manila">Manila</option>
                    <option value="Taguig">Taguig</option>
                    <option value="Pasig">Pasig</option>
                    <option value="Parañaque">Parañaque</option>
                    <option value="Las Piñas">Las Piñas</option>
                    <option value="Muntinlupa">Muntinlupa</option>
                    <option value="Marikina">Marikina</option>
                    <option value="Pasay">Pasay</option>
                    <option value="Valenzuela">Valenzuela</option>
                    <option value="Caloocan">Caloocan</option>
                    <option value="Malabon">Malabon</option>
                    <option value="Navotas">Navotas</option>
                    <option value="San Juan">San Juan</option>
                </select>
            </div>

            <div>
                <label for="service" class="block text-sm font-medium text-gray-700 mb-1">Service Type</label>
                <select 
                    id="service" 
                    wire:model.live="serviceType" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                >
                    <option value="">All Services</option>
                    <option value="oil_change">Oil Change</option>
                    <option value="brakes">Brake Service</option>
                    <option value="tires">Tire Service</option>
                    <option value="engine">Engine Repair</option>
                    <option value="electrical">Electrical</option>
                    <option value="ac">Air Conditioning</option>
                </select>
            </div>

            <div class="flex items-end">
                <a href="{{ route('shops.index') }}" class="w-full bg-gray-100 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-200 text-center">
                    List View
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3">
        <div class="w-full lg:w-1/3 border-r border-gray-200 max-h-[600px] overflow-y-auto">
            @if($shops->isEmpty())
                <div class="p-8 text-center text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <p class="mt-2 text-sm">No shops found matching your criteria</p>
                </div>
            @else
                <div class="divide-y divide-gray-200">
                    @foreach($shops as $shop)
                        <div class="p-4 hover:bg-gray-50 cursor-pointer" 
                             x-data="{ 
                                 showOnMap() { 
                                     if (window.shopMap) { 
                                         window.shopMap.setView([{{ $shop->latitude }}, {{ $shop->longitude }}], 16); 
                                         if (window.shopMarkers[{{ $shop->id }}]) { 
                                             window.shopMarkers[{{ $shop->id }}].openPopup(); 
                                         } 
                                     } 
                                 } 
                             }"
                             @click="showOnMap()">
                            <h3 class="font-semibold text-gray-900">{{ $shop->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $shop->address }}</p>
                            <p class="text-sm text-gray-500">{{ $shop->city }}</p>
                            @if($shop->phone)
                                <p class="text-sm text-gray-600 mt-1">📞 {{ $shop->phone }}</p>
                            @endif
                            @if($shop->services_offered)
                                <div class="mt-2 flex flex-wrap gap-1">
                                    @foreach(array_slice($shop->services_offered, 0, 3) as $service)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ ucfirst(str_replace('_', ' ', $service)) }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                            <div class="mt-2 flex space-x-2">
                                <a href="{{ route('shops.show', $shop) }}" class="text-sm text-blue-600 hover:text-blue-700">View Details</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="w-full lg:w-2/3">
            <div id="map" class="h-[600px]" 
                 x-data="{ 
                     initMap() { 
                         if (typeof L === 'undefined') {
                             console.error('Leaflet not loaded');
                             return;
                         }
                         
                         const map = L.map('map').setView([{{ $mapCenterLat }}, {{ $mapCenterLng }}], {{ $mapZoom }});
                         
                         L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                             attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                             maxZoom: 19
                         }).addTo(map);
                         
                         const shops = @js($shops->toArray());
                         window.shopMarkers = {};
                         
                         shops.forEach(shop => {
                             if (shop.latitude && shop.longitude) {
                                 const marker = L.marker([shop.latitude, shop.longitude]).addTo(map);
                                 marker.bindPopup('<div class="p-2"><b>' + shop.name + '</b><br>' + shop.address + '<br>' + (shop.phone ? '📞 ' + shop.phone + '<br>' : '') + '<a href="/shops/' + shop.id + '" class="text-blue-600">View Details</a></div>');
                                 window.shopMarkers[shop.id] = marker;
                             }
                         });
                         
                         window.shopMap = map;
                     }
                 }"
                 x-init="initMap()"
            ></div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
</div>
