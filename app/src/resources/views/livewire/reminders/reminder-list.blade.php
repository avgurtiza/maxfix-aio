<div class="max-w-4xl mx-auto py-8">
    <div class="flex justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
                Maintenance Reminders
            </h1>
            <p class="text-slate-500 font-medium text-sm mt-1">
                Manage upcoming service intervals
            </p>
        </div>
        @if($vehicle)
            <a 
                href="{{ route('reminders.create', $vehicle) }}" 
                class="bg-brand-orange text-white px-5 py-2.5 rounded-lg font-bold shadow-md shadow-brand-orange/20 hover:bg-orange-600 transform hover:-translate-y-0.5 transition-all flex items-center gap-2 text-sm whitespace-nowrap"
                wire:navigate
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span class="hidden sm:inline">Add Reminder</span>
            </a>
        @endif
    </div>

    @if(session('message'))
        <div class="bg-brand-success/10 border-l-4 border-l-brand-success text-green-800 px-6 py-4 rounded-lg mb-6 flex items-center shadow-sm">
            <svg class="w-6 h-6 mr-3 text-brand-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <p class="font-medium text-sm">{{ session('message') }}</p>
        </div>
    @endif

    <div class="space-y-10">
        @foreach($vehicles as $v)
            @if($v->reminders->isNotEmpty() || $vehicle)
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 sm:p-8">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                        <h2 class="text-lg font-bold text-slate-900 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-blue-50 text-brand-blue flex items-center justify-center border border-blue-100 shrink-0">
                                🚗
                            </span>
                            {{ $v->year }} {{ $v->make ?? $v->manufacturer }} {{ $v->model }}
                        </h2>
                        @if(!$vehicle)
                            <a href="{{ route('reminders.create', $v) }}" class="text-xs font-bold text-brand-blue bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-md transition-colors flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Add
                            </a>
                        @endif
                    </div>
                    
                    <div class="grid gap-4">
                        @forelse($v->reminders as $reminder)
                            <div class="bg-slate-50 border border-slate-200 hover:border-brand-blue/30 transition-all duration-200 rounded-lg overflow-hidden group {{ !$reminder->is_active ? 'opacity-60 grayscale' : '' }}">
                                <div class="p-5 flex flex-col md:flex-row md:items-center justify-between gap-5 relative">
                                    @if($reminder->isDue)
                                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-red-500"></div>
                                    @endif
                                    
                                    <div class="flex-1 {{ $reminder->isDue ? 'pl-2' : '' }}">
                                        <div class="flex items-center gap-3 mb-2">
                                            <h3 class="text-base font-bold text-slate-900 group-hover:text-brand-blue transition-colors">
                                                {{ $reminder->service_name }}
                                            </h3>
                                            @if($reminder->isDue)
                                                <span class="px-2 py-0.5 bg-red-100 text-red-700 text-[10px] font-bold uppercase tracking-widest rounded flex items-center gap-1.5">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                                                    Due Now
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <div class="flex flex-wrap gap-4 mt-2">
                                            @if($reminder->next_due_date)
                                                <div class="flex items-center text-xs font-medium bg-white px-2 py-1 rounded border {{ $reminder->isDateDue() ? 'border-red-200 text-red-700' : 'border-slate-200 text-slate-600' }}">
                                                    <span class="text-slate-400 mr-2 uppercase tracking-wide text-[10px] font-bold">Due Date</span>
                                                    <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> {{ $reminder->next_due_date->format('M d, Y') }}</span>
                                                </div>
                                            @endif
                                            @if($reminder->next_due_mileage)
                                                <div class="flex items-center text-xs font-medium bg-white px-2 py-1 rounded border {{ $reminder->isMileageDue() ? 'border-red-200 text-red-700' : 'border-slate-200 text-slate-600' }}">
                                                    <span class="text-slate-400 mr-2 uppercase tracking-wide text-[10px] font-bold">Due Mileage</span>
                                                    <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg> {{ number_format($reminder->next_due_mileage) }} KM</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-2 pt-4 border-t border-slate-200 md:pt-0 md:border-t-0">
                                        <button 
                                            wire:click="complete({{ $reminder->id }})"
                                            class="px-4 py-2 rounded-md bg-white border border-slate-200 text-brand-blue text-xs font-bold shadow-sm hover:bg-blue-50 transition-colors flex items-center gap-1.5"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Mark Done
                                        </button>
                                        <a 
                                            href="{{ route('reminders.edit', [$v, $reminder]) }}" 
                                            class="w-9 h-9 flex items-center justify-center rounded-md bg-white border border-slate-200 text-slate-500 hover:bg-slate-50 hover:text-brand-blue transition-colors shadow-sm"
                                            wire:navigate
                                            title="Edit Reminder"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <button 
                                            wire:click="delete({{ $reminder->id }})"
                                            wire:confirm="Are you sure you want to delete this reminder?"
                                            class="w-9 h-9 flex items-center justify-center rounded-md bg-white border border-slate-200 text-slate-500 hover:bg-red-50 hover:text-red-500 hover:border-red-200 transition-colors shadow-sm"
                                            title="Delete Reminder"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center bg-slate-50 rounded-xl border-2 border-dashed border-slate-200">
                                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm border border-slate-100">
                                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <p class="text-sm font-semibold text-slate-500">No active reminders for this vehicle</p>
                            </div>
                        @endempty
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <div class="mt-12 flex justify-center pb-8">
        <a href="{{ route('vehicles.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-800 flex items-center transition-colors bg-white px-4 py-2 rounded-lg border border-slate-200 shadow-sm" wire:navigate>
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Garage
        </a>
    </div>
</div>
