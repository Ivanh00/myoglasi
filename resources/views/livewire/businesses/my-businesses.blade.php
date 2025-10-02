<div class="max-w-7xl mx-auto py-6 px-1 sm:px-6 lg:px-8">
    <!-- Header with background -->
    <div class="bg-purple-100 dark:bg-purple-900/50 rounded-t-lg px-4 py-4 mb-6">
        <div class="flex justify-between items-center">
            <h1 class="text-xl font-semibold text-purple-900 dark:text-purple-100">Moji Business</h1>
            <a href="{{ route('businesses.create') }}"
                class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors text-sm">
                <i class="fas fa-plus mr-2"></i> Dodaj novi biznis
            </a>
        </div>
    </div>

    <!-- Filter -->
    <div class="flex justify-end mb-6">
        <div class="flex items-center space-x-4">
            <label class="text-sm font-medium text-slate-700 dark:text-slate-200">Prikaži:</label>
            <div class="w-60" x-data="{ open: false }" x-init="open = false">
                <div class="relative">
                    <button @click="open = !open" type="button"
                        class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 dark:hover:border-slate-500 focus:outline-none focus:border-purple-500 dark:focus:border-purple-400 transition-colors flex items-center justify-between">
                        <span>
                            @switch($filter)
                                @case('active')
                                    Aktivne business
                                @break

                                @case('expired')
                                    Istekle business
                                @break

                                @default
                                    Sve business
                            @endswitch
                        </span>
                        <svg class="w-4 h-4 text-slate-500 dark:text-slate-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open = false" x-transition
                        class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-lg">
                        <button @click="$wire.set('filter', 'all'); open = false" type="button"
                            class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg {{ $filter === 'all' ? 'bg-purple-50 dark:bg-slate-600 text-purple-700 dark:text-purple-300' : '' }}">
                            Sve business
                        </button>
                        <button @click="$wire.set('filter', 'active'); open = false" type="button"
                            class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 {{ $filter === 'active' ? 'bg-purple-50 dark:bg-slate-600 text-purple-700 dark:text-purple-300' : '' }}">
                            Aktivne business
                        </button>
                        <button @click="$wire.set('filter', 'expired'); open = false" type="button"
                            class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-b-lg {{ $filter === 'expired' ? 'bg-purple-50 dark:bg-slate-600 text-purple-700 dark:text-purple-300' : '' }}">
                            Istekle business
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Tabela business -->
    @if ($businesses->count() > 0)
        <div class="hidden lg:block space-y-1">
            <!-- Header -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md overflow-hidden">
                <div class="grid grid-cols-[35%_20%_20%_25%] bg-slate-50 dark:bg-slate-700">
                    <div
                        class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                        Business</div>
                    <div
                        class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                        Kategorija</div>
                    <div
                        class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                        Status</div>
                    <div
                        class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                        Datum</div>
                </div>
            </div>

            <!-- Data Rows -->
            @foreach ($businesses as $business)
                <div
                    class="bg-white dark:bg-slate-800 rounded-lg shadow-md overflow-hidden border-l-4 border-purple-500">
                    <div class="grid grid-cols-[35%_20%_20%_25%] hover:bg-slate-50 dark:hover:bg-slate-700">
                        <!-- Business Column -->
                        <div class="px-4 py-2">
                            <div class="flex items-center">
                                <div class="flex-shrink-0" style="width: 80px; height: 60px;">
                                    @if ($business->logo)
                                        <div class="w-full h-full flex items-center justify-start">
                                            <img class="max-w-full max-h-full rounded-lg object-contain"
                                                src="{{ Storage::url($business->logo) }}" alt="{{ $business->name }}">
                                        </div>
                                    @else
                                        <div
                                            class="w-full h-full rounded-lg bg-purple-100 dark:bg-purple-900 flex items-center justify-center">
                                            <i
                                                class="fas fa-briefcase text-purple-600 dark:text-purple-400 text-2xl"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-slate-900 dark:text-slate-100 break-words">
                                        {{ Str::limit($business->name, 40) }}</div>
                                    <div class="text-sm text-slate-500 dark:text-slate-300">
                                        <i class="fas fa-map-marker-alt mr-1"></i>{{ $business->location }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Kategorija Column -->
                        <div class="px-4 py-2">
                            @if ($business->category)
                                <div class="text-sm text-slate-900 dark:text-slate-100">
                                    {{ $business->category->name }}
                                </div>
                                @if ($business->subcategory)
                                    <div class="text-xs text-slate-500 dark:text-slate-400">
                                        {{ $business->subcategory->name }}
                                    </div>
                                @endif
                            @endif
                        </div>
                        <!-- Status Column -->
                        <div class="px-4 py-2">
                            <div class="flex flex-col items-start">
                                @if ($business->isExpired())
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-200 dark:bg-red-800 text-red-800 dark:text-red-200 mb-1 w-fit">Istekao</span>
                                    @if ($business->expires_at)
                                        <span class="text-xs text-slate-500 dark:text-slate-300">Istekao
                                            {{ $business->expires_at->format('d.m.Y') }}</span>
                                    @endif
                                @elseif ($business->status == 'active')
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-200 dark:bg-green-800 text-green-800 dark:text-green-200 mb-1 w-fit">Aktivan</span>

                                    @if ($business->is_from_business_plan)
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-200 dark:bg-purple-800 text-purple-800 dark:text-purple-200 mb-1 w-fit">
                                            <i class="fas fa-briefcase mr-1"></i>Biznis plan
                                        </span>
                                    @else
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-200 dark:bg-orange-800 text-orange-800 dark:text-orange-200 mb-1 w-fit">
                                            <i class="fas fa-credit-card mr-1"></i>Plaćen
                                        </span>
                                    @endif

                                    @if ($business->expires_at)
                                        @php
                                            $daysLeft = now()->diffInDays($business->expires_at, false);
                                            $daysLeft = max(0, (int) $daysLeft);
                                        @endphp
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full w-fit
                                                @if ($daysLeft <= 5) bg-red-200 dark:bg-red-800 text-red-800 dark:text-red-200
                                                @elseif($daysLeft <= 10) bg-amber-200 dark:bg-amber-800 text-amber-800 dark:text-amber-200
                                                @elseif($daysLeft > 10) bg-green-200 dark:bg-green-800 text-green-800 dark:text-green-200
                                                @else bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200 @endif">
                                            @if ($daysLeft > 1)
                                                Ističe za {{ $daysLeft }} dana
                                            @elseif($daysLeft == 1)
                                                Ističe sutra
                                            @elseif($daysLeft == 0)
                                                Ističe danas
                                            @else
                                                Istekao pre {{ abs($daysLeft) }} dana
                                            @endif
                                        </span>
                                    @endif
                                @elseif ($business->status == 'inactive')
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200 w-fit">Neaktivan</span>
                                @else
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200 w-fit">{{ ucfirst($business->status) }}</span>
                                @endif
                            </div>
                        </div>
                        <!-- Datum Column -->
                        <div class="px-4 py-2 text-sm text-slate-500 dark:text-slate-300">
                            <div class="flex flex-col">
                                <span>{{ $business->created_at->format('d.m.Y') }}</span>
                                @if ($business->renewed_at)
                                    <span class="text-xs text-purple-500">Obnovljen
                                        {{ $business->renewed_at->format('d.m.Y') }}</span>
                                @endif
                                @if ($business->renewal_count > 0)
                                    <span class="text-xs text-slate-400">({{ $business->renewal_count }}x
                                        obnovljen)</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- Actions Row -->
                    <div
                        class="border-t border-slate-200 dark:border-slate-600 px-4 py-2 bg-slate-50 dark:bg-slate-700/50">
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('businesses.show', $business->slug) }}"
                                class="inline-flex items-center px-2 py-1 text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300 rounded">
                                <i class="fas fa-eye mr-1"></i> Pregled
                            </a>

                            <a href="{{ route('businesses.edit', $business->slug) }}"
                                class="inline-flex items-center px-2 py-1 text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 rounded">
                                <i class="fas fa-edit mr-1"></i> Izmeni
                            </a>

                            @if ($business->status == 'inactive')
                                <button wire:click="openActivateModal({{ $business->id }})"
                                    class="inline-flex items-center px-2 py-1 text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300 rounded">
                                    <i class="fas fa-power-off mr-1"></i> Aktiviraj
                                </button>
                            @elseif ($business->isExpired())
                                <button wire:click="renewBusiness({{ $business->id }})"
                                    class="inline-flex items-center px-2 py-1 text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 rounded">
                                    <i class="fas fa-redo mr-1"></i> Obnovi
                                </button>
                            @endif

                            <button
                                class="inline-flex items-center px-2 py-1 text-orange-600 dark:text-orange-400 hover:text-orange-800 dark:hover:text-orange-300 rounded"
                                onclick="navigator.clipboard.writeText('{{ route('businesses.show', $business->slug) }}'); alert('Link kopiran!')">
                                <i class="fas fa-share-alt mr-1"></i> Podeli
                            </button>

                            <button wire:click="deleteBusiness({{ $business->id }})"
                                wire:confirm="Da li ste sigurni da želite da obrišete ovaj business?"
                                class="inline-flex items-center px-2 py-1 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 rounded">
                                <i class="fas fa-trash mr-1"></i> Obriši
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Desktop Paginacija -->
        <div class="mt-6">
            {{ $businesses->links() }}
        </div>

        <!-- Mobile Card View -->
        <div class="lg:hidden space-y-4">
            @foreach ($businesses as $business)
                <div class="bg-white dark:bg-slate-800 border-l-4 border-purple-500 shadow rounded-lg overflow-hidden">
                    <!-- Card Header -->
                    <div class="p-4 border-b border-slate-200 dark:border-slate-600">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start flex-1">
                                <!-- Logo -->
                                <div class="flex-shrink-0 mr-3" style="width: 80px; height: 60px;">
                                    @if ($business->logo)
                                        <div class="w-full h-full flex items-center justify-start">
                                            <img class="max-w-full max-h-full rounded-lg object-contain"
                                                src="{{ Storage::url($business->logo) }}"
                                                alt="{{ $business->name }}">
                                        </div>
                                    @else
                                        <div
                                            class="w-full h-full rounded-lg bg-purple-100 dark:bg-purple-900 flex items-center justify-center">
                                            <i
                                                class="fas fa-briefcase text-purple-600 dark:text-purple-400 text-2xl"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Business Info -->
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-1">
                                        {{ $business->name }}</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-300 mb-2">
                                        <i class="fas fa-map-marker-alt mr-1"></i>{{ $business->location }}
                                    </p>
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
                                Status business</div>
                            <div class="flex flex-col space-y-2">
                                @if ($business->isExpired())
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-200 dark:bg-red-800 text-red-800 dark:text-red-200 w-fit">Istekao</span>
                                    @if ($business->expires_at)
                                        <span class="text-xs text-slate-500 dark:text-slate-300">Istekao
                                            {{ $business->expires_at->format('d.m.Y') }}</span>
                                    @endif
                                @elseif ($business->status == 'active')
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-200 dark:bg-green-800 text-green-800 dark:text-green-200 w-fit">Aktivan</span>

                                    @if ($business->is_from_business_plan)
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-200 dark:bg-purple-800 text-purple-800 dark:text-purple-200 w-fit">
                                            <i class="fas fa-briefcase mr-1"></i>Biznis plan
                                        </span>
                                    @else
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-200 dark:bg-orange-800 text-orange-800 dark:text-orange-200 w-fit">
                                            <i class="fas fa-credit-card mr-1"></i>Plaćen
                                        </span>
                                    @endif

                                    @if ($business->expires_at)
                                        @php
                                            $daysLeft = now()->diffInDays($business->expires_at, false);
                                            $daysLeft = max(0, (int) $daysLeft);
                                        @endphp
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full w-fit
                                            @if ($daysLeft <= 5) bg-red-200 dark:bg-red-800 text-red-800 dark:text-red-200
                                            @elseif($daysLeft <= 10) bg-amber-200 dark:bg-amber-800 text-amber-800 dark:text-amber-200
                                            @elseif($daysLeft > 10) bg-green-200 dark:bg-green-800 text-green-800 dark:text-green-200
                                            @else bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200 @endif">
                                            @if ($daysLeft > 1)
                                                Ističe za {{ $daysLeft }} dana
                                            @elseif($daysLeft == 1)
                                                Ističe sutra
                                            @elseif($daysLeft == 0)
                                                Ističe danas
                                            @else
                                                Istekao pre {{ abs($daysLeft) }} dana
                                            @endif
                                        </span>
                                    @endif
                                @elseif ($business->status == 'inactive')
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200 w-fit">Neaktivan</span>
                                @else
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200 w-fit">{{ ucfirst($business->status) }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- Date Info -->
                        <div class="mb-4">
                            <div
                                class="text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider mb-2">
                                Informacije o datumu</div>
                            <div class="space-y-1">
                                <div class="text-sm text-slate-900 dark:text-slate-100">Kreiran:
                                    {{ $business->created_at->format('d.m.Y') }}</div>
                                @if ($business->renewed_at)
                                    <div class="text-xs text-purple-500">Obnovljen:
                                        {{ $business->renewed_at->format('d.m.Y') }}</div>
                                @endif
                                @if ($business->renewal_count > 0)
                                    <div class="text-xs text-slate-400">Obnovljen {{ $business->renewal_count }}x
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Category Info -->
                        @if ($business->category)
                            <div class="mb-4">
                                <div
                                    class="text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider mb-2">
                                    Kategorija</div>
                                <div class="text-sm text-slate-900 dark:text-slate-100">
                                    {{ $business->category->name }}
                                    @if ($business->subcategory)
                                        <span class="text-slate-500 dark:text-slate-400">-
                                            {{ $business->subcategory->name }}</span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('businesses.show', $business->slug) }}"
                                class="inline-flex items-center px-3 py-1.5 bg-purple-100 dark:bg-purple-800 text-purple-700 dark:text-purple-200 text-xs font-medium rounded-lg hover:bg-purple-200 dark:hover:bg-purple-700 transition-colors">
                                <i class="fas fa-eye mr-1"></i>
                                Pregled
                            </a>

                            <a href="{{ route('businesses.edit', $business->slug) }}"
                                class="inline-flex items-center px-3 py-1.5 bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-indigo-200 text-xs font-medium rounded-lg hover:bg-indigo-200 dark:hover:bg-indigo-700 transition-colors">
                                <i class="fas fa-edit mr-1"></i>
                                Izmeni
                            </a>

                            @if ($business->status == 'inactive')
                                <button wire:click="openActivateModal({{ $business->id }})"
                                    class="inline-flex items-center px-3 py-1.5 bg-purple-100 dark:bg-purple-800 text-purple-700 dark:text-purple-200 text-xs font-medium rounded-lg hover:bg-purple-200 dark:hover:bg-purple-700 transition-colors">
                                    <i class="fas fa-power-off mr-1"></i>
                                    Aktiviraj
                                </button>
                            @elseif ($business->isExpired())
                                <button wire:click="renewBusiness({{ $business->id }})"
                                    class="inline-flex items-center px-3 py-1.5 bg-green-100 dark:bg-green-800 text-green-700 dark:text-green-200 text-xs font-medium rounded-lg hover:bg-green-200 dark:hover:bg-green-700 transition-colors">
                                    <i class="fas fa-redo mr-1"></i>
                                    Obnovi
                                </button>
                            @endif

                            <button
                                class="inline-flex items-center px-3 py-1.5 bg-orange-100 text-orange-700 text-xs font-medium rounded-lg hover:bg-orange-200 transition-colors"
                                onclick="navigator.clipboard.writeText('{{ route('businesses.show', $business->slug) }}'); alert('Link kopiran!')">
                                <i class="fas fa-share-alt mr-1"></i>
                                Podeli
                            </button>

                            <button wire:click="deleteBusiness({{ $business->id }})"
                                wire:confirm="Da li ste sigurni da želite da obrišete ovaj business?"
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
            {{ $businesses->links() }}
        </div>
    @else
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md p-8 text-center">
            <i class="fas fa-briefcase text-slate-400 text-5xl mb-4"></i>
            <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-200 mb-2">Nemate nijedan business</h3>
            <p class="text-slate-600 dark:text-slate-400 mb-4">Kreirajte svoj prvi business oglas.</p>
            <a href="{{ route('businesses.create') }}"
                class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                Kreiraj prvi business
            </a>
        </div>
    @endif

    <!-- Activation Modal -->
    @if ($showActivateModal && $businessToActivate)
        <div class="fixed inset-0 bg-slate-900 bg-opacity-50 overflow-y-auto h-full w-full z-50"
            wire:click="closeActivateModal">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-lg bg-white dark:bg-slate-800"
                wire:click.stop>
                <div class="mt-3">
                    <!-- Header -->
                    <div
                        class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-slate-700">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">
                            <i class="fas fa-power-off mr-2 text-purple-600"></i>
                            Aktivacija biznisa
                        </h3>
                        <button wire:click="closeActivateModal"
                            class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <!-- Business Info -->
                    <div class="mt-4 p-3 bg-slate-50 dark:bg-slate-700 rounded-lg">
                        <p class="text-sm text-slate-600 dark:text-slate-300">
                            <strong>Biznis:</strong> {{ $businessToActivate->name }}
                        </p>
                    </div>

                    <!-- Check if user has business plan -->
                    @php
                        $user = auth()->user();
                        $hasActiveBusinessPlan =
                            $user->payment_plan === 'business' &&
                            $user->plan_expires_at &&
                            $user->plan_expires_at->isFuture() &&
                            $user->business_plan_total > 0;
                        // Count only businesses from business plan
                        $activeBusinessCount = $user
                            ->businesses()
                            ->where('status', 'active')
                            ->where('is_from_business_plan', true)
                            ->count();
                        $businessLimit = $user->business_plan_total;
                        $hasAvailableSlots = $hasActiveBusinessPlan && $activeBusinessCount < $businessLimit;
                        $businessFeeEnabled = \App\Models\Setting::get('business_fee_enabled', false);
                        $businessFeeAmount = \App\Models\Setting::get('business_fee_amount', 2000);
                    @endphp

                    <!-- Activation Options -->
                    <div class="mt-4 space-y-3">
                        @if ($hasAvailableSlots)
                            <!-- Option 1: Activate with Business Plan -->
                            <button wire:click="activateWithPlan"
                                class="w-full p-4 bg-purple-100 dark:bg-purple-900 border-2 border-purple-300 dark:border-purple-700 rounded-lg hover:bg-purple-200 dark:hover:bg-purple-800 transition-colors text-left">
                                <div class="flex items-start">
                                    <i
                                        class="fas fa-briefcase text-purple-600 dark:text-purple-400 text-2xl mr-3 mt-1"></i>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-purple-900 dark:text-purple-100">
                                            Aktiviraj preko biznis plana (Besplatno)
                                        </h4>
                                        <p class="text-sm text-purple-700 dark:text-purple-300 mt-1">
                                            Imate {{ $businessLimit - $activeBusinessCount }} slobodnih mesta u vašem
                                            biznis planu
                                        </p>
                                        <p class="text-xs text-purple-600 dark:text-purple-400 mt-1">
                                            Plan važi do: {{ $user->plan_expires_at->format('d.m.Y') }}
                                        </p>
                                    </div>
                                </div>
                            </button>
                        @endif

                        @if ($businessFeeEnabled && !$hasAvailableSlots)
                            <!-- Option 2: Activate with Payment (only if no business plan slots available) -->
                            @php
                                $hasEnoughBalance = $user->balance >= $businessFeeAmount;
                            @endphp
                            <button wire:click="activateWithPayment"
                                class="w-full p-4 {{ $hasEnoughBalance ? 'bg-orange-100 dark:bg-orange-900 border-orange-300 dark:border-orange-700 hover:bg-orange-200 dark:hover:bg-orange-800' : 'bg-red-100 dark:bg-red-900 border-red-300 dark:border-red-700 hover:bg-red-200 dark:hover:bg-red-800' }} border-2 rounded-lg transition-colors text-left">
                                <div class="flex items-start">
                                    <i
                                        class="fas {{ $hasEnoughBalance ? 'fa-credit-card text-orange-600 dark:text-orange-400' : 'fa-exclamation-triangle text-red-600 dark:text-red-400' }} text-2xl mr-3 mt-1"></i>
                                    <div class="flex-1">
                                        <h4
                                            class="font-semibold {{ $hasEnoughBalance ? 'text-orange-900 dark:text-orange-100' : 'text-red-900 dark:text-red-100' }}">
                                            {{ $hasEnoughBalance ? 'Plati i aktiviraj' : 'Nedovoljno kredita' }}
                                        </h4>
                                        <p
                                            class="text-sm {{ $hasEnoughBalance ? 'text-orange-700 dark:text-orange-300' : 'text-red-700 dark:text-red-300' }} mt-1">
                                            Cena: {{ number_format($businessFeeAmount, 0, ',', '.') }} RSD
                                        </p>
                                        <p
                                            class="text-xs {{ $hasEnoughBalance ? 'text-orange-600 dark:text-orange-400' : 'text-red-600 dark:text-red-400' }} mt-1">
                                            Vaš kredit: {{ number_format($user->balance, 0, ',', '.') }} RSD
                                        </p>
                                        @if (!$hasEnoughBalance)
                                            <p class="text-xs text-red-700 dark:text-red-300 mt-2 font-semibold">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                Nedostaje:
                                                {{ number_format($businessFeeAmount - $user->balance, 0, ',', '.') }}
                                                RSD
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </button>
                        @endif

                        @if (!$hasAvailableSlots && !$hasActiveBusinessPlan)
                            <!-- Option 3: Buy Business Plan -->
                            <a href="{{ route('balance.plan-selection') }}"
                                class="block w-full p-4 bg-sky-100 dark:bg-sky-900 border-2 border-sky-300 dark:border-sky-700 rounded-lg hover:bg-sky-200 dark:hover:bg-sky-800 transition-colors text-left">
                                <div class="flex items-start">
                                    <i
                                        class="fas fa-shopping-cart text-sky-600 dark:text-sky-400 text-2xl mr-3 mt-1"></i>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-sky-900 dark:text-sky-100">
                                            Kupi biznis plan
                                        </h4>
                                        <p class="text-sm text-sky-700 dark:text-sky-300 mt-1">
                                            Aktivirajte više biznisa sa biznis planom
                                        </p>
                                    </div>
                                </div>
                            </a>
                        @endif
                    </div>

                    <!-- Cancel Button -->
                    <div class="mt-4 pt-3 border-t border-slate-200 dark:border-slate-700">
                        <button wire:click="closeActivateModal"
                            class="w-full px-4 py-2 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors">
                            Otkaži
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
