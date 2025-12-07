<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('businesses.index') }}" class="text-gray-500 hover:text-gray-700">
                <i class="bi bi-arrow-left text-xl"></i>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <i class="bi bi-plus-circle me-2"></i>
                Create New Business
            </h2>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
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
</x-app-layout>