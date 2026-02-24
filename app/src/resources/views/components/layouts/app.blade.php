<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'MaxFix' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-slate-50 text-slate-800 flex h-screen overflow-hidden antialiased selection:bg-brand-orange selection:text-white font-sans">
    
    <!-- Sidebar -->
    <aside class="w-64 bg-slate-800 text-white flex-col pt-6 hidden md:flex shrink-0">
        <div class="px-6 mb-8 flex items-center gap-3">
            <div class="w-10 h-10 bg-brand-orange flex items-center justify-center rounded-lg font-bold text-lg shadow-lg">
                Mx
            </div>
            <div>
                <h1 class="font-bold text-xl leading-tight">MaxFix</h1>
                <p class="text-xs text-slate-400 font-medium tracking-wide">Vehicle Maintenance</p>
            </div>
        </div>
        
        <nav class="flex-1 px-4 space-y-2">
            <a href="/" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('home') ? 'bg-slate-700/50 text-brand-orange' : 'text-slate-300 hover:text-white hover:bg-slate-700/30' }}" wire:navigate>
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                Dashboard
            </a>
            
            @auth
            <a href="{{ route('vehicles.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('vehicles.*') ? 'bg-slate-700/50 text-brand-orange' : 'text-slate-300 hover:text-white hover:bg-slate-700/30' }}" wire:navigate>
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                </svg>
                Vehicles
            </a>
            <a href="{{ route('shops.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('shops.*') ? 'bg-slate-700/50 text-brand-orange' : 'text-slate-300 hover:text-white hover:bg-slate-700/30' }}" wire:navigate>
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Shops
            </a>
            @endauth
        </nav>

        <div class="p-4 border-t border-slate-700/50 mt-auto">
            <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                <div class="w-2 h-2 bg-brand-success rounded-full"></div>
                MaxFix v2.1.0 Design
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 overflow-y-auto w-full relative">
        
        <!-- Header -->
        <header class="bg-white border-b border-slate-200 px-6 sm:px-8 py-4 sm:py-5 flex items-center justify-between sticky top-0 z-50">
            <!-- Mobile Menu Toggle (stubbed for future) -->
            <button class="md:hidden p-2 -ml-2 text-slate-500 hover:bg-slate-100 rounded-lg">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            
            <div class="hidden sm:block">
                @if(request()->routeIs('home'))
                    <h2 class="text-xl sm:text-2xl font-bold flex flex-col text-slate-800">
                        @auth Welcome back, {{ auth()->user()->name }}! @else Welcome to MaxFix @endauth
                        <span class="text-sm font-normal text-slate-500 mt-0.5">@auth Here's what is happening with your fleet today. @else Manage your vehicle maintenance simply. @endauth</span>
                    </h2>
                @else
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-800 tracking-tight">{{ $title ?? 'Dashboard' }}</h2>
                @endif
            </div>

            <div class="flex items-center gap-2 sm:gap-4 ml-auto">
                @auth
                    <livewire:reminders.reminder-notifications />
                    <div class="h-6 w-px bg-slate-200 hidden sm:block"></div>
                    <livewire:auth.logout />
                @else
                    <a href="{{ route('login') }}" class="text-sm font-bold text-slate-600 hover:text-slate-900 transition-colors px-2">Login</a>
                    <a href="{{ route('register') }}" class="bg-brand-orange text-white px-4 sm:px-5 py-2 rounded-lg text-sm font-bold shadow-md shadow-brand-orange/20 hover:bg-orange-600 transform hover:-translate-y-0.5 transition-all">Start Engine</a>
                @endauth
            </div>
        </header>

        <div class="p-6 sm:p-8 max-w-6xl mx-auto space-y-8 pb-24">
            {{ $slot }}
        </div>
    </main>

    @livewireScripts
</body>
</html>
