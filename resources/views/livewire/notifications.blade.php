<!-- resources/views/livewire/notifications.blade.php -->
<div class="messages-container bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 min-h-screen">
    <style>
        /* Dark mode hover fixes for notifications - scoped to messages-container to avoid debugbar conflicts */
        .dark .messages-container .conversation-item:hover {
            background-color: rgb(75 85 99) !important;
            /* slate-600 */
        }

        .dark .messages-container .conversation-item {
            background-color: rgb(17 24 39) !important;
            /* slate-900 - same as main background */
            border-color: rgb(75 85 99) !important;
            /* slate-600 */
        }

        .dark .messages-container .conversation-item.unread {
            background-color: rgb(30 58 138) !important;
            /* sky-900 */
        }

        .dark .messages-container .user-name,
        .dark .messages-container .listing-name,
        .dark .messages-container .preview-text,
        .dark .messages-container .date-info {
            color: rgb(229 231 235) !important;
            /* slate-200 */
        }
    </style>
    <!-- Header -->
    <section class="navigation-holder">
        <div class="messages-header">
            <h1 class="text-slate-900 dark:text-slate-100">Obaveštenja</h1>
        </div>
    </section>

    <!-- Filter Options -->
    <div class="p-4 bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-600">
        <!-- Desktop filters -->
        <div class="hidden md:block">
            <div class="flex justify-between items-center">
                <!-- Mark all as read button -->
                <div class="flex items-center">
                    <button wire:click="markAllAsRead"
                        class="px-3 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700 transition-colors">
                        <i class="fas fa-check mr-1"></i>
                        Označi sve kao pročitano
                    </button>
                </div>

                <!-- Filter dropdown -->
                <div class="flex items-center" x-data="{ open: false }" x-init="open = false">
                    <label class="text-sm font-medium text-slate-700 dark:text-slate-200 mr-3">Prikaži:</label>
                    <div class="relative">
                        <button @click="open = !open" type="button"
                            class="px-3 py-2 bg-white dark:bg-slate-800 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-sky-500 transition-colors flex items-center justify-between min-w-32">
                            <span>
                                @if ($filter === 'all')
                                    Sve
                                @elseif($filter === 'unread')
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
                            class="absolute z-10 mt-1 right-0 w-40 bg-white dark:bg-slate-800 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg shadow-lg">
                            <button @click="$wire.set('filter', 'all'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm hover:bg-slate-200 dark:hover:bg-slate-600 rounded-t-lg {{ $filter === 'all' ? 'bg-sky-50 dark:bg-slate-600 text-sky-700 dark:text-slate-200' : 'text-slate-700 dark:text-slate-200' }}">
                                Sve
                            </button>
                            <button @click="$wire.set('filter', 'unread'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm hover:bg-slate-200 dark:hover:bg-slate-600 rounded-b-lg {{ $filter === 'unread' ? 'bg-sky-50 dark:bg-slate-600 text-sky-700 dark:text-slate-200' : 'text-slate-700 dark:text-slate-200' }}">
                                Nepročitane
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile filters -->
        <div class="md:hidden space-y-3">
            <button wire:click="markAllAsRead"
                class="w-full px-3 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700 transition-colors">
                <i class="fas fa-check mr-1"></i>
                Označi sve kao pročitano
            </button>

            <select wire:model.live="filter"
                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded text-sm">
                <option value="all">Sve</option>
                <option value="unread">Nepročitane</option>
            </select>
        </div>
    </div>

    <div class="divider"></div>

    <!-- Desktop Notifications List -->
    <div class="hidden md:block conversations-list">
        @forelse($notifications as $notification)
            <div class="conversation-item {{ $notification->is_read ? '' : 'unread' }}"
                wire:key="notification-{{ $notification->id }}">
                <div class="conversation-info" wire:click="selectNotification({{ $notification->id }})"
                    style="margin-left: 0; padding-left: 1rem;">
                    <div class="conversation-inner">
                        <!-- Notification Info -->
                        <div class="user-info">
                            <div class="user-name">
                                Obaveštenje
                                @if (!$notification->is_read)
                                    <span
                                        class="ml-2 bg-red-600 text-white rounded px-2 py-1 text-xs font-medium">1</span>
                                @endif
                            </div>
                            <div class="listing-name">
                                @if ($notification->listing)
                                    {{ $notification->listing->title }}
                                @else
                                    {{ $notification->subject ?? 'Admin obaveštenje' }}
                                @endif
                            </div>
                        </div>

                        <!-- Message Preview -->
                        <div class="message-preview">
                            <div class="preview-text">
                                {{ Str::limit($notification->message, 60) }}
                            </div>
                        </div>

                        <!-- Date Info and Actions -->
                        <div class="date-info" style="display: flex; flex-direction: column; align-items: flex-end;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem;">
                                <div class="full-date">
                                    {{ $notification->created_at->format('d.m.Y. H:i') }}
                                </div>
                                <!-- Delete button -->
                                <button wire:click="deleteNotification({{ $notification->id }})"
                                    wire:confirm="Da li ste sigurni da želite da obrišete ovo obaveštenje? Biće sakriveno samo vama."
                                    onclick="event.stopPropagation()"
                                    style="padding: 4px; color: #ef4444; border: none; background: none; border-radius: 4px; cursor: pointer;"
                                    onmouseover="this.style.backgroundColor='#fee2e2'"
                                    onmouseout="this.style.backgroundColor='transparent'">
                                    <i class="fas fa-trash" style="font-size: 12px;"></i>
                                </button>
                            </div>
                            <div class="short-date">
                                {{ $notification->created_at->format('d.m.Y.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="divider"></div>
        @empty
            <div class="empty-state">
                <p>Nemate obaveštenja</p>
            </div>
        @endforelse
    </div>

    <!-- Mobile Card View -->
    <div class="md:hidden">
        @forelse($notifications as $notification)
            <div class="bg-white dark:bg-slate-800 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-600 hover:bg-slate-200 dark:hover:bg-slate-600 cursor-pointer {{ !$notification->is_read ? 'bg-sky-50 border-l-4 border-l-sky-500' : '' }}"
                wire:click="selectNotification({{ $notification->id }})"
                wire:key="mobile-notification-{{ $notification->id }}">
                <div class="p-4">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center flex-1 min-w-0">
                            <!-- Notification Icon -->
                            <div
                                class="flex-shrink-0 h-10 w-10 bg-sky-200 dark:bg-sky-800 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-bell text-sky-600 dark:text-sky-300"></i>
                            </div>

                            <!-- Notification info -->
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-100 mb-1">
                                    @if ($notification->listing)
                                        {{ Str::limit($notification->listing->title, 30) }}
                                    @else
                                        {{ $notification->subject ?? 'Admin obaveštenje' }}
                                    @endif
                                </h3>
                                <p class="text-xs text-slate-500 dark:text-slate-300">Sistemsko obaveštenje</p>
                            </div>
                        </div>

                        <!-- Status and date -->
                        <div class="flex flex-col items-end ml-2">
                            @if (!$notification->is_read)
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-200 dark:bg-red-800 text-red-800 dark:text-red-200 mb-1">
                                    Novo
                                </span>
                            @endif
                            <span class="text-xs text-slate-400">
                                {{ $notification->created_at->format('d.m.Y') }}
                            </span>

                            <!-- Mobile trash button positioned under date -->
                            <button wire:click="deleteNotification({{ $notification->id }})"
                                wire:confirm="Da li ste sigurni da želite da obrišete ovo obaveštenje? Biće sakriveno samo vama."
                                onclick="event.stopPropagation()"
                                class="mt-1 p-1 text-red-500 hover:text-red-700 rounded">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Message preview -->
                    <div class="flex items-start">
                        <p class="text-sm text-slate-600 dark:text-slate-300">
                            {{ Str::limit($notification->message, 80) }}
                        </p>
                    </div>

                    <!-- Mark as read button (mobile only) -->
                    @if (!$notification->is_read)
                        <div class="mt-3 pt-3 border-t border-slate-200 dark:border-slate-600">
                            <button wire:click="markAsRead({{ $notification->id }})"
                                class="text-xs text-sky-600 dark:text-sky-400 hover:text-sky-800 dark:hover:text-sky-300"
                                onclick="event.stopPropagation()">
                                <i class="fas fa-check mr-1"></i>
                                Označi kao pročitano
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="p-8 text-center">
                <i class="fas fa-bell text-slate-400 text-4xl mb-3"></i>
                <h3 class="text-lg font-semibold text-slate-800 mb-2">Nemate obaveštenja</h3>
                <p class="text-slate-600 dark:text-slate-300">Obaveštenja će se pojaviti kada sistem pošalje poruke.
                </p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($notifications->hasPages())
        <div class="pagination-container">
            {{ $notifications->links() }}
        </div>
    @endif

    <!-- Selected Notification Modal -->
    @if ($selectedNotification)
        <div class="fixed inset-0 z-50 overflow-y-auto" onclick="closeModal()">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>

            <!-- Modal content -->
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <div onclick="event.stopPropagation()"
                    class="relative inline-block align-bottom bg-white dark:bg-slate-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">

                    <!-- Modal header with gradient background -->
                    <div class="bg-gradient-to-r from-sky-500 to-sky-600 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-white bg-opacity-20">
                                    <i class="fas fa-bell text-white text-xl"></i>
                                </div>
                                <h3 class="ml-3 text-xl font-bold text-white">Obaveštenje</h3>
                            </div>
                            <button onclick="closeModal()" class="text-white hover:text-slate-200 transition-colors" type="button">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Modal body -->
                    <div class="px-6 py-6">
                        <!-- Main message -->
                        <div class="mb-6">
                            <p class="text-lg text-slate-900 dark:text-slate-100 leading-relaxed">
                                {{ $selectedNotification->message }}
                            </p>
                        </div>

                        <!-- Information cards -->
                        <div class="space-y-4">
                            @if ($selectedNotification->listing)
                                <!-- Listing information card -->
                                <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-4">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 mr-3">
                                            <i class="fas fa-tag text-sky-500 text-lg"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-slate-600 dark:text-slate-300 mb-1">Oglas</p>
                                            <a href="{{ route('listings.show', $selectedNotification->listing) }}"
                                                class="text-base font-semibold text-sky-600 dark:text-sky-400 hover:text-sky-700 dark:hover:text-sky-300 transition-colors"
                                                wire:navigate>
                                                {{ $selectedNotification->listing->title }}
                                            </a>
                                            @if($selectedNotification->listing->listing_type === 'giveaway')
                                                <div class="mt-1">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                        <i class="fas fa-gift mr-1"></i> BESPLATNO
                                                    </span>
                                                </div>
                                            @else
                                                <p class="text-sm text-sky-600 dark:text-sky-400 mt-1 font-medium">
                                                    {{ number_format($selectedNotification->listing->price, 0, ',', '.') }} RSD
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- User information card if exists -->
                                @if ($selectedNotification->sender && $selectedNotification->sender->id !== auth()->id())
                                    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-4">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 mr-3">
                                                <i class="fas fa-user text-sky-500 text-lg"></i>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-slate-600 dark:text-slate-300 mb-1">Korisnik</p>
                                                <p class="text-base font-semibold text-slate-900 dark:text-slate-100">
                                                    {{ $selectedNotification->sender->name }}
                                                </p>
                                                @if ($selectedNotification->sender->city)
                                                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                                        {{ $selectedNotification->sender->city }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @elseif($selectedNotification->subject)
                                <!-- Subject card for admin notifications -->
                                <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 rounded-lg p-4">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 mr-3">
                                            <i class="fas fa-info-circle text-amber-500 text-lg"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-amber-800 dark:text-amber-200 mb-1">Naslov</p>
                                            <p class="text-base font-semibold text-amber-900 dark:text-amber-100">
                                                {{ $selectedNotification->subject }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Date and time card -->
                            <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mr-3">
                                        <i class="fas fa-clock text-slate-500 text-lg"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-slate-600 dark:text-slate-300 mb-1">Datum</p>
                                        <p class="text-base font-semibold text-slate-900 dark:text-slate-100">
                                            {{ $selectedNotification->created_at->format('d.m.Y. H:i') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal footer with actions -->
                    <div class="bg-slate-50 dark:bg-slate-700/50 px-6 py-4 border-t border-slate-200 dark:border-slate-600">
                        <div class="flex flex-col sm:flex-row gap-3">
                            @if ($selectedNotification->listing)
                                <a href="{{ route('listings.show', $selectedNotification->listing) }}"
                                    class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-sky-600 to-sky-700 text-white font-medium rounded-lg hover:from-sky-700 hover:to-sky-800 transition-all transform hover:scale-105"
                                    wire:navigate>
                                    <i class="fas fa-eye mr-2"></i>
                                    Pogledaj oglas
                                </a>
                            @endif

                            @if (str_contains($selectedNotification->subject, 'balans') ||
                                    str_contains($selectedNotification->subject, 'kredita') ||
                                    str_contains($selectedNotification->subject, 'plan ističe'))
                                <a href="{{ route('balance.payment-options') }}"
                                    class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-green-600 to-green-700 text-white font-medium rounded-lg hover:from-green-700 hover:to-green-800 transition-all transform hover:scale-105"
                                    wire:navigate>
                                    <i class="fas fa-plus mr-2"></i>
                                    Dopuna kredita
                                </a>

                                <a href="{{ route('balance.plan-selection') }}"
                                    class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-medium rounded-lg hover:from-purple-700 hover:to-purple-800 transition-all transform hover:scale-105"
                                    wire:navigate>
                                    <i class="fas fa-calendar-alt mr-2"></i>
                                    Promeni plan
                                </a>
                            @endif

                            @if (str_contains($selectedNotification->subject, 'oglas ističe'))
                                <a href="{{ route('listings.my') }}"
                                    class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-amber-600 to-amber-700 text-white font-medium rounded-lg hover:from-amber-700 hover:to-amber-800 transition-all transform hover:scale-105"
                                    wire:navigate>
                                    <i class="fas fa-redo mr-2"></i>
                                    Obnovi oglas
                                </a>

                                <a href="{{ route('balance.payment-options') }}"
                                    class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-green-600 to-green-700 text-white font-medium rounded-lg hover:from-green-700 hover:to-green-800 transition-all transform hover:scale-105"
                                    wire:navigate>
                                    <i class="fas fa-plus mr-2"></i>
                                    Dopuna kredita
                                </a>
                            @endif

                            @if (str_contains($selectedNotification->subject, 'Ponuda nadmašena') ||
                                    str_contains($selectedNotification->subject, 'Aukcija'))
                                @if ($selectedNotification->listing)
                                    <a href="{{ route('auction.show', $selectedNotification->listing->auction) }}"
                                        class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white font-medium rounded-lg hover:from-red-700 hover:to-red-800 transition-all transform hover:scale-105"
                                        wire:navigate>
                                        <i class="fas fa-gavel mr-2"></i>
                                        Idi na aukciju
                                    </a>
                                @endif
                            @endif

                            @if (str_contains($selectedNotification->subject, 'Novi zahtev za poklon'))
                                @if ($selectedNotification->listing && $selectedNotification->giveaway_reservation_id)
                                    <button wire:click="$dispatch('openReservationManager', { listingId: {{ $selectedNotification->listing_id }} }); closeModal()"
                                        class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-green-600 to-green-700 text-white font-medium rounded-lg hover:from-green-700 hover:to-green-800 transition-all transform hover:scale-105">
                                        <i class="fas fa-gift mr-2"></i>
                                        Vidi zahteve
                                    </button>
                                @endif
                            @endif

                            @if (str_contains($selectedNotification->subject, 'Vaš zahtev je odobren'))
                                @if ($selectedNotification->listing)
                                    <a href="{{ route('listing.chat', ['slug' => $selectedNotification->listing->slug, 'user' => $selectedNotification->listing->user_id]) }}"
                                        class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all transform hover:scale-105"
                                        wire:navigate>
                                        <i class="fas fa-comment mr-2"></i>
                                        Pošalji poruku
                                    </a>
                                    <a href="{{ route('listings.show', $selectedNotification->listing) }}"
                                        class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-green-600 to-green-700 text-white font-medium rounded-lg hover:from-green-700 hover:to-green-800 transition-all transform hover:scale-105"
                                        wire:navigate>
                                        <i class="fas fa-gift mr-2"></i>
                                        Vidi poklon
                                    </a>
                                @endif
                            @endif

                            <button onclick="closeModal()"
                                class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2.5 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-medium rounded-lg hover:bg-slate-100 dark:hover:bg-slate-600 transition-colors"
                                type="button">
                                <i class="fas fa-times mr-2"></i>
                                Zatvori
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Giveaway Reservation Manager Modal -->
    @livewire('giveaways.reservation-manager')
</div>

<script>
    // Global function to close modal
    function closeModal() {
        console.log('Close modal called');
        @this.call('closeModal');
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Event listener za select all checkbox
        const selectAllCheckbox = document.getElementById('selectAll');
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    // Pozovi Livewire metod za označavanje svih kao pročitanih
                    Livewire.dispatch('markAllAsRead');
                }
            });
        }

        // ESC key support for closing modal
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });
    });
</script>
