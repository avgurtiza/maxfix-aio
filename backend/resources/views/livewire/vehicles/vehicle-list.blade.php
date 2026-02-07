<div>
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-white italic tracking-wide uppercase">My Garage</h1>
            <div class="h-1 w-20 bg-gt-accent-orange mt-2 rounded-full shadow-[0_0_10px_rgba(255,107,53,0.5)]"></div>
        </div>
        <a 
            href="{{ route('vehicles.create') }}" 
            class="btn-gt-primary px-6 py-2 rounded uppercase tracking-widest font-bold text-sm flex items-center gap-2"
            wire:navigate
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Machine
        </a>
    </div>

    @if(session('message'))
        <div class="glass-panel border-l-4 border-l-green-500 text-green-400 px-6 py-4 rounded mb-6 flex items-center">
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('message') }}
        </div>
    @endif

    @if($vehicles->isEmpty())
        <div class="glass-panel rounded-xl p-12 text-center">
            <div class="w-20 h-20 bg-gt-bg-800 rounded-full flex items-center justify-center mx-auto mb-6 border border-white/10">
                <svg class="h-10 w-10 text-gt-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-white uppercase tracking-wide">Garage Empty</h3>
            <p class="mt-2 text-gt-text-secondary">Your racing career starts here. Add your first machine.</p>
            <a href="{{ route('vehicles.create') }}" class="btn-gt-primary mt-6 inline-flex px-6 py-2 rounded uppercase tracking-wider font-bold text-sm">
                Acquire Vehicle
            </a>
        </div>
    @else
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($vehicles as $vehicle)
                <div class="glass-card rounded-xl overflow-hidden group relative">
                    <div class="absolute top-0 right-0 p-4">
                        @if($vehicle->activeReminders->where('isDue')->count() > 0)
                            <span class="bg-red-600/90 text-white text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider shadow-[0_0_10px_rgba(220,38,38,0.5)] animate-pulse">
                                Maintenance Due
                            </span>
                        @endif
                    </div>
                    
                    <div class="p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 rounded bg-gradient-to-br from-gt-bg-700 to-gt-bg-900 flex items-center justify-center border border-white/10 shadow-inner">
                                <span class="text-2xl">🚗</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white uppercase tracking-wide leading-tight group-hover:text-gt-accent-orange transition-colors">
                                    {{ $vehicle->display_name }}
                                </h3>
                                @if($vehicle->current_plate)
                                    <p class="text-xs font-mono text-gt-text-muted bg-black/30 inline-block px-1 rounded mt-1 border border-white/5">{{ $vehicle->current_plate }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="bg-gt-bg-900/50 p-3 rounded border border-white/5">
                                <p class="text-[10px] text-gt-text-muted uppercase tracking-wider mb-1">Odometer</p>
                                <p class="text-sm font-mono text-gt-accent-cyan">{{ number_format($vehicle->current_mileage) }} <span class="text-[10px] text-gt-text-muted">km</span></p>
                            </div>
                            <div class="bg-gt-bg-900/50 p-3 rounded border border-white/5">
                                <p class="text-[10px] text-gt-text-muted uppercase tracking-wider mb-1">Records</p>
                                <p class="text-sm font-mono text-white">{{ $vehicle->service_records_count }} <span class="text-[10px] text-gt-text-muted">entries</span></p>
                            </div>
                        </div>

                        <div class="flex space-x-2 pt-4 border-t border-white/10">
                            <a 
                                href="{{ route('services.history', $vehicle) }}" 
                                class="flex-1 text-center btn-gt-secondary py-2 rounded text-xs font-bold uppercase tracking-wider"
                                wire:navigate
                            >
                                History
                            </a>
                            <a 
                                href="{{ route('vehicles.edit', $vehicle) }}" 
                                class="flex-1 text-center btn-gt-secondary py-2 rounded text-xs font-bold uppercase tracking-wider"
                                wire:navigate
                            >
                                Tuning
                            </a>
                            <button 
                                wire:click="delete({{ $vehicle->id }})"
                                wire:confirm="Are you sure you want to delete this vehicle?"
                                class="px-3 text-gt-text-muted hover:text-red-500 transition-colors"
                                title="Scrap Vehicle"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Racing stripe bottom -->
                    <div class="active-stripe h-1 w-0 bg-gt-accent-orange group-hover:w-full transition-all duration-500"></div>
                </div>
            @endforeach
        </div>
    @endif
</div>
