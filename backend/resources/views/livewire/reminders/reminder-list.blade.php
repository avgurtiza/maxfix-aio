<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">
            {{ $vehicle ? 'Reminders for ' . $vehicle->display_name : 'Maintenance Reminders' }}
        </h1>
        @if($vehicle)
            <a 
                href="{{ route('reminders.create', $vehicle) }}" 
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
                wire:navigate
            >
                + Add Reminder
            </a>
        @endif
            <div>
                <h1 class="text-3xl font-bold text-white italic tracking-wide uppercase">Pit Strategy</h1>
                <p class="text-sm text-gt-accent-orange font-bold uppercase tracking-wider">{{ $vehicle->display_name }}</p>
            </div>
        </div>
        <button 
            wire:click="create" 
            class="btn-gt-primary px-6 py-2 rounded uppercase tracking-widest font-bold text-sm flex items-center gap-2"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Set Reminder
        </button>
    </div>

    @if(session('message'))
        <div class="glass-panel border-l-4 border-l-green-500 text-green-400 px-6 py-4 rounded mb-8 flex items-center shadow-lg">
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('message') }}
        </div>
    @endif

    @if($reminders->isEmpty())
        <div class="glass-panel rounded-xl p-12 text-center">
            <div class="w-20 h-20 bg-gt-bg-800 rounded-full flex items-center justify-center mx-auto mb-6 border border-white/10">
                <svg class="h-10 w-10 text-gt-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            </div>
            <h3 class="text-xl font-bold text-white uppercase tracking-wide">All Systems Clear</h3>
            <p class="mt-2 text-gt-text-secondary">No maintenance reminders set. Stay ahead of the curve by scheduling one now.</p>
        </div>
    @else
                                                wire:click="delete({{ $reminder->id }})"
                                                wire:confirm="Are you sure you want to delete this reminder?"
                                                class="text-red-600 hover:bg-red-100 px-3 py-1 rounded text-sm"
                                            >
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
</div>
