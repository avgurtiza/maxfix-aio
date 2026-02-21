<div class="max-w-4xl mx-auto py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-white tracking-tighter uppercase italic">
                Service History
            </h1>
            <p class="text-gt-text-secondary text-sm font-bold uppercase tracking-widest mt-1">
                {{ $vehicle->year }} {{ $vehicle->manufacturer }} {{ $vehicle->model }}
            </p>
        </div>
        <a 
            href="{{ route('services.create', $vehicle) }}" 
            class="btn-gt-primary px-6 py-2 rounded uppercase tracking-widest font-bold text-sm"
            wire:navigate
        >
            + Add Record
        </a>
    </div>

    @if(session('message'))
        <div class="glass-panel border-l-4 border-gt-accent-cyan p-4 mb-6 animate-fade-in">
            <p class="text-gt-accent-cyan font-bold text-sm">{{ session('message') }}</p>
        </div>
    @endif

    @if($servicesByYear->isEmpty())
        <div class="glass-panel p-12 text-center rounded-xl border border-white/5">
            <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gt-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-white uppercase tracking-wider">No Records Found</h3>
            <p class="text-gt-text-muted mt-2">Start tracking your maintenance to maintain vehicle value.</p>
        </div>
    @else
        <div class="space-y-12">
            @foreach($servicesByYear as $year => $records)
                <div class="relative">
                    <div class="absolute -left-4 top-0 bottom-0 w-px bg-white/10"></div>
                    <h2 class="text-xl font-black text-gt-accent-orange mb-6 flex items-center">
                        <span class="w-3 h-3 bg-grow rounded-full bg-gt-accent-orange mr-3"></span>
                        {{ $year }}
                    </h2>
                    
                    <div class="grid gap-6">
                        @foreach($records as $record)
                            <div class="glass-panel group hover:border-white/20 transition-all duration-300 rounded-xl overflow-hidden border border-white/5">
                                <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <span class="px-2 py-0.5 bg-gt-accent-cyan/10 text-gt-accent-cyan text-[10px] font-bold uppercase tracking-widest rounded border border-gt-accent-cyan/20">
                                                {{ str_replace('_', ' ', $record->service_type) }}
                                            </span>
                                            <span class="text-xs font-mono text-gt-text-muted">
                                                {{ $record->service_date->format('M d, Y') }}
                                            </span>
                                        </div>
                                        
                                        <h3 class="text-lg font-bold text-white group-hover:text-gt-accent-cyan transition-colors">
                                            {{ $record->description ?: 'Maintenance Service' }}
                                        </h3>
                                        
                                        <div class="flex gap-4 mt-3">
                                            @if($record->mileage)
                                                <div class="flex items-center text-xs">
                                                    <span class="text-gt-text-secondary uppercase font-bold mr-1.5">Mileage:</span>
                                                    <span class="font-mono text-white">{{ number_format($record->mileage) }} KM</span>
                                                </div>
                                            @endif
                                            @if($record->cost)
                                                <div class="flex items-center text-xs">
                                                    <span class="text-gt-text-secondary uppercase font-bold mr-1.5">Cost:</span>
                                                    <span class="font-mono text-gt-accent-cyan">${{ number_format($record->cost, 2) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-2 border-t md:border-t-0 border-white/5 pt-4 md:pt-0">
                                        <a 
                                            href="{{ route('services.edit', [$vehicle, $record]) }}" 
                                            class="w-10 h-10 flex items-center justify-center rounded bg-white/5 text-gt-text-muted hover:bg-gt-accent-cyan hover:text-black transition-all"
                                            wire:navigate
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <button 
                                            wire:click="delete({{ $record->id }})"
                                            wire:confirm="Are you sure you want to delete this service record?"
                                            class="w-10 h-10 flex items-center justify-center rounded bg-white/5 text-gt-text-muted hover:bg-red-600 hover:text-white transition-all"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-12 flex justify-center">
            <a href="{{ route('vehicles.index') }}" class="text-xs font-bold text-gt-text-secondary hover:text-white uppercase tracking-widest flex items-center transition-colors" wire:navigate>
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Garage
            </a>
        </div>
    @endif
</div>
