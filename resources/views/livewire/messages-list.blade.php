<div class="messages-container">
    <!-- Header -->
    <section class="navigation-holder">
        <div class="messages-header">
            <h1>Poruke</h1>
        </div>
    </section>

    <!-- Search and Filter Options -->
    <div style="padding: 1rem; background: white; border-bottom: 1px solid #eee;">
        <!-- Desktop filters -->
        <div class="hidden md:block">
            <div class="flex justify-between items-center">
                <!-- Search -->
                <div class="flex items-center">
                    <input type="text" wire:model.live="search" placeholder="Pretraži po korisniku ili oglasu..." 
                        class="px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-gray-700 text-sm hover:border-gray-400 focus:outline-none focus:border-blue-500 transition-colors w-80">
                    @if($search)
                        <button wire:click="clearSearch" class="ml-2 px-2 py-1 text-gray-500 hover:text-gray-700 rounded">
                            <i class="fas fa-times"></i>
                        </button>
                    @endif
                </div>

                <!-- Filter dropdown -->
                <div class="flex items-center" x-data="{ open: false }">
                    <label class="text-sm font-medium text-gray-700 mr-3">Prikaži:</label>
                    <div class="relative">
                        <button @click="open = !open" type="button"
                            class="px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-gray-700 text-sm text-left hover:border-gray-400 focus:outline-none focus:border-blue-500 transition-colors flex items-center justify-between min-w-32">
                            <span>
                                @if($sortBy === 'all') Sve poruke
                                @elseif($sortBy === 'unread') Nepročitane
                                @endif
                            </span>
                            <svg class="w-4 h-4 text-gray-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute z-10 mt-1 right-0 w-40 bg-white border border-gray-300 rounded-lg shadow-lg">
                            <button @click="$wire.set('sortBy', 'all').then(() => $wire.call('loadConversations')); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 rounded-t-lg {{ $sortBy === 'all' ? 'bg-blue-50 text-blue-700' : '' }}">
                                Sve poruke
                            </button>
                            <button @click="$wire.set('sortBy', 'unread').then(() => $wire.call('loadConversations')); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 rounded-b-lg {{ $sortBy === 'unread' ? 'bg-blue-50 text-blue-700' : '' }}">
                                Nepročitane
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile filters -->
        <div class="md:hidden space-y-3">
            <div>
                <input type="text" wire:model.live="search" placeholder="Pretraži..." 
                    class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                @if($search)
                    <button wire:click="clearSearch" class="mt-1 text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times mr-1"></i> Očisti pretragu
                    </button>
                @endif
            </div>
            
            <div>
                <select wire:model.live="sortBy" wire:change="loadConversations" class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                    <option value="all">Sve poruke</option>
                    <option value="unread">Nepročitane</option>
                </select>
            </div>
        </div>
    </div>

    <div class="divider"></div>

    <!-- Results info -->
    @if($search)
        <div style="padding: 0.5rem 1rem; background: #f8f9fa; font-size: 0.9rem; color: #6c757d;">
            Rezultati za "{{ $search }}": {{ count($conversations) }} konverzacija
        </div>
    @endif

    <!-- Desktop Conversations List -->
    <div class="hidden md:block conversations-list">
        @forelse($conversations as $key => $conversation)
            <div class="conversation-item {{ $conversation['unread_count'] > 0 ? 'unread' : '' }}" wire:key="conversation-{{ $key }}">
                <div class="conversation-info" wire:click="selectConversation({{ $key }})" style="margin-left: 0; padding-left: 1rem;">
                    <div class="conversation-inner">
                        <!-- User Info -->
                        <div class="user-info">
                            <div class="user-name">
                                {{ $conversation['other_user']->name }}
                                @if ($conversation['unread_count'] > 0)
                                    <span class="unread-badge">{{ $conversation['unread_count'] }}</span>
                                @endif
                            </div>
                            <div class="listing-name">
                                {{ $conversation['listing']->title }}
                            </div>
                        </div>

                        <!-- Message Preview -->
                        <div class="message-preview">
                            @if ($conversation['last_message']->is_read && $conversation['last_message']->sender_id == Auth::id())
                                <svg width="16" height="11" viewBox="0 0 18 11" fill="none"
                                    stroke="var(--accent-primary)" class="read-icon">
                                    <path d="M10.5 1L4.5 9.5L1.5 6.5" stroke-width="2" />
                                    <path d="M16.5 1L10.5 9.5L9 8" stroke-width="2" />
                                </svg>
                            @endif
                            <div class="preview-text">
                                {{ Str::limit($conversation['last_message']->message, 60) }}
                            </div>
                        </div>

                        <!-- Date Info and Actions -->
                        <div class="date-info" style="display: flex; flex-direction: column; align-items: flex-end;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem;">
                                <div class="full-date">
                                    {{ $conversation['last_message']->created_at->format('d.m.Y. H:i') }}
                                </div>
                                <!-- Delete button -->
                                <button wire:click="deleteConversation({{ $key }})" 
                                    wire:confirm="Da li ste sigurni da želite da obrišete ovu konverzaciju? Poruke će biti sakrivene samo vama."
                                    onclick="event.stopPropagation()"
                                    style="padding: 4px; color: #ef4444; border: none; background: none; border-radius: 4px; cursor: pointer;"
                                    onmouseover="this.style.backgroundColor='#fee2e2'"
                                    onmouseout="this.style.backgroundColor='transparent'">
                                    <i class="fas fa-trash" style="font-size: 12px;"></i>
                                </button>
                            </div>
                            <div class="short-date">
                                {{ $conversation['last_message']->created_at->format('d.m.Y.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="divider"></div>
        @empty
            <div class="empty-state">
                <p>Nemate poruka</p>
            </div>
        @endforelse
    </div>

    <!-- Mobile Card View -->
    <div class="md:hidden">
        @forelse($conversations as $key => $conversation)
            <div class="bg-white border-b border-gray-200 hover:bg-gray-50 cursor-pointer" 
                 wire:click="selectConversation({{ $key }})" 
                 wire:key="mobile-conversation-{{ $key }}">
                <div class="p-4">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center flex-1 min-w-0">
                            <!-- Avatar -->
                            <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-blue-600 font-medium text-sm">
                                    {{ strtoupper(substr($conversation['other_user']->name, 0, 1)) }}
                                </span>
                            </div>
                            
                            <!-- User name -->
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-semibold text-gray-900 truncate">
                                    {{ $conversation['other_user']->name }}
                                </h3>
                                <p class="text-xs text-gray-500 truncate">
                                    {{ $conversation['listing']->title }}
                                </p>
                            </div>
                        </div>
                        
                        <!-- Status and date -->
                        <div class="flex flex-col items-end ml-2">
                            @if ($conversation['unread_count'] > 0)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mb-1">
                                    {{ $conversation['unread_count'] }}
                                </span>
                            @endif
                            <span class="text-xs text-gray-400">
                                {{ $conversation['last_message']->created_at->format('d.m.Y') }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Message preview -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center flex-1 min-w-0">
                            @if ($conversation['last_message']->is_read && $conversation['last_message']->sender_id == Auth::id())
                                <svg width="12" height="8" viewBox="0 0 18 11" fill="none" stroke="#10b981" class="mr-2 flex-shrink-0">
                                    <path d="M10.5 1L4.5 9.5L1.5 6.5" stroke-width="2" />
                                    <path d="M16.5 1L10.5 9.5L9 8" stroke-width="2" />
                                </svg>
                            @endif
                            <p class="text-sm text-gray-600 truncate">
                                {{ Str::limit($conversation['last_message']->message, 80) }}
                            </p>
                        </div>
                        
                        <!-- Delete button -->
                        <button wire:click="deleteConversation({{ $key }})" 
                            wire:confirm="Da li ste sigurni da želite da obrišete ovu konverzaciju? Poruke će biti sakrivene samo vama."
                            onclick="event.stopPropagation()"
                            class="ml-2 p-1 text-red-500 hover:text-red-700 rounded">
                            <i class="fas fa-trash text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-8 text-center">
                <i class="fas fa-envelope text-gray-400 text-4xl mb-3"></i>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Nemate poruka</h3>
                <p class="text-gray-600">Poruke će se pojaviti kada kontaktirate prodavce.</p>
            </div>
        @endforelse
    </div>
</div>
