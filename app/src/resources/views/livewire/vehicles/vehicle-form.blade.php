<div>
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('vehicles.index') }}" class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-slate-500 hover:text-slate-900 border border-slate-200 shadow-sm transition-all" wire:navigate>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
                    {{ $vehicle ? 'Edit Vehicle' : 'Add New Vehicle' }}
                </h1>
                <p class="text-sm text-slate-500 mt-1">{{ $vehicle ? 'Update vehicle information in your fleet' : 'Register a new vehicle to your fleet' }}</p>
            </div>
        </div>

        @if(session('message'))
            <div class="bg-brand-success/10 border-l-4 border-l-brand-success text-green-800 px-6 py-4 rounded-lg flex items-center shadow-sm">
                <svg class="w-6 h-6 mr-3 text-brand-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('message') }}
            </div>
        @endif

        @if($showVinDecoder)
            <div class="bg-white rounded-xl p-6 border border-brand-blue/30 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <span class="text-brand-blue flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                            VIN Decoder
                        </span>
                        <span class="text-xs bg-brand-blue/10 text-brand-blue font-semibold px-2 py-0.5 rounded-full uppercase tracking-wider">Auto-fill</span>
                    </h2>
                    <button wire:click="$set('showVinDecoder', false)" class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition">Skip Manual Entry</button>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input 
                            type="text" 
                            wire:model="vin"
                            placeholder="Enter 17-character VIN"
                            class="block w-full pl-10 pr-3 py-2 border border-slate-300 rounded-lg focus:ring-brand-blue focus:border-brand-blue text-sm uppercase font-mono tracking-widest placeholder-slate-400"
                            maxlength="17"
                        >
                    </div>
                    <button 
                        wire:click="decodeVin"
                        wire:loading.attr="disabled"
                        class="bg-slate-800 text-white font-bold px-6 py-2 rounded-lg shadow-sm hover:bg-slate-900 disabled:opacity-50 transition w-full sm:w-auto"
                    >
                        <span wire:loading.remove>Decode VIN</span>
                        <span wire:loading>Scanning...</span>
                    </button>
                </div>
                @error('vin') <span class="text-red-500 text-xs mt-2 block font-medium">{{ $message }}</span> @enderror
            </div>
        @endif

        <form wire:submit="save" class="bg-white rounded-xl shadow-sm border border-slate-200 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-1 h-full bg-brand-orange"></div>
            
            <div class="p-6 sm:p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5 mb-8">
                    <!-- Vehicle Identity -->
                    <div class="col-span-full">
                        <h3 class="text-sm font-bold text-slate-500 uppercase tracking-widest border-b border-slate-100 pb-2 mb-2">Vehicle Identity</h3>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Manufacturer</label>
                        <input type="text" wire:model="make" class="block w-full border border-slate-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-brand-blue focus:border-brand-blue text-sm" placeholder="e.g. Toyota">
                        @error('make') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Model Name</label>
                        <input type="text" wire:model="model" class="block w-full border border-slate-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-brand-blue focus:border-brand-blue text-sm" placeholder="e.g. GR86">
                        @error('model') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Model Year</label>
                        <input type="number" wire:model="year" class="block w-full border border-slate-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-brand-blue focus:border-brand-blue text-sm" placeholder="YYYY">
                        @error('year') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">License Plate</label>
                        <input type="text" wire:model="current_plate" class="block w-full border border-slate-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-brand-blue focus:border-brand-blue text-sm font-mono uppercase" placeholder="ABC-123">
                        @error('current_plate') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5 mb-8">
                    <!-- Specs -->
                    <div class="col-span-full">
                        <h3 class="text-sm font-bold text-slate-500 uppercase tracking-widest border-b border-slate-100 pb-2 mb-2">Specifications</h3>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Current Odometer (km)</label>
                        <input type="number" wire:model="current_mileage" class="block w-full border border-slate-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-brand-blue focus:border-brand-blue text-sm" placeholder="0">
                        @error('current_mileage') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Body Color</label>
                        <input type="text" wire:model="color" class="block w-full border border-slate-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-brand-blue focus:border-brand-blue text-sm" placeholder="e.g. Silver">
                        @error('color') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-span-full">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Powertrain Type</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @foreach(['gasoline' => 'Gasoline', 'diesel' => 'Diesel', 'electric' => 'Electric', 'hybrid' => 'Hybrid'] as $value => $label)
                                <label class="cursor-pointer relative">
                                    <input type="radio" wire:model="fuel_type" value="{{ $value }}" class="peer sr-only">
                                    <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-center text-sm font-semibold text-slate-600 transition-all peer-checked:border-brand-blue peer-checked:text-brand-blue peer-checked:bg-brand-blue/5 hover:bg-slate-100 peer-focus-visible:ring-2 peer-focus-visible:ring-brand-blue peer-focus-visible:ring-offset-2">
                                        {{ $label }}
                                    </div>
                                    <div class="absolute top-2 right-2 text-brand-blue opacity-0 peer-checked:opacity-100 transition-opacity">
                                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('fuel_type') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Additional Notes</label>
                    <textarea wire:model="notes" rows="3" class="block w-full border border-slate-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-brand-blue focus:border-brand-blue text-sm resize-none" placeholder="Any specific details to remember..."></textarea>
                    @error('notes') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                @error('limit')
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-6 shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium">{{ $message }}</p>
                            </div>
                        </div>
                    </div>
                @enderror
            </div>

            <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-3 rounded-b-xl">
                <a href="{{ route('vehicles.index') }}" class="px-4 py-2 bg-white border border-slate-300 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition" wire:navigate>Cancel</a>
                <button type="submit" class="inline-flex justify-center px-6 py-2 border border-transparent text-sm font-bold rounded-lg text-white bg-brand-orange hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-orange shadow-md shadow-brand-orange/20 transition-all transform hover:-translate-y-0.5" wire:loading.attr="disabled">
                    <span wire:loading.remove>{{ $vehicle ? 'Save Changes' : 'Add to Garage' }}</span>
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
</div>
