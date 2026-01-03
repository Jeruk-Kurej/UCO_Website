<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <a href="{{ route('dashboard') }}" 
                                   class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-gray-800 transition duration-150">
                                    <i class="bi bi-arrow-left text-lg"></i>
                                </a>
                                <h2 class="text-xl font-bold">Universitas Ciputra Online Testimonies</h2>
                            </div>
                            <p class="text-sm text-gray-600 ml-13">Share your experience with Universitas Ciputra Online.</p>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="mt-4 rounded-md bg-green-50 p-4 text-green-800 text-sm">
                            {{ session('success') }}
                        </div>
                    @endif

                    @auth
                        @if (!auth()->user()->isAdmin())
                            <div class="mt-6 border border-gray-200 rounded-lg p-4" x-data="{ rating: {{ old('rating', 0) }} }">
                                <h3 class="font-semibold">Write a Testimony</h3>

                                <form action="{{ route('uc-testimonies.store') }}" method="POST" class="mt-4 space-y-4">
                                    @csrf

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Your Name</label>
                                        <input type="text" name="customer_name" value="{{ old('customer_name') }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                        @error('customer_name')
                                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                                        <div class="flex items-center gap-2">
                                            <template x-for="star in 5" :key="star">
                                                <button type="button"
                                                        @click="rating = star"
                                                        class="focus:outline-none transition-transform hover:scale-110">
                                                    <svg class="w-8 h-8 fill-current transition-colors"
                                                         :class="star <= rating ? 'text-yellow-400' : 'text-gray-300'"
                                                         viewBox="0 0 20 20">
                                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                    </svg>
                                                </button>
                                            </template>
                                            <span class="ml-2 text-sm text-gray-600" x-show="rating > 0">
                                                <span x-text="rating"></span> star<span x-show="rating > 1">s</span>
                                            </span>
                                        </div>
                                        <input type="hidden" name="rating" :value="rating">
                                        @error('rating')
                                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Your Testimony</label>
                                        <textarea name="content" rows="5"
                                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">{{ old('content') }}</textarea>
                                        @error('content')
                                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="flex items-center gap-3">
                                        <button type="submit" class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-md font-semibold">
                                            Submit
                                        </button>
                                        
                                    </div>
                                </form>
                            </div>
                        @endif
                    @else
                        <div class="mt-6 border border-gray-200 rounded-lg p-4 text-sm text-gray-700">
                            <a class="text-orange-600 font-semibold hover:underline" href="{{ route('login') }}">Log in</a> to submit a testimony.
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
