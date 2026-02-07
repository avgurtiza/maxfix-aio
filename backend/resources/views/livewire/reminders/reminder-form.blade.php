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
                            placeholder="e.g., 150000"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                        @error('next_due_mileage') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Repeat Every (km)</label>
                        <input 
                            type="number" 
                            wire:model="trigger_mileage" 
                            placeholder="e.g., 10000"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                        @error('trigger_mileage') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700">Notification Methods</label>
                <div class="mt-2 space-y-2">
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="notification_methods" value="email" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Email</span>
                    </label>
                </div>
                @error('notification_methods') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="flex items-center">
                    <input type="checkbox" wire:model="is_active" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <span class="ml-2 text-sm font-medium text-gray-700">Active</span>
                </label>
            </div>

            <div class="flex justify-end space-x-4 pt-4">
                <a href="{{ $vehicle ? route('vehicles.show', $vehicle) : route('reminders.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300" wire:navigate>Cancel</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700" wire:loading.attr="disabled">
                    <span wire:loading.remove>{{ $reminder ? 'Update Reminder' : 'Create Reminder' }}</span>
                    <span wire:loading>Saving...</span>
                </button>
            </div>
        </form>
    </div>
</div>
