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
                <div>
                    <label class="block text-sm font-medium text-gray-700">Cost ($)</label>
                    <input 
                        type="number" 
                        wire:model="cost" 
                        step="0.01"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Service cost"
                    >
                    @error('cost') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Service Type *</label>
                <select 
                    wire:model="service_type" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                >
                    <option value="">Select service type</option>
                    @foreach($serviceTypes as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
                @error('service_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Service Shop</label>
                <select 
                    wire:model="shop_id" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                >
                    <option value="">Select shop (optional)</option>
                    @foreach($shops as $shop)
                        <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                    @endforeach
                </select>
                @error('shop_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea 
                    wire:model="description" 
                    rows="4" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Describe the work performed..."
                ></textarea>
                @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Receipt</label>
                
                @if($service && $service->hasReceipt())
                    <div class="mt-2 mb-3 p-3 bg-gray-50 rounded-md">
                        <div class="flex items-center justify-between">
                            <a 
                                href="{{ Storage::disk('public')->url($service->receipt_path) }}" 
                                target="_blank"
                                class="text-blue-600 hover:text-blue-800 text-sm flex items-center"
                            >
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                </svg>
                                View Current Receipt
                            </a>
                            <label class="flex items-center text-sm text-red-600 cursor-pointer">
                                <input type="checkbox" wire:model="deleteExistingReceipt" class="mr-2 rounded border-gray-300 text-red-600 focus:ring-red-500">
                                Delete receipt
                            </label>
                        </div>
                    </div>
                @endif

                <input 
                    type="file" 
                    wire:model="receipt" 
                    accept=".pdf,.jpg,.jpeg,.png"
                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                >
                <p class="mt-1 text-sm text-gray-500">Accepted: PDF, JPG, PNG (max 10MB)</p>
                @error('receipt') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                @if($receipt)
                    <div class="mt-2 text-sm text-green-600">
                        File selected: {{ $receipt->getClientOriginalName() }}
                    </div>
                @endif
            </div>

            <div class="flex justify-end space-x-4 pt-4">
                <a 
                    href="{{ route('services.history', $vehicle) }}" 
                    class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300"
                    wire:navigate
                >
                    Cancel
                </a>
                <button 
                    type="submit" 
                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove>{{ $service ? 'Update Record' : 'Create Record' }}</span>
                    <span wire:loading>Saving...</span>
                </button>
            </div>
        </form>
    </div>
</div>
