<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Single column layout -->
    <div>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
                <!-- Auction Header -->
                <div class="bg-gradient-to-r from-yellow-400 to-orange-500 text-white p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-xl font-bold">{{ $auction->listing->title }}</h1>
                            <p class="text-yellow-100">Aukcija #{{ $auction->id }}</p>
                        </div>
                        <div class="text-right">
                            @if($auction->isActive())
                                <div class="text-2xl font-bold auction-countdown" 
                                     data-end-time="{{ $auction->ends_at->timestamp }}"
                                     data-total-seconds="{{ $auction->time_left['total_seconds'] ?? 0 }}">
                                    @if($auction->time_left)
                                        {{ $auction->time_left['formatted'] }}
                                    @endif
                                </div>
                                <p class="text-yellow-100 text-sm">vremena ostalo</p>
                            @else
                                <div class="text-2xl font-bold">ZAVRŠENO</div>
                                @if($auction->winner)
                                    <p class="text-yellow-100 text-sm">Pobednik: {{ $auction->winner->name }}</p>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Main Content Area -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6">
                    <!-- Images -->
                    <div>
                        @if($auction->listing->images->count() > 0)
                            <div class="relative">
                                <!-- Main image -->
                                <div class="mb-4 rounded-lg overflow-hidden relative">
                                    <img id="mainImage" src="{{ $auction->listing->images->first()->url }}" 
                                         alt="{{ $auction->listing->title }}" 
                                         class="w-full h-80 object-cover rounded-lg">
                                </div>

                                <!-- Thumbnail gallery -->
                                <div class="grid grid-cols-4 gap-2">
                                    @foreach($auction->listing->images as $index => $image)
                                        <div class="cursor-pointer border-2 rounded-lg overflow-hidden {{ $index === 0 ? 'border-yellow-500' : 'border-gray-200' }}">
                                            <img src="{{ $image->url }}" 
                                                 alt="{{ $auction->listing->title }} - slika {{ $index + 1 }}" 
                                                 class="w-full h-20 object-cover"
                                                 onclick="changeMainImage('{{ $image->url }}', this)">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="w-full h-80 bg-gray-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-gavel text-gray-400 text-5xl"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Auction Info -->
                    <div>
                        <!-- Current Price -->
                        <div class="text-center mb-6 p-6 bg-gray-50 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Trenutna cena</h3>
                            <div class="text-4xl font-bold text-red-600 mb-2">
                                {{ number_format($auction->current_price, 0, ',', '.') }} RSD
                            </div>
                            @if($auction->total_bids > 0)
                                <p class="text-gray-600">{{ $auction->total_bids }} ponuda</p>
                                @if($auction->winningBid)
                                    <p class="text-sm text-gray-500">Vodi: {{ $auction->winningBid->user->name }}</p>
                                @endif
                            @else
                                <p class="text-gray-600">Još nema ponuda</p>
                            @endif
                        </div>

                        <!-- Bidding Form -->
                        @auth
                            @if(auth()->id() !== $auction->user_id && $auction->isActive())
                                <div class="mb-6 p-6 bg-white border border-gray-200 rounded-lg">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Postavite ponudu</h3>
                                    
                                    @if(!$isAutoBid)
                                        <div class="mb-4">
                                            <label for="bidAmount" class="block text-sm font-medium text-gray-700 mb-2">
                                                Vaša ponuda (minimum: {{ number_format($auction->minimum_bid, 0, ',', '.') }} RSD)
                                            </label>
                                            <input type="number" wire:model="bidAmount" id="bidAmount" 
                                                min="{{ $auction->minimum_bid }}" step="{{ $auction->bid_increment }}"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                            @error('bidAmount') 
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @endif

                                    <!-- Auto Bid Option -->
                                    <div class="mb-4">
                                        <div class="flex items-center justify-between">
                                            <label class="flex items-center cursor-pointer">
                                                <input type="checkbox" wire:model.live="isAutoBid" 
                                                    class="mr-3 h-4 w-4 text-red-600 focus:ring-red-500 rounded">
                                                <span class="text-sm text-gray-700">Automatska ponuda</span>
                                            </label>
                                            
                                            @php
                                                $userAutoBid = \App\Models\Bid::where('auction_id', $auction->id)
                                                    ->where('user_id', auth()->id())
                                                    ->where('is_auto_bid', true)
                                                    ->whereNotNull('max_bid')
                                                    ->latest()
                                                    ->first();
                                            @endphp
                                            @if($userAutoBid)
                                                <div class="flex items-center space-x-2">
                                                    <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded">
                                                        <i class="fas fa-robot mr-1"></i>
                                                        Auto-bid aktivan (do {{ number_format($userAutoBid->max_bid, 0, ',', '.') }} RSD)
                                                    </span>
                                                    <button wire:click="removeAutoBid" 
                                                        class="inline-flex items-center px-2 py-1 bg-red-100 text-red-700 text-xs font-medium rounded hover:bg-red-200"
                                                        onclick="return confirm('Da li želite da uklonite automatsku ponudu?')">
                                                        <i class="fas fa-times mr-1"></i>
                                                        Ukloni
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                        @if($isAutoBid)
                                            <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                                <div class="mb-3">
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Maksimalna cena</label>
                                                    <input type="number" wire:model="maxBidAmount" 
                                                        placeholder="Unesite maksimalnu cenu"
                                                        min="{{ $auction->minimum_bid + $auction->bid_increment }}"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                    @error('maxBidAmount') 
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                
                                                <!-- Set Auto-Bid Button -->
                                                <div class="mb-3">
                                                    <button type="button" wire:click="setAutoBid" 
                                                        class="w-full px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                                        <i class="fas fa-robot mr-2"></i>
                                                        Postavi automatsku ponudu
                                                    </button>
                                                </div>
                                                
                                                <div class="text-xs text-blue-800 space-y-1">
                                                    <div class="flex items-start">
                                                        <i class="fas fa-info-circle text-blue-600 mt-0.5 mr-2 flex-shrink-0"></i>
                                                        <div>
                                                            <strong>Kako funkcioniše automatska ponuda:</strong>
                                                            <ul class="list-disc list-inside mt-1 space-y-1">
                                                                <li>Kliknite "Postavi automatsku ponudu" da aktivirate</li>
                                                                <li>Sistem automatski povećava vašu ponudu kada vas neko nadmaši</li>
                                                                <li>Povećanje za {{ number_format($auction->bid_increment, 0, ',', '.') }} RSD (minimum korak)</li>
                                                                <li>Automatsko licitiranje se zaustavlja kad dostignete maksimalnu cenu</li>
                                                                <li>Dobićete obaveštenje svaki put kada se aktivira</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    @if(!$isAutoBid)
                                        <div class="space-y-3">
                                            <button type="button" wire:click="placeBid"
                                                class="w-full px-4 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-colors">
                                                <i class="fas fa-gavel mr-2"></i>
                                                Pošaljite ponudu
                                            </button>
                                        </div>
                                    @endif

                                    <!-- Common Actions (available in both modes) -->
                                    <div class="space-y-3 {{ $isAutoBid ? '' : 'mt-3' }}">
                                        @if($auction->buy_now_price && $auction->current_price < $auction->buy_now_price)
                                            <button type="button" 
                                                x-data="{ 
                                                    buyNow() {
                                                        if (confirm('Da li ste sigurni da želite da kupite odmah za {{ number_format($auction->buy_now_price, 0, ',', '.') }} RSD?')) {
                                                            $wire.confirmBuyNow();
                                                        }
                                                    }
                                                }"
                                                @click="buyNow()"
                                                class="w-full px-4 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors">
                                                <i class="fas fa-shopping-cart mr-2"></i>
                                                Kupi odmah ({{ number_format($auction->buy_now_price, 0, ',', '.') }} RSD)
                                            </button>
                                        @endif

                                        <!-- Message seller button -->
                                        @if(!$auction->seller->is_banned)
                                            <a href="{{ route('listing.chat', ['slug' => $auction->listing->slug]) }}"
                                                class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                                <i class="fas fa-envelope mr-2"></i>
                                                Pošaljite poruku prodavcu
                                            </a>
                                        @else
                                            <div class="w-full p-3 bg-red-50 border border-red-200 rounded-lg">
                                                <div class="flex items-center justify-center">
                                                    <i class="fas fa-ban text-red-500 mr-2"></i>
                                                    <span class="text-red-700 text-sm">Prodavac je blokiran</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    </div>
                                </div>
                            @endif
                        @endauth

                        <!-- Seller Info -->
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-user text-gray-500 mr-2"></i>
                                <span class="text-gray-700 font-bold">Prodavac: {{ $auction->seller->name }}</span>
                                {!! $auction->seller->verified_icon !!}
                            </div>
                            <div class="flex items-center mb-2">
                                <i class="fas fa-map-marker-alt text-gray-500 mr-2"></i>
                                <span class="text-gray-700">{{ $auction->listing->location }}</span>
                            </div>
                            <div class="flex items-center mb-2">
                                <i class="fas fa-folder text-gray-500 mr-2"></i>
                                <span class="text-gray-700">{{ $auction->listing->category->name }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-clock text-gray-500 mr-2"></i>
                                <span class="text-gray-700">
                                    @if($auction->isActive())
                                        @if($auction->time_left)
                                            {{ $auction->time_left['formatted'] }} ostalo
                                        @endif
                                    @else
                                        Završeno
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Listing Description -->
                <div class="border-t border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Opis proizvoda</h3>
                    <div class="text-gray-700 whitespace-pre-line">{{ $auction->listing->description }}</div>
                </div>
            </div>

        <!-- Bid History Section -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Istorija ponuda</h3>
                <button wire:click="$refresh" class="text-sm text-blue-600 hover:text-blue-800">
                    <i class="fas fa-refresh mr-1"></i> Osvežite
                </button>
            </div>
            
            @if($auction->bids->count() > 0)
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @foreach($auction->bids()->orderBy('amount', 'desc')->orderBy('created_at', 'desc')->take(10)->get() as $bid)
                        <div class="flex items-center justify-between p-3 {{ $bid->is_winning ? 'bg-green-50 border border-green-200' : 'bg-gray-50' }} rounded-lg">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3">
                                    @if($bid->user->avatar)
                                        <img src="{{ $bid->user->avatar_url }}" alt="{{ $bid->user->name }}" 
                                             class="w-8 h-8 rounded-full object-cover">
                                    @else
                                        <div class="w-8 h-8 bg-gray-500 rounded-full flex items-center justify-center text-white font-medium text-xs">
                                            {{ strtoupper(substr($bid->user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $bid->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $bid->created_at->format('d.m.Y H:i') }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-bold {{ $bid->is_winning ? 'text-green-600' : 'text-gray-900' }}">
                                    {{ number_format($bid->amount, 0, ',', '.') }} RSD
                                </div>
                                @if($bid->is_auto_bid)
                                    <div class="text-xs text-blue-500">Auto</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-gavel text-gray-400 text-3xl mb-3"></i>
                    <p class="text-gray-600">Još nema ponuda</p>
                    <p class="text-gray-500 text-sm">Budite prvi koji će licitirati!</p>
                </div>
            @endif
        </div>

        <!-- Auction Rules Section -->
        <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                Pravila licitiranja
            </h3>
            
            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="text-sm text-blue-800">
                    <ul class="space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-arrow-up text-blue-600 mt-1 mr-2 flex-shrink-0"></i>
                            <span>Minimalni korak povećanja ponude: <strong>{{ number_format($auction->bid_increment, 0, ',', '.') }} RSD</strong></span>
                        </li>
                        @php
                            $triggerTime = \App\Models\Setting::get('auction_extension_trigger_time', 3);
                            $extensionTime = \App\Models\Setting::get('auction_extension_time', 3);
                            $maxExtensions = \App\Models\Setting::get('auction_max_extensions', 10);
                        @endphp
                        <li class="flex items-start">
                            <i class="fas fa-clock text-blue-600 mt-1 mr-2 flex-shrink-0"></i>
                            <span>Ako se postavi ponuda u poslednje <strong>{{ $triggerTime }} minuta</strong> aukcije</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-plus text-blue-600 mt-1 mr-2 flex-shrink-0"></i>
                            <span>Aukcija se automatski produžava za <strong>{{ $extensionTime }} minuta</strong></span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-repeat text-blue-600 mt-1 mr-2 flex-shrink-0"></i>
                            <span>Ovo se može desiti maksimalno <strong>{{ $maxExtensions }} puta</strong> po aukciji</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-shield-alt text-blue-600 mt-1 mr-2 flex-shrink-0"></i>
                            <span>Sprečava "last second sniping" i omogućava fer nadmetanje</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-lock text-blue-600 mt-1 mr-2 flex-shrink-0"></i>
                            <span>Vlasnik ne može otkazati aukciju ako je trenutna cena veća od početne cene aukcije</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Real-time countdown script using Carbon data -->
<script>
    // Image gallery function
    function changeMainImage(src, element) {
        // Set main image
        document.getElementById('mainImage').src = src;

        // Remove previous border
        document.querySelectorAll('.border-yellow-500').forEach(item => {
            item.classList.remove('border-yellow-500');
            item.classList.add('border-gray-200');
        });

        // Add border to selected image
        element.parentElement.classList.remove('border-gray-200');
        element.parentElement.classList.add('border-yellow-500');
    }
    document.addEventListener('DOMContentLoaded', function() {
        const countdownElement = document.querySelector('.auction-countdown');
        
        if (countdownElement) {
            // Koristimo Carbon izračunat broj sekundi
            let totalSecondsLeft = parseInt(countdownElement.dataset.totalSeconds);
            
            function updateCountdown() {
                if (totalSecondsLeft <= 0) {
                    countdownElement.textContent = 'ZAVRŠENO';
                    // Refresh page when auction ends to show final results
                    setTimeout(() => location.reload(), 1000);
                    return;
                }
                
                // Izračunavamo vreme iz preostajućih sekundi
                const days = Math.floor(totalSecondsLeft / (24 * 60 * 60));
                const hours = Math.floor((totalSecondsLeft % (24 * 60 * 60)) / (60 * 60));
                const minutes = Math.floor((totalSecondsLeft % (60 * 60)) / 60);
                const seconds = totalSecondsLeft % 60;
                
                // Formatiramo prikaz kao što Carbon radi
                if (days > 0) {
                    countdownElement.textContent = `${days}d ${hours}h`;
                } else if (hours > 0) {
                    countdownElement.textContent = `${hours}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                } else {
                    countdownElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
                }
                
                // Smanjujemo broj sekundi za sledeći ciklus
                totalSecondsLeft--;
            }
            
            // Pokretamo countdown odmah i onda svakih sekund
            updateCountdown();
            const countdownInterval = setInterval(updateCountdown, 1000);
            
            // Čišćenje interval-a kad se komponenta uništi
            window.addEventListener('beforeunload', () => {
                clearInterval(countdownInterval);
            });
        }
    });
    
    // Refresh auction data after placing a bid (without full page reload)
    document.addEventListener('livewire:init', () => {
        Livewire.on('bid-placed', (event) => {
            // Update current price and bid count without full refresh
            setTimeout(() => {
                Livewire.dispatch('refreshAuction');
            }, 500);
        });
    });
</script>