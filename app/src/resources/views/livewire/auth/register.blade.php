<div class="max-w-md mx-auto mt-10">
    <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-1 h-full bg-brand-orange"></div>

        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Create an Account</h2>
            <p class="text-slate-500 text-sm mt-2">Join to manage your vehicles and maintenance</p>
        </div>

        <form wire:submit="register" class="space-y-6">
            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Full Name</label>
                <input 
                    type="text" 
                    id="name"
                    wire:model="name" 
                    class="block w-full border border-slate-300 rounded-lg shadow-sm py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-brand-blue/50 focus:border-brand-blue text-sm text-slate-900 placeholder-slate-400 transition-colors"
                    placeholder="Your Name"
                >
                @error('name') <span class="text-red-500 text-xs mt-1.5 block font-medium">{{ $message }}</span> @enderror
            </div>

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
                <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Password</label>
                <input 
                    type="password" 
                    id="password"
                    wire:model="password" 
                    class="block w-full border border-slate-300 rounded-lg shadow-sm py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-brand-blue/50 focus:border-brand-blue text-sm text-slate-900 transition-colors"
                    placeholder="••••••••"
                >
                @error('password') <span class="text-red-500 text-xs mt-1.5 block font-medium">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Confirm Password</label>
                <input 
                    type="password" 
                    id="password_confirmation"
                    wire:model="password_confirmation" 
                    class="block w-full border border-slate-300 rounded-lg shadow-sm py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-brand-blue/50 focus:border-brand-blue text-sm text-slate-900 transition-colors"
                    placeholder="••••••••"
                >
            </div>

            <button 
                type="submit" 
                class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-brand-blue hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-blue transition-colors transform hover:-translate-y-0.5"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove>Create Account</span>
                <span wire:loading class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Creating...
                </span>
            </button>
        </form>

        <p class="mt-8 text-center text-sm text-slate-500">
            Already have an account? 
            <a href="{{ route('login') }}" class="text-brand-blue hover:text-blue-800 transition-colors font-bold" wire:navigate>Sign in instead</a>
        </p>
    </div>
</div>
