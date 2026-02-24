<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">My Vehicles</h1>
            <p class="text-slate-500 text-sm mt-1">Manage your fleet and track maintenance records.</p>
        </div>
        <a 
            href="{{ route('vehicles.create') }}" 
            class="bg-brand-orange text-white px-5 py-2.5 rounded-lg font-bold shadow-md shadow-brand-orange/20 hover:bg-orange-600 transform hover:-translate-y-0.5 transition-all flex items-center gap-2 text-sm"
            wire:navigate
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Vehicle
        </a>
    </div>

    @if(session('message'))
        <div class="bg-brand-success/10 border-l-4 border-l-brand-success text-green-800 px-6 py-4 rounded-lg flex items-center shadow-sm">
            <svg class="w-6 h-6 mr-3 text-brand-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('message') }}
        </div>
    @endif

    @if($vehicles->isEmpty())
        <div class="bg-white border-2 border-dashed border-slate-200 rounded-2xl p-12 text-center text-slate-500">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                <svg class="h-10 w-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-1">No vehicles found</h3>
            <p class="text-sm">Get started by adding your first vehicle to the fleet.</p>
            <a href="{{ route('vehicles.create') }}" class="mt-6 inline-flex bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 px-5 py-2 rounded-lg font-bold text-sm shadow-sm transition">
                Add Vehicle
            </a>
        </div>
    @else
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($vehicles as $vehicle)
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm hover:border-slate-300 hover:shadow-md transition group overflow-hidden relative flex flex-col">
                    @if($vehicle->activeReminders->filter->isDue->count() > 0)
                        <div class="absolute top-0 right-0 p-4 z-10">
                            <span class="bg-amber-100 text-amber-800 text-xs font-bold px-2.5 py-1 rounded border border-amber-200 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                Maintenance Due
                            </span>
                        </div>
                    @endif
                    
                    <div class="p-6 flex-1">
                        <div class="flex items-center gap-4 mb-5">
                            <div class="w-12 h-12 rounded-lg bg-slate-50 flex items-center justify-center text-2xl border border-slate-100 shrink-0">
                                🚗
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 group-hover:text-brand-blue transition truncate pr-16" title="{{ $vehicle->display_name }}">
                                    {{ $vehicle->display_name }}
                                </h3>
                                @if($vehicle->current_plate)
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="inline-block px-2 py-0.5 bg-slate-100 border border-slate-200 rounded text-xs font-mono text-slate-600">{{ $vehicle->current_plate }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                                <p class="text-[10px] text-slate-500 uppercase tracking-wider font-bold mb-1">Odometer</p>
                                <p class="text-sm font-mono text-slate-900 font-medium">{{ number_format($vehicle->current_mileage) }} <span class="text-xs text-slate-500 normal-case font-sans">km</span></p>
                            </div>
                            <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                                <p class="text-[10px] text-slate-500 uppercase tracking-wider font-bold mb-1">Records</p>
                                <p class="text-sm font-mono text-slate-900 font-medium">{{ $vehicle->service_records_count }} <span class="text-xs text-slate-500 normal-case font-sans">entries</span></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="border-t border-slate-100 bg-slate-50 p-2 flex justify-between shrink-0">
                        <div class="flex space-x-1 w-full">
                            <a 
                                href="{{ route('services.history', $vehicle) }}" 
                                class="flex-1 text-center py-2 rounded-md text-xs font-bold text-slate-600 hover:bg-slate-200 hover:text-slate-900 transition"
                                wire:navigate
                            >
                                History
                            </a>
                            <a 
                                href="{{ route('reminders.index', $vehicle) }}" 
                                class="flex-1 text-center py-2 rounded-md text-xs font-bold text-slate-600 hover:bg-slate-200 hover:text-slate-900 transition"
                                wire:navigate
                            >
                                Reminders
                            </a>
                            <a 
                                href="{{ route('vehicles.edit', $vehicle) }}" 
                                class="flex-1 text-center py-2 rounded-md text-xs font-bold text-slate-600 hover:bg-slate-200 hover:text-slate-900 transition"
                                wire:navigate
                            >
                                Edit
                            </a>
                        </div>
                        <div class="pl-2 ml-1 border-l border-slate-200 flex items-center">
                            <button 
                                wire:click="delete({{ $vehicle->id }})"
                                wire:confirm="Are you sure you want to delete this vehicle?"
                                class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-md transition"
                                title="Delete Vehicle"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
