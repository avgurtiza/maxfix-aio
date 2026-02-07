<div 
    id="map" 
    class="w-full h-full bg-gt-bg-900 relative" 
    wire:ignore
>
    <!-- Custom Map Controls Overlay -->
    <div class="absolute bottom-6 right-6 z-[400] flex flex-col gap-2">
        <button 
            id="map-zoom-in"
            class="w-10 h-10 bg-gt-bg-900/90 text-white rounded flex items-center justify-center border border-white/10 hover:bg-gt-accent-orange hover:border-gt-accent-orange transition-colors shadow-lg backdrop-blur-sm"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
        </button>
        <button 
            id="map-zoom-out"
            class="w-10 h-10 bg-gt-bg-900/90 text-white rounded flex items-center justify-center border border-white/10 hover:bg-gt-accent-orange hover:border-gt-accent-orange transition-colors shadow-lg backdrop-blur-sm"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
        </button>
    </div>

    <div class="absolute top-4 left-4 z-[400]">
        <div class="glass-panel px-3 py-1.5 rounded text-xs font-bold text-white uppercase tracking-wider flex items-center gap-2 border border-gt-accent-cyan/30 shadow-[0_0_10px_rgba(0,212,255,0.2)]">
            <span class="w-2 h-2 rounded-full bg-gt-accent-cyan animate-pulse"></span>
            Satellite Uplink Active
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
    }).setView([{{ $centerLat ?? 14.5995 }}, {{ $centerLng ?? 120.9842 }}], 13);
    
    // Dark mode map tiles
    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
        subdomains: 'abcd',
        maxZoom: 20
    }).addTo(map);

    // Custom controls
    document.getElementById('map-zoom-in').addEventListener('click', () => map.setZoom(map.getZoom() + 1));
    document.getElementById('map-zoom-out').addEventListener('click', () => map.setZoom(map.getZoom() - 1));

    // Custom marker icon creation
    const createMarkerIcon = (isSelected) => {
        const color = isSelected ? '#ff6b35' : '#706f6c';
        const size = isSelected ? 32 : 24;
        const zIndex = isSelected ? 1000 : 1;
        
        return L.divIcon({
            className: 'custom-div-icon',
            html: `<div class="relative transition-all duration-300 transform hover:scale-110">
                    <div class="w-${isSelected ? '8' : '6'} h-${isSelected ? '8' : '6'} rounded-full border-2 border-white shadow-lg flex items-center justify-center transition-colors duration-300" style="background-color: ${color}; box-shadow: 0 0 15px ${color}80;">
                        <div class="w-2 h-2 bg-white rounded-full"></div>
                    </div>
                    ${isSelected ? '<div class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-1 h-4 bg-white/50 blur-sm"></div>' : ''}
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

            // GT7 Style Popup
            const popupContent = `
                <div class="p-4 min-w-[200px] bg-gt-bg-900 text-white rounded-lg border border-white/10 shadow-xl font-sans">
                    <h3 class="font-bold text-lg mb-1 italic uppercase tracking-wider text-gt-accent-orange">${shop.name}</h3>
                    <p class="text-xs text-gt-text-secondary mb-2">${shop.address}</p>
                    <div class="flex items-center justify-between border-t border-white/10 pt-2 mt-2">
                        <span class="text-xs font-mono text-gt-accent-cyan">${shop.distance ? shop.distance.toFixed(1) + ' km' : ''}</span>
                        <button onclick="Livewire.dispatch('selectShop', { shopId: ${shop.id} })" class="text-xs font-bold uppercase tracking-wide hover:text-gt-accent-orange transition-colors">Details &rarr;</button>
                    </div>
                </div>
            `;

            marker.bindPopup(popupContent, {
                className: 'gt-map-popup',
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
    updateMarkers(@js($shops), @js($selectedShop?->id));

</script>

<style>
    .gt-map-popup .leaflet-popup-content-wrapper {
        background: transparent;
        box-shadow: none;
        padding: 0;
    }
    .gt-map-popup .leaflet-popup-tip {
        background: #15151e; /* gt-bg-900 */
        border: 1px solid rgba(255,255,255,0.1);
    }
</style>
@endscript
