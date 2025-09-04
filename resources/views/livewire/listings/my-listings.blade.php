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

    <!-- Tabela oglasa -->
    @if ($listings->count() > 0)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Oglas
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cena
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Datum
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Akcije</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($listings as $listing)
                        <tr>
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex flex-wrap space-x-2 space-y-1">
                                    <a href="{{ route('listings.show', $listing) }}"
                                        class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye"></i> Pregled
                                    </a>
                                    
                                    @if($listing->isActive())
                                        <a href="{{ route('listings.edit', $listing) }}"
                                            class="text-indigo-600 hover:text-indigo-900">
                                            <i class="fas fa-edit"></i> Izmeni
                                        </a>
                                    @endif
                                    
                                    @if($listing->canBeRenewed())
                                        <button wire:click="renewListing({{ $listing->id }})" 
                                            class="text-green-600 hover:text-green-900"
                                            onclick="return confirm('Da li želite da obnovite ovaj oglas? {{ auth()->user()->payment_plan === 'per_listing' ? 'Biće naplaćeno ' . \App\Models\Setting::get('listing_fee_amount', 10) . ' RSD.' : 'Bесплатno jer imate aktivan plan.' }}')">
                                            <i class="fas fa-redo"></i> Obnovi
                                        </button>
                                    @endif
                                    
                                    <button class="text-orange-600 hover:text-orange-900"
                                        onclick="navigator.clipboard.writeText('{{ route('listings.show', $listing) }}'); alert('Link kopiran!')">
                                        <i class="fas fa-share-alt"></i> Podeli
                                    </button>
                                    
                                    <button x-data
                                        x-on:click.prevent="if (confirm('Da li ste sigurni da želite da obrišete ovaj oglas?')) { $wire.deleteListing({{ $listing->id }}) }"
                                        class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i> Obriši
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginacija -->
        <div class="mt-6">
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
