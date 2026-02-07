<div>
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">
            {{ $reminder ? 'Edit Reminder for ' . $vehicle->display_name : 'New Reminder for ' . $vehicle->display_name }}
        </h1>

        @if(session('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit="save" class="bg-white shadow rounded-lg p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Service Name</label>
                <input 
                    type="text" 
                    wire:model="service_name" 
                    placeholder="e.g., Oil Change, Tire Rotation"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                >
                @error('service_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Reminder Type</label>
                <select wire:model="reminder_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="time">Time-based only</option>
                    <option value="mileage">Mileage-based only</option>
                    <option value="both">Both time and mileage</option>
                </select>
                @error('reminder_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            @if(in_array($reminder_type, ['time', 'both']))
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Next Due Date</label>
                        <input 
                            type="date" 
                            wire:model="next_due_date" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                        @error('next_due_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Repeat Every (days)</label>
                        <input 
                            type="number" 
                            wire:model="trigger_days" 
                            placeholder="e.g., 180 for 6 months"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                        @error('trigger_days') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            @endif

            @if(in_array($reminder_type, ['mileage', 'both']))
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Next Due Mileage (km)</label>
                        <input 
                            type="number" 
                            wire:model="next_due_mileage" 
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('reminders.index', $vehicle) }}" class="w-10 h-10 rounded-full bg-gt-bg-800 flex items-center justify-center text-gt-text-muted hover:text-white hover:bg-gt-bg-700 transition-all border border-white/5 shadow-lg" wire:navigate>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-white italic tracking-wide uppercase">
                    {{ $reminderId ? 'Adjust Strategy' : 'New Pit Stop' }}
                </h1>
                <p class="text-sm text-gt-accent-orange font-bold uppercase tracking-wider">{{ $vehicle->display_name }}</p>
            </div>
        </div>

        <form wire:submit="save" class="glass-panel rounded-xl p-8 relative overflow-hidden">
             <div class="absolute top-0 left-0 w-1 h-full bg-gradient-to-b from-gt-accent-orange to-red-600"></div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                 <div class="col-span-full">
                    <h3 class="text-sm font-bold text-gt-text-secondary uppercase tracking-widest mb-4 border-b border-white/10 pb-2">Target Service</h3>
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
                    <label class="block text-xs font-bold text-gt-text-secondary uppercase tracking-wider mb-2">Description / Note</label>
                    <input type="text" wire:model="note" class="input-gt w-full rounded focus:ring-1 transition-all duration-300" placeholder="e.g. Synthetic Oil only">
                    @error('note') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="col-span-full">
                    <h3 class="text-sm font-bold text-gt-text-secondary uppercase tracking-widest mb-4 border-b border-white/10 pb-2 mt-2">Trigger Conditions</h3>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gt-text-secondary uppercase tracking-wider mb-2">Target Date</label>
                    <input type="date" wire:model="due_date" class="input-gt w-full rounded focus:ring-1 transition-all duration-300">
                    @error('due_date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gt-text-secondary uppercase tracking-wider mb-2">Target Mileage</label>
                    <div class="relative">
                        <input type="number" wire:model="due_mileage" class="input-gt w-full rounded focus:ring-1 transition-all duration-300 pl-10 font-mono text-gt-accent-cyan">
                        <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gt-text-muted text-xs">KM</div>
                    </div>
                    <p class="text-[10px] text-gt-text-muted mt-1 uppercase tracking-wide">Current: {{ number_format($vehicle->current_mileage) }} km</p>
                    @error('due_mileage') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-end space-x-4 pt-6 border-t border-white/5">
                <a href="{{ route('reminders.index', $vehicle) }}" class="px-6 py-2 rounded text-sm font-bold text-gt-text-muted hover:text-white uppercase tracking-wider transition-colors" wire:navigate>Cancel</a>
                <button type="submit" class="btn-gt-primary px-8 py-2 rounded uppercase tracking-widest font-bold text-sm" wire:loading.attr="disabled">
                    <span wire:loading.remove>{{ $reminderId ? 'Update Strategy' : 'Set Reminder' }}</span>
                    <span wire:loading>Calculated...</span>
                </button>
            </div>
        </form>
    </div>
</div>
