<x-app-layout>
    <div class="max-w-3xl mx-auto">
        {{-- Inline Back Button + Page Title --}}
        <div class="mb-6 flex items-center gap-3">
            <a href="{{ route('businesses.show', $business) }}" 
               class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-gray-800 transition duration-150">
                <i class="bi bi-arrow-left text-lg"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Business</h1>
                <p class="text-sm text-gray-600">{{ $business->name }}</p>
            </div>
        </div>

        <div class="bg-white shadow-sm sm:rounded-lg">
            <div class="p-6">
                <form method="POST" action="{{ route('businesses.update', $business) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Business Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Business Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name', $business->name) }}"
                               required
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Business Type --}}
                    <div>
                        <label for="business_type_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Business Type <span class="text-red-500">*</span>
                        </label>
                        <select name="business_type_id" 
                                id="business_type_id" 
                                required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm @error('business_type_id') border-red-500 @enderror">
                            <option value="">-- Select Business Type --</option>
                            @foreach($businessTypes as $type)
                                <option value="{{ $type->id }}" {{ old('business_type_id', $business->business_type_id) == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('business_type_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Business Mode --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Business Mode <span class="text-red-500">*</span>
                        </label>
                        
                        @php
                            $hasProducts = $business->products->count() > 0;
                            $hasServices = $business->services->count() > 0;
                            $canChangeMode = !$hasProducts && !$hasServices;
                        @endphp

                        @if(!$canChangeMode)
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-3">
                                <div class="flex gap-3">
                                    <i class="bi bi-exclamation-triangle text-yellow-600 text-xl flex-shrink-0"></i>
                                    <div class="text-sm text-yellow-800">
                                        <p class="font-semibold mb-1">Cannot Change Business Mode</p>
                                        <p class="text-xs">
                                            @if($hasProducts)
                                                This business has {{ $business->products->count() }} product(s). Delete all products first to change mode.
                                            @endif
                                            @if($hasServices)
                                                This business has {{ $business->services->count() }} service(s). Delete all services first to change mode.
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative flex cursor-pointer rounded-lg border border-gray-300 bg-white p-4 shadow-sm focus:outline-none hover:border-orange-500 transition duration-150 {{ !$canChangeMode ? 'opacity-50 cursor-not-allowed' : '' }}">
                                <input type="radio" 
                                       name="business_mode" 
                                       value="product" 
                                       {{ old('business_mode', $business->business_mode) === 'product' ? 'checked' : '' }}
                                       {{ !$canChangeMode ? 'disabled' : '' }}
                                       class="sr-only">
                                <span class="flex flex-1">
                                    <span class="flex flex-col">
                                        <span class="flex items-center gap-2 text-sm font-medium text-gray-900">
                                            <i class="bi bi-box-seam text-orange-600"></i>
                                            Product-Based
                                        </span>
                                        <span class="mt-1 flex items-center text-xs text-gray-500">
                                            Sell physical/digital products
                                        </span>
                                    </span>
                                </span>
                                <i class="bi bi-check-circle-fill text-orange-600 text-xl absolute top-3 right-3 opacity-0"></i>
                            </label>

                            <label class="relative flex cursor-pointer rounded-lg border border-gray-300 bg-white p-4 shadow-sm focus:outline-none hover:border-orange-500 transition duration-150 {{ !$canChangeMode ? 'opacity-50 cursor-not-allowed' : '' }}">
                                <input type="radio" 
                                       name="business_mode" 
                                       value="service" 
                                       {{ old('business_mode', $business->business_mode) === 'service' ? 'checked' : '' }}
                                       {{ !$canChangeMode ? 'disabled' : '' }}
                                       class="sr-only">
                                <span class="flex flex-1">
                                    <span class="flex flex-col">
                                        <span class="flex items-center gap-2 text-sm font-medium text-gray-900">
                                            <i class="bi bi-wrench text-blue-600"></i>
                                            Service-Based
                                        </span>
                                        <span class="mt-1 flex items-center text-xs text-gray-500">
                                            Offer services/expertise
                                        </span>
                                    </span>
                                </span>
                                <i class="bi bi-check-circle-fill text-blue-600 text-xl absolute top-3 right-3 opacity-0"></i>
                            </label>
                        </div>
                        @error('business_mode')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="5" 
                                  required
                                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm @error('description') border-red-500 @enderror">{{ old('description', $business->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Admin: Change Owner --}}
                    @if(isset($users) && auth()->user()->isAdmin())
                        <div>
                            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Business Owner (Admin Only)
                            </label>
                            <select name="user_id" 
                                    id="user_id"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id', $business->user_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }}) - {{ ucfirst($user->role) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    {{-- Submit Buttons --}}
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="{{ route('businesses.show', $business) }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-150">
                            <i class="bi bi-x-lg me-2"></i>
                            Cancel
                        </a>
                        <div class="flex items-center gap-3">
                            @if(auth()->user()->isAdmin() || auth()->id() === $business->user_id)
                                <button type="button" 
                                        onclick="if(confirm('Are you sure you want to delete this business? This action cannot be undone.')) document.getElementById('delete-form').submit();"
                                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-150">
                                    <i class="bi bi-trash me-2"></i>
                                    Delete
                                </button>
                            @endif

                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-orange-500 to-yellow-500 hover:from-orange-600 hover:to-yellow-600 text-white font-semibold rounded-md shadow-sm transition duration-150">
                                <i class="bi bi-check-lg me-2"></i>
                                Update Business
                            </button>
                        </div>
                    </div>
                </form>

                @if(auth()->user()->isAdmin() || auth()->id() === $business->user_id)
                    <form id="delete-form" action="{{ route('businesses.destroy', $business) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                @endif
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        input[type="radio"]:checked + span + i {
            opacity: 1 !important;
        }
        label:has(input[type="radio"]:checked):not(:has(input[type="radio"]:disabled)) {
            border-color: rgb(249 115 22) !important;
            background-color: rgb(255 247 237) !important;
        }
    </style>
    @endpush
</x-app-layout>