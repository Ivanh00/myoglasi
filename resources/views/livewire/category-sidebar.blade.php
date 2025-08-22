<div class="w-64 bg-white shadow-lg h-screen sticky top-0 overflow-y-auto">
    <div class="p-4 border-b">
        <h2 class="text-lg font-semibold text-gray-800">Kategorije</h2>
    </div>

    <div class="p-2">
        <a href="{{ route('listings.index') }}"
            class="flex items-center px-3 py-2 text-gray-700 rounded-lg hover:bg-gray-100 {{ request()->routeIs('listings.index') && !request()->get('category') ? 'bg-blue-50 text-blue-700' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 11H5m14-8H5a2 2 0 00-2 2v12a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2z"></path>
            </svg>
            Sve kategorije
        </a>

        @php
            $categories = \App\Models\Category::with('children')
                ->whereNull('parent_id')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        @endphp

        @foreach ($categories as $category)
            <div class="mt-1">
                <a href="{{ route('category.show', $category->slug) }}"
                    class="flex items-center px-3 py-2 text-gray-700 rounded-lg hover:bg-gray-100 {{ request()->route('category') === $category->slug ? 'bg-blue-50 text-blue-700' : '' }}">
                    @if ($category->icon)
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="{{ $category->icon }}"></path>
                        </svg>
                    @endif
                    {{ $category->name }}
                </a>

                @if ($category->children->count() > 0)
                    <div class="ml-8 mt-1">
                        @foreach ($category->children as $child)
                            <a href="{{ route('category.show', $child->slug) }}"
                                class="block px-3 py-1 text-sm text-gray-600 rounded hover:bg-gray-50 {{ request()->route('category') === $child->slug ? 'bg-blue-50 text-blue-600' : '' }}">
                                {{ $child->name }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    @auth
        <div class="border-t mt-4 pt-4 p-2">
            <div class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Brze akcije</div>

            <a href="{{ route('listings.create') }}"
                class="flex items-center px-3 py-2 text-green-700 rounded-lg hover:bg-green-50">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                    </path>
                </svg>
                Dodaj oglas
            </a>
        </div>
    @endauth
</div>
