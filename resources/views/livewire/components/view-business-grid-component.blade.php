@props(['business'])

<!-- Grid View -->
<div
    class="bg-white dark:bg-slate-700 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border-l-4 border-purple-500 flex flex-col h-full">
    <!-- Image -->
    <div class="w-full h-48">
        <a href="{{ route('businesses.show', $business) }}">
            @if ($business->images->count() > 0)
                <img src="{{ $business->images->first()->url }}" alt="{{ $business->name }}"
                    class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-slate-200 dark:bg-slate-800 flex items-center justify-center">
                    <i class="fas fa-briefcase text-slate-400 text-3xl"></i>
                </div>
            @endif
        </a>
    </div>

    <!-- Content -->
    <div class="p-4 flex flex-col flex-1">
        <!-- Main content -->
        <div class="flex-1">
            <div class="flex items-start justify-between mb-2">
                <a href="{{ route('businesses.show', $business) }}" class="flex-1">
                    <h3
                        class="text-lg font-semibold text-slate-900 dark:text-slate-100 hover:text-purple-600 transition-colors">
                        {{ Str::limit($business->name, 40) }}
                    </h3>
                </a>

                <!-- Promotion Badges -->
                @if ($business->hasActivePromotion())
                    <div class="flex flex-wrap gap-1 ml-2">
                        @foreach ($business->getPromotionBadges() as $badge)
                            <span class="px-2 py-1 text-xs font-bold rounded-full {{ $badge['class'] }}">
                                {{ $badge['text'] }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>

            @if ($business->slogan)
                <p class="text-sm italic text-slate-600 dark:text-slate-300 mb-2">
                    "{{ Str::limit($business->slogan, 60) }}"
                </p>
            @endif

            {{-- Owner info --}}
            @auth
                <p class="text-sm font-bold text-slate-700 dark:text-slate-200 mb-1">
                    {{ $business->user->name ?? 'Nepoznat korisnik' }}
                    @if ($business->user)
                        {!! $business->user->verified_icon !!}
                    @endif
                    @if ($business->user && $business->user->is_banned)
                        <span class="text-red-600 dark:text-red-400 font-bold ml-1">BLOKIRAN</span>
                    @endif
                </p>
            @endauth

            <div class="flex items-center text-sm text-slate-600 dark:text-slate-300 mb-2">
                <i class="fas fa-map-marker-alt mr-1"></i>
                <span class="truncate">{{ Str::limit($business->location, 15) }}</span>
                <span class="mx-2">"</span>
                <i class="fas fa-folder mr-1"></i>
                <span class="truncate">{{ Str::limit($business->category->name, 15) }}</span>
            </div>

            @if ($business->established_year)
                <div class="text-xs text-slate-600 dark:text-slate-300 mb-2">
                    <i class="fas fa-calendar-alt mr-1"></i>
                    Posluje od {{ $business->established_year }}. godine
                </div>
            @endif

            <p class="text-slate-700 dark:text-slate-200 text-sm mb-3"
                style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                {{ Str::limit(strip_tags($business->description), 100) }}
            </p>

            <!-- Stats -->
            <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-300 mb-3">
                <div class="flex items-center">
                    <i class="fas fa-eye mr-1"></i>
                    <span>{{ $business->views ?? 0 }}</span>
                </div>
            </div>

            <div class="text-xs text-slate-500 dark:text-slate-300 mb-3">
                <i class="fas fa-clock mr-1"></i>
                Postavljeno {{ $business->created_at->diffForHumans() }}
            </div>
        </div>

        <!-- Buttons - Always at bottom -->
        <div class="mt-auto">
            @auth
                @if (auth()->id() === $business->user_id)
                    <!-- Owner button -->
                    <a href="{{ route('businesses.edit', $business) }}"
                        class="block w-full text-center px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors text-sm">
                        <i class="fas fa-edit mr-2"></i> Uredi
                    </a>
                @else
                    <!-- View button -->
                    <a href="{{ route('businesses.show', $business) }}"
                        class="block w-full text-center px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors text-sm">
                        <i class="fas fa-eye mr-2"></i> Pregled
                    </a>
                @endif
            @else
                <!-- Guest button -->
                <a href="{{ route('businesses.show', $business) }}"
                    class="block w-full text-center px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors text-sm">
                    <i class="fas fa-eye mr-2"></i> Pregled
                </a>
            @endauth
        </div>
    </div>
</div>
