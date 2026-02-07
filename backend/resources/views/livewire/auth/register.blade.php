<div class="max-w-md mx-auto mt-10">
    <div class="glass-panel rounded-xl p-8 backdrop-blur-xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-1 h-full bg-gradient-to-b from-red-600 to-gt-accent-orange"></div>

        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-white tracking-wide uppercase italic">New River Registration</h2>
            <div class="h-1 w-24 bg-gt-accent-orange mx-auto mt-2 rounded-full shadow-[0_0_10px_rgba(255,107,53,0.5)]"></div>
        </div>

        <form wire:submit="register" class="space-y-6">
            <div>
                <label for="name" class="block text-xs font-bold text-gt-text-secondary uppercase tracking-wider mb-2">Driver Name</label>
                <input 
                    type="text" 
                    id="name"
                    wire:model="name" 
                    class="input-gt w-full rounded focus:ring-1 transition-all duration-300"
                    placeholder="Your Name"
                >
                @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

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

            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-gt-text-secondary uppercase tracking-wider mb-2">Confirm Password</label>
                <input 
                    type="password" 
                    id="password_confirmation"
                    wire:model="password_confirmation" 
                    class="input-gt w-full rounded focus:ring-1 transition-all duration-300"
                >
            </div>

            <button 
                type="submit" 
                class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove>Create Account</span>
                <span wire:loading>Creating...</span>
            </button>
        </form>

        <p class="mt-4 text-center text-sm text-gray-600">
            Already have an account? 
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline" wire:navigate>Login</a>
        </p>
    </div>
</div>
