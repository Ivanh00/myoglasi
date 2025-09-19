<!DOCTYPE html>
<html lang="sr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rezultati pretrage - PazAriO</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-100">
    @include('livewire.layout.navigation')

    <div class="max-w-7xl mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold text-slate-800 mb-6">Rezultati pretrage</h1>

        <!-- Prikaz filtera koji su primenjeni -->
        @if (request()->anyFilled(['query', 'city', 'category', 'condition', 'price_min', 'price_max']))
            <div class="bg-white p-4 rounded-lg shadow mb-6">
                <h2 class="text-lg font-semibold mb-2">Aktivni filteri:</h2>
                <div class="flex flex-wrap gap-2">
                    @if (request('query'))
                        <span class="bg-sky-100 text-sky-800 text-xs font-medium px-2.5 py-0.5 rounded">
                            Tekst: {{ request('query') }}
                        </span>
                    @endif

                    @if (request('city'))
                        <span class="bg-sky-100 text-sky-800 text-xs font-medium px-2.5 py-0.5 rounded">
                            Grad: {{ request('city') }}
                        </span>
                    @endif

                    @if (request('category') && $categories->find(request('category')))
                        <span class="bg-sky-100 text-sky-800 text-xs font-medium px-2.5 py-0.5 rounded">
                            Kategorija: {{ $categories->find(request('category'))->name }}
                        </span>
                    @endif

                    @if (request('condition') && $conditions->find(request('condition')))
                        <span class="bg-sky-100 text-sky-800 text-xs font-medium px-2.5 py-0.5 rounded">
                            Stanje: {{ $conditions->find(request('condition'))->name }}
                        </span>
                    @endif

                    @if (request('price_min'))
                        <span class="bg-sky-100 text-sky-800 text-xs font-medium px-2.5 py-0.5 rounded">
                            Cena od: {{ number_format(request('price_min'), 0, ',', '.') }} RSD
                        </span>
                    @endif

                    @if (request('price_max'))
                        <span class="bg-sky-100 text-sky-800 text-xs font-medium px-2.5 py-0.5 rounded">
                            Cena do: {{ number_format(request('price_max'), 0, ',', '.') }} RSD
                        </span>
                    @endif

                    <a href="{{ route('search.index') }}"
                        class="text-red-600 text-xs font-medium px-2.5 py-0.5 rounded border border-red-600 hover:bg-red-600 hover:text-white">
                        Obriši sve filtere
                    </a>
                </div>
            </div>
        @endif

        <!-- Rezultati pretrage -->
        @if ($listings->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($listings as $listing)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="h-48 bg-slate-200">
                            @if ($listing->images && $listing->images->count() > 0)
                                <img src="{{ asset('storage/' . $listing->images->first()->path) }}"
                                    alt="{{ $listing->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-400">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-slate-800 mb-2">{{ $listing->title }}</h3>
                            <p class="text-slate-600 dark:text-slate-400 mb-4">
                                {{ Str::limit($listing->description, 100) }}</p>
                            <div class="flex justify-between items-center">
                                <span
                                    class="text-xl font-bold text-sky-600 dark:text-sky-400">{{ number_format($listing->price, 0, ',', '.') }}
                                    RSD</span>
                                <span
                                    class="text-sm text-slate-500 dark:text-slate-300">{{ $listing->location }}</span>
                            </div>
                            <div class="mt-4 flex justify-between items-center">
                                <span
                                    class="text-sm text-slate-500 dark:text-slate-300">{{ $listing->created_at->diffForHumans() }}</span>
                                <a href="{{ route('listings.show', $listing) }}"
                                    class="text-sky-600 hover:text-sky-800 text-sm font-medium">Pogledaj oglas</a>
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
            <div class="bg-white p-8 rounded-lg shadow text-center">
                <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="mt-2 text-lg font-medium text-slate-900">Nema rezultata</h3>
                <p class="mt-1 text-slate-500 dark:text-slate-300">Nijedan oglas ne odgovara vašim filterima.</p>
                <div class="mt-6">
                    <a href="{{ route('search.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                        Poništi filtere
                    </a>
                </div>
            </div>
        @endif
    </div>
</body>

</html>
