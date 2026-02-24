<div class="max-w-4xl mx-auto py-8">
    <div class="flex justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
                Service History
            </h1>
            <p class="text-slate-500 font-semibold text-sm mt-1">
                {{ $vehicle->year }} {{ $vehicle->make }} {{ $vehicle->model }}
            </p>
        </div>
        <a 
            href="{{ route('services.create', $vehicle) }}" 
            class="bg-brand-orange text-white px-5 py-2.5 rounded-lg font-bold shadow-md shadow-brand-orange/20 hover:bg-orange-600 transform hover:-translate-y-0.5 transition-all flex items-center gap-2 text-sm whitespace-nowrap"
            wire:navigate
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span class="hidden sm:inline">Add Record</span>
        </a>
    </div>

    @if(session('message'))
        <div class="bg-brand-success/10 border-l-4 border-l-brand-success text-green-800 px-6 py-4 rounded-lg mb-6 flex items-center shadow-sm">
            <svg class="w-6 h-6 mr-3 text-brand-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <p class="font-medium text-sm">{{ session('message') }}</p>
        </div>
    @endif

    @if($servicesByYear->isEmpty())
        <div class="bg-white p-12 text-center rounded-xl border border-slate-200 shadow-sm">
            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900">No Records Found</h3>
            <p class="text-slate-500 mt-2 text-sm">Start tracking your maintenance to maintain vehicle value.</p>
        </div>
    @else
        <div class="space-y-10">
            @foreach($servicesByYear as $year => $records)
                <div class="relative pl-6 sm:pl-0">
                    <div class="absolute left-[11px] sm:hidden top-0 bottom-0 w-px bg-slate-200"></div>
                    
                    <h2 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-3">
                        <span class="w-6 h-6 rounded-full bg-brand-blue/10 flex items-center justify-center border border-brand-blue/20 shrink-0">
                            <span class="w-2 h-2 rounded-full bg-brand-blue"></span>
                        </span>
                        {{ $year }}
                    </h2>
                    
                    <div class="grid gap-4 sm:gap-6 pl-4 sm:pl-9 relative">
                        <!-- Timeline line for desktop -->
                        <div class="hidden sm:block absolute left-[15.5px] top-4 bottom-0 w-px bg-slate-200 z-0"></div>

                        @foreach($records as $record)
                            <div class="bg-white rounded-xl border border-slate-200 shadow-sm hover:border-brand-blue/30 hover:shadow-md transition-all group relative z-10 w-full overflow-hidden">
                                <div class="absolute top-0 left-0 w-1 h-full bg-slate-200 group-hover:bg-brand-blue transition-colors"></div>
                                <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-5 pl-7">
                                    <div class="flex-1">
                                        <div class="flex flex-wrap items-center gap-2.5 mb-2.5">
                                            <span class="px-2.5 py-1 bg-blue-50 text-brand-blue text-xs font-bold uppercase tracking-wider rounded-md border border-blue-100">
                                                {{ str_replace('_', ' ', $record->service_type) }}
                                            </span>
                                            <span class="text-xs font-semibold text-slate-500 flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                {{ $record->service_date->format('M d, Y') }}
                                            </span>
                                        </div>
                                        
                                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-brand-blue transition-colors">
                                            {{ $record->description ?: 'Maintenance Service' }}
                                        </h3>
                                        
                                        <div class="flex flex-wrap gap-4 mt-3">
                                            @if($record->mileage)
                                                <div class="flex items-center text-xs font-medium text-slate-600 bg-slate-50 px-2 py-1 rounded border border-slate-100">
                                                    <span class="text-slate-400 mr-2 uppercase tracking-wide text-[10px] font-bold">Mileage</span>
                                                    {{ number_format($record->mileage) }} KM
                                                </div>
                                            @endif
                                            @if($record->cost)
                                                <div class="flex items-center text-xs font-semibold text-slate-900 bg-slate-50 px-2 py-1 rounded border border-slate-100">
                                                    <span class="text-slate-400 mr-2 uppercase tracking-wide text-[10px] font-bold">Cost</span>
                                                    ${{ number_format($record->cost, 2) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-2 border-t sm:border-t-0 border-slate-100 pt-4 sm:pt-0">
                                        <a 
                                            href="{{ route('services.edit', [$vehicle, $record]) }}" 
                                            class="w-10 h-10 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-slate-50 hover:text-brand-blue hover:border-brand-blue/30 transition-all shadow-sm"
                                            wire:navigate
                                            title="Edit Record"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <button 
                                            wire:click="delete({{ $record->id }})"
                                            wire:confirm="Are you sure you want to delete this service record?"
                                            class="w-10 h-10 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-red-50 hover:text-red-500 hover:border-red-200 transition-all shadow-sm"
                                            title="Delete Record"
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
        
        <div class="mt-12 flex justify-center pb-8">
            <a href="{{ route('vehicles.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-800 flex items-center transition-colors bg-white px-4 py-2 rounded-lg border border-slate-200 shadow-sm" wire:navigate>
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Garage
            </a>
        </div>
    @endif
</div>
