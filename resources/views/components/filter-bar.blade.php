@use('Illuminate\Support\Facades\Route')

<div class="mb-4 px-6">
    <div class="flex items-center gap-3 flex-wrap">
        <a href="{{ route('businesses.index') }}" class="px-3 py-1.5 rounded-full text-sm font-medium {{ request('type') ? 'bg-gray-100 text-gray-700 hover:bg-gray-200' : 'bg-gray-900 text-white' }}" aria-current="{{ request('type') ? 'false' : 'true' }}">All</a>
        @foreach($businessTypes as $type)
            <a href="{{ route('businesses.index', array_merge(request()->except('page'), ['type' => $type->id])) }}" class="px-3 py-1.5 rounded-full text-sm font-medium {{ request('type') == $type->id ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}" aria-pressed="{{ request('type') == $type->id ? 'true' : 'false' }}">
                {{ $type->name }}
            </a>
        @endforeach
    </div>
</div>
