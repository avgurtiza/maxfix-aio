<div class="max-w-md mx-auto mt-10">
    <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-8 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-1 h-full bg-brand-blue"></div>
        
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Welcome Back</h2>
            <p class="text-slate-500 text-sm mt-2">Sign in to manage your garage</p>
        </div>

        <form wire:submit="login" class="space-y-6">
            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Email Address</label>
                <input 
                    type="email" 
                    id="email"
                    wire:model="email" 
                    class="block w-full border border-slate-300 rounded-lg shadow-sm py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-brand-blue/50 focus:border-brand-blue text-sm text-slate-900 placeholder-slate-400 transition-colors"
                    placeholder="you@example.com"
                >
                @error('email') <span class="text-red-500 text-xs mt-1.5 block font-medium">{{ $message }}</span> @enderror
            </div>

            <div>
                <div class="flex items-center justify-between mb-2">
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Password</label>
                    <a href="#" class="text-xs font-semibold text-brand-blue hover:text-blue-800 transition-colors">Forgot password?</a>
                </div>
                <input 
                    type="password" 
                    id="password"
                    wire:model="password" 
                    class="block w-full border border-slate-300 rounded-lg shadow-sm py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-brand-blue/50 focus:border-brand-blue text-sm text-slate-900 transition-colors"
                    placeholder="••••••••"
                >
                @error('password') <span class="text-red-500 text-xs mt-1.5 block font-medium">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="remember" wire:model="remember" class="w-4 h-4 rounded border-slate-300 text-brand-blue focus:ring-brand-blue transition-colors cursor-pointer">
                <label for="remember" class="ml-2.5 text-sm text-slate-600 hover:text-slate-900 transition-colors cursor-pointer font-medium">Remember me</label>
            </div>

            <button 
                type="submit" 
                class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-brand-blue hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-blue transition-colors transform hover:-translate-y-0.5"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-70 cursor-wait"
            >
                <span wire:loading.remove>Sign In</span>
                <span wire:loading class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Signing in...
                </span>
            </button>
        </form>

        <p class="mt-8 text-center text-sm text-slate-500">
            Don't have an account? 
            <a href="{{ route('register') }}" class="text-brand-blue hover:text-blue-800 transition-colors font-bold" wire:navigate>Create one now</a>
        </p>
    </div>
</div>
