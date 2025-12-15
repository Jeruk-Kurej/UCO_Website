<x-app-layout>
    {{-- ✅ REMOVED: <x-slot name="header"> section --}}

    <div class="max-w-3xl mx-auto">
        {{-- ✅ NEW: Inline Back Button + Page Title --}}
        <div class="mb-6 flex items-center gap-3">
            <a href="{{ route('businesses.index') }}" 
               class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-gray-800 transition duration-150">
                <i class="bi bi-arrow-left text-lg"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Create New Business</h1>
                <p class="text-sm text-gray-600">Fill in the details to register your business</p>
            </div>
        </div>

        <div class="bg-white shadow-sm sm:rounded-lg">
            <div class="p-6">
                <form method="POST" action="{{ route('businesses.store') }}" class="space-y-6">
                    @csrf

                    {{-- Business Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Business Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name') }}"
                               required
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm @error('name') border-red-500 @enderror"
                               placeholder="e.g., Warung Nasi Goreng Pak Joko">
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
                                <option value="{{ $type->id }}" {{ old('business_type_id') == $type->id ? 'selected' : '' }}>
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
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative flex cursor-pointer rounded-lg border border-gray-300 bg-white p-4 shadow-sm focus:outline-none hover:border-orange-500 transition duration-150 @error('business_mode') border-red-500 @enderror">
                                <input type="radio" 
                                       name="business_mode" 
                                       value="product" 
                                       {{ old('business_mode', 'product') === 'product' ? 'checked' : '' }}
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

                            <label class="relative flex cursor-pointer rounded-lg border border-gray-300 bg-white p-4 shadow-sm focus:outline-none hover:border-orange-500 transition duration-150 @error('business_mode') border-red-500 @enderror">
                                <input type="radio" 
                                       name="business_mode" 
                                       value="service" 
                                       {{ old('business_mode') === 'service' ? 'checked' : '' }}
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
                        <p class="mt-2 text-xs text-gray-500">
                            <i class="bi bi-info-circle me-1"></i>
                            Choose whether your business sells products or provides services. You can only choose one.
                        </p>
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
                                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm @error('description') border-red-500 @enderror"
                                  placeholder="Describe your business...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Provide a detailed description of your business, products, and services.</p>
                    </div>

                    {{-- Admin: Assign to User (Optional) --}}
                    @if(isset($users) && auth()->user()->isAdmin())
                        <div>
                            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Assign to User (Admin Only)
                            </label>
                            <select name="user_id" 
                                    id="user_id"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                                <option value="">-- Assign to myself --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }}) - {{ ucfirst($user->role) }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-sm text-gray-500">Leave empty to assign to yourself.</p>
                        </div>
                    @endif

                    {{-- Submit Buttons --}}
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="{{ route('businesses.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-150">
                            <i class="bi bi-x-lg me-2"></i>
                            Cancel
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-orange-500 to-yellow-500 hover:from-orange-600 hover:to-yellow-600 text-white font-semibold rounded-md shadow-sm transition duration-150">
                            <i class="bi bi-check-lg me-2"></i>
                            Create Business
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        input[type="radio"]:checked + span + i {
            opacity: 1 !important;
        }
        label:has(input[type="radio"]:checked) {
            border-color: rgb(249 115 22) !important;
            background-color: rgb(255 247 237) !important;
        }
    </style>
    @endpush
</x-app-layout>