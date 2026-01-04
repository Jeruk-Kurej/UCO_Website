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

                                    <div x-data="{
                                        rating: {{ old('rating', 0) }},
                                        hoverRating: 0,
                                        setRating(value) {
                                            this.rating = value;
                                        }
                                    }">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                                        <input type="hidden" name="rating" x-model="rating">
                                        <div class="flex items-center gap-1">
                                            <template x-for="star in 5" :key="star">
                                                <button type="button" 
                                                        @click="setRating(star)"
                                                        @mouseenter="hoverRating = star"
                                                        @mouseleave="hoverRating = 0"
                                                        class="text-3xl transition-colors focus:outline-none"
                                                        :class="(hoverRating >= star || (hoverRating === 0 && rating >= star)) ? 'text-yellow-400' : 'text-gray-300'">
                                                    ★
                                                </button>
                                            </template>
                                            <span class="ml-2 text-sm text-gray-600" x-show="rating > 0" x-text="rating + ' star' + (rating > 1 ? 's' : '')"></span>
                                        </div>
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

                    <div class="mt-8">
                        <h3 class="font-semibold">Approved Testimonies</h3>

                        <div class="mt-4 space-y-4">
                            @forelse ($testimonies as $testimony)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between gap-4">
                                        <div>
                                            <p class="font-semibold">{{ $testimony->customer_name }}</p>
                                            <p class="text-xs text-gray-500">{{ optional($testimony->date)->format('M d, Y') }}</p>
                                        </div>
                                        <div class="flex items-center gap-0.5 text-yellow-400 text-xl">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="{{ $i <= $testimony->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="mt-3 text-gray-800">{{ $testimony->content }}</p>
                                </div>
                            @empty
                                <p class="text-sm text-gray-600">No approved testimonies yet.</p>
                            @endforelse
                        </div>

                        <div class="mt-6">
                            {{ $testimonies->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
