<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">
            {{ $vehicle ? 'Reminders for ' . $vehicle->display_name : 'Maintenance Reminders' }}
        </h1>
        @if($vehicle)
            <a 
                href="{{ route('reminders.create', $vehicle) }}" 
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
                wire:navigate
            >
                + Add Reminder
            </a>
        @endif
    </div>

    @if(session('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <div class="mb-4">
        <select wire:model.live="filter" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <option value="all">All Reminders</option>
            <option value="active">Active Only</option>
            <option value="due">Due Now</option>
        </select>
    </div>

    @php
        $allReminders = $vehicles->flatMap(fn($v) => $v->reminders);
    @endphp

    @if($allReminders->isEmpty())
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="mt-2 text-lg font-medium text-gray-900">No reminders</h3>
            <p class="mt-1 text-gray-500">
                @if($vehicle)
                    Create your first maintenance reminder for this vehicle.
                @else
                    You don't have any maintenance reminders yet.
                @endif
            </p>
        </div>
    @else
        <div class="space-y-6">
            @foreach($vehicles as $v)
                @if($v->reminders->isNotEmpty())
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">{{ $v->display_name }}</h2>
                        </div>
                        <div class="divide-y divide-gray-200">
                            @foreach($v->reminders as $reminder)
                                <div class="p-4 hover:bg-gray-50">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <h3 class="font-medium text-gray-900">{{ $reminder->service_name }}</h3>
                                                @if($reminder->isDue())
                                                    <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">Due</span>
                                                @elseif($reminder->is_active)
                                                    <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Active</span>
                                                @else
                                                    <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded-full">Inactive</span>
                                                @endif
                                            </div>
                                            <div class="mt-1 text-sm text-gray-500 space-y-1">
                                                @if($reminder->next_due_date)
                                                    <p>Due date: {{ $reminder->next_due_date->format('M d, Y') }}</p>
                                                @endif
                                                @if($reminder->next_due_mileage)
                                                    <p>Due mileage: {{ number_format($reminder->next_due_mileage) }} km</p>
                                                @endif
                                                <p>Type: {{ ucfirst($reminder->reminder_type) }}</p>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            @if($reminder->is_active)
                                                <button 
                                                    wire:click="complete({{ $reminder->id }})"
                                                    class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700"
                                                >
                                                    Complete
                                                </button>
                                            @endif
                                            <a 
                                                href="{{ route('reminders.edit', ['vehicle' => $v, 'reminder' => $reminder]) }}" 
                                                class="bg-blue-100 text-blue-700 px-3 py-1 rounded text-sm hover:bg-blue-200"
                                                wire:navigate
                                            >
                                                Edit
                                            </a>
                                            <button 
                                                wire:click="delete({{ $reminder->id }})"
                                                wire:confirm="Are you sure you want to delete this reminder?"
                                                class="text-red-600 hover:bg-red-100 px-3 py-1 rounded text-sm"
                                            >
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
</div>
