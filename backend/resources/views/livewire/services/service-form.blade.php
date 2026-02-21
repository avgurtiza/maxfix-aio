<div class="max-w-2xl mx-auto py-8">
    <div class="flex items-center mb-8">
        <a href="{{ route('services.history', $vehicle) }}" class="w-10 h-10 flex items-center justify-center rounded bg-white/5 text-gt-text-muted hover:text-white mr-4 transition-colors" wire:navigate>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h1 class="text-3xl font-extrabold text-white tracking-tighter uppercase italic">
            {{ $service ? 'Modify Record' : 'New Maintenance' }}
        </h1>
    </div>

    @if(session('message'))
        <div class="glass-panel border-l-4 border-gt-accent-cyan p-4 mb-6">
            <p class="text-gt-accent-cyan font-bold text-sm">{{ session('message') }}</p>
        </div>
    @endif

    <form wire:submit="save" class="glass-panel rounded-xl p-8 relative overflow-hidden border border-white/5">
        <div class="absolute top-0 left-0 w-1 h-full bg-gradient-to-b from-gt-accent-orange to-red-600"></div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="col-span-full">
                <h3 class="text-sm font-bold text-gt-text-secondary uppercase tracking-widest mb-4 border-b border-white/10 pb-2">Service Details</h3>
            </div>

            <div>
                <label class="block text-xs font-bold text-gt-text-secondary uppercase tracking-wider mb-2">Service Type</label>
                <div class="relative">
                    <select wire:model="service_type" class="input-gt w-full rounded focus:ring-1 transition-all duration-300 appearance-none">
                        <option value="">Select Operation</option>
                        @foreach(['oil_change' => 'Oil Change', 'tire_rotation' => 'Tire Rotation', 'brake_service' => 'Brake Service', 'transmission' => 'Transmission', 'engine' => 'Engine', 'electrical' => 'Electrical', 'air_conditioning' => 'Air Conditioning', 'suspension' => 'Suspension', 'inspection' => 'Inspection', 'other' => 'Other'] as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-gt-text-muted">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
                @error('service_type') <span class="text-red-500 text-xs mt-1 block font-bold uppercase">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-gt-text-secondary uppercase tracking-wider mb-2">Date Performed</label>
                <input type="date" wire:model="service_date" class="input-gt w-full rounded focus:ring-1 transition-all duration-300">
                @error('service_date') <span class="text-red-500 text-xs mt-1 block font-bold uppercase">{{ $message }}</span> @enderror
            </div>

            <div class="col-span-full">
                <label class="block text-xs font-bold text-gt-text-secondary uppercase tracking-wider mb-2">Description</label>
                <input type="text" wire:model="description" class="input-gt w-full rounded focus:ring-1 transition-all duration-300" placeholder="Brief summary of work performed...">
                @error('description') <span class="text-red-500 text-xs mt-1 block font-bold uppercase">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="col-span-full">
                <h3 class="text-sm font-bold text-gt-text-secondary uppercase tracking-widest mb-4 border-b border-white/10 pb-2">Metrics</h3>
            </div>

            <div>
                <label class="block text-xs font-bold text-gt-text-secondary uppercase tracking-wider mb-2">Mileage at Service</label>
                <div class="relative">
                    <input type="number" wire:model="mileage" class="input-gt w-full rounded focus:ring-1 transition-all duration-300 pl-10 font-mono text-gt-accent-cyan">
                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gt-text-muted text-xs">KM</div>
                </div>
                @error('mileage') <span class="text-red-500 text-xs mt-1 block font-bold uppercase">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-gt-text-secondary uppercase tracking-wider mb-2">Total Cost</label>
                <div class="relative">
                    <input type="number" wire:model="cost" step="0.01" class="input-gt w-full rounded focus:ring-1 transition-all duration-300 pl-8 font-mono text-white">
                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gt-text-muted">$</div>
                </div>
                @error('cost') <span class="text-red-500 text-xs mt-1 block font-bold uppercase">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="col-span-full">
                <h3 class="text-sm font-bold text-gt-text-secondary uppercase tracking-widest mb-4 border-b border-white/10 pb-2">Registration</h3>
            </div>

            <div class="col-span-full">
                <label class="block text-xs font-bold text-gt-text-secondary uppercase tracking-wider mb-2">Shop/Mechanic Name</label>
                <div class="relative">
                    <select wire:model="shop_id" class="input-gt w-full rounded focus:ring-1 transition-all duration-300 appearance-none">
                        <option value="">Private Garage / Unlisted Shop</option>
                        @foreach($shops as $shop)
                            <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-gt-text-muted">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
                @error('shop_id') <span class="text-red-500 text-xs mt-1 block font-bold uppercase">{{ $message }}</span> @enderror
            </div>
            
            <div class="col-span-full">
                <label class="block text-xs font-bold text-gt-text-secondary uppercase tracking-wider mb-2">Technical Notes</label>
                <textarea wire:model="description" rows="3" class="input-gt w-full rounded focus:ring-1 transition-all duration-300" placeholder="Technical details, parts used..."></textarea>
            </div>

            <div class="col-span-full">
                <div class="flex items-center gap-3 p-4 bg-gt-bg-900/50 rounded border border-white/5">
                    <div class="flex items-center h-5">
                        <input id="set_reminder" wire:model.live="set_next_service_reminder" type="checkbox" class="rounded bg-gt-bg-800 border-gt-bg-700 text-gt-accent-cyan focus:ring-gt-accent-cyan/50">
                    </div>
                    <div class="ml-2 text-sm">
                        <label for="set_reminder" class="font-bold text-white uppercase tracking-wide">Schedule Next Service</label>
                        <p class="text-xs text-gt-text-muted">Automatically create a reminder for the next interval</p>
                    </div>
                </div>
            </div>

            @if($set_next_service_reminder)
                <div>
                    <label class="block text-xs font-bold text-gt-text-secondary uppercase tracking-wider mb-2">Next Service Date</label>
                    <input type="date" wire:model="next_service_date" class="input-gt w-full rounded focus:ring-1 transition-all duration-300">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gt-text-secondary uppercase tracking-wider mb-2">Next Service Mileage (km)</label>
                    <input type="number" wire:model="next_service_mileage" class="input-gt w-full rounded focus:ring-1 transition-all duration-300 font-mono text-gt-accent-cyan">
                </div>
            @endif
        </div>

        <div class="flex justify-end space-x-4 pt-6 border-t border-white/5">
            <a href="{{ route('services.history', $vehicle) }}" class="px-6 py-2 rounded text-xs font-bold text-gt-text-muted hover:text-white uppercase tracking-widest transition-colors" wire:navigate>Cancel</a>
            <button type="submit" class="btn-gt-primary px-8 py-2 rounded uppercase tracking-widest font-bold text-sm" wire:loading.attr="disabled">
                <span wire:loading.remove>{{ $service ? 'Save Records' : 'Validate Entry' }}</span>
                <span wire:loading>Processing...</span>
            </button>
        </div>
    </form>
</div>
