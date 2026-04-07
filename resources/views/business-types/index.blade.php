<x-app-layout>
    <div class="space-y-6 pb-8"
         x-data="{
            search: '{{ request('search') }}',
            isSearching: false,
            performSearch() {
                this.isSearching = true;
                const trimmed = this.search.trim();
                const url = trimmed.length > 0
                    ? '{{ route('business-types.index') }}?search=' + encodeURIComponent(trimmed)
                    : '{{ route('business-types.index') }}';

                fetch(url, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newTableBody = doc.querySelector('#business-types-table-body');
                    const newPagination = doc.querySelector('#pagination-container');
                    const newStats = doc.querySelector('#stats-summary');
                    
                    if (newTableBody) {
                        document.querySelector('#business-types-table-body').innerHTML = newTableBody.innerHTML;
                        if (newPagination) document.querySelector('#pagination-container').innerHTML = newPagination.innerHTML;
                        if (newStats) document.querySelector('#stats-summary').innerHTML = newStats.innerHTML;
                        
                        window.history.pushState({}, '', url);
                        if (typeof window.initRevealOnScroll === 'function') {
                            window.initRevealOnScroll();
                        }
                    }
                    this.isSearching = false;
                })
                .catch(error => {
                    console.error('Search error:', error);
                    window.location.href = url;
                });
            }
         }">
        {{-- Page Header --}}
        <section class="relative overflow-hidden rounded-3xl border border-uco-orange-100 bg-white px-6 py-8 shadow-sm md:px-8 md:py-10 mb-8">
            <div class="uco-hero-mesh"></div>
            <div class="relative z-10 flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                <div class="space-y-2 reveal-on-scroll">
                    <span class="inline-flex items-center rounded-full border border-uco-orange-200 bg-uco-orange-50 px-4 py-1.5 text-xs font-semibold uppercase tracking-wide text-uco-orange-700">
                        Admin Dashboard
                    </span>
                    <h1 class="text-3xl font-extrabold text-soft-gray-900 md:text-4xl">Business Type Management</h1>
                    <p class="text-sm text-soft-gray-600 mt-1">Organize and maintain industrial categories for all businesses.</p>
                </div>

                <div class="flex items-center gap-3 relative z-10 reveal-on-scroll" style="transition-delay: 100ms;">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('business-types.create') }}"
                               class="inline-flex items-center gap-2 rounded-xl bg-uco-orange-500 px-5 py-3 text-sm font-semibold text-white shadow-md shadow-uco-orange-200 transition hover:-translate-y-0.5 hover:bg-uco-orange-600">
                                <i class="bi bi-plus-circle"></i>
                                Create Business Type
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </section>

        {{-- Search Section --}}
        <section class="rounded-2xl border border-soft-gray-200 bg-white p-4 shadow-sm md:p-5 reveal-on-scroll">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <div class="relative flex-1">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="bi bi-search text-soft-gray-400"></i>
                    </div>
                    <input type="text"
                           x-model="search"
                           @input.debounce.500ms="performSearch()"
                           @keydown.enter="performSearch()"
                           placeholder="Search business types by name or description..."
                           class="block w-full rounded-xl border border-soft-gray-300 py-2.5 pl-10 pr-10 text-sm text-soft-gray-800 focus:border-uco-orange-300 focus:ring-2 focus:ring-uco-orange-200 transition-all">
                    <div x-show="isSearching" class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <svg class="h-4 w-4 animate-spin text-soft-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </div>
                </div>

                @if(request('search'))
                    <button type="button"
                            @click="search = ''; performSearch()"
                            class="inline-flex items-center justify-center gap-2 rounded-xl border border-uco-yellow-300 bg-uco-yellow-50 px-4 py-2.5 text-sm font-semibold text-uco-yellow-800 transition hover:border-uco-yellow-400 hover:bg-uco-yellow-100">
                        <i class="bi bi-x-circle"></i>
                        Clear
                    </button>
                @endif
            </div>
        </section>

        {{-- Business Types Table --}}
        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm reveal-on-scroll">
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-widest w-[10%]">
                                Icon
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest w-[45%]">
                                Business Type
                            </th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-widest w-[20%]">
                                Usage Count
                            </th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-widest w-[25%]">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody id="business-types-table-body" class="bg-white divide-y divide-gray-100 italic-none">
                        @forelse($businessTypes as $type)
                            <tr class="hover:bg-gray-50/80 transition-colors group">
                                {{-- Icon Preview --}}
                                <td class="px-6 py-5">
                                    <div class="flex items-center justify-center">
                                        <div class="w-12 h-12 rounded-xl bg-uco-orange-50 text-uco-orange-600 flex items-center justify-center text-xl shadow-sm border border-uco-orange-100 group-hover:scale-110 transition-transform">
                                            <i class="bi bi-briefcase"></i>
                                        </div>
                                    </div>
                                </td>

                                {{-- Type Name & Description --}}
                                <td class="px-6 py-5">
                                    <div>
                                        <div class="text-base font-bold text-gray-900 group-hover:text-uco-orange-600 transition-colors">{{ $type->name }}</div>
                                        <div class="text-xs text-gray-500 mt-1 line-clamp-1 max-w-sm">{{ $type->description ?: 'No description provided' }}</div>
                                    </div>
                                </td>

                                {{-- Usage Count --}}
                                <td class="px-6 py-5 text-center">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold
                                        {{ $type->businesses_count > 0 ? 'bg-uco-orange-100 text-uco-orange-700' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $type->businesses_count ?? 0 }} businesses
                                    </span>
                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-5">
                                    <div class="flex items-center justify-center gap-3">
                                        <a href="{{ route('business-types.show', $type) }}" 
                                           class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-gray-50 text-gray-600 hover:bg-uco-orange-500 hover:text-white transition-all shadow-sm"
                                           title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        @if(auth()->check() && auth()->user()->isAdmin())
                                            @if(($type->businesses_count ?? 0) == 0)
                                                <form action="{{ route('business-types.destroy', $type) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('⚠️ Delete {{ $type->name }}?\n\nThis action cannot be undone!');"
                                                      class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-gray-50 text-red-600 hover:bg-red-500 hover:text-white transition-all shadow-sm"
                                                            title="Delete Type">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-gray-100 text-gray-300 cursor-not-allowed" 
                                                      title="Cannot delete - {{ $type->businesses_count }} business(es) using this type">
                                                    <i class="bi bi-lock"></i>
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                            <i class="bi bi-tags text-4xl text-gray-300"></i>
                                        </div>
                                        <p class="text-gray-900 text-xl font-bold">No business types found</p>
                                        <p class="text-gray-500 text-sm mt-1">Try a different search keyword or create a new category.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Container --}}
            <div id="pagination-container">
                @if($businessTypes->hasPages())
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                        {{ $businessTypes->links() }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Stats Summary --}}
        <div id="stats-summary" class="grid grid-cols-1 md:grid-cols-3 gap-6 reveal-on-scroll">
            <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-uco-orange-500 hover:shadow-md transition-shadow group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Total Categories</p>
                        <p class="text-3xl font-extrabold text-gray-900 group-hover:text-uco-orange-600 transition-colors">{{ $businessTypes->total() }}</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-uco-orange-50 flex items-center justify-center text-uco-orange-500">
                        <i class="bi bi-tags text-3xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-blue-500 hover:shadow-md transition-shadow group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Total Linked Businesses</p>
                        <p class="text-3xl font-extrabold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $businessTypes->sum('businesses_count') }}</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-500">
                        <i class="bi bi-briefcase text-3xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-emerald-500 hover:shadow-md transition-shadow group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Avg per Category</p>
                        <p class="text-3xl font-extrabold text-gray-900 group-hover:text-emerald-600 transition-colors">
                            {{ $businessTypes->count() > 0 ? round($businessTypes->sum('businesses_count') / $businessTypes->count(), 1) : 0 }}
                        </p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-500">
                        <i class="bi bi-bar-chart text-3xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>