@props(['auction'])

<div class="bg-white dark:bg-slate-700 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border-l-4 border-amber-500">
    <!-- Image -->
    <div class="w-full h-48 relative">
        @if ($auction->listing->images->count() > 0)
            <img src="{{ $auction->listing->images->first()->url }}"
                alt="{{ $auction->listing->title }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full bg-slate-200 flex items-center justify-center">
                <i class="fas fa-image text-slate-400 text-3xl"></i>
            </div>
        @endif

        <!-- Time overlay -->
        <div class="absolute top-2 right-2">
            <span class="px-2 py-1 bg-green-600 bg-opacity-90 text-white text-xs font-medium rounded">
                @if ($auction->time_left)
                    {{ $auction->time_left['formatted'] }}
                @endif
            </span>
        </div>
    </div>

    <!-- Content -->
    <div class="p-4">
        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2 line-clamp-1">
            {{ $auction->listing->title }}
        </h3>

        <div class="flex items-center text-sm text-slate-600 dark:text-slate-300 mb-2">
            <i class="fas fa-map-marker-alt mr-1"></i>
            <span>{{ Str::limit($auction->listing->location, 15) }}</span>
            <span class="mx-2">•</span>
            <i class="fas fa-folder mr-1"></i>
            <span>{{ $auction->listing->category->name }}</span>
        </div>

        <p class="text-slate-700 dark:text-slate-200 mb-3 text-sm"
            style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
            {{ Str::limit(strip_tags($auction->listing->description), 80) }}
        </p>

        <div class="mb-3">
            <div class="text-xl font-bold text-red-600 dark:text-red-400">
                {{ number_format($auction->current_price, 0, ',', '.') }} RSD
            </div>
            <div class="text-sm text-slate-500 dark:text-slate-300">
                {{ $auction->total_bids }} ponuda
            </div>
        </div>

        @if ($auction->buy_now_price && $auction->current_price < $auction->buy_now_price)
            <div class="mb-3">
                <div class="text-xs text-slate-500 dark:text-slate-300">Kupi odmah:</div>
                <div class="text-lg font-bold text-green-600 dark:text-green-400">
                    {{ number_format($auction->buy_now_price, 0, ',', '.') }} RSD
                </div>
            </div>
        @endif
    </div>

    <!-- Bottom section -->
    <div class="bg-amber-50 dark:bg-amber-900 p-4">
        <div class="text-center mb-3">
            <div class="text-lg font-bold text-amber-700 dark:text-amber-200">
                @if ($auction->time_left)
                    {{ $auction->time_left['formatted'] }}
                @endif
            </div>
            <div class="text-xs text-amber-600 dark:text-amber-400">vremena ostalo</div>
        </div>

        <div class="space-y-2">
            @auth
                @if (auth()->id() === $auction->user_id)
                    <a href="{{ route('listings.edit', $auction->listing) }}"
                        class="block w-full text-center px-3 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors text-sm">
                        <i class="fas fa-gavel mr-2"></i> Uredi
                    </a>
                @else
                    <a href="{{ route('auction.show', $auction) }}"
                        class="block w-full text-center px-3 py-2 bg-amber-700 text-white rounded-lg hover:bg-amber-800 transition-colors text-sm">
                        <i class="fas fa-gavel mr-2"></i> Licitiraj
                    </a>
                    @if ($auction->buy_now_price && $auction->current_price < $auction->buy_now_price)
                        <a href="{{ route('auction.show', $auction) }}"
                            class="block w-full text-center px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-xs">
                            <i class="fas fa-shopping-cart mr-1"></i> Kupi odmah
                        </a>
                    @endif
                @endif
            @else
                <a href="{{ route('login') }}"
                    class="block w-full text-center px-3 py-2 bg-amber-700 text-white rounded-lg hover:bg-amber-800 transition-colors text-sm">
                    <i class="fas fa-sign-in-alt mr-2"></i> Prijavite se
                </a>
            @endauth
        </div>
    </div>
</div>