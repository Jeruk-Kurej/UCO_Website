<x-app-layout>
    <div class="max-w-4xl mx-auto">
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
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Product Categories</p>
                    <p class="mt-1">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            {{ $businessType->productCategories->count() }} categories
                        </span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Businesses Using This Type --}}
        @if($businessType->businesses->count() > 0)
            <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Businesses ({{ $businessType->businesses->count() }})</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($businessType->businesses as $business)
                        <a href="{{ route('businesses.show', $business) }}" 
                           class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition duration-150">
                            <h3 class="font-semibold text-gray-900 mb-1">{{ $business->name }}</h3>
                            <p class="text-xs text-gray-600 mb-2">
                                <i class="bi bi-person me-1"></i>
                                {{ $business->user->name }}
                            </p>
                            <p class="text-xs text-gray-500">
                                <i class="bi bi-box-seam me-1"></i>
                                {{ $business->products->count() }} products
                            </p>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Product Categories --}}
        @if($businessType->productCategories->count() > 0)
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Product Categories ({{ $businessType->productCategories->count() }})</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                    @foreach($businessType->productCategories as $category)
                        <div class="border border-gray-200 rounded-lg p-3 text-center">
                            <div class="w-10 h-10 mx-auto mb-2 rounded-full bg-gradient-to-br from-orange-400 to-yellow-400 flex items-center justify-center text-white">
                                <i class="bi bi-tag"></i>
                            </div>
                            <p class="text-sm font-medium text-gray-900">{{ $category->name }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-app-layout>