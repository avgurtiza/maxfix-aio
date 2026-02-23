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
<body class="min-h-screen bg-gt-bg-950 text-gt-text-primary antialiased selection:bg-gt-accent-orange selection:text-white">
    <nav class="glass-panel sticky top-0 z-50 border-b border-white/5">
        <div class="racing-stripe absolute top-0 left-0"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center gap-3 group">
                        <div class="w-8 h-8 rounded bg-gradient-to-tr from-gt-accent-orange to-red-600 flex items-center justify-center shadow-lg group-hover:shadow-gt-glow-orange transition-all duration-300">
                            <span class="font-bold text-white italic">M</span>
                        </div>
                        <span class="text-xl font-bold italic tracking-wider text-white group-hover:text-gt-accent-orange transition-colors">MaxFix</span>
                    </a>
                </div>
                
                @auth
                    <div class="flex items-center space-x-8">
                        <a href="{{ route('vehicles.index') }}" class="text-sm font-bold tracking-wide transition-colors duration-200 uppercase {{ request()->routeIs('vehicles.*') ? 'text-gt-accent-orange' : 'text-gt-text-secondary hover:text-white' }}">
                            Garage
                        </a>
                        <a href="{{ route('shops.index') }}" class="text-sm font-bold tracking-wide transition-colors duration-200 uppercase {{ request()->routeIs('shops.*') ? 'text-gt-accent-orange' : 'text-gt-text-secondary hover:text-white' }}">
                            Tuning Shops
                        </a>
                        <div class="border-l border-white/10 h-6 mx-2"></div>
                        <livewire:reminders.reminder-notifications />
                        <livewire:auth.logout />
                    </div>
                @else
                    <div class="flex items-center space-x-6">
                        <a href="{{ route('login') }}" class="text-sm font-bold tracking-wide text-gt-text-secondary hover:text-white transition-colors uppercase">Login</a>
                        <a href="{{ route('register') }}" class="btn-gt-primary px-6 py-2 rounded text-sm font-bold tracking-wide uppercase transform hover:-translate-y-0.5 transition-transform">
                            Start Engine
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 flex-grow w-full">
        {{ $slot }}
    </main>
    
    <footer class="border-t border-white/5 bg-gt-bg-900/50 mt-auto backdrop-blur-sm">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center text-xs text-gt-text-muted gap-4">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 bg-gt-accent-orange rounded-full"></div>
                <p>&copy; {{ date('Y') }} MaxFix Automotive. All rights reserved.</p>
            </div>
            <div class="flex space-x-6">
                <span class="uppercase tracking-widest opacity-50 hover:opacity-100 transition-opacity cursor-default">Driving Simulator Aesthetic</span>
                <span class="uppercase tracking-widest opacity-50 hover:opacity-100 transition-opacity cursor-default">Ver. 2.0</span>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
