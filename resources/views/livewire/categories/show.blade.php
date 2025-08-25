<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Navigacija - breadcrumbs -->
    <nav class="mb-6 flex" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2">
            <li>
                <a href="{{ route('home') }}" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-home"></i>
                </a>
            </li>
            <li class="flex items-center">
                <span class="mx-2 text-gray-400">/</span>
                <a href="{{ route('categories.index') }}" class="text-gray-500 hover:text-gray-700">
                    Kategorije
                </a>
            </li>
            <li class="flex items-center">
                <span class="mx-2 text-gray-400">/</span>
                <span class="text-gray-700 font-medium">{{ $category->name }}</span>
            </li>
        </ol>
    </nav>

    <!-- Naslov -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">{{ $category->name }} - Oglasi</h1>
        <p class="text-gray-600 mt-2">Pronađite najbolje ponude iz kategorije {{ $category->name }}</p>
    </div>

    <!-- Lista oglasa -->
    @if ($listings->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            @foreach ($listings as $listing)
                <div
                    class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <a href="{{ route('listings.show', $listing) }}">
                        @if ($listing->images->count() > 0)
                            <img src="{{ $listing->images->first()->url }}" alt="{{ $listing->title }}"
                                class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 text-3xl"></i>
                            </div>
                        @endif
                    </a>

                    <div class="p-4">
                        <a href="{{ route('listings.show', $listing) }}">
                            <h3 class="font-semibold text-lg text-gray-900 mb-2 hover:text-blue-600 transition-colors">
                                {{ Str::limit($listing->title, 40) }}
                            </h3>
                        </a>

                        <div class="flex items-center justify-between mb-2">
                            <span class="text-blue-600 font-bold text-xl">{{ number_format($listing->price, 2) }}
                                RSD</span>
                            @if ($listing->condition)
                                <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full">
                                    {{ $listing->condition->name }}
                                </span>
                            @endif
                        </div>

                        <p class="text-gray-600 text-sm mb-2">{{ $listing->location }}</p>

                        <div class="flex items-center justify-between text-xs text-gray-400">
                            <span>{{ $listing->created_at->diffForHumans() }}</span>
                            <span><i class="fas fa-eye mr-1"></i> {{ $listing->views ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginacija -->
        <div class="mt-6">
            {{ $listings->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <i class="fas fa-search text-gray-400 text-5xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Nema oglasa u ovoj kategoriji</h3>
            <p class="text-gray-600 mb-4">Trenutno nema aktivnih oglasa u ovoj kategoriji. Pokušajte kasnije ili
                pogledajte druge kategorije.</p>
            <a href="{{ route('categories.index') }}"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                Pogledaj sve kategorije
            </a>
        </div>
    @endif
</div>
