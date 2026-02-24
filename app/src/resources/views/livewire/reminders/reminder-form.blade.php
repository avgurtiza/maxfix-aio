<div class="max-w-3xl mx-auto py-8">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('reminders.index', $vehicle) }}" class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-slate-500 hover:text-slate-900 hover:bg-slate-50 transition-all border border-slate-200 shadow-sm" wire:navigate>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
                {{ $reminder ? 'Edit Reminder' : 'New Maintenance Reminder' }}
            </h1>
            <p class="text-sm text-slate-500 font-medium mt-1">{{ $vehicle->year }} {{ $vehicle->make ?? $vehicle->manufacturer }} {{ $vehicle->model }}</p>
        </div>
    </div>

    @if(session('message'))
        <div class="bg-brand-success/10 border-l-4 border-l-brand-success text-green-800 px-6 py-4 rounded-lg mb-6 flex items-center shadow-sm">
            <svg class="w-6 h-6 mr-3 text-brand-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <p class="font-medium text-sm">{{ session('message') }}</p>
        </div>
    @endif

    <form wire:submit="save" class="bg-white rounded-xl shadow-sm border border-slate-200 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-1 h-full bg-brand-blue"></div>

        <div class="p-6 sm:p-8 border-b border-slate-100">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest border-b border-slate-100 pb-2 mb-6">Service Details</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                <div class="col-span-full">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Service Name</label>
                    <input type="text" wire:model="service_name" class="block w-full border border-slate-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-brand-blue focus:border-brand-blue text-sm" placeholder="e.g. Engine Oil, Front Tires, Brake Pads">
                    @error('service_name') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-full">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Interval Tracking Type</label>
                    <div class="relative">
                        <select wire:model.live="reminder_type" class="block w-full border border-slate-300 rounded-md shadow-sm py-2 pl-3 pr-10 focus:outline-none focus:ring-brand-blue focus:border-brand-blue text-sm appearance-none bg-white">
                            <option value="time">Time Interval (Date based)</option>
                            <option value="mileage">Distance Interval (Mileage based)</option>
                            <option value="both">Both (Whichever comes first)</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                    @error('reminder_type') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="p-6 sm:p-8 border-b border-slate-100 bg-slate-50/50">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest border-b border-slate-200 pb-2 mb-6">Thresholds</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                @if(in_array($reminder_type, ['time', 'both']))
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Next Due Date</label>
                        <input type="date" wire:model="next_due_date" class="block w-full border border-slate-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-brand-blue focus:border-brand-blue text-sm">
                        @error('next_due_date') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Repeat Interval (Days)</label>
                        <input type="number" wire:model="trigger_days" class="block w-full border border-slate-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-brand-blue focus:border-brand-blue text-sm" placeholder="e.g. 180 for 6 months">
                    </div>
                @endif

                @if(in_array($reminder_type, ['mileage', 'both']))
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Next Due Mileage</label>
                        <div class="relative">
                            <input type="number" wire:model="next_due_mileage" class="block w-full border border-slate-300 rounded-md shadow-sm py-2 pl-12 pr-3 focus:outline-none focus:ring-brand-blue focus:border-brand-blue text-sm">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 text-xs font-semibold">KM</div>
                        </div>
                        @error('next_due_mileage') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Repeat Interval (KM)</label>
                        <input type="number" wire:model="trigger_mileage" class="block w-full border border-slate-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-brand-blue focus:border-brand-blue text-sm" placeholder="e.g. 10000 for oil change">
                    </div>
                @endif
            </div>
        </div>

        <div class="p-6 sm:p-8 border-b border-slate-100">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest border-b border-slate-100 pb-2 mb-6">Status</h3>

            <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <label for="status_toggle" class="font-bold text-slate-800 text-sm cursor-pointer">Active Reminder</label>
                        <p class="text-xs text-slate-500 mt-0.5" id="status-description">Turn off to temporarily disable notifications for this reminder.</p>
                    </div>
                    <button type="button" wire:click="$toggle('is_active')" class="ml-4 flex-shrink-0 group relative rounded-full inline-flex items-center justify-center h-5 w-10 cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-blue" role="switch" aria-checked="{{ $is_active ? 'true' : 'false' }}" aria-labelledby="status_toggle" aria-describedby="status-description">
                        <span aria-hidden="true" class="pointer-events-none absolute w-full h-full rounded-md bg-white"></span>
                        <span aria-hidden="true" class="{{ $is_active ? 'bg-brand-blue' : 'bg-slate-200' }} pointer-events-none absolute h-4 w-9 mx-auto rounded-full transition-colors ease-in-out duration-200"></span>
                        <span aria-hidden="true" class="{{ $is_active ? 'translate-x-5' : 'translate-x-0' }} pointer-events-none absolute left-0 inline-block h-5 w-5 border border-slate-200 rounded-full bg-white shadow transform ring-0 transition-transform ease-in-out duration-200"></span>
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-3 rounded-b-xl">
            <a href="{{ route('reminders.index', $vehicle) }}" class="px-4 py-2 bg-white border border-slate-300 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition" wire:navigate>Cancel</a>
            <button type="submit" class="inline-flex justify-center px-6 py-2 border border-transparent text-sm font-bold rounded-lg text-white bg-brand-orange hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-orange shadow-md shadow-brand-orange/20 transition-all transform hover:-translate-y-0.5" wire:loading.attr="disabled">
                <span wire:loading.remove>{{ $reminder ? 'Save Changes' : 'Create Reminder' }}</span>
                <span wire:loading class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Saving...
                </span>
            </button>
        </div>
    </form>
</div>
