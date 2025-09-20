<div class="messages-container bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100">
    <style>
        /* Dark mode hover fixes */
        .dark .conversation-item:hover {
            background-color: rgb(75 85 99) !important;
            /* slate-600 */
        }

        .dark .conversation-item {
            background-color: rgb(17 24 39) !important;
            /* slate-900 - same as main background */
            border-color: rgb(75 85 99) !important;
            /* slate-600 */
        }

        .dark .conversation-item.unread {
            background-color: rgb(30 58 138) !important;
            /* sky-900 */
        }

        .dark .user-name,
        .dark .listing-name,
        .dark .preview-text,
        .dark .date-info {
            color: rgb(229 231 235) !important;
            /* slate-200 */
        }
    </style>
    <!-- Header -->
    <section class="navigation-holder">
        <div class="messages-header">
            <h1 class="text-slate-900 dark:text-slate-100">Poruke</h1>
        </div>
    </section>

    <!-- Search and Filter Options -->
    <div class="p-4 bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-600">
        <!-- Desktop filters -->
        <div class="hidden md:block">
            <div class="flex justify-between items-center">
                <!-- Search -->
                <div class="flex items-center">
                    <input type="text" wire:model.live="search" placeholder="Pretraži po korisniku ili oglasu..."
                        class="px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm hover:border-slate-400 focus:outline-none focus:border-sky-500 transition-colors w-80">
                    @if ($search)
                        <button wire:click="clearSearch"
                            class="ml-2 px-2 py-1 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:text-slate-200 rounded">
                            <i class="fas fa-times"></i>
                        </button>
                    @endif
                </div>

                <!-- Filter dropdown -->
                <div class="flex items-center" x-data="{ open: false }" x-init="open = false">
                    <label class="text-sm font-medium text-slate-700 dark:text-slate-200 mr-3">Prikaži:</label>
                    <div class="relative">
                        <button @click="open = !open" type="button"
                            class="px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-sky-500 transition-colors flex items-center justify-between min-w-32">
                            <span>
                                @if ($sortBy === 'all')
                                    Sve poruke
                                @elseif($sortBy === 'unread')
                                    Nepročitane
                                @endif
                            </span>
                            <svg class="w-4 h-4 text-slate-400 ml-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute z-10 mt-1 right-0 w-40 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg shadow-lg">
                            <button
                                @click="$wire.set('sortBy', 'all').then(() => $wire.call('loadConversations')); open = false"
                                type="button"
                                class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-slate-600 rounded-t-lg {{ $sortBy === 'all' ? 'bg-sky-100 dark:bg-sky-900 text-sky-700 dark:text-sky-200' : '' }}">
                                Sve poruke
                            </button>
                            <button
                                @click="$wire.set('sortBy', 'unread').then(() => $wire.call('loadConversations')); open = false"
                                type="button"
                                class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-slate-600 rounded-b-lg {{ $sortBy === 'unread' ? 'bg-sky-100 dark:bg-sky-900 text-sky-700 dark:text-sky-200' : '' }}">
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
                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded text-sm">
                @if ($search)
                    <button wire:click="clearSearch"
                        class="mt-1 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:text-slate-200">
                        <i class="fas fa-times mr-1"></i> Očisti pretragu
                    </button>
                @endif
            </div>

            <div>
                <select wire:model.live="sortBy" wire:change="loadConversations"
                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded text-sm">
                    <option value="all">Sve poruke</option>
                    <option value="unread">Nepročitane</option>
                </select>
            </div>
        </div>
    </div>

    <div class="divider"></div>

    <!-- Results info -->
    @if ($search)
        <div class="p-2 bg-slate-100 dark:bg-slate-700 text-sm text-slate-600 dark:text-slate-300">
            Rezultati za "{{ $search }}": {{ count($conversations) }} konverzacija
        </div>
    @endif

    <!-- Desktop Conversations List -->
    <div class="hidden md:block conversations-list">
        @forelse($conversations as $key => $conversation)
            <div class="conversation-item {{ $conversation['unread_count'] > 0 ? 'unread' : '' }}"
                wire:key="conversation-{{ $key }}">
                <div class="conversation-info" wire:click="selectConversation({{ $key }})"
                    style="margin-left: 0; padding-left: 1rem;">
                    <div class="conversation-inner">
                        <!-- User Info -->
                        <div class="user-info">
                            <div class="user-name">
                                {{ $conversation['other_user']->name }}
                                {!! $conversation['other_user']->verified_icon !!}
                                @if ($conversation['unread_count'] > 0)
                                    <span
                                        class="ml-2 bg-red-200 dark:bg-red-800 text-red-800 dark:text-red-200 rounded px-2 py-1 text-xs font-medium">{{ $conversation['unread_count'] }}</span>
                                @endif
                            </div>
                            <div class="listing-name">
                                @if ($conversation['listing'])
                                    {{ $conversation['listing']->title }}
                                @else
                                    <span class="text-slate-500 dark:text-slate-300">Direktna komunikacija</span>
                                @endif
                            </div>
                        </div>

                        <!-- Message Preview -->
                        <div class="message-preview">
                            @if ($conversation['last_message']->sender_id == Auth::id())
                                @if ($conversation['last_message']->is_read)
                                    <i class="fas fa-eye text-green-600 dark:text-green-300 text-sm mr-2"
                                        title="Pročitano"></i>
                                @else
                                    <i class="fas fa-eye text-slate-400 text-sm mr-2" title="Dostavljeno"></i>
                                @endif
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
            <div class="bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-600 hover:bg-slate-200 dark:hover:bg-slate-600 cursor-pointer"
                wire:click="selectConversation({{ $key }})"
                wire:key="mobile-conversation-{{ $key }}">
                <div class="p-4">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center flex-1 min-w-0">
                            <!-- Avatar -->
                            <div
                                class="flex-shrink-0 h-10 w-10 bg-sky-200 dark:bg-sky-800 rounded-full flex items-center justify-center mr-3">
                                <span class="text-sky-600 dark:text-sky-300 font-medium text-sm">
                                    {{ strtoupper(substr($conversation['other_user']->name, 0, 1)) }}
                                </span>
                            </div>

                            <!-- User name -->
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-100 truncate">
                                    {{ $conversation['other_user']->name }}
                                    {!! $conversation['other_user']->verified_icon !!}
                                </h3>
                                <p class="text-xs text-slate-500 dark:text-slate-300 truncate">
                                    @if ($conversation['listing'])
                                        {{ $conversation['listing']->title }}
                                    @else
                                        Direktna komunikacija
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Status and date -->
                        <div class="flex flex-col items-end ml-2">
                            @if ($conversation['unread_count'] > 0)
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-200 dark:bg-red-800 text-red-800 dark:text-red-200 mb-1">
                                    {{ $conversation['unread_count'] }}
                                </span>
                            @endif
                            <span class="text-xs text-slate-400">
                                {{ $conversation['last_message']->created_at->format('d.m.Y') }}
                            </span>
                        </div>
                    </div>

                    <!-- Message preview -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center flex-1 min-w-0">
                            @if ($conversation['last_message']->sender_id == Auth::id())
                                @if ($conversation['last_message']->is_read)
                                    <i class="fas fa-eye text-green-600 dark:text-green-300 text-sm mr-2 flex-shrink-0"
                                        title="Pročitano"></i>
                                @else
                                    <i class="fas fa-eye text-slate-400 text-sm mr-2 flex-shrink-0"
                                        title="Dostavljeno"></i>
                                @endif
                            @endif
                            <p class="text-sm text-slate-600 dark:text-slate-300 truncate">
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
                <i class="fas fa-envelope text-slate-400 text-4xl mb-3"></i>
                <h3 class="text-lg font-semibold text-slate-800 mb-2">Nemate poruka</h3>
                <p class="text-slate-600 dark:text-slate-300">Poruke će se pojaviti kada kontaktirate prodavce.</p>
            </div>
        @endforelse
    </div>
</div>
