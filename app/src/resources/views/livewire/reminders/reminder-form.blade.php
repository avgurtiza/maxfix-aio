<div class="max-w-2xl mx-auto py-8">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('reminders.index', $vehicle) }}" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-gt-text-muted hover:text-white transition-all border border-white/5 shadow-lg" wire:navigate>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h1 class="text-3xl font-extrabold text-white italic tracking-tighter uppercase">
                {{ $reminder ? 'Adjust strategy' : 'New Strategy' }}
            </h1>
            <p class="text-sm text-gt-accent-orange font-bold uppercase tracking-wider">{{ $vehicle->year }} {{ $vehicle->manufacturer }} {{ $vehicle->model }}</p>
        </div>
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
                <h3 class="text-sm font-bold text-gt-text-secondary uppercase tracking-widest mb-4 border-b border-white/10 pb-2">Target Maintenance</h3>
            </div>

            <div class="col-span-full">
                <label class="block text-xs font-bold text-gt-text-secondary uppercase tracking-wider mb-2">Service Component Name</label>
                <input type="text" wire:model="service_name" class="input-gt w-full rounded focus:ring-1 transition-all duration-300" placeholder="e.g. Engine Oil, Front Tires, Brake Pads">
                @error('service_name') <span class="text-red-500 text-[10px] mt-1 block font-bold uppercase">{{ $message }}</span> @enderror
            </div>

            <div class="col-span-full">
                <label class="block text-xs font-bold text-gt-text-secondary uppercase tracking-wider mb-2">Interval Logic</label>
                <select wire:model.live="reminder_type" class="input-gt w-full rounded focus:ring-1 transition-all duration-300 appearance-none">
                    <option value="time">Time Interval (Date)</option>
                    <option value="mileage">Distance Interval (KM)</option>
                    <option value="both">Synchronized (Both)</option>
                </select>
                @error('reminder_type') <span class="text-red-500 text-[10px] mt-1 block font-bold uppercase">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="col-span-full">
                <h3 class="text-sm font-bold text-gt-text-secondary uppercase tracking-widest mb-4 border-b border-white/10 pb-2">Thresholds</h3>
            </div>

            @if(in_array($reminder_type, ['time', 'both']))
                <div>
                    <label class="block text-xs font-bold text-gt-text-secondary uppercase tracking-wider mb-2">Target Date</label>
                    <input type="date" wire:model="next_due_date" class="input-gt w-full rounded focus:ring-1 transition-all duration-300">
                    @error('next_due_date') <span class="text-red-500 text-[10px] mt-1 block font-bold uppercase">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-gt-text-secondary uppercase tracking-wider mb-2">Auto-Cycle (Days)</label>
                    <input type="number" wire:model="trigger_days" class="input-gt w-full rounded focus:ring-1 transition-all duration-300" placeholder="e.g. 180 for 6 months">
                </div>
            @endif

            @if(in_array($reminder_type, ['mileage', 'both']))
                <div>
                    <label class="block text-xs font-bold text-gt-text-secondary uppercase tracking-wider mb-2">Target Mileage</label>
                    <div class="relative">
                        <input type="number" wire:model="next_due_mileage" class="input-gt w-full rounded focus:ring-1 transition-all duration-300 pl-10 font-mono text-gt-accent-cyan">
                        <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gt-text-muted text-[10px]">KM</div>
                    </div>
                    @error('next_due_mileage') <span class="text-red-500 text-[10px] mt-1 block font-bold uppercase">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-gt-text-secondary uppercase tracking-wider mb-2">Distance Interval</label>
                    <input type="number" wire:model="trigger_mileage" class="input-gt w-full rounded focus:ring-1 transition-all duration-300 font-mono" placeholder="e.g. 5000">
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="col-span-full">
                <h3 class="text-sm font-bold text-gt-text-secondary uppercase tracking-widest mb-4 border-b border-white/10 pb-2">Configuration</h3>
            </div>

            <div class="col-span-full flex items-center justify-between p-4 bg-gt-bg-900/50 rounded border border-white/5">
                <div>
                    <span class="text-xs font-bold text-white uppercase tracking-wider">Operational Status</span>
                    <p class="text-[10px] text-gt-text-muted">Disable to pause tracking without deleting strategy</p>
                </div>
                <div class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" wire:model="is_active" class="sr-only peer">
                    <div class="w-11 h-6 bg-gt-bg-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gt-accent-cyan"></div>
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-4 pt-6 border-t border-white/5">
            <a href="{{ route('reminders.index', $vehicle) }}" class="px-6 py-2 rounded text-xs font-bold text-gt-text-muted hover:text-white uppercase tracking-widest transition-colors" wire:navigate>Cancel</a>
            <button type="submit" class="btn-gt-primary px-8 py-2 rounded uppercase tracking-widest font-bold text-sm" wire:loading.attr="disabled">
                <span wire:loading.remove>{{ $reminder ? 'Update Strategy' : 'Confirm PIT STOP' }}</span>
                <span wire:loading>Processing...</span>
            </button>
        </div>
    </form>
</div>
