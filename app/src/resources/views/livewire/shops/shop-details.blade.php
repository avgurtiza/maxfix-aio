@if($shop)
    <div
        class="relative w-full"
        x-data="{ show: true }"
        x-show="show"
    >
        <div class="p-6 sm:p-8">
            <!-- Header -->
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-3">
                        {{ $shop->name }}
                        @if($shop->is_verified)
                            <span title="Verified Shop" class="text-brand-blue flex items-center">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            </span>
                        @endif
                    </h2>
                    <p class="text-sm text-slate-500 mt-1 flex items-start gap-1.5">
                        <svg class="w-4 h-4 text-slate-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $shop->address }}
                    </p>
                </div>
            </div>

            <!-- Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50 p-5 rounded-xl border border-slate-100">
                <div class="space-y-4">
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest border-b border-slate-200 pb-2">Contact Info</h3>
                    
                    @if($shop->phone)
                        <div class="flex items-center text-sm">
                            <svg class="w-5 h-5 text-slate-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <a href="tel:{{ $shop->phone }}" class="text-slate-700 hover:text-brand-blue font-medium">{{ $shop->phone }}</a>
                        </div>
                    @endif

                    @if($shop->email)
                        <div class="flex items-center text-sm">
                            <svg class="w-5 h-5 text-slate-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <a href="mailto:{{ $shop->email }}" class="text-slate-700 hover:text-brand-blue font-medium">{{ $shop->email }}</a>
                        </div>
                    @endif

                    @if($shop->website)
                        <div class="flex items-center text-sm">
                            <svg class="w-5 h-5 text-slate-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                            <a href="{{ $shop->website }}" target="_blank" rel="noopener noreferrer" class="text-slate-700 hover:text-brand-blue font-medium">{{ $shop->website }}</a>
                        </div>
                    @endif
                </div>
                
                <div class="space-y-4">
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest border-b border-slate-200 pb-2">Overview</h3>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-600">Total Service Records</span>
                        <span class="text-lg font-bold text-slate-900 bg-white border border-slate-200 px-3 py-1 rounded-lg shadow-sm">{{ $shop->service_records_count ?? 0 }}</span>
                    </div>
                </div>
            </div>
            
            @if(isset($shop->description) && $shop->description)
                <div class="mt-6">
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">About this Shop</h3>
                    <p class="text-sm text-slate-600 leading-relaxed bg-white border border-slate-200 rounded-lg p-4">{{ $shop->description }}</p>
                </div>
            @endif
        </div>
    </div>
@endif
