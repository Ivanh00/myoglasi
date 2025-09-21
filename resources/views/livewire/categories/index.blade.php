<div class="max-w-7xl mx-auto py-6 px-1 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 mb-2">Kategorije oglasa</h1>
        <p class="text-slate-600 dark:text-slate-400">Pronađite ono što tražite u našim kategorijama</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach ($categories as $category)
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-sky-100 rounded-full flex items-center justify-center">
                            @if ($category->icon)
                                <i class="{{ $category->icon }} text-sky-600 dark:text-sky-400 text-xl"></i>
                            @else
                                <i class="fas fa-folder text-sky-600 dark:text-sky-400 text-xl"></i>
                            @endif
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-slate-900">{{ $category->name }}</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-300">{{ $category->listings_count }} oglasa
                            </p>
                        </div>
                    </div>

                    @if ($category->description)
                        <p class="text-slate-600 dark:text-slate-400 text-sm mb-4">
                            {{ Str::limit($category->description, 100) }}</p>
                    @endif

                    <a href="{{ route('category.show', $category->slug) }}"
                        class="inline-flex items-center text-sky-600 hover:text-sky-800 font-medium">
                        Pogledaj oglase
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </a>
                </div>

                @if ($category->children->count() > 0)
                    <div class="border-t border-slate-200 px-6 py-4">
                        <h4 class="text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">Podkategorije:</h4>
                        <div class="flex flex-wrap gap-1">
                            @foreach ($category->children->take(4) as $subcategory)
                                <a href="{{ route('category.show', ['category' => $category->slug, 'subcategory' => $subcategory->slug]) }}"
                                    class="inline-block px-2 py-1 bg-slate-100 hover:bg-slate-200 text-xs text-slate-700 rounded">
                                    {{ $subcategory->name }}
                                </a>
                            @endforeach
                            @if ($category->children->count() > 4)
                                <span class="inline-block px-2 py-1 text-xs text-slate-500 dark:text-slate-300">
                                    +{{ $category->children->count() - 4 }} više
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    @if ($categories->isEmpty())
        <div class="text-center py-12">
            <i class="fas fa-folder-open text-slate-400 text-5xl mb-4"></i>
            <h3 class="text-xl font-semibold text-slate-800 mb-2">Nema dostupnih kategorija</h3>
            <p class="text-slate-600 dark:text-slate-400">Kategorije će biti dodane uskoro.</p>
        </div>
    @endif
</div>
