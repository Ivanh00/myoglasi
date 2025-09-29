@props(['listing'])

<!-- Grid View -->
<div
    class="listing-card {{ $listing->isHighlighted() ? 'bg-amber-100 dark:bg-amber-900 border-2 border-amber-400 dark:border-amber-600' : 'bg-white dark:bg-slate-700 border-l-4 ' . ($listing->isGiveaway() ? 'border-green-500' : 'border-sky-500') }} rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 flex flex-col h-full">
    <!-- Image -->
    <div class="w-full h-48">
        <a href="{{ route('listings.show', $listing) }}">
            @if ($listing->images->count() > 0)
                <x-responsive-image :image="$listing->images->first()" :alt="$listing->title"
                    class="w-full h-full object-cover" />
            @else
                <div class="w-full h-full bg-slate-200 flex items-center justify-center">
                    <i class="fas fa-image text-slate-400 text-3xl"></i>
                </div>
            @endif
        </a>
    </div>

    <!-- Content -->
    <div class="p-4 flex flex-col flex-1">
        <!-- Main content -->
        <div class="flex-1">
            <div class="flex items-start justify-between mb-2">
                <a href="{{ route('listings.show', $listing) }}" class="flex-1">
                    <h3
                        class="text-lg font-semibold text-slate-900 dark:text-slate-100 hover:text-sky-600 transition-colors">
                        {{ Str::limit($listing->title, 40) }}
                    </h3>
                </a>

                <!-- Promotion Badges -->
                @if ($listing->hasActivePromotion())
                    <div class="flex flex-wrap gap-1 ml-2">
                        @foreach ($listing->getPromotionBadges() as $badge)
                            <span
                                class="px-2 py-1 text-xs font-bold rounded-full {{ $badge['class'] }}">
                                {{ $badge['text'] }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Seller info --}}
            @auth
                <p class="text-sm font-bold text-slate-700 dark:text-slate-200 mb-1">
                    {{ $listing->user->name ?? 'Nepoznat korisnik' }}
                    @if ($listing->user)
                        {!! $listing->user->verified_icon !!}
                    @endif
                    @if ($listing->user && $listing->user->is_banned)
                        <span class="text-red-600 dark:text-red-400 font-bold ml-1">BLOKIRAN</span>
                    @endif
                    @if ($listing->user && $listing->user->shouldShowLastSeen())
                        <span class="text-xs text-slate-500 dark:text-slate-300 ml-2">
                            @if ($listing->user->is_online)
                                <span class="inline-flex items-center">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                    {{ $listing->user->last_seen }}
                                </span>
                            @else
                                {{ $listing->user->last_seen }}
                            @endif
                        </span>
                    @endif
                </p>

                {{-- User ratings --}}
                @if ($listing->user && $listing->user->total_ratings_count > 0)
                    <a href="{{ route('user.ratings', $listing->user->id) }}"
                        class="inline-flex items-center text-xs text-slate-600 dark:text-slate-300 mb-2 hover:text-sky-600 transition-colors">
                        <span class="text-green-600 dark:text-green-400 mr-1">😊
                            {{ $listing->user->positive_ratings_count }}</span>
                        <span class="text-amber-600 dark:text-amber-400 mr-1">😐
                            {{ $listing->user->neutral_ratings_count }}</span>
                        <span class="text-red-600 dark:text-red-400 mr-1">😞
                            {{ $listing->user->negative_ratings_count }}</span>
                        @if ($listing->user->rating_badge)
                            <span class="ml-1">{{ $listing->user->rating_badge }}</span>
                        @endif
                        <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                    </a>
                @endif
            @endauth

            <div class="flex items-center text-sm text-slate-600 dark:text-slate-300 mb-2">
                <i class="fas fa-map-marker-alt mr-1"></i>
                <span class="truncate">{{ Str::limit($listing->location, 15) }}</span>
                <span class="mx-2">•</span>
                <i class="fas fa-folder mr-1"></i>
                <span class="truncate">{{ Str::limit($listing->category->name, 15) }}</span>
            </div>

            <p class="text-slate-700 dark:text-slate-200 text-sm mb-3"
                style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                {{ Str::limit(strip_tags($listing->description), 100) }}
            </p>

            <div class="flex items-center justify-between mb-3">
                @if ($listing->isGiveaway())
                    <div class="text-green-600 dark:text-green-400 font-bold text-xl">BESPLATNO
                    </div>
                @else
                    <div class="text-sky-600 dark:text-sky-400 font-bold text-xl">
                        {{ number_format($listing->price, 2) }} RSD
                    </div>
                @endif

                <div class="flex items-center gap-2">
                    @if ($listing->getTypeBadge())
                        <span
                            class="px-2 py-1 text-xs font-bold rounded-full {{ $listing->getTypeBadge()['class'] }}">
                            {{ $listing->getTypeBadge()['text'] }}
                        </span>
                    @endif

                    @if ($listing->condition)
                        <span
                            class="px-2 py-1 bg-slate-200 dark:bg-slate-500 text-slate-800 dark:text-slate-100 text-xs font-medium rounded-full">
                            {{ $listing->condition->name }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Stats -->
            <div
                class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-300 mb-3">
                <div class="flex items-center">
                    <i class="fas fa-eye mr-1"></i>
                    <span>{{ $listing->views ?? 0 }}</span>
                </div>
                <div class="flex items-center">
                    <span>❤️ {{ $listing->favorites_count ?? 0 }}</span>
                </div>
            </div>

            <div class="text-xs text-slate-500 dark:text-slate-300 mb-3">
                <i class="fas fa-clock mr-1"></i>
                Postavljeno pre {{ floor($listing->created_at->diffInDays()) }} dana
            </div>
        </div>

        <!-- Buttons - Always at bottom -->
        <div class="mt-auto">
            @auth
                @if (auth()->id() === $listing->user_id)
                    <!-- Owner buttons -->
                    @if ($listing->isRegularListing() && !$listing->auction)
                        <a href="{{ route('auction.setup', $listing) }}"
                            class="block w-full text-center px-3 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg transition-colors text-sm mb-2">
                            <i class="fas fa-gavel mr-2"></i> Prodaj na aukciji
                        </a>
                    @endif

                    <a href="{{ route('listings.edit', $listing) }}"
                        class="block w-full text-center px-3 py-2 {{ $listing->auction ? 'bg-amber-600 hover:bg-amber-700' : ($listing->isGiveaway() ? 'bg-green-600 hover:bg-green-700' : 'bg-sky-600 hover:bg-sky-700') }} text-white rounded-lg transition-colors text-sm">
                        @if ($listing->auction)
                            <i class="fas fa-gavel mr-2"></i> Uredi aukciju
                        @else
                            <i class="fas fa-edit mr-2"></i> Uredi oglas
                        @endif
                    </a>
                @else
                    <!-- Regular view button -->
                    <a href="{{ route('listings.show', $listing) }}"
                        class="block w-full text-center px-3 py-2 {{ $listing->isGiveaway() ? 'bg-green-600 hover:bg-green-700' : 'bg-sky-600 hover:bg-sky-700' }} text-white rounded-lg transition-colors text-sm">
                        <i class="fas fa-eye mr-2"></i> Pregled
                    </a>
                @endif
            @else
                <!-- Guest user button -->
                <a href="{{ route('listings.show', $listing) }}"
                    class="block w-full text-center px-3 py-2 {{ $listing->isGiveaway() ? 'bg-green-600 hover:bg-green-700' : 'bg-sky-600 hover:bg-sky-700' }} text-white rounded-lg transition-colors text-sm">
                    <i class="fas fa-eye mr-2"></i> Pregled
                </a>
            @endauth
        </div>
    </div>
</div>