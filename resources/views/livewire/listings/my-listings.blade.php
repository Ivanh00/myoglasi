<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Naslov -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Moji oglasi</h1>
        <p class="text-gray-600 mt-2">Upravljajte svojim oglasima</p>
    </div>

    <!-- Dugme za dodavanje novog oglasa i filter -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
        <a href="{{ route('listings.create') }}"
            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus mr-2"></i> Dodaj novi oglas
        </a>
        
        <!-- Filter -->
        <div class="flex items-center space-x-4">
            <label class="text-sm font-medium text-gray-700">Prikaži:</label>
            <select wire:model.live="filter" 
                class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="all">Sve oglase</option>
                <option value="active">Aktivne oglase</option>
                <option value="expired">Istekle oglase</option>
            </select>
        </div>
    </div>

    <!-- Desktop Tabela oglasa -->
    @if ($listings->count() > 0)
        <div class="hidden lg:block bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Oglas
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cena
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aukcija</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Datum
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Akcije</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($listings as $listing)
                        <tr class="{{ $listing->auction ? 'border-l-4 !border-l-yellow-500 bg-yellow-50' : '' }}" style="{{ $listing->auction ? 'border-left: 4px solid #eab308 !important;' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if ($listing->images->count() > 0)
                                            <img class="h-10 w-10 rounded-full object-cover"
                                                src="{{ $listing->images->first()->url }}" alt="{{ $listing->title }}">
                                        @else
                                            <div
                                                class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                <i class="fas fa-image text-gray-400"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ Str::limit($listing->title, 30) }}</div>
                                        <div class="text-sm text-gray-500">{{ $listing->category->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 font-bold">{{ number_format($listing->price, 2) }} RSD
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    @if ($listing->isExpired() || $listing->status == 'expired')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 mb-1">Istekao</span>
                                        @if($listing->expires_at)
                                            <span class="text-xs text-gray-500">Istekao {{ $listing->expires_at->format('d.m.Y') }}</span>
                                        @endif
                                    @elseif ($listing->status == 'active')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 mb-1">Aktivan</span>
                                        @if($listing->expires_at)
                                            @php
                                                $daysLeft = now()->diffInDays($listing->expires_at, false);
                                                $daysLeft = max(0, (int)$daysLeft); // Ensure positive number
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($daysLeft <= 5) bg-red-100 text-red-800
                                                @elseif($daysLeft <= 10) bg-yellow-100 text-yellow-800
                                                @elseif($daysLeft > 10) bg-green-100 text-green-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                @if($daysLeft > 1)
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
                                    @elseif($listing->status == 'pending')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Na čekanju</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($listing->status) }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($listing->auction)
                                    @if($listing->auction->isActive())
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-gavel mr-1"></i>
                                            Aktivna aukcija
                                        </span>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $listing->auction->total_bids }} ponuda
                                        </div>
                                        @if($listing->auction->current_price > $listing->auction->starting_price)
                                            <div class="text-xs text-green-600">
                                                {{ number_format($listing->auction->current_price, 0, ',', '.') }} RSD
                                            </div>
                                        @endif
                                    @elseif($listing->auction->hasEnded())
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            <i class="fas fa-flag-checkered mr-1"></i>
                                            Završena
                                        </span>
                                        @if($listing->auction->winner)
                                            <div class="text-xs text-green-600">
                                                Pobednik: {{ $listing->auction->winner->name }}
                                            </div>
                                        @endif
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ ucfirst($listing->auction->status) }}
                                        </span>
                                    @endif
                                @else
                                    <span class="text-gray-400 text-xs">Nije na aukciji</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex flex-col">
                                    <span>{{ $listing->created_at->format('d.m.Y') }}</span>
                                    @if($listing->renewed_at)
                                        <span class="text-xs text-blue-500">Obnovljen {{ $listing->renewed_at->format('d.m.Y') }}</span>
                                    @endif
                                    @if($listing->renewal_count > 0)
                                        <span class="text-xs text-gray-400">({{ $listing->renewal_count }}x obnovljen)</span>
                                    @endif
                                </div>
                            <td class="px-6 py-4 text-sm font-medium">
                                <div class="space-y-2">
                                    <!-- First row: Primary actions -->
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('listings.show', $listing) }}"
                                            class="inline-flex items-center px-2 py-1 text-blue-600 hover:text-blue-900 rounded">
                                            <i class="fas fa-eye mr-1"></i> Pregled
                                        </a>
                                        
                                        @if($listing->isActive())
                                            <a href="{{ route('listings.edit', $listing) }}"
                                                class="inline-flex items-center px-2 py-1 text-indigo-600 hover:text-indigo-900 rounded">
                                                <i class="fas fa-edit mr-1"></i> Izmeni
                                            </a>
                                        @endif
                                        
                                        @if($listing->canBeRenewed())
                                            <button wire:click="renewListing({{ $listing->id }})" 
                                                class="inline-flex items-center px-2 py-1 text-green-600 hover:text-green-900 rounded"
                                                onclick="return confirm('Da li želite da obnovite ovaj oglas? {{ auth()->user()->payment_plan === 'per_listing' ? 'Biće naplaćeno ' . \App\Models\Setting::get('listing_fee_amount', 10) . ' RSD.' : 'Besplatno jer imate aktivan plan.' }}')">
                                                <i class="fas fa-redo mr-1"></i> Obnovi
                                            </button>
                                        @endif

                                        <button class="inline-flex items-center px-2 py-1 text-orange-600 hover:text-orange-900 rounded"
                                            onclick="navigator.clipboard.writeText('{{ route('listings.show', $listing) }}'); alert('Link kopiran!')">
                                            <i class="fas fa-share-alt mr-1"></i> Podeli
                                        </button>
                                    </div>

                                    <!-- Second row: Auction and delete actions (only if auction exists) -->
                                    @if($listing->auction)
                                        <div class="flex items-center space-x-2">
                                            @if($listing->auction->isActive())
                                                <a href="{{ route('auction.show', $listing->auction) }}"
                                                    class="inline-flex items-center px-2 py-1 text-yellow-600 hover:text-yellow-900 rounded">
                                                    <i class="fas fa-gavel mr-1"></i> Aukcija
                                                </a>
                                            @endif
                                            <button wire:click="removeFromAuction({{ $listing->id }})" 
                                                class="inline-flex items-center px-2 py-1 text-orange-600 hover:text-orange-900 rounded"
                                                onclick="return confirm('Da li ste sigurni da želite da uklonite ovaj oglas iz aukcije?')">
                                                <i class="fas fa-times mr-1"></i> Ukloni
                                            </button>
                                            
                                            <button x-data
                                                x-on:click.prevent="if (confirm('Da li ste sigurni da želite da obrišete ovaj oglas?')) { $wire.deleteListing({{ $listing->id }}) }"
                                                class="inline-flex items-center px-2 py-1 text-red-600 hover:text-red-900 rounded">
                                                <i class="fas fa-trash mr-1"></i> Obriši
                                            </button>
                                        </div>
                                    @else
                                        <!-- No auction - delete button on same row -->
                                        <div class="flex items-center space-x-2">
                                            <button x-data
                                                x-on:click.prevent="if (confirm('Da li ste sigurni da želite da obrišete ovaj oglas?')) { $wire.deleteListing({{ $listing->id }}) }"
                                                class="inline-flex items-center px-2 py-1 text-red-600 hover:text-red-900 rounded">
                                                <i class="fas fa-trash mr-1"></i> Obriši
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Desktop Paginacija -->
        <div class="mt-6">
            {{ $listings->links() }}
        </div>

        <!-- Mobile Card View -->
        <div class="lg:hidden space-y-4">
            @foreach ($listings as $listing)
                <div class="bg-white shadow rounded-lg overflow-hidden {{ $listing->auction ? 'border-l-4 border-yellow-500 bg-yellow-50' : '' }}">
                    <!-- Card Header -->
                    <div class="p-4 border-b border-gray-200">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start flex-1">
                                <!-- Image -->
                                <div class="flex-shrink-0 h-16 w-16 mr-3">
                                    @if ($listing->images->count() > 0)
                                        <img class="h-16 w-16 rounded-lg object-cover" 
                                             src="{{ $listing->images->first()->url }}" alt="{{ $listing->title }}">
                                    @else
                                        <div class="h-16 w-16 rounded-lg bg-gray-200 flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Listing Info -->
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $listing->title }}</h3>
                                    <p class="text-sm text-gray-500 mb-2">{{ $listing->category->name }}</p>
                                    <p class="text-xl font-bold text-blue-600">{{ number_format($listing->price, 2) }} RSD</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-4">
                        <!-- Status Section -->
                        <div class="mb-4">
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Status oglasa</div>
                            <div class="flex flex-col space-y-2">
                                @if ($listing->isExpired() || $listing->status == 'expired')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 w-fit">Istekao</span>
                                    @if($listing->expires_at)
                                        <span class="text-xs text-gray-500">Istekao {{ $listing->expires_at->format('d.m.Y') }}</span>
                                    @endif
                                @elseif ($listing->status == 'active')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 w-fit">Aktivan</span>
                                    @if($listing->expires_at)
                                        @php
                                            $daysLeft = now()->diffInDays($listing->expires_at, false);
                                            $daysLeft = max(0, (int)$daysLeft);
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full w-fit
                                            @if($daysLeft <= 5) bg-red-100 text-red-800
                                            @elseif($daysLeft <= 10) bg-yellow-100 text-yellow-800
                                            @elseif($daysLeft > 10) bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            @if($daysLeft > 1)
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
                                @elseif($listing->status == 'pending')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 w-fit">Na čekanju</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 w-fit">{{ ucfirst($listing->status) }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- Date Info -->
                        <div class="mb-4">
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Informacije o datumu</div>
                            <div class="space-y-1">
                                <div class="text-sm text-gray-900">Kreiran: {{ $listing->created_at->format('d.m.Y') }}</div>
                                @if($listing->renewed_at)
                                    <div class="text-xs text-blue-500">Obnovljen: {{ $listing->renewed_at->format('d.m.Y') }}</div>
                                @endif
                                @if($listing->renewal_count > 0)
                                    <div class="text-xs text-gray-400">Obnovljen {{ $listing->renewal_count }}x</div>
                                @endif
                            </div>
                        </div>

                        <!-- Auction Info -->
                        @if($listing->auction)
                            <div class="mb-4">
                                <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Status aukcije</div>
                                <div class="space-y-2">
                                    @if($listing->auction->isActive())
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 w-fit">
                                            <i class="fas fa-gavel mr-1"></i>
                                            Aktivna aukcija
                                        </span>
                                        <div class="text-xs text-gray-500">{{ $listing->auction->total_bids }} ponuda</div>
                                        @if($listing->auction->current_price > $listing->auction->starting_price)
                                            <div class="text-xs text-green-600">{{ number_format($listing->auction->current_price, 0, ',', '.') }} RSD</div>
                                        @endif
                                    @elseif($listing->auction->hasEnded())
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 w-fit">
                                            <i class="fas fa-flag-checkered mr-1"></i>
                                            Završena
                                        </span>
                                        @if($listing->auction->winner)
                                            <div class="text-xs text-green-600">Pobednik: {{ $listing->auction->winner->name }}</div>
                                        @endif
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 w-fit">
                                            {{ ucfirst($listing->auction->status) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('listings.show', $listing) }}"
                                class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 text-xs font-medium rounded-lg hover:bg-blue-200 transition-colors">
                                <i class="fas fa-eye mr-1"></i>
                                Pregled
                            </a>
                            
                            @if($listing->isActive())
                                <a href="{{ route('listings.edit', $listing) }}"
                                    class="inline-flex items-center px-3 py-1.5 bg-indigo-100 text-indigo-700 text-xs font-medium rounded-lg hover:bg-indigo-200 transition-colors">
                                    <i class="fas fa-edit mr-1"></i>
                                    Izmeni
                                </a>
                            @endif
                            
                            @if($listing->canBeRenewed())
                                <button wire:click="renewListing({{ $listing->id }})" 
                                    class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-700 text-xs font-medium rounded-lg hover:bg-green-200 transition-colors"
                                    onclick="return confirm('Da li želite da obnovite ovaj oglas? {{ auth()->user()->payment_plan === 'per_listing' ? 'Biće naplaćeno ' . \App\Models\Setting::get('listing_fee_amount', 10) . ' RSD.' : 'Besplatno jer imate aktivan plan.' }}')">
                                    <i class="fas fa-redo mr-1"></i>
                                    Obnovi
                                </button>
                            @endif
                            
                            <button class="inline-flex items-center px-3 py-1.5 bg-orange-100 text-orange-700 text-xs font-medium rounded-lg hover:bg-orange-200 transition-colors"
                                onclick="navigator.clipboard.writeText('{{ route('listings.show', $listing) }}'); alert('Link kopiran!')">
                                <i class="fas fa-share-alt mr-1"></i>
                                Podeli
                            </button>

                            @if($listing->auction)
                                @if($listing->auction->isActive())
                                    <a href="{{ route('auction.show', $listing->auction) }}"
                                        class="inline-flex items-center px-3 py-1.5 bg-yellow-100 text-yellow-700 text-xs font-medium rounded-lg hover:bg-yellow-200 transition-colors">
                                        <i class="fas fa-gavel mr-1"></i>
                                        Aukcija
                                    </a>
                                @endif
                                <button wire:click="removeFromAuction({{ $listing->id }})" 
                                    class="inline-flex items-center px-3 py-1.5 bg-orange-100 text-orange-700 text-xs font-medium rounded-lg hover:bg-orange-200 transition-colors"
                                    onclick="return confirm('Da li ste sigurni da želite da uklonite ovaj oglas iz aukcije?')">
                                    <i class="fas fa-times mr-1"></i>
                                    Ukloni iz aukcije
                                </button>
                            @endif
                            
                            <button x-data
                                x-on:click.prevent="if (confirm('Da li ste sigurni da želite da obrišete ovaj oglas?')) { $wire.deleteListing({{ $listing->id }}) }"
                                class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 text-xs font-medium rounded-lg hover:bg-red-200 transition-colors">
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
            {{ $listings->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <i class="fas fa-list-alt text-gray-400 text-5xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Nemate nijedan oglas</h3>
            <p class="text-gray-600 mb-4">Kreirajte svoj prvi oglas i počnite da prodajete.</p>
            <a href="{{ route('listings.create') }}"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                Kreiraj prvi oglas
            </a>
        </div>
    @endif
</div>
