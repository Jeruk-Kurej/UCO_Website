<x-app-layout>
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">UCO Testimony Review</h1>
            </div>
        </div>



        {{-- UC Analyses Table --}}
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Testimony</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Rating</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Sentiment</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($ucAnalyses as $analysis)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="max-w-xs">
                                        <p class="text-sm font-medium text-gray-900">{{ $analysis->ucTestimony?->customer_name ?? 'Unknown' }}</p>
                                        <p class="text-xs text-gray-600 line-clamp-2 mt-1">{{ $analysis->ucTestimony?->content ?? '' }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center items-center gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $analysis->ucTestimony && $i <= $analysis->ucTestimony->rating ? 'text-yellow-400' : 'text-gray-300' }} fill-current" viewBox="0 0 20 20">
                                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $analysis->sentiment_score >= 70 ? 'bg-green-100 text-green-800' :
                                           ($analysis->sentiment_score >= 40 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ round($analysis->sentiment_score) }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($analysis->is_approved)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            ✅ Approved
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            ⚠️ Needs Review
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center text-xs text-gray-500">
                                    {{ $analysis->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($analysis->ucTestimony)
                                        <a href="{{ route('uc-ai-analyses.show', $analysis->ucTestimony) }}"
                                           class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-blue-600 hover:bg-blue-50 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                    @else
                                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-300">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <p class="text-gray-500 text-lg font-medium">No UC AI analyses yet</p>
                                    <p class="text-sm text-gray-400 mt-1">UC testimonies will be automatically analyzed when submitted</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($ucAnalyses->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $ucAnalyses->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
