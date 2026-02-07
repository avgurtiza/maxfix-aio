<div class="relative">
    <button wire:click="toggleDropdown" class="relative p-2 text-gray-600 hover:text-blue-600 focus:outline-none">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        @if($unreadCount > 0)
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-red-600 rounded-full">
                {{ $unreadCount }}
            </span>
        @endif
    </button>

    @if($showDropdown)
        <div class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50" x-data="{ open: true }" @click.away="$wire.set('showDropdown', false)">
            <div class="p-4 border-b border-gray-200">
                <h3 class="text-sm font-semibold text-gray-900">Maintenance Reminders</h3>
            </div>

            <div class="max-h-96 overflow-y-auto">
                @if(empty($notifications))
                    <div class="p-8 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="mt-2 text-sm">No reminders set up</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-100">
                        @foreach($notifications as $notification)
                            <div class="p-4 hover:bg-gray-50 cursor-pointer">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        @if($notification['is_due'])
                                            <span class="inline-flex items-center justify-center h-2 w-2 rounded-full bg-red-500"></span>
                                        @else
                                            <span class="inline-flex items-center justify-center h-2 w-2 rounded-full bg-green-500"></span>
                                        @endif
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $notification['service_name'] }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ $notification['vehicle_name'] }}
                                        </p>
                                        <div class="mt-1 text-xs text-gray-400">
                                            @if($notification['due_date'])
                                                Due: {{ $notification['due_date'] }}
                                            @endif
                                            @if($notification['due_mileage'])
                                                @if($notification['due_date']) · @endif
                                                At {{ number_format($notification['due_mileage']) }} km
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="p-3 border-t border-gray-200 bg-gray-50">
                <a href="{{ route('vehicles.index') }}" class="block text-sm text-center text-blue-600 hover:text-blue-700" wire:navigate>
                    View All Vehicles
                </a>
            </div>
        </div>
    @endif
</div>
