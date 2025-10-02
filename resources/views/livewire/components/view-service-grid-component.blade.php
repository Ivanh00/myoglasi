@props(['service'])

<div
    class="bg-white dark:bg-slate-700 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border-l-4 flex flex-col h-full
    {{ $service->hasActivePromotion('urgent') ? 'border-red-500' : ($service->hasActivePromotion('featured') ? 'border-sky-500' : ($service->hasActivePromotion('top') ? 'border-purple-500' : 'border-slate-500')) }}
    {{ $service->hasActivePromotion('highlighted') ? 'bg-amber-50 dark:bg-amber-900' : '' }}">
    <!-- Slika usluge -->
    <div class="w-full h-48">
        <a href="{{ route('services.show', $service) }}">
            @if ($service->images->count() > 0)
                <img src="{{ $service->images->first()->url }}" alt="{{ $service->title }}"
                    class="w-full h-full object-cover">
            @else
                <div
                    class="w-full h-full bg-slate-200 dark:bg-slate-800 flex items-center justify-center">
                    <i class="fas fa-tools text-slate-400 dark:text-slate-500 text-4xl"></i>
                </div>
            @endif
        </a>
    </div>

    <!-- Informacije o usluzi -->
    <div class="p-4 flex flex-col flex-1">
        <!-- Main content -->
        <div class="flex-1">
            <div class="flex items-start justify-between mb-2">
                <a href="{{ route('services.show', $service) }}" class="flex-1">
                    <h3
                        class="text-lg font-semibold text-slate-900 dark:text-slate-100 hover:text-slate-600 dark:hover:text-slate-400 transition-colors">
                        {{ $service->title }}
                    </h3>
                </a>
                <!-- Promotion Badges -->
                @if ($service->hasActivePromotion())
                    <div class="flex flex-wrap gap-1">
                        @foreach ($service->getPromotionBadges() as $badge)
                            <span
                                class="px-2 py-1 text-xs font-bold rounded-full {{ $badge['class'] }}">
                                {{ $badge['text'] }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- User info --}}
            @auth
                <p class="text-sm font-bold text-slate-700 dark:text-slate-200 mb-1">
                    {{ $service->user->name ?? 'Nepoznat korisnik' }}
                    @if ($service->user)
                        {!! $service->user->verified_icon !!}
                    @endif
                    @if ($service->user && $service->user->is_banned)
                        <span class="text-red-600 dark:text-red-400 font-bold ml-1">BLOKIRAN</span>
                    @endif
                    @if ($service->user && $service->user->shouldShowLastSeen())
                        <span class="text-xs text-slate-500 dark:text-slate-300 ml-2">
                            @if ($service->user->is_online)
                                <span class="inline-flex items-center">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                    {{ $service->user->last_seen }}
                                </span>
                            @else
                                {{ $service->user->last_seen }}
                            @endif
                        </span>
                    @endif
                </p>
            @endauth

            <!-- Lokacija i kategorija -->
            <div class="flex items-center text-sm text-slate-600 dark:text-slate-300 mb-2">
                <i class="fas fa-map-marker-alt mr-1"></i>
                <span class="truncate">{{ Str::limit($service->location, 15) }}</span>
                <span class="mx-2">•</span>
                <i class="fas fa-folder mr-1"></i>
                <span class="truncate">{{ $service->category ? Str::limit($service->category->name, 15) : 'Bez kategorije' }}</span>
            </div>

            <p class="text-slate-700 dark:text-slate-200 mb-3 text-sm"
                style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                {{ Str::limit(strip_tags($service->description), 80) }}
            </p>

            <!-- Cena -->
            <div class="mb-3">
                <span class="text-xl font-bold text-slate-700 dark:text-slate-300">
                    @if ($service->price_type === 'fixed')
                        {{ number_format($service->price, 0, ',', '.') }} RSD
                    @elseif($service->price_type === 'hourly')
                        {{ number_format($service->price, 0, ',', '.') }} RSD/sat
                    @elseif($service->price_type === 'daily')
                        {{ number_format($service->price, 0, ',', '.') }} RSD/dan
                    @elseif($service->price_type === 'per_m2')
                        {{ number_format($service->price, 0, ',', '.') }} RSD/m²
                    @else
                        Po dogovoru
                    @endif
                </span>
            </div>

            <!-- Stats -->
            <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-300 mb-3">
                <div class="flex items-center">
                    <i class="fas fa-eye mr-1"></i>
                    <span>{{ $service->views ?? 0 }}</span>
                </div>
                <div class="flex items-center">
                    <span>❤️ 0</span>
                </div>
            </div>

            <div class="text-xs text-slate-500 dark:text-slate-300 mb-3">
                <i class="fas fa-clock mr-1"></i>
                Postavljeno {{ $service->created_at?->diffForHumans() ?? 'nepoznato' }}
            </div>
        </div>

        <!-- Buttons - Always at bottom -->
        <div class="mt-auto">
            @auth
                @if (auth()->id() === $service->user_id)
                    <!-- Owner button -->
                    <a href="{{ route('services.edit', $service) }}"
                        class="block w-full text-center px-3 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-lg transition-colors text-sm">
                        <i class="fas fa-edit mr-2"></i> Uredi
                    </a>
                @else
                    <!-- Contact button -->
                    <a href="{{ route('services.show', $service) }}"
                        class="block w-full text-center px-3 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-lg transition-colors text-sm">
                        <i class="fas fa-eye mr-2"></i> Pregled
                    </a>
                @endif
            @else
                <!-- Guest button -->
                <a href="{{ route('services.show', $service) }}"
                    class="block w-full text-center px-3 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-lg transition-colors text-sm">
                    <i class="fas fa-eye mr-2"></i> Pregled
                </a>
            @endauth
        </div>
    </div>
</div>