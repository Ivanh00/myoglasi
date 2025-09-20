<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    <!-- Filteri i sortiranje -->
    <div class="bg-green-50 dark:bg-slate-700 rounded-lg shadow-md p-4 mb-6">
        <!-- Results Info (Top - Left aligned) -->
        <div class="text-slate-600 dark:text-slate-300 mb-4">
            Pronađeno poklona: <span class="font-semibold">{{ $giveaways->total() }}</span>
            @if ($selectedCategory)
                @php $selectedCat = $categories->firstWhere('id', $selectedCategory); @endphp
                @if ($selectedCat)
                    u kategoriji: <span class="font-semibold">{{ $selectedCat->name }}</span>
                @endif
            @endif
        </div>

        <!-- Filter Controls -->
        <div class="flex items-center justify-between gap-4">
            <!-- Left: Category filter -->
            <div class="flex items-center gap-3">
                <div class="w-60" x-data="{ open: false }" x-init="open = false">
                    <div class="relative">
                        <button @click="open = !open" type="button"
                            class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-green-500 transition-colors flex items-center justify-between">
                            <span>
                                @if ($selectedCategory)
                                    @php $selectedCat = $categories->firstWhere('id', $selectedCategory); @endphp
                                    {{ $selectedCat ? $selectedCat->name : 'Sve kategorije' }}
                                @else
                                    Sve kategorije
                                @endif
                            </span>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                            <button @click="$wire.setCategory(''); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg {{ !$selectedCategory ? 'bg-green-50 dark:bg-slate-600 text-green-700 dark:text-slate-200' : 'text-slate-700 dark:text-slate-200' }}">
                                Sve kategorije
                            </button>
                            @foreach ($categories as $category)
                                <button @click="$wire.setCategory('{{ $category->id }}'); open = false" type="button"
                                    class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-600 flex items-center {{ $selectedCategory == $category->id ? 'bg-green-50 dark:bg-slate-600 text-green-700 dark:text-slate-200' : 'text-slate-700 dark:text-slate-200' }}">
                                    @if ($category->icon)
                                        <i class="{{ $category->icon }} text-green-600 mr-2"></i>
                                    @endif
                                    {{ $category->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: View Mode Toggle -->
            <div class="flex bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm">
                <button wire:click="setViewMode('list')"
                    class="px-3 py-2 {{ $viewMode === 'list' ? 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600' }} rounded-l-lg transition-colors">
                    <i class="fas fa-list"></i>
                </button>
                <button wire:click="setViewMode('grid')"
                    class="px-3 py-2 {{ $viewMode === 'grid' ? 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600' }} rounded-r-lg transition-colors">
                    <i class="fas fa-th"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Lista/Grid poklonja -->
    @if ($giveaways->count() > 0)
        @if($viewMode === 'list')
            <!-- List View -->
            <div class="space-y-4 mb-8">
                @foreach ($giveaways as $giveaway)
                <div
                    class="bg-white dark:bg-slate-700 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border-l-4 border-green-500">
                    <div class="flex flex-col md:flex-row">
                        <!-- Slika poklonja -->
                        <div class="w-full md:w-48 md:min-w-48 h-48">
                            @if ($giveaway->images->count() > 0)
                                <img src="{{ $giveaway->images->first()->url }}" alt="{{ $giveaway->title }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-green-100 dark:bg-green-900 flex items-center justify-center">
                                    <i class="fas fa-gift text-green-500 dark:text-green-400 text-3xl"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Informacije o poklonju -->
                        <div class="flex-1 p-4 md:p-6">
                            <div class="flex flex-col h-full">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <h3
                                            class="text-lg font-semibold text-slate-900 dark:text-slate-100 hover:text-green-600 transition-colors">
                                            {{ $giveaway->title }}
                                        </h3>
                                        <span
                                            class="ml-2 bg-green-200 dark:bg-green-800 text-green-800 dark:text-green-200 text-xs px-2 py-1 rounded-full font-medium">
                                            BESPLATNO
                                        </span>
                                    </div>

                                    <!-- Davalac -->
                                    @auth
                                        <p class="text-sm font-bold text-slate-700 dark:text-slate-200 mb-1">
                                            Poklanja: {{ $giveaway->user->name ?? 'Nepoznat korisnik' }}
                                            @if ($giveaway->user)
                                                {!! $giveaway->user->verified_icon !!}
                                            @endif
                                            @if ($giveaway->user && $giveaway->user->is_banned)
                                                <span class="text-red-600 dark:text-red-400 font-bold ml-2">BLOKIRAN</span>
                                            @endif
                                            @if ($giveaway->user && $giveaway->user->shouldShowLastSeen())
                                                <span class="text-xs text-slate-500 dark:text-slate-300 ml-2">
                                                    @if ($giveaway->user->is_online)
                                                        <span class="inline-flex items-center">
                                                            <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                                            {{ $giveaway->user->last_seen }}
                                                        </span>
                                                    @else
                                                        {{ $giveaway->user->last_seen }}
                                                    @endif
                                                </span>
                                            @endif
                                        </p>
                                    @endauth

                                    <div class="flex items-center text-sm text-slate-600 dark:text-slate-400 mb-2">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        <span>{{ $giveaway->location }}</span>
                                        <span class="mx-2">•</span>
                                        <i class="fas fa-folder mr-1"></i>
                                        <span>{{ $giveaway->category->name }}</span>
                                        @if ($giveaway->condition)
                                            <span class="mx-2">•</span>
                                            <span class="px-2 py-1 bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-200 text-xs rounded-full">
                                                {{ $giveaway->condition->name }}
                                            </span>
                                        @endif
                                    </div>

                                    <p class="text-slate-700 dark:text-slate-200 mb-3"
                                        style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ Str::limit(strip_tags($giveaway->description), 120) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Desna strana - akcije -->
                        <div
                            class="md:w-48 md:min-w-48 p-4 border-t md:border-t-0 md:border-l border-slate-200 dark:border-slate-600 bg-green-50 dark:bg-slate-600">
                            <div class="flex flex-col h-full justify-between">
                                <div class="flex items-center justify-between text-sm text-slate-500 dark:text-slate-300 mb-4">
                                    <div class="flex items-center">
                                        <i class="fas fa-eye mr-1"></i>
                                        <span>{{ $giveaway->views ?? 0 }}</span>
                                    </div>
                                </div>

                                <div class="text-xs text-slate-700 dark:text-slate-200 mb-4">
                                    <i class="fas fa-clock mr-1"></i>
                                    Postavljeno pre {{ floor($giveaway->created_at->diffInDays()) }} dana
                                </div>

                                <div class="space-y-2">
                                    @auth
                                        @if (auth()->id() !== $giveaway->user_id)
                                            <button wire:click="markAsTaken({{ $giveaway->id }})"
                                                wire:confirm="Da li ste sigurni da ste uzeli ovaj poklon?"
                                                class="block w-full text-center px-3 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors text-sm">
                                                <i class="fas fa-hand-paper mr-2"></i> Označi kao uzeto
                                            </button>
                                        @endif
                                    @endauth

                                    <a href="{{ route('giveaways.show', $giveaway) }}"
                                        class="block w-full text-center px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                                        <i class="fas fa-gift mr-2"></i> Pregled
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <!-- Grid View -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                @foreach ($giveaways as $giveaway)
                    <div class="bg-white dark:bg-slate-700 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border-t-4 border-green-500 flex flex-col h-full">
                        <!-- Slika poklonja -->
                        <div class="w-full h-48">
                            @if ($giveaway->images->count() > 0)
                                <img src="{{ $giveaway->images->first()->url }}" alt="{{ $giveaway->title }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-green-100 dark:bg-green-900 flex items-center justify-center">
                                    <i class="fas fa-gift text-green-500 dark:text-green-400 text-4xl"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Informacije o poklonju -->
                        <div class="p-4 flex flex-col flex-1">
                            <!-- Main content -->
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 hover:text-green-600 transition-colors mb-2">
                                    {{ $giveaway->title }}
                                </h3>

                                <p class="text-sm text-slate-600 dark:text-slate-300 mb-3 line-clamp-2">
                                    {{ Str::limit($giveaway->description, 100) }}
                                </p>

                                <!-- Kategorija -->
                                <div class="flex items-center text-xs text-green-600 dark:text-green-400 mb-3">
                                    @if ($giveaway->category->icon)
                                        <i class="{{ $giveaway->category->icon }} mr-1"></i>
                                    @endif
                                    {{ $giveaway->category->name }}
                                </div>

                                <!-- Korisnik i vreme -->
                                <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 mb-3">
                                    <div>
                                        <i class="fas fa-user mr-1"></i>
                                        {{ $giveaway->user->name }}
                                    </div>
                                    <div>
                                        <i class="fas fa-clock mr-1"></i>
                                        Pre {{ floor($giveaway->created_at->diffInDays()) }} dana
                                    </div>
                                </div>
                            </div>

                            <!-- Dugmići - Always at bottom -->
                            <div class="space-y-2 mt-auto">
                                @auth
                                    @if (auth()->id() !== $giveaway->user_id)
                                        <button wire:click="markAsTaken({{ $giveaway->id }})"
                                            wire:confirm="Da li ste sigurni da ste uzeli ovaj poklon?"
                                            class="block w-full text-center px-3 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors text-sm">
                                            <i class="fas fa-hand-paper mr-2"></i> Označi kao uzeto
                                        </button>
                                    @endif
                                @endauth

                                <a href="{{ route('giveaways.show', $giveaway) }}"
                                    class="block w-full text-center px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                                    <i class="fas fa-gift mr-2"></i> Pregled
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Paginacija -->
        @if ($giveaways->hasPages())
            <div class="mt-8 bg-white dark:bg-slate-700 rounded-lg shadow-sm p-4">
                {{ $giveaways->links() }}
            </div>
        @endif
    @else
        <div class="bg-white dark:bg-slate-700 rounded-lg shadow-md p-8 text-center">
            <i class="fas fa-gift text-green-400 text-5xl mb-4"></i>
            <h3 class="text-xl font-semibold text-slate-800 mb-2">Nema poklona</h3>
            <p class="text-slate-600 dark:text-slate-400 mb-4">
                @if ($selectedCategory)
                    Trenutno nema poklona u ovoj kategoriji.
                @else
                    Trenutno nema poklona.
                @endif
            </p>
            <p class="text-sm text-slate-500 dark:text-slate-300">Budite prvi koji će pokloniti nešto!</p>
        </div>
    @endif
</div>
