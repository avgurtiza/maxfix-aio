<div class="relative" x-data="{ open: false }">
    <button 
        @click="open = !open" 
        @click.away="open = false"
        class="relative p-2 text-gt-text-secondary hover:text-white transition-colors group"
    >
        <svg class="w-6 h-6 group-hover:drop-shadow-[0_0_5px_rgba(255,255,255,0.5)] transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        
        @if($unreadCount > 0)
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-gt-accent-orange rounded-full shadow-[0_0_8px_rgba(255,107,53,0.6)] animate-pulse">
                {{ $unreadCount }}
            </span>
        @endif
    </button>

    <div 
        x-show="open" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="absolute right-0 mt-2 w-80 bg-gt-bg-900 border border-white/10 rounded-lg shadow-xl overflow-hidden z-50 backdrop-blur-md"
        style="display: none;"
    >
        @if(count($notifications) > 0)
            <div class="max-h-96 overflow-y-auto custom-scrollbar">
                @foreach($notifications as $notification)
                    <div class="p-4 border-b border-white/5 hover:bg-white/5 transition-colors group">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="text-sm font-bold text-white group-hover:text-gt-accent-orange transition-colors">
                                    {{ $notification['vehicle_name'] ?? 'Vehicle' }}
                                </h4>
                                <p class="text-xs text-gt-text-secondary mt-0.5">
                                    {{ $notification['service_name'] ?? 'Service' }}
                                </p>
                            </div>
                            @if($notification['is_due'] ?? false)
                                <span class="w-2 h-2 rounded-full bg-gt-accent-orange shadow-[0_0_5px_rgba(255,107,53,0.8)]"></span>
                            @endif
                        </div>
                        <div class="mt-2 flex items-center gap-3 text-xs text-gt-text-muted">
                            @if(isset($notification['due_date']))
                                <div class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>{{ $notification['due_date'] }}</span>
                                </div>
                            @endif
                            @if(isset($notification['due_mileage']))
                                <div class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    <span>{{ number_format($notification['due_mileage']) }} km</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="p-3 bg-white/5 border-t border-white/5 text-center">
                {{-- Updated route to point to reminder list --}}
                <a href="{{ route('vehicles.index') }}" class="text-xs font-bold text-gt-accent-cyan hover:text-white uppercase tracking-wider transition-colors">
                    View Garage
                </a>
            </div>
        @else
            <div class="p-8 text-center text-gt-text-muted">
                <p>No active reminders</p>
            </div>
        @endif
    </div>
</div>
