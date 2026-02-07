<div class="max-w-md mx-auto mt-10">
    <div class="glass-panel rounded-xl p-8 backdrop-blur-xl relative overflow-hidden">
        <div class="absolute top-0 left-0 w-1 h-full bg-gradient-to-b from-gt-accent-orange to-red-600"></div>
        
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-white tracking-wide uppercase italic">Driver Login</h2>
            <div class="h-1 w-16 bg-gt-accent-orange mx-auto mt-2 rounded-full shadow-[0_0_10px_rgba(255,107,53,0.5)]"></div>
        </div>

        <form wire:submit="login" class="space-y-6">
            <div>
                <label for="email" class="block text-xs font-bold text-gt-text-secondary uppercase tracking-wider mb-2">Email Address</label>
                <input 
                    type="email" 
                    id="email"
                    wire:model="email" 
                    class="input-gt w-full rounded focus:ring-1 transition-all duration-300"
                    placeholder="racer@example.com"
                >
                @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-gt-text-secondary uppercase tracking-wider mb-2">Password</label>
                <input 
                    type="password" 
                    id="password"
                    wire:model="password" 
                    class="input-gt w-full rounded focus:ring-1 transition-all duration-300"
                >
                @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="remember" wire:model="remember" class="rounded bg-gt-bg-800 border-gt-bg-700 text-gt-accent-orange focus:ring-gt-accent-orange/50">
                <label for="remember" class="ml-2 text-sm text-gt-text-secondary hover:text-white transition-colors cursor-pointer">Remember me</label>
            </div>

            <button 
                type="submit" 
                class="btn-gt-primary w-full py-3 rounded uppercase tracking-widest font-bold text-sm"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-70 cursor-wait"
            >
                <span wire:loading.remove>Enter Garage</span>
                <span wire:loading>Starting Engine...</span>
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gt-text-muted">
            New Driver? 
            <a href="{{ route('register') }}" class="text-gt-accent-orange hover:text-white transition-colors font-medium border-b border-transparent hover:border-gt-accent-orange" wire:navigate>Get Your License</a>
        </p>
    </div>
</div>
