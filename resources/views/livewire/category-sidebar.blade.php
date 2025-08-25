<div class="w-64 bg-white shadow-lg h-screen sticky top-0 overflow-y-auto" x-data="{ openCategory: null }">
    <div class="p-4 border-b">
        <h2 class="text-lg font-semibold text-gray-800">Kategorije</h2>
    </div>

    <div class="p-2">
        <!-- Globalni "Svi oglasi" -->
        <a href="{{ route('listings.index') }}"
            class="flex items-center px-3 py-2 text-gray-700 rounded-lg hover:bg-gray-100 {{ request()->routeIs('listings.index') && !request()->get('selectedCategory') ? 'bg-blue-50 text-blue-700' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 11H5m14-8H5a2 2 0 00-2 2v12a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2z"></path>
            </svg>
            Svi oglasi
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
                <!-- Glavna kategorija sa "Svi oglasi" linkom -->
                <div class="flex items-center justify-between group">
                    <a href="{{ route('listings.index', ['selectedCategory' => $category->id]) }}"
                        class="flex-1 flex items-center px-3 py-2 text-gray-700 rounded-lg hover:bg-gray-100 {{ request()->get('selectedCategory') == $category->id ? 'bg-blue-50 text-blue-700' : '' }}">
                        @if ($category->icon)
                            <img src="{{ $category->icon }}" alt="{{ $category->name }}" class="w-5 h-5 mr-3">
                        @else
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4">
                                </path>
                            </svg>
                        @endif
                        {{ $category->name }}
                    </a>

                    @if ($category->children->count() > 0)
                        <button
                            @click="openCategory = openCategory === '{{ $category->id }}' ? null : '{{ $category->id }}'"
                            class="p-2 text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4 transition-transform duration-200"
                                :class="{ 'transform rotate-90': openCategory === '{{ $category->id }}' }"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    @endif
                </div>

                <!-- Podkategorije -->
                @if ($category->children->count() > 0)
                    <div class="ml-6 mt-1 overflow-hidden transition-all duration-200"
                        :class="{ 'max-h-0': openCategory !== '{{ $category->id }}', 'max-h-96': openCategory === '{{ $category->id }}' }">
                        <!-- "Svi oglasi" za ovu kategoriju -->
                        <a href="{{ route('listings.index', ['selectedCategory' => $category->id]) }}"
                            class="block px-3 py-2 text-sm text-gray-700 rounded hover:bg-gray-50 {{ request()->get('selectedCategory') == $category->id ? 'bg-blue-50 text-blue-600' : '' }}">
                            📁 Svi oglasi u {{ $category->name }}
                        </a>

                        @foreach ($category->children as $child)
                            <a href="{{ route('listings.index', ['selectedCategory' => $child->id]) }}"
                                class="block px-3 py-2 text-sm text-gray-600 rounded hover:bg-gray-50 {{ request()->get('selectedCategory') == $child->id ? 'bg-blue-50 text-blue-600' : '' }}">
                                • {{ $child->name }}
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

            <a href="{{ route('listings.my') }}"
                class="flex items-center px-3 py-2 text-indigo-700 rounded-lg hover:bg-indigo-50 mt-2">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Moji oglasi
            </a>
        </div>
    @endauth
</div>
