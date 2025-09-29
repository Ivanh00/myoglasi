<div class="max-w-7xl mx-auto py-6 px-1 sm:px-6 lg:px-8">
    <!-- Header with background -->
    <div class="bg-slate-200 dark:bg-slate-700 rounded-t-lg px-6 py-4 mb-6">
        <div class="flex justify-between items-center">
            <h1 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Moje usluge</h1>
            <a href="{{ route('services.create') }}"
                class="inline-flex items-center px-4 py-2 bg-slate-600 text-white rounded-lg hover:bg-slate-700 transition-colors text-sm">
                <i class="fas fa-plus mr-2"></i> Dodaj novu uslugu
            </a>
        </div>
    </div>

    <!-- Search and filters -->
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex-1">
                <input type="text" wire:model.live="search" placeholder="Pretraži usluge..."
                    class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
            </div>
            <div class="flex gap-2">
                <div class="w-48" x-data="{ open: false }" x-init="open = false">
                    <div class="relative">
                        <button @click="open = !open" type="button"
                            class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm text-slate-900 dark:text-slate-100 text-sm text-left hover:border-slate-400 dark:hover:border-slate-500 focus:outline-none focus:border-sky-500 dark:focus:border-sky-400 transition-colors flex items-center justify-between">
                            <span>
                                @switch($status)
                                    @case('active')
                                        Aktivne
                                    @break

                                    @case('inactive')
                                        Neaktivne
                                    @break

                                    @default
                                        Sve
                                @endswitch
                            </span>
                            <svg class="w-4 h-4 text-slate-500 dark:text-slate-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-lg">
                            <button @click="$wire.set('status', 'all'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg {{ $status === 'all' ? 'bg-sky-50 dark:bg-slate-600 text-sky-700 dark:text-sky-300' : '' }}">
                                Sve
                            </button>
                            <button @click="$wire.set('status', 'active'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 {{ $status === 'active' ? 'bg-sky-50 dark:bg-slate-600 text-sky-700 dark:text-sky-300' : '' }}">
                                Aktivne
                            </button>
                            <button @click="$wire.set('status', 'inactive'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-b-lg {{ $status === 'inactive' ? 'bg-sky-50 dark:bg-slate-600 text-sky-700 dark:text-sky-300' : '' }}">
                                Neaktivne
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Services list -->
    @if ($services->count() > 0)
        <!-- Desktop Table View -->
        <div class="hidden lg:block space-y-1">
            <!-- Header -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow overflow-hidden">
                <div class="grid grid-cols-[35%_20%_15%_15%_15%] bg-slate-50 dark:bg-slate-700">
                    <div
                        class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                        Usluga</div>
                    <div
                        class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                        Cena</div>
                    <div
                        class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                        Status</div>
                    <div
                        class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                        Pregledi</div>
                    <div
                        class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                        Datum</div>
                </div>
            </div>

            <!-- Data Rows -->
            @foreach ($services as $service)
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow overflow-hidden border-l-4 border-slate-500">
                    <div class="grid grid-cols-[35%_20%_15%_15%_15%] hover:bg-slate-50 dark:hover:bg-slate-700">
                        <!-- Usluga Column -->
                        <div class="px-6 py-2">
                            <div class="flex items-center">
                                @if ($service->images->count() > 0)
                                    <img src="{{ $service->images->first()->url }}" alt="{{ $service->title }}"
                                        class="w-10 h-10 rounded-lg object-cover mr-3">
                                @else
                                    <div
                                        class="w-10 h-10 bg-slate-200 dark:bg-slate-700 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-tools text-slate-400"></i>
                                    </div>
                                @endif
                                <div>
                                    <div
                                        class="text-sm font-medium text-slate-900 dark:text-slate-100 flex items-center">
                                        <span>{{ Str::limit($service->title, 50) }}</span>
                                        <!-- Promotion Badges -->
                                        @if ($service->hasActivePromotion())
                                            @foreach ($service->getPromotionBadges() as $badge)
                                                <span
                                                    class="ml-2 px-1 py-0.5 text-xs font-bold rounded-full {{ $badge['class'] }}">
                                                    {{ $badge['text'] }}
                                                </span>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="text-sm text-slate-500 dark:text-slate-300">
                                        {{ $service->category->name ?? 'Bez kategorije' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Cena Column -->
                        <div class="px-6 py-2">
                            <div class="text-sm text-slate-900 dark:text-slate-100 whitespace-nowrap">
                                {{ number_format($service->price, 2) }} RSD
                            </div>
                        </div>
                        <!-- Status Column -->
                        <div class="px-6 py-2">
                            @if ($service->status === 'active')
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-200 dark:bg-green-800 text-green-800 dark:text-green-200">
                                    Aktivna
                                </span>
                            @else
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-200 dark:bg-red-800 text-red-800 dark:text-red-200">
                                    Neaktivna
                                </span>
                            @endif
                        </div>
                        <!-- Pregledi Column -->
                        <div class="px-6 py-2 text-sm text-slate-500 dark:text-slate-300 whitespace-nowrap">
                            {{ $service->views ?? 0 }}
                        </div>
                        <!-- Datum Column -->
                        <div class="px-6 py-2 text-sm text-slate-500 dark:text-slate-300 whitespace-nowrap">
                            {{ $service->created_at->format('d.m.Y') }}
                        </div>
                    </div>
                    <!-- Actions Row -->
                    <div class="border-t border-slate-200 dark:border-slate-600 px-6 py-2 bg-slate-50 dark:bg-slate-700/50">
                        <div class="flex flex-wrap gap-2">
                                <a href="{{ route('services.show', $service->slug) }}"
                                    class="inline-flex items-center px-2 py-1 text-sky-600 dark:text-sky-400 hover:text-sky-800 dark:hover:text-sky-300 rounded">
                                    <i class="fas fa-eye mr-1"></i> Pregled
                                </a>

                                <a href="{{ route('services.edit', $service->slug) }}"
                                    class="inline-flex items-center px-2 py-1 text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 rounded">
                                    <i class="fas fa-edit mr-1"></i> Izmeni
                                </a>

                                <button wire:click="toggleStatus({{ $service->id }})"
                                    class="inline-flex items-center px-2 py-1 text-amber-600 dark:text-amber-400 hover:text-amber-800 dark:hover:text-amber-300 rounded">
                                    @if ($service->status === 'active')
                                        <i class="fas fa-pause mr-1"></i> Pauziraj
                                    @else
                                        <i class="fas fa-play mr-1"></i> Aktiviraj
                                    @endif
                                </button>

                                @if ($service->status === 'active')
                                    <button
                                        wire:click="$dispatch('openServicePromotionModal', { serviceId: {{ $service->id }} })"
                                        class="inline-flex items-center px-2 py-1 {{ $service->hasActivePromotion() ? 'text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300' : 'text-amber-600 dark:text-amber-400 hover:text-amber-800 dark:hover:text-amber-300' }} rounded">
                                        <i class="fas fa-bullhorn mr-1"></i> Promocija
                                    </button>
                                @endif

                                <button x-data
                                    @click="$dispatch('open-delete-modal', { serviceId: {{ $service->id }} })"
                                    class="inline-flex items-center px-2 py-1 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 rounded">
                                    <i class="fas fa-trash mr-1"></i> Obriši
                                </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Desktop Pagination -->
        <div class="hidden lg:block mt-4">
            {{ $services->links() }}
        </div>

        <!-- Mobile Card View -->
        <div class="lg:hidden space-y-4">
            @foreach ($services as $service)
                <div class="bg-white dark:bg-slate-800 border-l-4 border-slate-500 shadow rounded-lg overflow-hidden">
                    <!-- Card Header -->
                    <div class="p-4 border-b border-slate-200 dark:border-slate-600">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start flex-1">
                                <!-- Image -->
                                <div class="flex-shrink-0 h-16 w-16 mr-3">
                                    @if ($service->images->count() > 0)
                                        <img class="h-16 w-16 rounded-lg object-cover"
                                            src="{{ $service->images->first()->url }}" alt="{{ $service->title }}">
                                    @else
                                        <div
                                            class="h-16 w-16 rounded-lg bg-slate-200 dark:bg-slate-700 flex items-center justify-center">
                                            <i class="fas fa-tools text-slate-400"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Service Info -->
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-1">
                                        {{ $service->title }}
                                        <!-- Promotion Badges -->
                                        @if ($service->hasActivePromotion())
                                            <div class="inline-flex flex-wrap gap-1 ml-2">
                                                @foreach ($service->getPromotionBadges() as $badge)
                                                    <span
                                                        class="px-1 py-0.5 text-xs font-bold rounded-full {{ $badge['class'] }}">
                                                        {{ $badge['text'] }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-300 mb-2">
                                        {{ $service->category->name ?? 'Bez kategorije' }}</p>
                                    <p class="text-xl font-bold text-sky-600 dark:text-sky-400">
                                        {{ number_format($service->price, 2) }} RSD</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-4">
                        <!-- Status Section -->
                        <div class="mb-4">
                            <div
                                class="text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider mb-2">
                                Status usluge</div>
                            <div class="flex items-center space-x-4">
                                @if ($service->status === 'active')
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-200 dark:bg-green-800 text-green-800 dark:text-green-200">
                                        Aktivna
                                    </span>
                                @else
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-200 dark:bg-red-800 text-red-800 dark:text-red-200">
                                        Neaktivna
                                    </span>
                                @endif
                                <span class="text-sm text-slate-500 dark:text-slate-300">
                                    <i class="fas fa-eye mr-1"></i>{{ $service->views ?? 0 }} pregleda
                                </span>
                            </div>
                        </div>

                        <!-- Date Info -->
                        <div class="mb-4">
                            <div
                                class="text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider mb-2">
                                Informacije o datumu</div>
                            <div class="text-sm text-slate-900 dark:text-slate-100">
                                Kreirana: {{ $service->created_at->format('d.m.Y') }}
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('services.show', $service->slug) }}"
                                class="inline-flex items-center px-3 py-1.5 bg-sky-100 text-sky-700 text-xs font-medium rounded-lg hover:bg-sky-200 transition-colors">
                                <i class="fas fa-eye mr-1"></i>
                                Pregled
                            </a>

                            <a href="{{ route('services.edit', $service->slug) }}"
                                class="inline-flex items-center px-3 py-1.5 bg-indigo-100 text-indigo-700 text-xs font-medium rounded-lg hover:bg-indigo-200 transition-colors">
                                <i class="fas fa-edit mr-1"></i>
                                Izmeni
                            </a>

                            <button wire:click="toggleStatus({{ $service->id }})"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg transition-colors
                                    @if ($service->status === 'active') bg-amber-100 dark:bg-amber-800 text-amber-700 dark:text-amber-200 hover:bg-amber-200 dark:hover:bg-amber-700
                                    @else
                                        bg-green-100 dark:bg-green-800 text-green-700 dark:text-green-200 hover:bg-green-200 dark:hover:bg-green-700 @endif">
                                @if ($service->status === 'active')
                                    <i class="fas fa-pause mr-1"></i>
                                    Pauziraj
                                @else
                                    <i class="fas fa-play mr-1"></i>
                                    Aktiviraj
                                @endif
                            </button>

                            @if ($service->status === 'active')
                                <button
                                    wire:click="$dispatch('openServicePromotionModal', { serviceId: {{ $service->id }} })"
                                    class="inline-flex items-center px-3 py-1.5 bg-green-100 dark:bg-green-800 text-green-700 dark:text-green-200 text-xs font-medium rounded-lg hover:bg-green-200 dark:hover:bg-green-700 transition-colors">
                                    <i class="fas fa-bullhorn mr-1"></i>
                                    Promocija
                                </button>
                            @endif

                            <button x-data @click="$dispatch('open-delete-modal', { serviceId: {{ $service->id }} })"
                                class="inline-flex items-center px-3 py-1.5 bg-red-100 dark:bg-red-800 text-red-700 dark:text-red-200 text-xs font-medium rounded-lg hover:bg-red-200 dark:hover:bg-red-700 transition-colors">
                                <i class="fas fa-trash mr-1"></i>
                                Obriši
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Mobile Pagination -->
        <div class="lg:hidden mt-6">
            {{ $services->links() }}
        </div>
    @else
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-8 text-center">
            <i class="fas fa-tools text-slate-400 text-5xl mb-4"></i>
            <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-200 mb-2">Nemate usluga</h3>
            <p class="text-slate-600 dark:text-slate-400 mb-4">
                @if ($search)
                    Nema rezultata za vašu pretragu.
                @else
                    Još uvek nemate postavljenih usluga.
                @endif
            </p>
            @if (!$search)
                <a href="{{ route('services.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i> Dodaj prvu uslugu
                </a>
            @endif
        </div>
    @endif

    <!-- Service Promotion Manager Modal -->
    @livewire('services.promotion-manager')

    <!-- Delete Service Modal -->
    <div x-data="{
        showDeleteModal: false,
        selectedService: null,
        deleteService() {
            if (this.selectedService) {
                @this.deleteService(this.selectedService.id);
                this.showDeleteModal = false;
            }
        }
    }"
        @open-delete-modal.window="
            showDeleteModal = true;
            selectedService = $services.find(s => s.id === $event.detail.serviceId);
        "
        x-show="showDeleteModal" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">

        <!-- Background overlay -->
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="showDeleteModal = false"></div>

        <!-- Modal content -->
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative inline-block align-bottom bg-white dark:bg-slate-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                <!-- Modal header with delete icon -->
                <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-1">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-white bg-opacity-20">
                                <i class="fas fa-trash text-white text-xl"></i>
                            </div>
                            <h3 class="ml-3 text-xl font-bold text-white">Brisanje usluge</h3>
                        </div>
                        <button @click="showDeleteModal = false" class="text-white hover:text-slate-200">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal body -->
                <div class="px-6 py-5">
                    <!-- Warning message -->
                    <div class="mb-4">
                        <h4 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">
                            Da li ste sigurni?
                        </h4>
                        <p class="text-slate-600 dark:text-slate-400">
                            Ova usluga će biti trajno obrisana. Ova akcija se ne može poništiti.
                        </p>
                    </div>

                    <!-- Service info -->
                    <template x-if="selectedService">
                        <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-4 mb-4">
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-slate-600 dark:text-slate-300">Naziv:</span>
                                    <span class="text-sm font-medium text-slate-900 dark:text-slate-100"
                                        x-text="selectedService?.title || 'N/A'"></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-slate-600 dark:text-slate-300">Kategorija:</span>
                                    <span class="text-sm font-medium text-slate-900 dark:text-slate-100"
                                        x-text="selectedService?.category?.name || 'Bez kategorije'"></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-slate-600 dark:text-slate-300">Cena:</span>
                                    <span class="text-sm font-medium text-slate-900 dark:text-slate-100">
                                        <span
                                            x-text="new Intl.NumberFormat('sr-RS').format(selectedService?.price || 0)"></span>
                                        RSD
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-slate-600 dark:text-slate-300">Status:</span>
                                    <span class="text-sm font-medium"
                                        :class="{
                                            'text-green-600 dark:text-green-400': selectedService
                                                ?.status === 'active',
                                            'text-slate-600 dark:text-slate-400': selectedService
                                                ?.status !== 'active'
                                        }"
                                        x-text="selectedService?.status === 'active' ? 'Aktivna' : 'Neaktivna'"></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-slate-600 dark:text-slate-300">Pregledi:</span>
                                    <span class="text-sm font-medium text-slate-900 dark:text-slate-100"
                                        x-text="selectedService?.views || 0"></span>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Warning notice -->
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-lg p-3">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-800 dark:text-red-200">
                                    <strong>Upozorenje:</strong> Brisanjem usluge gubite sve podatke vezane za nju,
                                    uključujući slike i statistiku pregleda.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal footer with actions -->
                <div class="bg-slate-50 dark:bg-slate-700/50 px-6 py-1">
                    <div class="flex space-x-3">
                        <button type="button" @click="showDeleteModal = false"
                            class="flex-1 px-4 py-2.5 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-medium rounded-lg hover:bg-slate-100 dark:hover:bg-slate-600 transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            Otkaži
                        </button>
                        <button type="button" @click="deleteService()"
                            class="flex-1 px-4 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white font-medium rounded-lg hover:from-red-700 hover:to-red-800 transition-all transform hover:scale-105">
                            <i class="fas fa-trash mr-2"></i>
                            Obriši uslugu
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.$services = @json($services->items());
    </script>
</div>
