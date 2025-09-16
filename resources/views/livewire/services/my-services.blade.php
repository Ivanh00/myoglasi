<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Moje usluge</h1>
            <p class="text-gray-600 dark:text-gray-400">Upravljajte svojim uslugama</p>
        </div>
        <a href="{{ route('services.create') }}"
            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus mr-2"></i> Dodaj novu uslugu
        </a>
    </div>

    <!-- Search and filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex-1">
                <input type="text" wire:model.live="search" placeholder="Pretraži usluge..."
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="flex gap-2">
                <select wire:model.live="status"
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="all">Sve</option>
                    <option value="active">Aktivne</option>
                    <option value="inactive">Neaktivne</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Services list -->
    @if($services->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Usluga
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Cena
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Pregledi
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Datum
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Akcije
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($services as $service)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($service->images->count() > 0)
                                        <img src="{{ $service->images->first()->url }}" alt="{{ $service->title }}"
                                            class="w-10 h-10 rounded-lg object-cover mr-3">
                                    @else
                                        <div class="w-10 h-10 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-tools text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100 flex items-center">
                                            {{ Str::limit($service->title, 40) }}
                                            <!-- Promotion Badges -->
                                            @if($service->hasActivePromotion())
                                                <div class="flex flex-wrap gap-1 ml-2">
                                                    @foreach($service->getPromotionBadges() as $badge)
                                                        <span class="px-1 py-0.5 text-xs font-bold rounded-full {{ $badge['class'] }}">
                                                            {{ $badge['text'] }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $service->category->name ?? 'Bez kategorije' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ number_format($service->price, 2) }} RSD
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($service->status === 'active')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Aktivna
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Neaktivna
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $service->views ?? 0 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $service->created_at->format('d.m.Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('services.show', $service->slug) }}"
                                        class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('services.edit', $service->slug) }}"
                                        class="text-indigo-600 hover:text-indigo-900 dark:hover:text-indigo-400">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button wire:click="toggleStatus({{ $service->id }})"
                                        class="text-yellow-600 hover:text-yellow-900 dark:hover:text-yellow-400">
                                        @if($service->status === 'active')
                                            <i class="fas fa-pause"></i>
                                        @else
                                            <i class="fas fa-play"></i>
                                        @endif
                                    </button>
                                    @if($service->status === 'active')
                                        <button wire:click="$dispatch('openServicePromotionModal', { serviceId: {{ $service->id }} })"
                                            class="text-green-600 hover:text-green-900 dark:hover:text-green-400">
                                            <i class="fas fa-bullhorn"></i>
                                        </button>
                                    @endif
                                    <button wire:click="deleteService({{ $service->id }})"
                                        wire:confirm="Da li ste sigurni da želite da obrišete ovu uslugu?"
                                        class="text-red-600 hover:text-red-900 dark:hover:text-red-400">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $services->links() }}
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center">
            <i class="fas fa-tools text-gray-400 text-5xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-2">Nemate usluga</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">
                @if($search)
                    Nema rezultata za vašu pretragu.
                @else
                    Još uvek nemate postavljenih usluga.
                @endif
            </p>
            @if(!$search)
                <a href="{{ route('services.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i> Dodaj prvu uslugu
                </a>
            @endif
        </div>
    @endif

    <!-- Service Promotion Manager Modal -->
    @livewire('services.promotion-manager')
</div>