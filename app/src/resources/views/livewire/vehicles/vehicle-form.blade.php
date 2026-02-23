<div>
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('vehicles.index') }}" class="w-10 h-10 rounded-full bg-gt-bg-800 flex items-center justify-center text-gt-text-muted hover:text-white hover:bg-gt-bg-700 transition-all border border-white/5 shadow-lg" wire:navigate>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-white italic tracking-wide uppercase">
                    {{ $vehicle ? 'Tuning Setup' : 'New Acquisition' }}
                </h1>
                <p class="text-sm text-gt-text-muted uppercase tracking-wider">{{ $vehicle ? 'Modify Vehicle Specs' : 'Register New Machine' }}</p>
            </div>
        </div>

        @if(session('message'))
            <div class="glass-panel border-l-4 border-l-green-500 text-green-400 px-6 py-4 rounded mb-6 flex items-center shadow-lg">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('message') }}
            </div>
        @endif

        @if($showVinDecoder)
            <div class="glass-panel rounded-xl p-6 mb-8 border border-gt-accent-cyan/30 shadow-[0_0_15px_rgba(0,212,255,0.1)]">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-white uppercase tracking-wide flex items-center gap-2">
                        <span class="text-gt-accent-cyan">VIN Decoder</span>
                        <span class="text-xs bg-gt-accent-cyan/20 text-gt-accent-cyan px-2 py-0.5 rounded">AUTO-FILL</span>
                    </h2>
                    <button wire:click="$set('showVinDecoder', false)" class="text-xs text-gt-text-muted hover:text-white uppercase tracking-wider underline">Skip Manual Entry</button>
                </div>
                
                <div class="flex gap-4">
                    <div class="flex-1 relative">
                        <input 
                            type="text" 
                            wire:model="vin"
                            placeholder="Enter 17-character VIN"
                            class="input-gt w-full rounded pl-10 font-mono tracking-widest uppercase"
                            maxlength="17"
                        >
                        <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gt-text-muted">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                    </div>
                    <button 
                        wire:click="decodeVin"
                        wire:loading.attr="disabled"
                        class="bg-gt-accent-cyan text-gt-bg-950 font-bold px-6 py-2 rounded shadow-lg hover:shadow-[0_0_15px_rgba(0,212,255,0.4)] disabled:opacity-50 transition-all uppercase tracking-wide text-sm"
                    >
                        <span wire:loading.remove>Scan VIN</span>
                        <span wire:loading>Scanning...</span>
                    </button>
                </div>
                @error('vin') <span class="text-red-500 text-xs mt-2 block">{{ $message }}</span> @enderror
            </div>
        @endif

        <form wire:submit="save" class="glass-panel rounded-xl p-8 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-1 h-full bg-gradient-to-b from-gt-accent-orange to-red-600"></div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Vehicle Identity -->
                <div class="col-span-full">
                    <h3 class="text-sm font-bold text-gt-text-secondary uppercase tracking-widest mb-4 border-b border-white/10 pb-2">Vehicle Identity</h3>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gt-text-secondary uppercase tracking-wider mb-2">Manufacturer</label>
                    <input type="text" wire:model="make" class="input-gt w-full rounded focus:ring-1 transition-all duration-300" placeholder="e.g. Toyota">
                    @error('make') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-gt-text-secondary uppercase tracking-wider mb-2">Model Name</label>
                    <input type="text" wire:model="model" class="input-gt w-full rounded focus:ring-1 transition-all duration-300" placeholder="e.g. GR Supra">
                    @error('model') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gt-text-secondary uppercase tracking-wider mb-2">Model Year</label>
                    <input type="number" wire:model="year" class="input-gt w-full rounded focus:ring-1 transition-all duration-300" placeholder="YYYY">
                    @error('year') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-gt-text-secondary uppercase tracking-wider mb-2">License Plate</label>
                    <input type="text" wire:model="current_plate" class="input-gt w-full rounded focus:ring-1 transition-all duration-300 font-mono uppercase" placeholder="ABC-123">
                    @error('current_plate') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Specs -->
                <div class="col-span-full">
                    <h3 class="text-sm font-bold text-gt-text-secondary uppercase tracking-widest mb-4 border-b border-white/10 pb-2 mt-2">Specifications</h3>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gt-text-secondary uppercase tracking-wider mb-2">Current Odometer (km)</label>
                    <input type="number" wire:model="current_mileage" class="input-gt w-full rounded focus:ring-1 transition-all duration-300 font-mono text-gt-accent-cyan">
                    @error('current_mileage') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-gt-text-secondary uppercase tracking-wider mb-2">Body Color</label>
                    <input type="text" wire:model="color" class="input-gt w-full rounded focus:ring-1 transition-all duration-300" placeholder="e.g. Matte Storm Gray">
                    @error('color') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-full">
                    <label class="block text-xs font-bold text-gt-text-secondary uppercase tracking-wider mb-2">Powertrain Type</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach(['gasoline' => 'Gasoline', 'diesel' => 'Diesel', 'electric' => 'Electric', 'hybrid' => 'Hybrid'] as $value => $label)
                            <label class="cursor-pointer">
                                <input type="radio" wire:model="fuel_type" value="{{ $value }}" class="peer sr-only">
                                <div class="rounded border border-gt-bg-700 bg-gt-bg-900/50 p-3 text-center text-sm font-medium text-gt-text-muted transition-all peer-checked:border-gt-accent-orange peer-checked:text-white peer-checked:bg-gt-accent-orange/10 peer-checked:shadow-[0_0_10px_rgba(255,107,53,0.2)] hover:border-white/20">
                                    {{ $label }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('fuel_type') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mb-8">
                <label class="block text-xs font-bold text-gt-text-secondary uppercase tracking-wider mb-2">Mechanic Notes</label>
                <textarea wire:model="notes" rows="3" class="input-gt w-full rounded focus:ring-1 transition-all duration-300" placeholder="Additional details..."></textarea>
                @error('notes') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            @error('limit')
                <div class="bg-red-900/50 border border-red-500/50 text-red-200 px-4 py-3 rounded mb-6">
                    {{ $message }}
                </div>
            @enderror

            <div class="flex justify-end space-x-4 pt-6 border-t border-white/5">
                <a href="{{ route('vehicles.index') }}" class="px-6 py-2 rounded text-sm font-bold text-gt-text-muted hover:text-white uppercase tracking-wider transition-colors" wire:navigate>Cancel</a>
                <button type="submit" class="btn-gt-primary px-8 py-2 rounded uppercase tracking-widest font-bold text-sm" wire:loading.attr="disabled">
                    <span wire:loading.remove>{{ $vehicle ? 'Save Configuration' : 'Add to Garage' }}</span>
                    <span wire:loading>Saving...</span>
                </button>
            </div>
        </form>
    </div>
</div>
