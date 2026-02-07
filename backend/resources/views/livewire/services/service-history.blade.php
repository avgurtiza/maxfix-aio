<div>
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
