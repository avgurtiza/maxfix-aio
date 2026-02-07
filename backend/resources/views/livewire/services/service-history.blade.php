<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Service History</h1>
            <p class="text-gray-600">{{ $vehicle->display_name }}</p>
        </div>
        <a 
            href="{{ route('services.create', $vehicle) }}" 
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
            wire:navigate
        >
            + Add Service Record
        </a>
    </div>

    @if(session('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    @if($servicesByYear->isEmpty())
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <h3 class="mt-2 text-lg font-medium text-gray-900">No service records yet</h3>
            <p class="mt-1 text-gray-500">Start tracking your vehicle's maintenance history.</p>
            <a href="{{ route('services.create', $vehicle) }}" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded-lg" wire:navigate>
                Add First Service Record
            </a>
        </div>
    @else
        <div class="space-y-8">
            @foreach($servicesByYear as $year => $records)
                <div class="bg-white rounded-lg shadow">
                    <div class="bg-gray-50 px-6 py-3 border-b rounded-t-lg">
                        <h2 class="text-lg font-semibold text-gray-900">{{ $year }}</h2>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @foreach($records as $record)
                            <div class="p-6 hover:bg-gray-50 transition-colors">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $serviceTypes[$record->service_type] ?? $record->service_type }}
                                            </span>
                                            <span class="text-sm text-gray-500">
                                                {{ $record->service_date->format('M j, Y') }}
                                            </span>
                                        </div>
                                        
                                        @if($record->shop)
                                            <p class="text-sm text-gray-600 mb-1">
                                                <span class="font-medium">Shop:</span> {{ $record->shop->name }}
                                            </p>
                                        @endif
                                        
                                        @if($record->mileage)
                                            <p class="text-sm text-gray-600 mb-1">
                                                <span class="font-medium">Mileage:</span> {{ number_format($record->mileage) }} km
                                            </p>
                                        @endif
                                        
                                        @if($record->cost)
                                            <p class="text-sm text-gray-600 mb-1">
                                                <span class="font-medium">Cost:</span> ${{ number_format($record->cost, 2) }}
                                            </p>
                                        @endif
                                        
                                        @if($record->description)
                                            <p class="text-sm text-gray-600 mt-2">{{ $record->description }}</p>
                                        @endif
                                        
                                        @if($record->hasReceipt())
                                            <div class="mt-3">
                                                <a 
                                                    href="{{ Storage::disk('public')->url($record->receipt_path) }}" 
                                                    target="_blank"
                                                    class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800"
                                                >
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                                    </svg>
                                                    View Receipt
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="flex space-x-2 ml-4">
                                        <a 
                                            href="{{ route('services.edit', [$vehicle, $record]) }}" 
                                            class="text-blue-600 hover:bg-blue-100 px-3 py-2 rounded"
                                            wire:navigate
                                        >
                                            Edit
                                        </a>
                                        <button 
                                            wire:click="delete({{ $record->id }})"
                                            wire:confirm="Are you sure you want to delete this service record?"
                                            class="text-red-600 hover:bg-red-100 px-3 py-2 rounded"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-6">
            <a href="{{ route('vehicles.index') }}" class="text-blue-600 hover:underline" wire:navigate>
                ← Back to Vehicles
            </a>
        </div>
    @endif
</div>
