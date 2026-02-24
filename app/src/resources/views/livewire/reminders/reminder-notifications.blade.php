<div class="relative" x-data="{ open: false }">
    <button 
        @click="open = !open" 
        @click.away="open = false"
        class="relative p-2 text-slate-500 hover:text-brand-blue hover:bg-blue-50/50 rounded-full transition-colors group"
    >
        <svg class="w-6 h-6 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        
        @if($unreadCount > 0)
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-white transform bg-red-500 rounded-full shadow-sm animate-pulse border-2 border-white">
                {{ $unreadCount }}
            </span>
        @endif
    </button>

    <div 
        x-show="open" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="absolute right-0 mt-2 w-80 bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden z-50 origin-top-right ring-1 ring-black ring-opacity-5"
        style="display: none;"
    >
        <div class="px-4 py-3 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
            <h3 class="text-sm font-bold text-slate-800">Notifications</h3>
            @if(count($notifications) > 0)
                <span class="text-xs text-brand-blue font-semibold">{{ count($notifications) }} New</span>
            @endif
        </div>
        
        @if(count($notifications) > 0)
            <div class="max-h-96 overflow-y-auto">
                @foreach($notifications as $notification)
                    <div class="p-4 border-b border-slate-100 hover:bg-blue-50/50 cursor-pointer transition-colors group">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 group-hover:text-brand-blue transition-colors">
                                    {{ $notification['vehicle_name'] ?? 'Vehicle' }}
                                </h4>
                                <p class="text-xs font-semibold text-slate-600 mt-0.5">
                                    {{ $notification['service_name'] ?? 'Service' }}
                                </p>
                            </div>
                            @if($notification['is_due'] ?? false)
                                <span class="w-2.5 h-2.5 rounded-full bg-red-500 shadow-sm mt-1 shrink-0"></span>
                            @endif
                        </div>
                        <div class="mt-2.5 flex flex-wrap items-center gap-3 text-xs text-slate-500 font-medium">
                            @if(isset($notification['due_date']))
                                <div class="flex items-center gap-1.5 bg-white border border-slate-200 px-2 py-0.5 rounded shadow-sm">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span class="{{ ($notification['is_due'] ?? false) ? 'text-red-600' : '' }}">{{ $notification['due_date'] }}</span>
                                </div>
                            @endif
                            @if(isset($notification['due_mileage']))
                                <div class="flex items-center gap-1.5 bg-white border border-slate-200 px-2 py-0.5 rounded shadow-sm">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    <span class="{{ ($notification['is_due'] ?? false) ? 'text-red-600' : '' }}">{{ number_format($notification['due_mileage']) }} km</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="p-3 bg-slate-50 border-t border-slate-100 text-center">
                <a href="{{ route('vehicles.index') }}" class="text-xs font-bold text-brand-blue hover:text-blue-800 transition-colors inline-block w-full py-1">
                    View All Activity &rarr;
                </a>
            </div>
        @else
            <div class="p-8 text-center text-slate-500 flex flex-col items-center">
                <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                </div>
                <p class="font-medium text-sm">You're all caught up!</p>
                <p class="text-xs mt-1 text-slate-400">No active maintenance reminders.</p>
            </div>
        @endif
    </div>
</div>
