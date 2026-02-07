<div>
    <div class="p-6">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $shop->name }}</h2>
                <p class="text-gray-600 mt-1">{{ $shop->city }}</p>
            </div>
            <div class="flex items-center space-x-2">
                @if($shop->is_verified)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Verified
                    </span>
                @endif
                <button 
                    wire:click="toggleFavorite"
                    class="p-2 rounded-full transition-colors {{ $isFavorited ? 'text-red-600 bg-red-100 hover:bg-red-200' : 'text-gray-400 hover:text-red-600 hover:bg-red-50' }}"
                    title="{{ $isFavorited ? 'Remove from favorites' : 'Add to favorites' }}"
                >
                    <svg class="w-6 h-6" fill="{{ $isFavorited ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </button>
            </div>
        </div>

        <div class="mt-6 space-y-4">
            <div>
                <h3 class="text-sm font-medium text-gray-900">Address</h3>
                <p class="mt-1 text-gray-600">{{ $shop->address }}</p>
                @if($shop->postal_code)
                    <p class="text-gray-600">{{ $shop->city }}, {{ $shop->postal_code }}</p>
                @else
                    <p class="text-gray-600">{{ $shop->city }}</p>
                @endif
            </div>

            @if($shop->services_offered)
                <div>
                    <h3 class="text-sm font-medium text-gray-900">Services Offered</h3>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @foreach($shop->services_offered as $service)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ ucwords(str_replace('_', ' ', $service)) }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($shop->operating_hours)
                <div>
                    <h3 class="text-sm font-medium text-gray-900">Operating Hours</h3>
                    <p class="mt-1 text-gray-600 whitespace-pre-line">{{ $shop->operating_hours }}</p>
                </div>
            @endif

            <div class="border-t border-gray-200 pt-4">
                <button 
                    wire:click="toggleContactInfo"
                    class="flex items-center text-blue-600 hover:text-blue-800 font-medium"
                >
                    <span>{{ $showContactInfo ? 'Hide Contact Info' : 'Show Contact Info' }}</span>
                    <svg class="w-5 h-5 ml-1 transform transition-transform {{ $showContactInfo ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                @if($showContactInfo)
                    <div class="mt-4 space-y-3" wire:transition>
                        @if($shop->phone)
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <a href="tel:{{ $shop->phone }}" class="text-gray-700 hover:text-blue-600">{{ $shop->phone }}</a>
                            </div>
                        @endif

                        @if($shop->email)
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <a href="mailto:{{ $shop->email }}" class="text-gray-700 hover:text-blue-600">{{ $shop->email }}</a>
                            </div>
                        @endif

                        @if($shop->website)
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                </svg>
                                <a href="{{ $shop->website }}" target="_blank" rel="noopener noreferrer" class="text-gray-700 hover:text-blue-600">{{ $shop->website }}</a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <div class="border-t border-gray-200 pt-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Service Records</span>
                    <span class="text-lg font-semibold text-gray-900">{{ $shop->service_records_count ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
