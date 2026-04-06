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
                    const newContent = doc.querySelector('.space-y-6');
                    if (newContent) {
                        document.querySelector('.space-y-6').innerHTML = newContent.innerHTML;
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
        <section class="relative overflow-hidden rounded-3xl border border-uco-orange-100 bg-white px-6 py-8 shadow-sm md:px-8 md:py-10">
            <div class="uco-hero-mesh"></div>
            <div class="relative z-10 flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                <div class="space-y-2 reveal-on-scroll">
                    <span class="inline-flex items-center rounded-full border border-uco-orange-200 bg-uco-orange-50 px-4 py-1.5 text-xs font-semibold uppercase tracking-wide text-uco-orange-700">
                        UCO Directory
                    </span>
                    @if(auth()->check() && auth()->user()->isAdmin())
                        <h1 class="text-3xl font-extrabold text-soft-gray-900 md:text-4xl">Business Type Management</h1>
                        <p class="text-sm text-soft-gray-600 md:text-base">Organize, refine, and maintain categories for all listed businesses.</p>
                    @else
                        <h1 class="text-3xl font-extrabold text-soft-gray-900 md:text-4xl">Explore Business Types</h1>
                        <p class="text-sm text-soft-gray-600 md:text-base">Browse categories and jump into businesses built by UCO students & alumni.</p>
                    @endif
                </div>

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
        </section>

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
                           class="block w-full rounded-xl border border-soft-gray-300 py-2.5 pl-10 pr-10 text-sm text-soft-gray-800 focus:border-uco-orange-300 focus:ring-2 focus:ring-uco-orange-200">
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

        <section class="space-y-4">
            <div class="flex flex-wrap items-center gap-2 reveal-on-scroll">
                <span class="inline-flex items-center gap-2 rounded-full border border-uco-orange-200 bg-uco-orange-50 px-4 py-2 text-xs font-semibold text-uco-orange-700">
                    <i class="bi bi-tags"></i>
                    {{ $businessTypes->total() }} total type(s)
                </span>
                <span class="inline-flex items-center gap-2 rounded-full border border-uco-yellow-300 bg-uco-yellow-50 px-4 py-2 text-xs font-semibold text-uco-yellow-800">
                    <i class="bi bi-briefcase"></i>
                    {{ $businessTypes->sum('businesses_count') }} linked business(es)
                </span>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                @forelse($businessTypes as $type)
                    <article class="group flex h-full flex-col rounded-2xl border border-soft-gray-200 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-uco-orange-200 hover:shadow-lg reveal-on-scroll">
                        <div class="mb-4 flex items-start justify-between gap-3">
                            <div class="flex min-w-0 items-center gap-3">
                                <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl bg-uco-yellow-100 text-uco-yellow-700">
                                    <i class="bi bi-grid-3x3-gap-fill"></i>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="line-clamp-1 text-lg font-bold text-soft-gray-900" title="{{ $type->name }}">{{ $type->name }}</h3>
                                    <p class="text-xs text-soft-gray-500">Category</p>
                                </div>
                            </div>
                            <span class="inline-flex flex-shrink-0 items-center whitespace-nowrap rounded-full px-3 py-1 text-xs font-semibold {{ $type->businesses_count > 0 ? 'bg-uco-orange-100 text-uco-orange-700' : 'bg-soft-gray-100 text-soft-gray-600' }}">
                                {{ $type->businesses_count ?? 0 }} business(es)
                            </span>
                        </div>

                        <p class="line-clamp-2 flex-1 text-sm leading-relaxed text-soft-gray-600">
                            {{ $type->description ?: 'No description provided for this business type yet.' }}
                        </p>

                        <div class="mt-5 flex flex-wrap items-center gap-2">
                            <a href="{{ route('business-types.show', $type) }}"
                               class="inline-flex items-center gap-2 rounded-xl bg-uco-orange-500 px-4 py-2 text-xs font-semibold text-white transition hover:bg-uco-orange-600">
                                Details
                                <i class="bi bi-eye"></i>
                            </a>

                            @auth
                                @if(auth()->user()->isAdmin())

                                    @if($type->businesses_count == 0)
                                        <form action="{{ route('business-types.destroy', $type) }}"
                                              method="POST"
                                              onsubmit="return confirm('⚠️ Delete {{ $type->name }}?\n\nThis action cannot be undone!');"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-2 rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-100">
                                                Delete
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="inline-flex cursor-not-allowed items-center gap-2 rounded-xl border border-soft-gray-200 bg-soft-gray-100 px-4 py-2 text-xs font-semibold text-soft-gray-400"
                                              title="Cannot delete - {{ $type->businesses_count }} business(es) using this type">
                                            Delete Locked
                                            <i class="bi bi-lock"></i>
                                        </span>
                                    @endif
                                @endif
                            @endauth
                        </div>
                    </article>
                @empty
                    <div class="col-span-full rounded-2xl border border-dashed border-soft-gray-300 bg-soft-gray-50 p-10 text-center reveal-on-scroll">
                        <div class="mb-3 text-uco-orange-500"><i class="bi bi-tags text-3xl"></i></div>
                        <p class="text-lg font-semibold text-soft-gray-700">No business types found</p>
                        <p class="mt-1 text-sm text-soft-gray-500">Try a different keyword, or create a new category to get started.</p>
                    </div>
                @endforelse
            </div>

            @if($businessTypes->hasPages())
                <div class="rounded-2xl border border-soft-gray-200 bg-white px-4 py-4 shadow-sm">
                    {{ $businessTypes->links() }}
                </div>
            @endif
        </section>

        @auth
            <section class="grid grid-cols-1 gap-4 md:grid-cols-3 reveal-on-scroll">
                <article class="rounded-2xl border border-uco-orange-100 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-soft-gray-500">Total Types</p>
                    <div class="mt-2 flex items-center justify-between">
                        <p class="text-3xl font-bold text-soft-gray-900">{{ $businessTypes->total() }}</p>
                        <span class="rounded-xl bg-uco-orange-50 p-2 text-uco-orange-600"><i class="bi bi-tags"></i></span>
                    </div>
                </article>

                <article class="rounded-2xl border border-uco-yellow-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-soft-gray-500">Total Businesses</p>
                    <div class="mt-2 flex items-center justify-between">
                        <p class="text-3xl font-bold text-soft-gray-900">{{ $businessTypes->sum('businesses_count') }}</p>
                        <span class="rounded-xl bg-uco-yellow-50 p-2 text-uco-yellow-700"><i class="bi bi-briefcase"></i></span>
                    </div>
                </article>

                <article class="rounded-2xl border border-soft-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-soft-gray-500">Average per Type</p>
                    <div class="mt-2 flex items-center justify-between">
                        <p class="text-3xl font-bold text-soft-gray-900">{{ $businessTypes->count() > 0 ? round($businessTypes->sum('businesses_count') / $businessTypes->count(), 1) : 0 }}</p>
                        <span class="rounded-xl bg-soft-gray-100 p-2 text-soft-gray-600"><i class="bi bi-bar-chart"></i></span>
                    </div>
                </article>
            </section>
        @endauth
    </div>
</x-app-layout>