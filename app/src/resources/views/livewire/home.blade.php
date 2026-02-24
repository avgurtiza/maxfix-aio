<div>
    @auth
        @if($hasVehicles ?? false)
            <!-- Health Score -->
            <section class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm flex items-center gap-6">
                <div class="w-20 h-20 rounded-full bg-brand-success/10 border-4 border-brand-success flex items-center justify-center shrink-0">
                    <span class="text-2xl font-bold text-brand-success">{{ $healthScore ?? 100 }}%</span>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold mb-1">Your fleet is <span class="text-brand-success">{{ $healthScore ?? 100 }}%</span> healthy</h3>
                    <p class="text-slate-500">{{ $healthyVehicles ?? 0 }} of {{ $totalVehicles ?? 0 }} vehicles are fully up to date on maintenance.</p>
                </div>
                <a href="{{ route('vehicles.index') }}" class="hidden sm:block px-5 py-2.5 bg-slate-100 font-semibold text-slate-700 hover:bg-slate-200 rounded-lg transition text-center" wire:navigate>
                    View Details
                </a>
            </section>
        @else
            <!-- Empty Fleet State -->
            <section class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm flex items-center gap-6">
                <div class="w-20 h-20 rounded-full bg-slate-100 border-4 border-slate-300 flex items-center justify-center shrink-0">
                    <svg class="w-10 h-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h8m-8 4h8m-4 4h4M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold mb-1">Add your first vehicle</h3>
                    <p class="text-slate-500">Start tracking your fleet's maintenance and health.</p>
                </div>
                <a href="{{ route('vehicles.create') }}" class="hidden sm:block px-5 py-2.5 bg-brand-orange font-semibold text-white hover:bg-orange-600 rounded-lg transition text-center shadow-md shadow-brand-orange/20" wire:navigate>
                    Add Vehicle
                </a>
            </section>
        @endif

        <!-- Quick Actions -->
        <section class="mt-8">
            <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-4">Quick Actions</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <a href="{{ route('vehicles.index') }}" class="p-3 bg-white border border-slate-200 rounded-xl shadow-sm hover:border-brand-orange hover:shadow-md transition flex items-center gap-3 group" wire:navigate>
                    <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-600 group-hover:bg-brand-orange/10 group-hover:text-brand-orange flex items-center justify-center shrink-0 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <span class="font-bold text-slate-800 text-sm">Add Vehicle</span>
                </a>
                <a href="{{ route('vehicles.index') }}" class="p-3 bg-white border border-slate-200 rounded-xl shadow-sm hover:border-brand-blue hover:shadow-md transition flex items-center gap-3 group" wire:navigate>
                    <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-600 group-hover:bg-brand-blue/10 group-hover:text-brand-blue flex items-center justify-center shrink-0 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <span class="font-bold text-slate-800 text-sm">Log Service</span>
                </a>
                <a href="{{ route('shops.index') }}" class="p-3 bg-white border border-slate-200 rounded-xl shadow-sm hover:border-slate-400 hover:shadow-md transition flex items-center gap-3 group" wire:navigate>
                    <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-600 group-hover:bg-slate-200 flex items-center justify-center shrink-0 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <span class="font-bold text-slate-800 text-sm">Find Shop</span>
                </a>
                <button class="p-3 bg-white border border-slate-200 rounded-xl shadow-sm hover:border-slate-400 hover:shadow-md transition flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-600 group-hover:bg-slate-200 flex items-center justify-center shrink-0 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <span class="font-bold text-slate-800 text-sm">Reminders</span>
                </button>
            </div>
        </section>

        <!-- Main Dashboard Split -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">
            <!-- Vehicles -->
            <section class="lg:col-span-2 space-y-4">
                <div class="flex items-center justify-between px-1">
                    <h3 class="font-bold text-lg">Your Vehicles</h3>
                    <a href="{{ route('vehicles.index') }}" class="text-sm font-semibold text-brand-blue hover:underline" wire:navigate>View All &rarr;</a>
                </div>
                
                <div class="space-y-3">
                    @forelse($vehicles as $vehicle)
                        @php
                            $hasReminders = $vehicle->activeReminders->isNotEmpty();
                        @endphp
                        @if($hasReminders)
                            <!-- Vehicle Card (Warning) -->
                            <div class="bg-white rounded-xl border border-amber-200 p-5 shadow-sm relative overflow-hidden group hover:border-amber-300 transition">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-amber-400"></div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-lg bg-amber-50 flex items-center justify-center text-2xl border border-amber-100">🚙</div>
                                        <div>
                                            <h4 class="font-bold text-lg text-slate-900 group-hover:text-brand-orange transition">{{ $vehicle->make }} {{ $vehicle->model }}</h4>
                                            <div class="flex items-center gap-3 text-sm mt-1">
                                                <span class="font-mono text-slate-500 bg-slate-100 px-2 py-0.5 rounded">{{ $vehicle->license_plate }}</span>
                                                <span class="text-slate-500 tracking-wide font-mono">{{ number_format($vehicle->current_mileage) }} km</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end">
                                        <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full bg-amber-100 text-amber-800 text-sm font-bold">
                                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                            {{ $vehicle->activeReminders->count() }} reminder(s) due
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Vehicle Card (Success) -->
                            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm hover:border-slate-300 transition group">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-lg bg-slate-50 flex items-center justify-center text-2xl border border-slate-100">🚗</div>
                                        <div>
                                            <h4 class="font-bold text-lg text-slate-900 group-hover:text-brand-blue transition">{{ $vehicle->make }} {{ $vehicle->model }}</h4>
                                            <div class="flex items-center gap-3 text-sm mt-1">
                                                <span class="font-mono text-slate-500 bg-slate-100 px-2 py-0.5 rounded">{{ $vehicle->license_plate }}</span>
                                                <span class="text-slate-500 tracking-wide font-mono">{{ number_format($vehicle->current_mileage) }} km</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end">
                                        <span class="inline-flex items-center gap-1.5 text-slate-500 text-sm font-medium pr-2">
                                            <svg class="w-5 h-5 text-brand-success" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            All current
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="bg-white border-2 border-dashed border-slate-200 rounded-xl p-8 text-center text-slate-500">
                            No vehicles added yet. Start managing your fleet by adding a vehicle!
                        </div>
                    @endforelse
                </div>
            </section>

            <!-- Needs Attention Sidebar -->
            <section class="space-y-4">
                <div class="flex items-center justify-between px-1">
                    <h3 class="font-bold text-lg">Needs Attention</h3>
                </div>

                @if(isset($needsAttention) && $needsAttention->isNotEmpty())
                    @foreach($needsAttention->take(2) as $vehicle)
                        @foreach($vehicle->activeReminders as $reminder)
                            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col items-start h-full p-6 relative mb-4">
                                <div class="absolute -right-4 -top-4 text-7xl opacity-5 mix-blend-multiply">⚠️</div>
                                <div class="flex flex-col items-start gap-1 w-full border-b border-slate-100 pb-4 mb-4 z-10">
                                   <div class="flex items-center gap-2 text-amber-500 font-bold tracking-wide text-xs uppercase mb-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                        High Priority
                                   </div>
                                   <h4 class="font-bold text-slate-900 text-lg">{{ $vehicle->make }} {{ $vehicle->model }}</h4>
                                   <span class="text-xs font-mono text-slate-500">{{ $vehicle->license_plate }}</span>
                                </div>
                                <div class="space-y-1 w-full mb-8 z-10">
                                    <p class="font-bold text-lg text-slate-800">{{ $reminder->title }}</p>
                                    @if($reminder->due_date)
                                        <p class="text-amber-600 font-medium text-sm">Target: {{ \Carbon\Carbon::parse($reminder->due_date)->format('M d, Y') }}</p>
                                    @endif
                                </div>
                                
                                <a href="{{ route('vehicles.index') }}" class="mt-auto w-full block text-center py-2.5 rounded-lg font-bold text-white bg-brand-orange hover:bg-orange-600 shadow-md shadow-brand-orange/20 transition hover:-translate-y-0.5 z-10" wire:navigate>
                                    Resolve
                                </a>
                            </div>
                        @endforeach
                    @endforeach
                @else
                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 text-center text-slate-500 flex flex-col items-center gap-4">
                        <div class="w-16 h-16 rounded-full bg-brand-success/10 flex items-center justify-center text-brand-success">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="font-medium text-slate-700">All caught up!</p>
                        <p class="text-sm">No vehicles require immediate attention right now.</p>
                    </div>
                @endif
            </section>
        </div>
    @else
        <!-- Guest View -->
        <div class="flex flex-col items-center justify-center min-h-[60vh] text-center max-w-2xl mx-auto px-4 gap-8">
            <div class="w-20 h-20 bg-brand-orange text-white flex items-center justify-center rounded-2xl font-bold text-4xl shadow-xl shadow-brand-orange/20">
                Mx
            </div>
            
            <div class="space-y-4">
                <h1 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">
                    Vehicle Maintenance <span class="text-brand-orange">Made Simple</span>
                </h1>
                <p class="text-lg text-slate-500 max-w-lg mx-auto leading-relaxed">
                    Track your service history, schedule upcoming maintenance, and manage your entire fleet all in one intuitive dashboard.
                </p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 w-full mt-4">
                <a href="{{ route('register') }}" class="flex items-center justify-center py-4 bg-brand-orange text-white rounded-xl font-bold hover:bg-orange-600 transition shadow-lg shadow-brand-orange/20 transform hover:-translate-y-1">
                    Create Free Account
                </a>
                <a href="{{ route('login') }}" class="flex items-center justify-center py-4 bg-white border border-slate-200 text-slate-800 rounded-xl font-bold hover:bg-slate-50 transition shadow-sm">
                    Login to Dashboard
                </a>
            </div>
            
            <div class="grid grid-cols-3 gap-8 mt-12 pt-12 border-t border-slate-200 text-slate-400 w-full text-sm font-medium">
                <div class="flex flex-col items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    Service History
                </div>
                <div class="flex flex-col items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Smart Reminders
                </div>
                <div class="flex flex-col items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                    Local Shops
                </div>
            </div>
        </div>
    @endauth
</div>


