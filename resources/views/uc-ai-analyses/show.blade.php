<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between">
                        <a href="{{ route('ai-analyses.index') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900">
                            ← Back 
                        </a>
                    </div>

                    <div class="mt-6">
                        <h2 class="text-xl font-bold">UCO Testimony Review</h2>
                    </div>

                    <div class="mt-6">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $testimony->customer_name }}</p>
                                <p class="text-xs text-gray-500">{{ optional($testimony->date)->format('M d, Y') }}</p>
                            </div>
                            <div class="text-sm font-semibold text-gray-700">{{ $testimony->rating }}/5</div>
                        </div>
                        <p class="mt-3 text-gray-800">{{ $testimony->content }}</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
