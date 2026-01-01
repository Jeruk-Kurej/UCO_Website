<x-app-layout>
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">AI Testimony Moderation Dashboard</h1>
                <p class="text-sm text-gray-600">Review and manage AI-moderated testimonies powered by Google Gemini</p>
            </div>
        </div>

        {{-- Stats Cards --}}
        @php
            $totalCount = $analyses->total();
            $approvedCount = \App\Models\AiAnalysis::where('is_approved', true)->count();
            $rejectedCount = \App\Models\AiAnalysis::where('is_approved', false)->count();
            $approvalRate = $totalCount > 0 ? round(($approvedCount / $totalCount) * 100, 1) : 0;
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-blue-500">
                <p class="text-xs font-medium text-gray-500 uppercase">Total Analyzed</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalCount }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-green-500">
                <p class="text-xs font-medium text-gray-500 uppercase">Auto-Approved</p>
                <p class="text-2xl font-bold text-green-600 mt-1">{{ $approvedCount }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-red-500">
                <p class="text-xs font-medium text-gray-500 uppercase">Needs Review</p>
                <p class="text-2xl font-bold text-red-600 mt-1">{{ $rejectedCount }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-purple-500">
                <p class="text-xs font-medium text-gray-500 uppercase">Approval Rate</p>
                <p class="text-2xl font-bold text-purple-600 mt-1">{{ $approvalRate }}%</p>
            </div>
        </div>

        {{-- Analyses Table --}}
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Testimony</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Business</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Rating</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Sentiment</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($analyses as $analysis)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="max-w-xs">
                                        <p class="text-sm font-medium text-gray-900">{{ $analysis->testimony->customer_name }}</p>
                                        <p class="text-xs text-gray-600 line-clamp-2 mt-1">{{ $analysis->testimony->content }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('businesses.show', $analysis->testimony->business) }}" 
                                       class="text-sm text-blue-600 hover:text-blue-800 hover:underline">
                                        {{ $analysis->testimony->business->name }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center items-center gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $analysis->testimony->rating ? 'text-yellow-400' : 'text-gray-300' }} fill-current" viewBox="0 0 20 20">
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
                                    <a href="{{ route('ai-analyses.show', $analysis->testimony) }}" 
                                       class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-blue-600 hover:bg-blue-50 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="mt-4 text-gray-500 text-lg font-medium">No AI analyses yet</p>
                                    <p class="text-sm text-gray-400 mt-1">Testimonies will be automatically analyzed when submitted</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($analyses->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $analyses->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
