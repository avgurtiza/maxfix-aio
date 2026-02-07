<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">My Vehicles</h1>
        <a 
            href="{{ route('vehicles.create') }}" 
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
            wire:navigate
        >
            + Add Vehicle
        </a>
    </div>

    @if(session('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    @if($vehicles->isEmpty())
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            <h3 class="mt-2 text-lg font-medium text-gray-900">No vehicles yet</h3>
            <p class="mt-1 text-gray-500">Get started by adding your first vehicle.</p>
            <a href="{{ route('vehicles.create') }}" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded-lg">
                Add Your First Vehicle
            </a>
        </div>
    @else
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($vehicles as $vehicle)
                <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $vehicle->display_name }}
                                </h3>
                                @if($vehicle->current_plate)
                                    <p class="text-sm text-gray-500">{{ $vehicle->current_plate }}</p>
                                @endif
                            </div>
                            @if($vehicle->activeReminders->where('isDue')->count() > 0)
                                <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">
                                    Maintenance Due
                                </span>
                            @endif
                        </div>

                        <div class="mt-4 space-y-2 text-sm text-gray-600">
                            <p>Mileage: {{ number_format($vehicle->current_mileage) }} km</p>
                            <p>Service Records: {{ $vehicle->service_records_count }}</p>
                        </div>

                        <div class="mt-4 flex space-x-2">
                            <a 
                                href="{{ route('services.history', $vehicle) }}" 
                                class="flex-1 text-center bg-green-100 text-green-700 px-3 py-2 rounded hover:bg-green-200"
                                wire:navigate
                            >
                                Services
                            </a>
                            <a 
                                href="{{ route('vehicles.edit', $vehicle) }}" 
                                class="flex-1 text-center bg-blue-100 text-blue-700 px-3 py-2 rounded hover:bg-blue-200"
                                wire:navigate
                            >
                                Edit
                            </a>
                            <button 
                                wire:click="delete({{ $vehicle->id }})"
                                wire:confirm="Are you sure you want to delete this vehicle?"
                                class="px-3 py-2 text-red-600 hover:bg-red-100 rounded"
                            >
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
