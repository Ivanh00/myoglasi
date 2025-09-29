@props(['service'])

<div
    class="rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border-l-4
    {{ $service->hasActivePromotion('urgent') ? 'border-red-500' : ($service->hasActivePromotion('featured') ? 'border-sky-500' : ($service->hasActivePromotion('top') ? 'border-purple-500' : 'border-slate-500')) }}
    {{ $service->hasActivePromotion('highlighted') ? 'bg-amber-50 dark:bg-amber-900' : 'bg-white dark:bg-slate-700' }}">
    <div class="flex flex-col md:flex-row">
        <!-- Slika usluge -->
        <div class="w-full md:w-48 md:min-w-48 h-48">
            @if ($service->images->count() > 0)
                <img src="{{ $service->images->first()->url }}" alt="{{ $service->title }}"
                    class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-slate-200 flex items-center justify-center">
                    <i class="fas fa-tools text-slate-400 text-3xl"></i>
                </div>
            @endif
        </div>

        <!-- Informacije o usluzi -->
        <div class="flex-1 p-4 md:p-6">
            <div class="flex flex-col h-full">
                <div class="flex-1">
                    <div class="flex items-start">
                        <h3
                            class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2 hover:text-sky-600 transition-colors">
                            {{ $service->title }}
                        </h3>

                        <!-- Promotion Badges -->
                        @if ($service->hasActivePromotion())
                            <div class="flex flex-wrap gap-1 ml-2">
                                @foreach ($service->getPromotionBadges() as $badge)
                                    <span
                                        class="px-2 py-1 text-xs font-bold rounded-full {{ $badge['class'] }}">
                                        {{ $badge['text'] }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Pružalac usluge -->
                    @auth
                        <p class="text-sm font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Pružalac: {{ $service->user->name ?? 'Nepoznat korisnik' }}
                            @if ($service->user)
                                {!! $service->user->verified_icon !!}
                            @endif
                            @if ($service->user && $service->user->is_banned)
                                <span
                                    class="text-red-600 dark:text-red-400 font-bold ml-2">BLOKIRAN</span>
                            @endif
                            @if ($service->user && $service->user->shouldShowLastSeen())
                                <span class="text-xs text-slate-500 dark:text-slate-300 ml-2">
                                    @if ($service->user->is_online)
                                        <span class="inline-flex items-center">
                                            <span
                                                class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                            {{ $service->user->last_seen }}
                                        </span>
                                    @else
                                        {{ $service->user->last_seen }}
                                    @endif
                                </span>
                            @endif
                        </p>

                        {{-- User ratings --}}
                        @if ($service->user && $service->user->total_ratings_count > 0)
                            <a href="{{ route('user.ratings', $service->user->id) }}"
                                class="inline-flex items-center text-xs text-slate-600 dark:text-slate-300 mb-2 hover:text-sky-600 transition-colors">
                                <span class="text-green-600 dark:text-green-400 mr-1">😊
                                    {{ $service->user->positive_ratings_count }}</span>
                                <span class="text-amber-600 dark:text-amber-400 mr-1">😐
                                    {{ $service->user->neutral_ratings_count }}</span>
                                <span class="text-red-600 dark:text-red-400 mr-1">😞
                                    {{ $service->user->negative_ratings_count }}</span>
                                @if ($service->user->rating_badge)
                                    <span class="ml-1">{{ $service->user->rating_badge }}</span>
                                @endif
                                <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                            </a>
                        @endif
                    @endauth

                    <!-- Kategorija i lokacija -->
                    <div class="flex items-center gap-4 text-sm text-slate-600 dark:text-slate-400 mb-3">
                        @if ($service->serviceCategory)
                            <div class="flex items-center">
                                @if ($service->serviceCategory->icon)
                                    <i class="{{ $service->serviceCategory->icon }} mr-1"></i>
                                @endif
                                {{ $service->serviceCategory->name }}
                            </div>
                        @endif
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            {{ $service->location }}
                        </div>
                    </div>

                    <!-- Opis -->
                    <p class="text-slate-700 dark:text-slate-200 mb-3"
                        style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        {{ Str::limit(strip_tags($service->description), 150) }}
                    </p>
                </div>

                <!-- Cena i dugmad -->
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-2xl font-bold text-slate-900 dark:text-slate-100">
                            @if ($service->price_type === 'fixed')
                                {{ number_format($service->price, 0, ',', '.') }} RSD
                            @elseif($service->price_type === 'hourly')
                                {{ number_format($service->price, 0, ',', '.') }} RSD/sat
                            @else
                                Po dogovoru
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Desna strana - dodatne informacije i dugmad -->
        <div
            class="md:w-48 md:min-w-48 p-4 border-t md:border-t-0 md:border-l border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-800">
            <div class="flex flex-col h-full justify-between">
                <!-- Stats -->
                <div class="mb-4 space-y-2">
                    <div class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-400">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 4.5C7.5 4.5 3.5 8.5 2 12c1.5 3.5 5.5 7.5 10 7.5s8.5-4 10-7.5c-1.5-3.5-5.5-7.5-10-7.5zm0 12c-2.5 0-4.5-2-4.5-4.5S9.5 8.5 12 8.5 16.5 10.5 16.5 12 14.5 16.5 12 16.5zm0-7c-1.5 0-2.5 1-2.5 2.5S10.5 14.5 12 14.5 14.5 13.5 14.5 12 13.5 9.5 12 9.5z" />
                            </svg>
                            <span>{{ $service->views ?? 0 }} pregleda</span>
                        </div>
                    </div>
                    <div class="text-xs text-slate-600 dark:text-slate-400">
                        <i class="fas fa-clock mr-1"></i>
                        Postavljeno pre {{ $service->created_at ? floor($service->created_at->diffInDays()) : 0 }} dana
                    </div>
                </div>

                <!-- Buttons -->
                <div class="space-y-2">
                    @auth
                        @if (auth()->id() === $service->user_id)
                            <!-- Owner buttons -->
                            <a href="{{ route('services.edit', $service) }}"
                                class="block w-full text-center px-3 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-lg transition-colors text-sm">
                                <i class="fas fa-edit mr-2"></i> Uredi uslugu
                            </a>
                        @else
                            <!-- User buttons -->
                            <a href="{{ route('services.show', $service) }}"
                                class="block w-full text-center px-3 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-lg transition-colors text-sm">
                                <i class="fas fa-eye mr-2"></i> Pogledaj
                            </a>
                        @endif
                    @else
                        <!-- Guest button -->
                        <a href="{{ route('services.show', $service) }}"
                            class="block w-full text-center px-3 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-lg transition-colors text-sm">
                            <i class="fas fa-eye mr-2"></i> Pogledaj
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>