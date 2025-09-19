<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    <!-- Filteri i sortiranje -->
    <div class="bg-green-50 dark:bg-slate-700 rounded-lg shadow-md p-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <!-- Left: Category filter -->
            <div class="flex items-center gap-3">
                <div class="w-60" x-data="{ open: false }" x-init="open = false">
                    <div class="relative">
                        <button @click="open = !open" type="button"
                            class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-green-500 transition-colors flex items-center justify-between">
                            <span>
                                @if($selectedCategory)
                                    @php $selectedCat = $categories->firstWhere('id', $selectedCategory); @endphp
                                    {{ $selectedCat ? $selectedCat->name : 'Sve kategorije' }}
                                @else
                                    Sve kategorije
                                @endif
                            </span>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
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
                                    @if($category->icon)
                                        <i class="{{ $category->icon }} text-green-600 mr-2"></i>
                                    @endif
                                    {{ $category->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Results count -->
            <div class="text-slate-600">
                Pronađeno poklona: <span class="font-semibold">{{ $giveaways->total() }}</span>
            </div>
        </div>
    </div>

    <!-- Lista poklonja -->
    @if ($giveaways->count() > 0)
        <div class="space-y-4 mb-8">
            @foreach ($giveaways as $giveaway)
                <div class="bg-white dark:bg-slate-700 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border-l-4 border-green-500">
                    <div class="flex flex-col md:flex-row">
                        <!-- Slika poklonja -->
                        <div class="w-full md:w-48 md:min-w-48 h-48">
                            @if ($giveaway->images->count() > 0)
                                <img src="{{ $giveaway->images->first()->url }}" alt="{{ $giveaway->title }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-green-100 flex items-center justify-center">
                                    <i class="fas fa-gift text-green-500 text-3xl"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Informacije o poklonju -->
                        <div class="flex-1 p-4 md:p-6">
                            <div class="flex flex-col h-full">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 hover:text-green-600 transition-colors">
                                            {{ $giveaway->title }}
                                        </h3>
                                        <span class="ml-2 bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-medium">
                                            BESPLATNO
                                        </span>
                                    </div>

                                    <!-- Davalac -->
                                    @auth
                                        <p class="text-sm font-bold text-slate-700 dark:text-slate-200 mb-1">
                                            Poklanja: {{ $giveaway->user->name ?? 'Nepoznat korisnik' }}
                                            @if($giveaway->user){!! $giveaway->user->verified_icon !!}@endif
                                            @if ($giveaway->user && $giveaway->user->is_banned)
                                                <span class="text-red-600 dark:text-red-400 font-bold ml-2">BLOKIRAN</span>
                                            @endif
                                            @if($giveaway->user && $giveaway->user->shouldShowLastSeen())
                                                <span class="text-xs text-slate-500 ml-2">
                                                    @if($giveaway->user->is_online)
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

                                    <div class="flex items-center text-sm text-slate-600 mb-2">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        <span>{{ $giveaway->location }}</span>
                                        <span class="mx-2">•</span>
                                        <i class="fas fa-folder mr-1"></i>
                                        <span>{{ $giveaway->category->name }}</span>
                                        @if($giveaway->condition)
                                            <span class="mx-2">•</span>
                                            <span class="px-2 py-1 bg-slate-100 text-slate-800 text-xs rounded-full">
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
                        <div class="md:w-48 md:min-w-48 p-4 border-t md:border-t-0 md:border-l border-slate-200 dark:border-slate-600 bg-green-50 dark:bg-slate-600">
                            <div class="flex flex-col h-full justify-between">
                                <div class="flex items-center justify-between text-sm text-slate-500 mb-4">
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
                                    <a href="{{ route('giveaways.show', $giveaway) }}"
                                        class="block w-full text-center px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                                        <i class="fas fa-gift mr-2"></i> Pregled
                                    </a>
                                    
                                    @auth
                                        @if(auth()->id() !== $giveaway->user_id)
                                            <button wire:click="markAsTaken({{ $giveaway->id }})"
                                                wire:confirm="Da li ste sigurni da ste uzeli ovaj poklon?"
                                                class="block w-full text-center px-3 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors text-sm">
                                                <i class="fas fa-hand-paper mr-2"></i> Uzeto!
                                            </button>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginacija -->
        @if($giveaways->hasPages())
            <div class="mt-8 bg-white dark:bg-slate-700 rounded-lg shadow-sm p-4">
                {{ $giveaways->links() }}
            </div>
        @endif
    @else
        <div class="bg-white dark:bg-slate-700 rounded-lg shadow-md p-8 text-center">
            <i class="fas fa-gift text-green-400 text-5xl mb-4"></i>
            <h3 class="text-xl font-semibold text-slate-800 mb-2">Nema poklona</h3>
            <p class="text-slate-600 mb-4">
                @if ($selectedCategory)
                    Trenutno nema poklona u ovoj kategoriji.
                @else
                    Trenutno nema poklona.
                @endif
            </p>
            <p class="text-sm text-slate-500">Budite prvi koji će pokloniti nešto!</p>
        </div>
    @endif
</div>