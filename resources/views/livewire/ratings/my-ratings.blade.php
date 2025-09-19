<div class="messages-container bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100">
    <style>
        /* Dark mode hover fixes for ratings */
        .dark .conversation-item:hover {
            background-color: rgb(75 85 99) !important; /* slate-600 */
        }
        .dark .conversation-item {
            background-color: rgb(17 24 39) !important; /* slate-900 - same as main background */
            border-color: rgb(75 85 99) !important; /* slate-600 */
        }
        .dark .user-name, .dark .listing-name, .dark .preview-text, .dark .date-info {
            color: rgb(229 231 235) !important; /* slate-200 */
        }
        .dark .text-slate-400 {
            color: rgb(156 163 175) !important; /* slate-400 */
        }
    </style>
    <!-- Header -->
    <section class="navigation-holder">
        <div class="messages-header">
            <h1 class="text-slate-900 dark:text-slate-100">Moje ocene</h1>
        </div>
    </section>

    <!-- Filter Options (Smiley Filters) -->
    <div class="p-4 bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-600">
        <!-- Desktop filters -->
        <div class="hidden md:block">
            <div class="flex justify-center items-center gap-6">
                <!-- All Ratings -->
                <button wire:click="setFilter('all')" 
                    class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all {{ $filter === 'all' ? 'bg-sky-200 dark:bg-sky-800 text-sky-800 dark:text-sky-200 shadow-md' : 'hover:bg-slate-100 dark:hover:bg-slate-600' }}">
                    <span class="text-2xl">📊</span>
                    <span class="font-medium">Sve</span>
                    <span class="text-sm text-slate-500 dark:text-slate-400">({{ $user->total_ratings_count }})</span>
                </button>
                
                <!-- Positive -->
                <button wire:click="setFilter('positive')" 
                    class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all {{ $filter === 'positive' ? 'bg-green-200 dark:bg-green-800 text-green-800 dark:text-green-200 shadow-md' : 'hover:bg-green-100 dark:hover:bg-green-700' }}">
                    <span class="text-2xl">😊</span>
                    <span class="font-medium">Pozitivne</span>
                    <span class="text-sm text-slate-500 dark:text-slate-400">({{ $user->positive_ratings_count }})</span>
                </button>
                
                <!-- Neutral -->
                <button wire:click="setFilter('neutral')" 
                    class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all {{ $filter === 'neutral' ? 'bg-amber-200 dark:bg-amber-800 text-amber-800 dark:text-amber-200 shadow-md' : 'hover:bg-amber-100 dark:hover:bg-amber-700' }}">
                    <span class="text-2xl">😐</span>
                    <span class="font-medium">Neutralne</span>
                    <span class="text-sm text-slate-500 dark:text-slate-400">({{ $user->neutral_ratings_count }})</span>
                </button>
                
                <!-- Negative -->
                <button wire:click="setFilter('negative')" 
                    class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all {{ $filter === 'negative' ? 'bg-red-200 dark:bg-red-800 text-red-800 dark:text-red-200 shadow-md' : 'hover:bg-red-100 dark:hover:bg-red-700' }}">
                    <span class="text-2xl">😞</span>
                    <span class="font-medium">Negativne</span>
                    <span class="text-sm text-slate-500 dark:text-slate-400">({{ $user->negative_ratings_count }})</span>
                </button>
            </div>
        </div>

        <!-- Mobile filters -->
        <div class="md:hidden grid grid-cols-2 gap-3">
            <button wire:click="setFilter('all')" 
                class="flex items-center justify-center gap-2 px-3 py-2 rounded-lg {{ $filter === 'all' ? 'bg-sky-200 dark:bg-sky-800 text-sky-800 dark:text-sky-200' : 'bg-slate-100 dark:bg-slate-600' }}">
                <span class="text-lg">📊</span>
                <span class="text-sm font-medium">Sve ({{ $user->total_ratings_count }})</span>
            </button>
            
            <button wire:click="setFilter('positive')" 
                class="flex items-center justify-center gap-2 px-3 py-2 rounded-lg {{ $filter === 'positive' ? 'bg-green-200 dark:bg-green-800 text-green-800 dark:text-green-200' : 'bg-slate-100 dark:bg-slate-600' }}">
                <span class="text-lg">😊</span>
                <span class="text-sm font-medium">{{ $user->positive_ratings_count }}</span>
            </button>
            
            <button wire:click="setFilter('neutral')" 
                class="flex items-center justify-center gap-2 px-3 py-2 rounded-lg {{ $filter === 'neutral' ? 'bg-amber-200 dark:bg-amber-800 text-amber-800 dark:text-amber-200' : 'bg-slate-100 dark:bg-slate-600' }}">
                <span class="text-lg">😐</span>
                <span class="text-sm font-medium">{{ $user->neutral_ratings_count }}</span>
            </button>
            
            <button wire:click="setFilter('negative')" 
                class="flex items-center justify-center gap-2 px-3 py-2 rounded-lg {{ $filter === 'negative' ? 'bg-red-200 dark:bg-red-800 text-red-800 dark:text-red-200' : 'bg-slate-100 dark:bg-slate-600' }}">
                <span class="text-lg">😞</span>
                <span class="text-sm font-medium">{{ $user->negative_ratings_count }}</span>
            </button>
        </div>
    </div>

    <div class="divider"></div>

    <!-- Desktop Ratings List -->
    <div class="hidden md:block conversations-list">
        @forelse($ratings as $rating)
            <div class="conversation-item {{ $rating->rating === 'positive' ? 'border-l-4 border-l-green-500' : ($rating->rating === 'neutral' ? 'border-l-4 border-l-amber-500' : 'border-l-4 border-l-red-500') }}" wire:key="rating-{{ $rating->id }}" style="border-left: 4px solid {{ $rating->rating === 'positive' ? '#10b981' : ($rating->rating === 'neutral' ? '#f59e0b' : '#ef4444') }};">
                <div class="conversation-info" style="margin-left: 0; padding-left: 1rem;">
                    <div class="conversation-inner">
                        <!-- Rating Info -->
                        <div class="user-info">
                            <div class="user-name">
                                <span class="text-2xl mr-2">{{ $rating->rating_icon }}</span>
                                {{ $rating->rater->name }}
                            </div>
                            <div class="listing-name">
                                @if($rating->listing)
                                    {{ $rating->listing->title }}
                                @else
                                    <span class="text-slate-500 dark:text-slate-400">Oglas obrisan</span>
                                @endif
                            </div>
                        </div>

                        <!-- Comment Preview -->
                        <div class="message-preview">
                            <div class="preview-text">
                                @if($rating->comment)
                                    {{ Str::limit($rating->comment, 60) }}
                                @else
                                    <span class="text-slate-400">Bez komentara</span>
                                @endif
                            </div>
                        </div>

                        <!-- Date Info -->
                        <div class="date-info">
                            <div class="full-date">
                                {{ $rating->created_at->format('d.m.Y. H:i') }}
                            </div>
                            <div class="short-date">
                                {{ $rating->created_at->format('d.m.Y.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="divider"></div>
        @empty
            <div class="empty-state">
                <p>Nemate 
                    @switch($filter)
                        @case('positive') pozitivnih @break
                        @case('neutral') neutralnih @break  
                        @case('negative') negativnih @break
                        @default @break
                    @endswitch
                    ocena
                </p>
            </div>
        @endforelse
    </div>

    <!-- Mobile Card View -->
    <div class="md:hidden">
        @forelse($ratings as $rating)
            <div class="bg-white dark:bg-slate-800 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-600 {{ $rating->rating === 'positive' ? 'border-l-4 border-l-green-500' : ($rating->rating === 'neutral' ? 'border-l-4 border-l-amber-500' : 'border-l-4 border-l-red-500') }}" 
                 wire:key="mobile-rating-{{ $rating->id }}">
                <div class="p-4">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center flex-1 min-w-0">
                            <!-- Rating Icon -->
                            <div class="flex-shrink-0 h-10 w-10 {{ $rating->rating_background }} rounded-full flex items-center justify-center mr-3">
                                <span class="text-2xl">{{ $rating->rating_icon }}</span>
                            </div>
                            
                            <!-- Rater info -->
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $rating->rater->name }}</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                    @if($rating->listing)
                                        {{ Str::limit($rating->listing->title, 30) }}
                                    @else
                                        <span class="text-slate-400">Oglas obrisan</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        <!-- Date -->
                        <div class="flex flex-col items-end ml-2">
                            <span class="text-xs text-slate-400">
                                {{ $rating->created_at->format('d.m.Y') }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Comment -->
                    @if($rating->comment)
                        <div class="flex items-start">
                            <p class="text-sm text-slate-600 dark:text-slate-300">
                                "{{ $rating->comment }}"
                            </p>
                        </div>
                    @else
                        <div class="text-sm text-slate-400 italic">
                            Bez komentara
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="p-8 text-center">
                <div class="text-4xl mb-3">
                    @switch($filter)
                        @case('positive') 😊 @break
                        @case('neutral') 😐 @break
                        @case('negative') 😞 @break
                        @default 📊 @break
                    @endswitch
                </div>
                <h3 class="text-lg font-semibold text-slate-800 mb-2">
                    Nemate {{ $filter === 'all' ? '' : $filter }} ocena
                </h3>
                <p class="text-slate-600 dark:text-slate-300">Ocene će se pojaviti kada vas drugi korisnici ocene.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($ratings->hasPages())
        <div class="pagination-container">
            {{ $ratings->links() }}
        </div>
    @endif
</div>