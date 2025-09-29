@props(['seller', 'listing', 'showPhone' => true])

<div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg overflow-hidden">
    <div class="p-2 md:p-6 bg-slate-50 dark:bg-slate-700">
        <h2 class="text-xl font-semibold text-slate-800 dark:text-slate-200 mb-4">Informacije o
            @if(class_exists('\App\Models\Service') && $listing instanceof \App\Models\Service)
                pružaocu usluge
            @elseif(isset($listing->listing_type) && $listing->listing_type === 'giveaway')
                davaocu
            @else
                prodavcu
            @endif
        </h2>

        <!-- Main flex container - column on mobile, row on desktop with justify-between -->
        <div class="flex flex-col md:flex-row md:items-start md:justify-between">
            <!-- Left side: Avatar and user info -->
            <div class="flex items-start mb-4 md:mb-0">
                <!-- Avatar -->
                <div class="w-16 h-16 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                    @if ($seller->avatar)
                        <img src="{{ $seller->avatar_url }}" alt="{{ $seller->name }}"
                            class="w-16 h-16 rounded-full object-cover">
                    @else
                        <div
                            class="w-16 h-16 bg-sky-500 rounded-full flex items-center justify-center text-white font-bold text-xl">
                            {{ strtoupper(substr($seller->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <div class="flex-1">
                    <!-- Name with verified icon -->
                    <h3 class="font-medium text-slate-900 dark:text-slate-100 text-lg">
                        {{ $seller->name }}
                        {!! $seller->verified_icon !!}
                        @if ($seller->is_banned)
                            <span class="text-red-600 dark:text-red-400 font-bold ml-2">BLOKIRAN</span>
                        @endif
                    </h3>

                    <!-- Online status -->
                    @if ($seller->shouldShowLastSeen())
                        <div class="text-sm text-slate-500 dark:text-slate-300">
                            @if ($seller->is_online)
                                <span class="inline-flex items-center">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                    {{ $seller->last_seen }}
                                </span>
                            @else
                                {{ $seller->last_seen }}
                            @endif
                        </div>
                    @endif

                    <!-- Member since -->
                    <p class="text-slate-600 dark:text-slate-400 text-sm mt-1">Član od: {{ $seller->created_at->format('m/Y') }}</p>
                </div>
            </div>

            <!-- Right side on desktop / Bottom on mobile: Location, phone, statistics -->
            <div class="space-y-3 border-t pt-4 md:border-t-0 md:border-0 md:pt-0">
                <!-- Location and Phone on same line -->
                <div class="flex items-center gap-6 text-sm md:justify-end">
                    <div class="flex items-center text-slate-600 dark:text-slate-400">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        <span>{{ $listing->location ?? ($listing->listing ? $listing->listing->location : 'N/A') }}</span>
                    </div>

                    {{-- Show phone if allowed, user logged in and seller NOT banned --}}
                    @if ($showPhone && auth()->check())
                        @php
                            $contactPhone = $listing->contact_phone ?? ($listing->listing->contact_phone ?? null);
                        @endphp
                        @if ($contactPhone && $seller->phone_visible && !$seller->is_banned)
                            <div class="flex items-center text-slate-600 dark:text-slate-400">
                                <i class="fas fa-phone mr-2"></i>
                                <a href="tel:{{ $contactPhone }}"
                                    class="text-slate-700 dark:text-slate-200 hover:text-green-600">
                                    {{ $contactPhone }}
                                </a>
                            </div>
                        @endif
                    @endif
                </div>

                <!-- Statistics -->
                <div class="flex items-center gap-6 text-sm md:justify-end">
                    <div class="flex items-center text-slate-600 dark:text-slate-400">
                        <i class="fas fa-list-ul mr-2"></i>
                        <span>Aktivnih oglasa:
                            @php
                                $activeListingsCount = \App\Models\Listing::where('user_id', $seller->id)
                                    ->where('status', 'active')
                                    ->count();
                            @endphp
                            <span class="font-semibold text-slate-900 dark:text-slate-100">{{ $activeListingsCount }}</span>
                        </span>
                    </div>

                    @if(class_exists('\App\Models\Service'))
                        <div class="flex items-center text-slate-600 dark:text-slate-400">
                            <i class="fas fa-tools mr-2"></i>
                            <span>Aktivnih usluga:
                                @php
                                    $activeServicesCount = \App\Models\Service::where('user_id', $seller->id)
                                        ->where('status', 'active')
                                        ->count();
                                @endphp
                                <span class="font-semibold text-slate-900 dark:text-slate-100">{{ $activeServicesCount }}</span>
                            </span>
                        </div>
                    @endif
                </div>

                {{-- User ratings if exists --}}
                @if ($seller->total_ratings_count > 0)
                    <a href="{{ route('user.ratings', $seller->id) }}"
                        class="inline-flex items-center text-sm text-slate-600 dark:text-slate-400 hover:text-sky-600 dark:hover:text-sky-400 transition-colors">
                        <span class="text-green-600 dark:text-green-400 mr-2">😊
                            {{ $seller->positive_ratings_count }}</span>
                        <span class="text-amber-600 dark:text-amber-400 mr-2">😐
                            {{ $seller->neutral_ratings_count }}</span>
                        <span class="text-red-600 dark:text-red-400 mr-2">😞
                            {{ $seller->negative_ratings_count }}</span>
                        @if ($seller->rating_badge)
                            <span class="ml-1 mr-2">{{ $seller->rating_badge }}</span>
                        @endif
                        <span class="text-xs text-slate-500 dark:text-slate-400">
                            <i class="fas fa-external-link-alt ml-1"></i>
                        </span>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>