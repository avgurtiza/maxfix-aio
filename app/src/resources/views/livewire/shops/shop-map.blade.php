<div 
    id="map" 
    class="w-full bg-slate-100 relative rounded-xl overflow-hidden border border-slate-200" 
    style="height: calc(100vh - 180px); min-height: 500px;"
    wire:ignore
>
    <!-- Custom Map Controls Overlay -->
    <div class="absolute bottom-6 right-6 z-[400] flex flex-col gap-2">
        <button 
            id="map-zoom-in"
            class="w-10 h-10 bg-white text-slate-700 rounded-lg flex items-center justify-center border border-slate-200 hover:text-brand-blue hover:border-brand-blue transition-colors shadow-sm"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
        </button>
        <button 
            id="map-zoom-out"
            class="w-10 h-10 bg-white text-slate-700 rounded-lg flex items-center justify-center border border-slate-200 hover:text-brand-blue hover:border-brand-blue transition-colors shadow-sm"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
        </button>
    </div>

    <div class="absolute top-4 left-4 z-[400]">
        <div class="bg-white px-3 py-1.5 rounded-lg text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-2 border border-brand-blue/30 shadow-sm">
            <span class="w-2 h-2 rounded-full bg-brand-blue animate-pulse"></span>
            Live Map Active
        </div>
    </div>
</div>

@assets
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endassets

@script
<script>
    let map;
    let markers = {};
    let activeLayer = null;

    // Initialize map
    map = L.map('map', {
        zoomControl: false,
        attributionControl: false
    }).setView([{{ $this->mapCenterLat }}, {{ $this->mapCenterLng }}], {{ $this->mapZoom }});
    
    // Light mode map tiles
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
        subdomains: 'abcd',
        maxZoom: 20
    }).addTo(map);

    // Custom controls
    document.getElementById('map-zoom-in').addEventListener('click', () => map.setZoom(map.getZoom() + 1));
    document.getElementById('map-zoom-out').addEventListener('click', () => map.setZoom(map.getZoom() - 1));

    // Custom marker icon creation
    const createMarkerIcon = (isSelected) => {
        const color = isSelected ? '#EA580C' : '#64748B'; // brand-orange or slate-500
        const size = isSelected ? 32 : 24;
        const zIndex = isSelected ? 1000 : 1;
        
        return L.divIcon({
            className: 'custom-div-icon',
            html: `<div class="relative transition-all duration-300 transform hover:scale-110">
                    <div class="w-${isSelected ? '8' : '6'} h-${isSelected ? '8' : '6'} rounded-full border-2 border-white shadow-md flex items-center justify-center transition-colors duration-300" style="background-color: ${color};">
                        <div class="w-2 h-2 bg-white rounded-full"></div>
                    </div>
                   </div>`,
            iconSize: [size, size],
            iconAnchor: [size/2, size/2],
            zIndexOffset: zIndex
        });
    };

    // Update markers function
    const updateMarkers = (shops, selectedShopId) => {
        // Clear existing markers
        Object.values(markers).forEach(marker => map.removeLayer(marker));
        markers = {};

        shops.forEach(shop => {
            const isSelected = selectedShopId === shop.id;
            const marker = L.marker([shop.latitude, shop.longitude], {
                icon: createMarkerIcon(isSelected),
                zIndexOffset: isSelected ? 1000 : 0
            }).addTo(map);

            // SaaS Style Popup
            const popupContent = `
                <div class="p-4 min-w-[200px] bg-white text-slate-800 rounded-lg border border-slate-200 shadow-md font-sans">
                    <h3 class="font-bold text-lg mb-1 text-slate-900 tracking-tight">${shop.name}</h3>
                    <p class="text-xs text-slate-500 mb-2">${shop.address}</p>
                    <div class="flex items-center justify-between border-t border-slate-100 pt-3 mt-3">
                        <span class="text-xs font-semibold text-brand-blue bg-blue-50 px-2 py-0.5 rounded">${shop.distance ? shop.distance.toFixed(1) + ' km' : ''}</span>
                        <button onclick="Livewire.dispatch('selectShop', { shopId: ${shop.id} })" class="text-xs font-bold text-slate-700 hover:text-brand-orange transition-colors">Details &rarr;</button>
                    </div>
                </div>
            `;

            marker.bindPopup(popupContent, {
                className: 'saas-map-popup',
                closeButton: false,
                offset: [0, -10]
            });

            marker.on('click', () => {
                Livewire.dispatch('selectShop', { shopId: shop.id });
            });

            markers[shop.id] = marker;

            if (isSelected) {
                marker.openPopup();
                map.flyTo([shop.latitude, shop.longitude], 15, {
                    animate: true,
                    duration: 1.5
                });
            }
        });
    };

    // Listen for Livewire updates
    $wire.on('shopsUpdated', (data) => {
        updateMarkers(data.shops, data.selectedShopId);
    });

    $wire.on('locationUpdated', (data) => {
        map.flyTo([data.lat, data.lng], 13);
    });

    // Initial load
    updateMarkers(@js($this->shops), null);

</script>

<style>
    .saas-map-popup .leaflet-popup-content-wrapper {
        background: transparent;
        box-shadow: none;
        padding: 0;
    }
    .saas-map-popup .leaflet-popup-tip {
        background: #ffffff;
        border: 1px solid #e2e8f0;
    }
</style>
@endscript
