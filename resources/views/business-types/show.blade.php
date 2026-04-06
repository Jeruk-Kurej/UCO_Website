<x-app-layout>
    <div class="w-full max-w-[1600px] 2xl:max-w-[1720px] mx-auto">
        {{-- Page Header --}}
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('business-types.index') }}" 
                   class="group inline-flex items-center gap-2.5 px-4 py-2.5 bg-white hover:bg-gray-900 border border-gray-200 hover:border-gray-900 text-gray-700 hover:text-white rounded-xl font-medium text-sm shadow-sm hover:shadow-md transition-all duration-200">
                    <i class="bi bi-arrow-left text-base group-hover:-translate-x-0.5 transition-transform duration-200"></i>
                    <span>Back</span>
                </a>
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $businessType->name }}</h1>
                    <p class="text-sm text-gray-600">Business Type Details</p>
                </div>
            </div>

            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('business-types.edit', $businessType) }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white rounded-lg font-semibold text-sm shadow-sm transition duration-150">
                        <i class="bi bi-pencil me-2"></i>
                        Edit Business Type
                    </a>
                @endif
            @endauth
        </div>

        {{-- Business Type Information --}}
        <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Information</h2>
            <div class="space-y-4">
                <div>
                    <p class="text-sm font-medium text-gray-500">Type Name</p>
                    <p class="mt-1 text-sm text-gray-900">{{ $businessType->name }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Description</p>
                    <p class="mt-1 text-sm text-gray-900">{{ $businessType->description ?? 'No description provided' }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Businesses</p>
                    <p class="mt-1">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $businessType->businesses->count() }} businesses
                        </span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Businesses Using This Type --}}
        @if($businessType->businesses->count() > 0)
            <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Businesses ({{ $businessType->businesses->count() }})</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($businessType->businesses as $business)
                        @php $delay = ($loop->index % 12) * 50; @endphp
                        <div class="bg-white border rounded-xl overflow-hidden hover:-translate-y-1 hover:border-uco-orange-300 hover:shadow-xl transition-all duration-300 relative group reveal-on-scroll"
                             style="transition-delay: {{ $delay }}ms;">
                            <a href="{{ route('businesses.show', $business) }}" class="block p-5 h-full">
                                <div class="flex items-start gap-4">
                                    @php
                                        $logo = $business->logo_url ?? null;
                                        $logoUrl = $logo ? storage_image_url($logo, 'logo_thumb') : null;
                                    @endphp
                                    @if ($logoUrl)
                                        <img src="{{ $logoUrl }}" alt="{{ $business->name }}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl object-cover shadow-sm ring-1 ring-gray-900/5 mt-1">
                                    @else
                                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl flex items-center justify-center text-gray-400 shadow-sm ring-1 ring-gray-900/5 mt-1">
                                            <i class="bi bi-building text-2xl sm:text-3xl"></i>
                                        </div>
                                    @endif

                                    <div class="flex-1 min-w-0 pr-2">
                                        <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                                            <h3 class="font-bold text-gray-900 text-lg truncate group-hover:text-soft-gray-900 transition-colors">{{ $business->name }}</h3>
                                        </div>
                                        
                                        <div class="min-h-[2.5rem] mb-3">
                                            <p class="text-sm text-gray-600 line-clamp-2 leading-relaxed">{{ $business->description ?: 'No description provided' }}</p>
                                        </div>
                                        
                                        <div class="flex flex-wrap items-center gap-4 text-xs text-gray-500 font-medium mt-auto">
                                            @if($business->user)
                                                <div class="flex items-center gap-1.5 text-gray-600 bg-gray-50 px-2 py-1 rounded-lg">
                                                    <i class="bi bi-person-fill text-gray-400"></i>
                                                    <span class="truncate max-w-[120px]">{{ $business->user->name }}</span>
                                                </div>
                                            @endif
                                            <div class="flex items-center gap-1.5 text-gray-500">
                                                <i class="bi bi-box-seam text-gray-400"></i>
                                                <span>{{ $business->products->count() }} products</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-app-layout>