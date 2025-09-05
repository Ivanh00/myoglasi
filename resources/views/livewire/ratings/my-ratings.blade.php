<div class="messages-container">
    <!-- Header -->
    <section class="navigation-holder">
        <div class="messages-header">
            <h1>Moje ocene</h1>
        </div>
    </section>

    <!-- Filter Options (Smiley Filters) -->
    <div style="padding: 1rem; background: white; border-bottom: 1px solid #eee;">
        <!-- Desktop filters -->
        <div class="hidden md:block">
            <div class="flex justify-center items-center gap-6">
                <!-- All Ratings -->
                <button wire:click="setFilter('all')" 
                    class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all {{ $filter === 'all' ? 'bg-blue-100 text-blue-700 shadow-md' : 'hover:bg-gray-100' }}">
                    <span class="text-2xl">📊</span>
                    <span class="font-medium">Sve</span>
                    <span class="text-sm text-gray-500">({{ $user->total_ratings_count }})</span>
                </button>
                
                <!-- Positive -->
                <button wire:click="setFilter('positive')" 
                    class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all {{ $filter === 'positive' ? 'bg-green-100 text-green-700 shadow-md' : 'hover:bg-green-50' }}">
                    <span class="text-2xl">😊</span>
                    <span class="font-medium">Pozitivne</span>
                    <span class="text-sm text-gray-500">({{ $user->positive_ratings_count }})</span>
                </button>
                
                <!-- Neutral -->
                <button wire:click="setFilter('neutral')" 
                    class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all {{ $filter === 'neutral' ? 'bg-yellow-100 text-yellow-700 shadow-md' : 'hover:bg-yellow-50' }}">
                    <span class="text-2xl">😐</span>
                    <span class="font-medium">Neutralne</span>
                    <span class="text-sm text-gray-500">({{ $user->neutral_ratings_count }})</span>
                </button>
                
                <!-- Negative -->
                <button wire:click="setFilter('negative')" 
                    class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all {{ $filter === 'negative' ? 'bg-red-100 text-red-700 shadow-md' : 'hover:bg-red-50' }}">
                    <span class="text-2xl">😞</span>
                    <span class="font-medium">Negativne</span>
                    <span class="text-sm text-gray-500">({{ $user->negative_ratings_count }})</span>
                </button>
            </div>
        </div>

        <!-- Mobile filters -->
        <div class="md:hidden grid grid-cols-2 gap-3">
            <button wire:click="setFilter('all')" 
                class="flex items-center justify-center gap-2 px-3 py-2 rounded-lg {{ $filter === 'all' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100' }}">
                <span class="text-lg">📊</span>
                <span class="text-sm font-medium">Sve ({{ $user->total_ratings_count }})</span>
            </button>
            
            <button wire:click="setFilter('positive')" 
                class="flex items-center justify-center gap-2 px-3 py-2 rounded-lg {{ $filter === 'positive' ? 'bg-green-100 text-green-700' : 'bg-gray-100' }}">
                <span class="text-lg">😊</span>
                <span class="text-sm font-medium">{{ $user->positive_ratings_count }}</span>
            </button>
            
            <button wire:click="setFilter('neutral')" 
                class="flex items-center justify-center gap-2 px-3 py-2 rounded-lg {{ $filter === 'neutral' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100' }}">
                <span class="text-lg">😐</span>
                <span class="text-sm font-medium">{{ $user->neutral_ratings_count }}</span>
            </button>
            
            <button wire:click="setFilter('negative')" 
                class="flex items-center justify-center gap-2 px-3 py-2 rounded-lg {{ $filter === 'negative' ? 'bg-red-100 text-red-700' : 'bg-gray-100' }}">
                <span class="text-lg">😞</span>
                <span class="text-sm font-medium">{{ $user->negative_ratings_count }}</span>
            </button>
        </div>
    </div>

    <div class="divider"></div>

    <!-- Desktop Ratings List -->
    <div class="hidden md:block conversations-list">
        @forelse($ratings as $rating)
            <div class="conversation-item {{ $rating->rating === 'positive' ? 'border-l-4 border-l-green-500' : ($rating->rating === 'neutral' ? 'border-l-4 border-l-yellow-500' : 'border-l-4 border-l-red-500') }}" wire:key="rating-{{ $rating->id }}" style="border-left: 4px solid {{ $rating->rating === 'positive' ? '#10b981' : ($rating->rating === 'neutral' ? '#f59e0b' : '#ef4444') }};">
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
                                    <span class="text-gray-500">Oglas obrisan</span>
                                @endif
                            </div>
                        </div>

                        <!-- Comment Preview -->
                        <div class="message-preview">
                            <div class="preview-text">
                                @if($rating->comment)
                                    {{ Str::limit($rating->comment, 60) }}
                                @else
                                    <span class="text-gray-400">Bez komentara</span>
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
            <div class="bg-white border-b border-gray-200 {{ $rating->rating === 'positive' ? 'border-l-4 border-l-green-500' : ($rating->rating === 'neutral' ? 'border-l-4 border-l-yellow-500' : 'border-l-4 border-l-red-500') }}" 
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
                                <h3 class="text-sm font-semibold text-gray-900">{{ $rating->rater->name }}</h3>
                                <p class="text-xs text-gray-500">
                                    @if($rating->listing)
                                        {{ Str::limit($rating->listing->title, 30) }}
                                    @else
                                        <span class="text-gray-400">Oglas obrisan</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        <!-- Date -->
                        <div class="flex flex-col items-end ml-2">
                            <span class="text-xs text-gray-400">
                                {{ $rating->created_at->format('d.m.Y') }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Comment -->
                    @if($rating->comment)
                        <div class="flex items-start">
                            <p class="text-sm text-gray-600">
                                "{{ $rating->comment }}"
                            </p>
                        </div>
                    @else
                        <div class="text-sm text-gray-400 italic">
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
                <h3 class="text-lg font-semibold text-gray-800 mb-2">
                    Nemate {{ $filter === 'all' ? '' : $filter }} ocena
                </h3>
                <p class="text-gray-600">Ocene će se pojaviti kada vas drugi korisnici ocene.</p>
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