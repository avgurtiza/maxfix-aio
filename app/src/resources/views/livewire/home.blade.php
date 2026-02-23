<div class="h-[calc(100vh-8rem)] relative flex flex-col overflow-hidden">
    <!-- World Map Background Pattern -->
    <div class="absolute inset-0 opacity-20 pointer-events-none">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_var(--color-gt-bg-800)_0%,_var(--color-gt-bg-950)_100%)]"></div>
        <div class="grid grid-cols-[repeat(20,minmax(0,1fr))] grid-rows-[repeat(10,minmax(0,1fr))] w-full h-full">
            @for($i = 0; $i < 200; $i++)
                <div class="border-[0.5px] border-white/5"></div>
            @endfor
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="relative z-10 flex-1 flex flex-col p-6 md:p-12">
        <!-- Header / Status -->
        <div class="flex justify-between items-start mb-8">
            <div>
                <div class="inline-flex items-center gap-3 px-4 py-2 glass-panel rounded-full text-xs font-bold uppercase tracking-widest text-gt-accent-orange border-l-4 border-l-gt-accent-orange">
                    <span class="w-2 h-2 rounded-full bg-gt-accent-orange animate-pulse"></span>
                    System Online
                </div>
                <h1 class="mt-4 text-4xl md:text-6xl font-black italic uppercase text-white tracking-tighter">
                    Max<span class="text-gt-accent-orange">Fix</span> <span class="text-gt-text-muted text-2xl md:text-3xl not-italic font-light ml-2">v2.0</span>
                </h1>
                <p class="text-gt-text-secondary mt-2 max-w-xl font-light">
                    Select a destination to begin fleet management operations.
                </p>
            </div>
            
            <!-- Weather / Time Widget (Static Mock) -->
            <div class="hidden md:flex flex-col items-end text-right">
                <div class="text-5xl font-mono text-white font-bold tracking-tighter">{{ now()->format('H:i') }}</div>
                <div class="text-gt-accent-cyan font-bold uppercase text-sm tracking-wide">{{ now()->format('l, F j') }}</div>
                <div class="flex items-center gap-2 mt-2 text-gt-text-muted text-xs uppercase">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/></svg>
                    <span>Clear Sky / 24°C</span>
                </div>
            </div>
        </div>

        <!-- Center Stage (Empty space for the "Car" typically, but here functionally empty for now) -->
        <div class="flex-1"></div>

        <!-- Bottom Navigation Menu (GT7 Menu Style) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-8 mt-auto">
            @auth
                <!-- Garage Card -->
                <a href="{{ route('vehicles.index') }}" class="group relative h-48 glass-panel rounded-xl overflow-hidden border border-white/10 hover:border-gt-accent-orange transition-all duration-300 transform hover:-translate-y-2 hover:shadow-gt-glow-orange" wire:navigate>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent z-10"></div>
                    <div class="absolute right-4 top-4 z-20 w-10 h-10 rounded-full border border-white/20 flex items-center justify-center bg-black/30 group-hover:bg-gt-accent-orange group-hover:border-gt-accent-orange transition-colors">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <div class="absolute bottom-0 left-0 p-6 z-20 w-full">
                        <div class="h-1 w-12 bg-gt-accent-orange mb-3 group-hover:w-full transition-all duration-500"></div>
                        <h3 class="text-2xl font-black italic uppercase text-white group-hover:text-gt-accent-orange transition-colors">My Garage</h3>
                        <p class="text-sm text-gray-300 mt-1 line-clamp-1">Manage vehicles and pending services</p>
                    </div>
                </a>

                <!-- Shops Card -->
                <a href="{{ route('shops.index') }}" class="group relative h-48 glass-panel rounded-xl overflow-hidden border border-white/10 hover:border-gt-accent-cyan transition-all duration-300 transform hover:-translate-y-2 hover:shadow-gt-glow-cyan" wire:navigate>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent z-10"></div>
                    <div class="absolute right-4 top-4 z-20 w-10 h-10 rounded-full border border-white/20 flex items-center justify-center bg-black/30 group-hover:bg-gt-accent-cyan group-hover:border-gt-accent-cyan transition-colors">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div class="absolute bottom-0 left-0 p-6 z-20 w-full">
                        <div class="h-1 w-12 bg-gt-accent-cyan mb-3 group-hover:w-full transition-all duration-500"></div>
                        <h3 class="text-2xl font-black italic uppercase text-white group-hover:text-gt-accent-cyan transition-colors">Tuning Shops</h3>
                        <p class="text-sm text-gray-300 mt-1 line-clamp-1">Find certified service centers nearby</p>
                    </div>
                </a>

                <!-- Analytics/History Card -->
                <div class="group relative h-48 glass-panel rounded-xl overflow-hidden border border-white/10 hover:border-purple-500 transition-all duration-300 transform hover:-translate-y-2 hover:shadow-[0_0_15px_rgba(168,85,247,0.3)] opacity-75 cursor-not-allowed">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent z-10"></div>
                    <div class="absolute right-4 top-4 z-20 w-10 h-10 rounded-full border border-white/20 flex items-center justify-center bg-black/30 group-hover:bg-purple-500 group-hover:border-purple-500 transition-colors">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-30">
                        <span class="px-3 py-1 bg-black/50 border border-white/10 rounded text-xs font-mono uppercase text-white/50">Coming Soon</span>
                    </div>
                    <div class="absolute bottom-0 left-0 p-6 z-20 w-full blur-[1px] group-hover:blur-0 transition-all">
                        <div class="h-1 w-12 bg-purple-500 mb-3 group-hover:w-full transition-all duration-500"></div>
                        <h3 class="text-2xl font-black italic uppercase text-white group-hover:text-purple-400 transition-colors">Service History</h3>
                        <p class="text-sm text-gray-300 mt-1 line-clamp-1">Track costs and performance</p>
                    </div>
                </div>
            @else
                <!-- Guest View: Auth Options -->
                <div class="md:col-span-3 flex flex-col md:flex-row gap-6 justify-center items-center h-48">
                    <a href="{{ route('login') }}" class="group relative w-full md:w-1/3 h-full glass-panel rounded-xl overflow-hidden border border-white/10 hover:border-white transition-all duration-300 flex flex-col items-center justify-center" wire:navigate>
                        <span class="text-3xl font-black italic uppercase text-white group-hover:scale-110 transition-transform mb-2">Login</span>
                        <div class="h-1 w-12 bg-white/20 group-hover:bg-white group-hover:w-24 transition-all"></div>
                    </a>
                    
                    <a href="{{ route('register') }}" class="group relative w-full md:w-1/3 h-full bg-gt-accent-orange rounded-xl overflow-hidden shadow-lg hover:shadow-gt-glow-orange transition-all duration-300 flex flex-col items-center justify-center transform hover:-translate-y-1" wire:navigate>
                        <div class="absolute inset-0 bg-gradient-to-tr from-black/20 to-transparent"></div>
                        <span class="relative text-3xl font-black italic uppercase text-white group-hover:scale-110 transition-transform mb-2">Start Engine</span>
                        <div class="relative h-1 w-12 bg-white/50 group-hover:bg-white group-hover:w-24 transition-all"></div>
                        <span class="relative mt-2 text-xs font-bold uppercase tracking-widest text-white/80">Create Account</span>
                    </a>
                </div>
            @endauth
        </div>
    </div>

    <!-- Ticker / Footer Line -->
    <div class="h-8 bg-gt-bg-900 border-t border-white/10 flex items-center px-4 overflow-hidden relative z-20">
        <div class="flex items-center gap-8 animate-marquee whitespace-nowrap text-xs font-mono text-gt-text-muted uppercase tracking-wider">
            <span class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Server Status: Stable</span>
            <span class="flex items-center gap-2 text-gt-accent-orange">:: Latest Update: UI Refactor v2.1.0</span>
            <span class="text-gt-accent-cyan">:: Priority: High Performance</span>
            <span>:: MaxFix Automotive Systems ::</span>
            <span class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Database: Connected</span>
        </div>
    </div>
    <style>
        @keyframes marquee {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
        .animate-marquee {
            animation: marquee 30s linear infinite;
        }
    </style>
</div>


