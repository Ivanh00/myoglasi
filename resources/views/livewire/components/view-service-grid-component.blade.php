@props(['service'])

<div
    class="bg-white dark:bg-slate-700 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border-l-4 flex flex-col h-full
    {{ $service->hasActivePromotion('urgent') ? 'border-red-500' : ($service->hasActivePromotion('featured') ? 'border-sky-500' : ($service->hasActivePromotion('top') ? 'border-purple-500' : 'border-slate-500')) }}
    {{ $service->hasActivePromotion('highlighted') ? 'bg-amber-50 dark:bg-amber-900' : '' }}">
    <!-- Slika usluge -->
    <div class="w-full h-48">
        @if ($service->images->count() > 0)
            <img src="{{ $service->images->first()->url }}" alt="{{ $service->title }}"
                class="w-full h-full object-cover">
        @else
            <div
                class="w-full h-full bg-slate-200 dark:bg-slate-800 flex items-center justify-center">
                <i class="fas fa-tools text-slate-400 dark:text-slate-500 text-4xl"></i>
            </div>
        @endif
    </div>

    <!-- Informacije o usluzi -->
    <div class="p-4 flex flex-col flex-1">
        <!-- Main content -->
        <div class="flex-1">
            <div class="flex items-start justify-between mb-2">
                <h3
                    class="text-lg font-semibold text-slate-900 dark:text-slate-100 hover:text-slate-600 dark:hover:text-slate-400 transition-colors">
                    {{ $service->title }}
                </h3>
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

            <p class="text-sm text-slate-600 dark:text-slate-300 mb-3 line-clamp-2">
                {{ Str::limit($service->description, 100) }}
            </p>

            <!-- Kategorija -->
            @if ($service->serviceCategory)
                <div class="flex items-center text-xs text-slate-600 dark:text-slate-400 mb-3">
                    @if ($service->serviceCategory->icon)
                        <i class="{{ $service->serviceCategory->icon }} mr-1"></i>
                    @endif
                    {{ $service->serviceCategory->name }}
                </div>
            @endif

            <!-- Lokacija -->
            <div class="flex items-center text-xs text-slate-600 dark:text-slate-400 mb-3">
                <i class="fas fa-map-marker-alt mr-1"></i>
                {{ $service->location }}
            </div>

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
            <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 mb-3">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 4.5C7.5 4.5 3.5 8.5 2 12c1.5 3.5 5.5 7.5 10 7.5s8.5-4 10-7.5c-1.5-3.5-5.5-7.5-10-7.5zm0 12c-2.5 0-4.5-2-4.5-4.5S9.5 8.5 12 8.5 16.5 10.5 16.5 12 14.5 16.5 12 16.5zm0-7c-1.5 0-2.5 1-2.5 2.5S10.5 14.5 12 14.5 14.5 13.5 14.5 12 13.5 9.5 12 9.5z" />
                    </svg>
                    <span>{{ $service->views ?? 0 }}</span>
                </div>
                <div>
                    <i class="fas fa-clock mr-1"></i>
                    Pre {{ $service->created_at ? floor($service->created_at->diffInDays()) : 0 }} dana
                </div>
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
                        <i class="fas fa-eye mr-2"></i> Detalji
                    </a>
                @endif
            @else
                <!-- Guest button -->
                <a href="{{ route('services.show', $service) }}"
                    class="block w-full text-center px-3 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-lg transition-colors text-sm">
                    <i class="fas fa-eye mr-2"></i> Detalji
                </a>
            @endauth
        </div>
    </div>
</div>