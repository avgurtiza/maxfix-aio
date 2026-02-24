<div class="max-w-3xl mx-auto py-8">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('services.history', $vehicle) }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-slate-200 shadow-sm text-slate-500 hover:text-slate-900 hover:bg-slate-50 transition-colors" wire:navigate>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
                {{ $service ? 'Edit Service Record' : 'New Service Record' }}
            </h1>
            <p class="text-sm text-slate-500 font-medium mt-1">
                {{ $vehicle->year }} {{ $vehicle->make }} {{ $vehicle->model }}
            </p>
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
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Service Type</label>
                    <div class="relative">
                        <select wire:model="service_type" class="block w-full border border-slate-300 rounded-md shadow-sm py-2 pl-3 pr-10 focus:outline-none focus:ring-brand-blue focus:border-brand-blue text-sm appearance-none bg-white">
                            <option value="">Select Operation...</option>
                            @foreach(['oil_change' => 'Oil Change', 'tire_rotation' => 'Tire Rotation', 'brake_service' => 'Brake Service', 'transmission' => 'Transmission', 'engine' => 'Engine', 'electrical' => 'Electrical', 'air_conditioning' => 'Air Conditioning', 'suspension' => 'Suspension', 'inspection' => 'Inspection', 'other' => 'Other'] as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                    @error('service_type') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Date Performed</label>
                    <input type="date" wire:model="service_date" class="block w-full border border-slate-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-brand-blue focus:border-brand-blue text-sm">
                    @error('service_date') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-full">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Summary</label>
                    <input type="text" wire:model="description" class="block w-full border border-slate-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-brand-blue focus:border-brand-blue text-sm" placeholder="Brief summary of work performed...">
                    @error('description') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="p-6 sm:p-8 border-b border-slate-100 bg-slate-50/50">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest border-b border-slate-200 pb-2 mb-6">Metrics</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Mileage at Service</label>
                    <div class="relative">
                        <input type="number" wire:model="mileage" class="block w-full border border-slate-300 rounded-md shadow-sm py-2 pl-12 pr-3 focus:outline-none focus:ring-brand-blue focus:border-brand-blue text-sm">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 text-xs font-semibold">KM</div>
                    </div>
                    @error('mileage') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Total Cost</label>
                    <div class="relative">
                        <input type="number" wire:model="cost" step="0.01" class="block w-full border border-slate-300 rounded-md shadow-sm py-2 pl-8 pr-3 focus:outline-none focus:ring-brand-blue focus:border-brand-blue text-sm">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 font-medium">$</div>
                    </div>
                    @error('cost') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="p-6 sm:p-8 border-b border-slate-100">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest border-b border-slate-100 pb-2 mb-6">Mechanic & Notes</h3>

            <div class="grid grid-cols-1 gap-y-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Shop / Mechanic Name</label>
                    <div class="relative">
                        <select wire:model="shop_id" class="block w-full border border-slate-300 rounded-md shadow-sm py-2 pl-3 pr-10 focus:outline-none focus:ring-brand-blue focus:border-brand-blue text-sm appearance-none bg-white">
                            <option value="">Private Garage / Unlisted Shop</option>
                            @foreach($shops as $shop)
                                <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                    @error('shop_id') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Technical Notes</label>
                    <textarea wire:model="description" rows="3" class="block w-full border border-slate-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-brand-blue focus:border-brand-blue text-sm resize-y" placeholder="Additional details, parts used..."></textarea>
                </div>

                <div class="bg-blue-50/50 p-4 rounded-lg border border-blue-100 mt-2">
                    <div class="flex items-start gap-3">
                        <div class="flex items-center h-5 mt-0.5">
                            <input id="set_reminder" wire:model.live="set_next_service_reminder" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-brand-blue focus:ring-brand-blue cursor-pointer">
                        </div>
                        <div class="flex-1 text-sm">
                            <label for="set_reminder" class="font-bold text-slate-800 cursor-pointer">Schedule Next Service Reminder</label>
                            <p class="text-slate-500 mt-0.5">Automatically create a reminder for the next interval</p>
                        </div>
                    </div>
                    
                    @if($set_next_service_reminder)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4 pt-4 border-t border-blue-100/60">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Target Date</label>
                                <input type="date" wire:model="next_service_date" class="block w-full border border-slate-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-brand-blue focus:border-brand-blue text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Target Mileage (km)</label>
                                <input type="number" wire:model="next_service_mileage" class="block w-full border border-slate-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-brand-blue focus:border-brand-blue text-sm" placeholder="e.g. 50000">
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-3 rounded-b-xl">
            <a href="{{ route('services.history', $vehicle) }}" class="px-4 py-2 bg-white border border-slate-300 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition" wire:navigate>Cancel</a>
            <button type="submit" class="inline-flex justify-center px-6 py-2 border border-transparent text-sm font-bold rounded-lg text-white bg-brand-orange hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-orange shadow-md shadow-brand-orange/20 transition-all transform hover:-translate-y-0.5" wire:loading.attr="disabled">
                <span wire:loading.remove>{{ $service ? 'Save Record' : 'Add Record' }}</span>
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
