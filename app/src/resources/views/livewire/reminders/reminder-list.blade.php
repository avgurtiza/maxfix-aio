<div class="max-w-4xl mx-auto py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-white tracking-tighter uppercase italic">
                Pit Strategy
            </h1>
            <p class="text-gt-text-secondary text-sm font-bold uppercase tracking-widest mt-1">
                Maintenance Reminders & Intervals
            </p>
        </div>
        @if($vehicle)
            <a 
                href="{{ route('reminders.create', $vehicle) }}" 
                class="btn-gt-primary px-6 py-2 rounded uppercase tracking-widest font-bold text-sm"
                wire:navigate
            >
                + Set Reminder
            </a>
        @endif
    </div>

    @if(session('message'))
        <div class="glass-panel border-l-4 border-gt-accent-cyan p-4 mb-6 animate-fade-in">
            <p class="text-gt-accent-cyan font-bold text-sm">{{ session('message') }}</p>
        </div>
    @endif

    <div class="space-y-12">
        @foreach($vehicles as $v)
            @if($v->reminders->isNotEmpty() || $vehicle)
                <div class="relative">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-black text-white flex items-center">
                            <span class="w-3 h-3 bg-grow rounded-full bg-gt-accent-orange mr-3"></span>
                            {{ $v->year }} {{ $v->manufacturer }} {{ $v->model }}
                        </h2>
                        @if(!$vehicle)
                            <a href="{{ route('reminders.create', $v) }}" class="text-[10px] font-bold text-gt-accent-cyan uppercase tracking-widest hover:text-white transition-colors">
                                Add To Garage
                            </a>
                        @endif
                    </div>
                    
                    <div class="grid gap-4">
                        @forelse($v->reminders as $reminder)
                            <div class="glass-panel group hover:border-white/20 transition-all duration-300 rounded-xl overflow-hidden border border-white/5 {{ !$reminder->is_active ? 'opacity-50' : '' }}">
                                <div class="p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-1">
                                            <h3 class="text-lg font-bold text-white group-hover:text-gt-accent-cyan transition-colors uppercase italic tracking-tight">
                                                {{ $reminder->service_name }}
                                            </h3>
                                            @if($reminder->isDue)
                                                <span class="px-2 py-0.5 bg-red-600/20 text-red-500 text-[10px] font-bold uppercase tracking-widest rounded border border-red-600/30 animate-pulse">
                                                    Box Now
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <div class="flex gap-4">
                                            @if($reminder->next_due_date)
                                                <div class="flex items-center text-xs">
                                                    <span class="text-gt-text-secondary uppercase font-bold mr-1.5">Due Date:</span>
                                                    <span class="font-mono {{ $reminder->isDateDue() ? 'text-red-500' : 'text-white' }}">
                                                        {{ $reminder->next_due_date->format('M d, Y') }}
                                                    </span>
                                                </div>
                                            @endif
                                            @if($reminder->next_due_mileage)
                                                <div class="flex items-center text-xs">
                                                    <span class="text-gt-text-secondary uppercase font-bold mr-1.5">Due Mileage:</span>
                                                    <span class="font-mono {{ $reminder->isMileageDue() ? 'text-red-500' : 'text-gt-accent-cyan' }}">
                                                        {{ number_format($reminder->next_due_mileage) }} KM
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-2">
                                        <button 
                                            wire:click="complete({{ $reminder->id }})"
                                            class="px-4 py-2 rounded bg-gt-accent-cyan/10 text-gt-accent-cyan text-[10px] font-bold uppercase tracking-widest border border-gt-accent-cyan/20 hover:bg-gt-accent-cyan hover:text-black transition-all"
                                        >
                                            Complete
                                        </button>
                                        <a 
                                            href="{{ route('reminders.edit', [$v, $reminder]) }}" 
                                            class="w-10 h-10 flex items-center justify-center rounded bg-white/5 text-gt-text-muted hover:bg-white/10 transition-all border border-white/5"
                                            wire:navigate
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <button 
                                            wire:click="delete({{ $reminder->id }})"
                                            wire:confirm="Permanent deletion of this strategy?"
                                            class="w-10 h-10 flex items-center justify-center rounded bg-white/5 text-gt-text-muted hover:bg-red-600 hover:text-white transition-all border border-white/5"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="glass-panel rounded-xl p-8 text-center border border-dashed border-white/10">
                                <p class="text-xs font-bold text-gt-text-muted uppercase tracking-widest italic">No Active Strategies For This Machine</p>
                            </div>
                        @endempty
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <div class="mt-12 flex justify-center">
        <a href="{{ route('vehicles.index') }}" class="text-[10px] font-bold text-gt-text-secondary hover:text-white uppercase tracking-widest flex items-center transition-colors" wire:navigate>
            <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Return to Garage
        </a>
    </div>
</div>
