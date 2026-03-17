<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between">
                        <a href="{{ route('ai-analyses.index') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900">
                            ← Back to AI Moderation
                        </a>
                    </div>

                    <div class="mt-6">
                        <h2 class="text-xl font-bold">UC-wide Testimony AI Analysis</h2>
                        <p class="text-sm text-gray-600 mt-1">Universitas Ciputra Online</p>
                    </div>

                    <div class="mt-6 grid grid-cols-1 gap-4">
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $testimony->customer_name }}</p>
                                    <p class="text-xs text-gray-500">{{ optional($testimony->date)->format('M d, Y') }}</p>
                                </div>
                                <div class="text-sm font-semibold text-gray-700">{{ $testimony->rating }}/5</div>
                            </div>
                            <p class="mt-3 text-gray-800">{{ $testimony->content }}</p>
                        </div>

                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <h3 class="font-semibold">AI Result</h3>
                                @if($analysis->is_approved)
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                @else
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                @endif
                            </div>

                            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500">Sentiment Score</p>
                                    <p class="text-lg font-bold text-gray-900">{{ number_format((float)$analysis->sentiment_score, 2) }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Rejection Reason</p>
                                    <p class="text-sm text-gray-900">{{ $analysis->rejection_reason ?? '—' }}</p>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
