<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Naslov -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Omiljeni oglasi</h1>
        <p class="text-slate-600 dark:text-slate-300 mt-2">Vaši sačuvani oglasi</p>
    </div>

    <!-- Sortiranje i info -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
        <div class="text-slate-600 dark:text-slate-300">
            Ukupno: <span class="font-semibold">{{ $favorites->total() }}</span> omiljenih oglasa
        </div>

        <!-- Sortiranje -->
        <div class="flex items-center space-x-4">
            <label class="text-sm font-medium text-slate-700 dark:text-slate-200">Sortiraj po:</label>
            <div class="w-60" x-data="{ open: false }" x-init="open = false">
                <div class="relative">
                    <button @click="open = !open" type="button"
                        class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 dark:hover:border-slate-500 focus:outline-none focus:border-sky-500 dark:focus:border-sky-400 transition-colors flex items-center justify-between">
                        <span>
                            @switch($sortBy)
                                @case('oldest')
                                    Najstarije dodato
                                @break

                                @case('price_asc')
                                    Cena ↑
                                @break

                                @case('price_desc')
                                    Cena ↓
                                @break

                                @default
                                    Najnovije dodato
                            @endswitch
                        </span>
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open = false" x-transition
                        class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-lg">
                        <button @click="$wire.set('sortBy', 'newest'); open = false" type="button"
                            class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg {{ $sortBy === 'newest' ? 'bg-sky-50 dark:bg-slate-600 text-sky-700 dark:text-sky-300' : '' }}">
                            Najnovije dodato
                        </button>
                        <button @click="$wire.set('sortBy', 'oldest'); open = false" type="button"
                            class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 {{ $sortBy === 'oldest' ? 'bg-sky-50 dark:bg-slate-600 text-sky-700 dark:text-sky-300' : '' }}">
                            Najstarije dodato
                        </button>
                        <button @click="$wire.set('sortBy', 'price_asc'); open = false" type="button"
                            class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 {{ $sortBy === 'price_asc' ? 'bg-sky-50 dark:bg-slate-600 text-sky-700 dark:text-sky-300' : '' }}">
                            Cena ↑
                        </button>
                        <button @click="$wire.set('sortBy', 'price_desc'); open = false" type="button"
                            class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-b-lg {{ $sortBy === 'price_desc' ? 'bg-sky-50 dark:bg-slate-600 text-sky-700 dark:text-sky-300' : '' }}">
                            Cena ↓
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash poruke -->
    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Desktop Tabela oglasa -->
    @if ($favorites->count() > 0)
        <div class="hidden lg:block bg-white dark:bg-slate-800 rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-600">
                <thead class="bg-slate-50 dark:bg-slate-700">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                            Oglas</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                            Cena</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                            Status</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                            Dodato</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                            Akcije</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-600">
                    @foreach ($favorites as $listing)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if ($listing->images->count() > 0)
                                            <img class="h-10 w-10 rounded-full object-cover"
                                                src="{{ $listing->images->first()->url }}" alt="{{ $listing->title }}">
                                        @else
                                            <div
                                                class="h-10 w-10 rounded-full bg-slate-200 flex items-center justify-center">
                                                <i class="fas fa-image text-slate-400"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-slate-900 dark:text-slate-100">
                                            {{ Str::limit($listing->title, 40) }}</div>
                                        <div class="text-sm text-slate-500 dark:text-slate-300">
                                            {{ $listing->category->name }}</div>
                                        <div class="text-xs text-slate-400">{{ $listing->location }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-sky-600 dark:text-sky-400 font-bold">
                                    {{ number_format($listing->price, 2) }} RSD</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($listing->status === 'active')
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-200 dark:bg-green-800 text-green-800 dark:text-green-200">
                                        Aktivan
                                    </span>
                                @elseif($listing->status === 'expired')
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-200 dark:bg-red-800 text-red-800 dark:text-red-200">
                                        Istekao
                                    </span>
                                @else
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200">
                                        {{ ucfirst($listing->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-300">
                                {{ $listing->pivot->created_at->format('d.m.Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('listings.show', $listing) }}"
                                        class="inline-flex items-center px-2 py-1 text-sky-600 dark:text-sky-400 hover:text-sky-800 dark:hover:text-sky-300 rounded">
                                        <i class="fas fa-eye mr-1"></i> Pregled
                                    </a>

                                    <button
                                        onclick="navigator.clipboard.writeText('{{ route('listings.show', $listing) }}'); alert('Link kopiran!')"
                                        class="inline-flex items-center px-2 py-1 text-orange-600 dark:text-orange-400 hover:text-orange-800 dark:hover:text-orange-300 rounded">
                                        <i class="fas fa-share-alt mr-1"></i> Podeli
                                    </button>

                                    <button wire:click="removeFromFavorites({{ $listing->id }})"
                                        wire:confirm="Da li ste sigurni da želite da uklonite ovaj oglas iz omiljenih?"
                                        class="inline-flex items-center px-2 py-1 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 rounded">
                                        <i class="fas fa-heart-broken mr-1"></i> Ukloni
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Desktop Paginacija -->
        <div class="hidden lg:block mt-6">
            {{ $favorites->links() }}
        </div>

        <!-- Mobile Card View -->
        <div class="lg:hidden space-y-4">
            @foreach ($favorites as $listing)
                <div class="bg-white dark:bg-slate-800 shadow rounded-lg overflow-hidden">
                    <!-- Card Header -->
                    <div class="p-4 border-b border-slate-200 dark:border-slate-600">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start flex-1">
                                <!-- Image -->
                                <div class="flex-shrink-0 h-16 w-16 mr-3">
                                    @if ($listing->images->count() > 0)
                                        <img class="h-16 w-16 rounded-lg object-cover"
                                            src="{{ $listing->images->first()->url }}" alt="{{ $listing->title }}">
                                    @else
                                        <div class="h-16 w-16 rounded-lg bg-slate-200 flex items-center justify-center">
                                            <i class="fas fa-image text-slate-400"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Listing Info -->
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-1">
                                        {{ $listing->title }}</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-300 mb-1">
                                        {{ $listing->category->name }}</p>
                                    <p class="text-xs text-slate-400 mb-2">{{ $listing->location }}</p>
                                    <p class="text-xl font-bold text-sky-600 dark:text-sky-400">
                                        {{ number_format($listing->price, 2) }} RSD</p>
                                </div>
                            </div>

                            <!-- Status Badge -->
                            @if ($listing->status === 'active')
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-200 dark:bg-green-800 text-green-800 dark:text-green-200">
                                    Aktivan
                                </span>
                            @elseif($listing->status === 'expired')
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-200 dark:bg-red-800 text-red-800 dark:text-red-200">
                                    Istekao
                                </span>
                            @else
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200">
                                    {{ ucfirst($listing->status) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-4">
                        <!-- Date Added -->
                        <div class="mb-4">
                            <div
                                class="text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider mb-1">
                                Dodato u omiljene</div>
                            <div class="text-sm text-slate-900 dark:text-slate-100">
                                {{ $listing->pivot->created_at->format('d.m.Y H:i') }}</div>
                        </div>

                        @if ($listing->user)
                            <!-- Seller Info -->
                            <div class="mb-4">
                                <div
                                    class="text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider mb-1">
                                    Prodavac</div>
                                <div class="text-sm text-slate-900 dark:text-slate-100">
                                    {{ $listing->user->name }}
                                    @if ($listing->user->is_banned)
                                        <span class="text-red-600 dark:text-red-400 text-xs ml-1">BLOKIRAN</span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('listings.show', $listing) }}"
                                class="inline-flex items-center px-3 py-1.5 bg-sky-100 text-sky-700 text-xs font-medium rounded-lg hover:bg-sky-200 transition-colors">
                                <i class="fas fa-eye mr-1"></i>
                                Pregled
                            </a>

                            <button
                                onclick="navigator.clipboard.writeText('{{ route('listings.show', $listing) }}'); alert('Link kopiran!')"
                                class="inline-flex items-center px-3 py-1.5 bg-orange-100 text-orange-700 text-xs font-medium rounded-lg hover:bg-orange-200 transition-colors">
                                <i class="fas fa-share-alt mr-1"></i>
                                Podeli
                            </button>

                            <button wire:click="removeFromFavorites({{ $listing->id }})"
                                wire:confirm="Da li ste sigurni da želite da uklonite ovaj oglas iz omiljenih?"
                                class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 text-xs font-medium rounded-lg hover:bg-red-200 transition-colors">
                                <i class="fas fa-heart-broken mr-1"></i>
                                Ukloni
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Mobile Pagination -->
        <div class="lg:hidden mt-6">
            {{ $favorites->links() }}
        </div>
    @else
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md p-8 text-center">
            <i class="fas fa-heart text-slate-400 text-5xl mb-4"></i>
            <h3 class="text-xl font-semibold text-slate-800 mb-2">Nemate omiljene oglase</h3>
            <p class="text-slate-600 dark:text-slate-300 mb-4">Počnite da čuvate oglase koje volite klikom na srce.</p>
            <a href="{{ route('home') }}"
                class="px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition-colors">
                Pretraži oglase
            </a>
        </div>
    @endif
</div>
