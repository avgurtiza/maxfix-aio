<div>
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center mb-6">
            <a href="{{ route('services.history', $vehicle) }}" class="text-blue-600 hover:underline mr-4" wire:navigate>
                ← Back
            </a>
            <h1 class="text-2xl font-bold text-gray-900">
                {{ $service ? 'Edit Service Record' : 'Add Service Record' }}
            </h1>
        </div>

        @if(session('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit="save" class="bg-white shadow rounded-lg p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Service Date *</label>
                <input 
                    type="date" 
                    wire:model="service_date" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                >
                @error('service_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Mileage (km)</label>
                    <input 
                        type="number" 
                        wire:model="mileage" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Current mileage"
                    >
                    @error('mileage') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
        <form wire:submit="save" class="glass-panel rounded-xl p-8 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-1 h-full bg-gradient-to-b from-gt-accent-orange to-red-600"></div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Service Details -->
                <div class="col-span-full">
                    <h3 class="text-sm font-bold text-gt-text-secondary uppercase tracking-widest mb-4 border-b border-white/10 pb-2">Service Details</h3>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gt-text-secondary uppercase tracking-wider mb-2">Service Type</label>
                    <div class="relative">
                        <select wire:model="service_type" class="input-gt w-full rounded focus:ring-1 transition-all duration-300 appearance-none">
                            <option value="">Select Operation</option>
                            @foreach(['oil_change' => 'Oil Change', 'tire_rotation' => 'Tire Rotation', 'inspection' => 'Inspection', 'repair' => 'Repair', 'maintenance' => 'Maintenance', 'other' => 'Other'] as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-gt-text-muted">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                    @error('service_type') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gt-text-secondary uppercase tracking-wider mb-2">Date Performed</label>
                    <input type="date" wire:model="date" class="input-gt w-full rounded focus:ring-1 transition-all duration-300">
                    @error('date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-full">
                    <label class="block text-xs font-bold text-gt-text-secondary uppercase tracking-wider mb-2">Description</label>
                    <input type="text" wire:model="description" class="input-gt w-full rounded focus:ring-1 transition-all duration-300" placeholder="Brief summary of work performed...">
                    @error('description') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Metrics -->
                <div class="col-span-full">
                    <h3 class="text-sm font-bold text-gt-text-secondary uppercase tracking-widest mb-4 border-b border-white/10 pb-2 mt-2">Metrics</h3>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gt-text-secondary uppercase tracking-wider mb-2">Mileage at Service</label>
                    <div class="relative">
                        <input type="number" wire:model="mileage_at_service" class="input-gt w-full rounded focus:ring-1 transition-all duration-300 pl-10 font-mono text-gt-accent-cyan">
                        <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gt-text-muted text-xs">KM</div>
                    </div>
                    @error('mileage_at_service') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gt-text-secondary uppercase tracking-wider mb-2">Total Cost</label>
                    <div class="relative">
                        <input type="number" wire:model="cost" step="0.01" class="input-gt w-full rounded focus:ring-1 transition-all duration-300 pl-8 font-mono text-white">
                        <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gt-text-muted">$</div>
                    </div>
                    @error('cost') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Provider -->
                <div class="col-span-full">
                    <h3 class="text-sm font-bold text-gt-text-secondary uppercase tracking-widest mb-4 border-b border-white/10 pb-2 mt-2">Service Provider</h3>
                </div>

                <div class="col-span-full">
                    <label class="block text-xs font-bold text-gt-text-secondary uppercase tracking-wider mb-2">Shop/Mechanic Name</label>
                    <input type="text" wire:model="service_provider" class="input-gt w-full rounded focus:ring-1 transition-all duration-300" placeholder="e.g. GT Auto Tuning">
                    @error('service_provider') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div class="col-span-full">
                    <label class="block text-xs font-bold text-gt-text-secondary uppercase tracking-wider mb-2">Additional Notes</label>
                    <textarea wire:model="notes" rows="3" class="input-gt w-full rounded focus:ring-1 transition-all duration-300" placeholder="Technical details, parts used..."></textarea>
                    @error('notes') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-full">
                    <div class="flex items-center gap-3 p-4 bg-gt-bg-900/50 rounded border border-white/5">
                         <div class="flex items-center h-5">
                            <input id="set_reminder" wire:model="set_next_service_reminder" type="checkbox" class="rounded bg-gt-bg-800 border-gt-bg-700 text-gt-accent-cyan focus:ring-gt-accent-cyan/50">
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
                        <input type="number" wire:model="next_service_mileage" class="input-gt w-full rounded focus:ring-1 transition-all duration-300 font-mono">
                    </div>
                @endif
            </div>

            <div class="flex justify-end space-x-4 pt-6 border-t border-white/5">
                <a href="{{ route('services.history', $vehicle) }}" class="px-6 py-2 rounded text-sm font-bold text-gt-text-muted hover:text-white uppercase tracking-wider transition-colors" wire:navigate>Cancel</a>
                <button type="submit" class="btn-gt-primary px-8 py-2 rounded uppercase tracking-widest font-bold text-sm" wire:loading.attr="disabled">
                    <span wire:loading.remove>{{ $service ? 'Update Record' : 'Log Service' }}</span>
                    <span wire:loading>Processing...</span>
                </button>
            </div>
        </form>
    </div>
</div>
