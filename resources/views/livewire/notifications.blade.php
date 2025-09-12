<!-- resources/views/livewire/notifications.blade.php -->
<div class="messages-container bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen">
    <style>
        /* Dark mode hover fixes for notifications - using conversation-item class */
        .dark .conversation-item:hover {
            background-color: rgb(75 85 99) !important; /* gray-600 */
        }
        .dark .conversation-item {
            background-color: rgb(17 24 39) !important; /* gray-900 - same as main background */
            border-color: rgb(75 85 99) !important; /* gray-600 */
        }
        .dark .conversation-item.unread {
            background-color: rgb(30 58 138) !important; /* blue-900 */
        }
        .dark .user-name, .dark .listing-name, .dark .preview-text, .dark .date-info {
            color: rgb(229 231 235) !important; /* gray-200 */
        }
    </style>
    <!-- Header -->
    <section class="navigation-holder">
        <div class="messages-header">
            <h1 class="text-gray-900 dark:text-gray-100">Obaveštenja</h1>
        </div>
    </section>

    <!-- Filter Options -->
    <div class="p-4 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-600">
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
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-200 mr-3">Prikaži:</label>
                    <div class="relative">
                        <button @click="open = !open" type="button"
                            class="px-3 py-2 bg-white dark:bg-gray-800 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-gray-700 dark:text-gray-200 text-sm text-left hover:border-gray-400 focus:outline-none focus:border-blue-500 transition-colors flex items-center justify-between min-w-32">
                            <span>
                                @if($filter === 'all') Sve
                                @elseif($filter === 'unread') Nepročitane
                                @endif
                            </span>
                            <svg class="w-4 h-4 text-gray-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute z-10 mt-1 right-0 w-40 bg-white dark:bg-gray-800 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg">
                            <button @click="$wire.set('filter', 'all'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-t-lg {{ $filter === 'all' ? 'bg-blue-50 text-blue-700' : '' }}">
                                Sve
                            </button>
                            <button @click="$wire.set('filter', 'unread'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-b-lg {{ $filter === 'unread' ? 'bg-blue-50 text-blue-700' : '' }}">
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
            
            <select wire:model.live="filter" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded text-sm">
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
                <div class="conversation-info" wire:click="selectNotification({{ $notification->id }})" style="margin-left: 0; padding-left: 1rem;">
                    <div class="conversation-inner">
                        <!-- Notification Info -->
                        <div class="user-info">
                            <div class="user-name">
                                Obaveštenje
                                @if (!$notification->is_read)
                                    <span class="ml-2 bg-red-600 text-white rounded px-2 py-1 text-xs font-medium">1</span>
                                @endif
                            </div>
                            <div class="listing-name">
                                @if($notification->listing)
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
            <div class="bg-white dark:bg-gray-800 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-600 cursor-pointer {{ !$notification->is_read ? 'bg-blue-50 border-l-4 border-l-blue-500' : '' }}" 
                 wire:click="selectNotification({{ $notification->id }})" 
                 wire:key="mobile-notification-{{ $notification->id }}">
                <div class="p-4">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center flex-1 min-w-0">
                            <!-- Notification Icon -->
                            <div class="flex-shrink-0 h-10 w-10 bg-blue-200 dark:bg-blue-800 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-bell text-blue-600 dark:text-blue-300"></i>
                            </div>
                            
                            <!-- Notification info -->
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-1">
                                    @if($notification->listing)
                                        {{ Str::limit($notification->listing->title, 30) }}
                                    @else
                                        {{ $notification->subject ?? 'Admin obaveštenje' }}
                                    @endif
                                </h3>
                                <p class="text-xs text-gray-500">Sistemsko obaveštenje</p>
                            </div>
                        </div>
                        
                        <!-- Status and date -->
                        <div class="flex flex-col items-end ml-2">
                            @if (!$notification->is_read)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-200 dark:bg-red-800 text-red-800 dark:text-red-200 mb-1">
                                    Novo
                                </span>
                            @endif
                            <span class="text-xs text-gray-400">
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
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            {{ Str::limit($notification->message, 80) }}
                        </p>
                    </div>

                    <!-- Mark as read button (mobile only) -->
                    @if (!$notification->is_read)
                        <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-600">
                            <button wire:click="markAsRead({{ $notification->id }})" 
                                class="text-xs text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300"
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
                <i class="fas fa-bell text-gray-400 text-4xl mb-3"></i>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Nemate obaveštenja</h3>
                <p class="text-gray-600 dark:text-gray-300">Obaveštenja će se pojaviti kada sistem pošalje poruke.</p>
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
        <div class="notification-modal" id="notificationModal" onclick="closeModal()">
            <div class="modal-overlay"></div>
            <div class="modal-content" onclick="event.stopPropagation()">
                <div class="modal-header">
                    <h3>Obaveštenje</h3>
                    <button onclick="closeModal()" class="modal-close" type="button">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="notification-details">
                        <p>{{ $selectedNotification->message }}</p>

                        @if($selectedNotification->listing)
                            <!-- Vezano za listing obaveštenje (favorites) -->
                            <div class="notification-listing-info">
                                <strong>Oglas:</strong>
                                <a href="{{ route('listings.show', $selectedNotification->listing) }}" class="listing-link"
                                    wire:navigate>
                                    {{ $selectedNotification->listing->title }}
                                </a>
                                <div style="font-size: 12px; color: #0ea5e9; margin-top: 4px;">
                                    {{ number_format($selectedNotification->listing->price, 0) }} RSD
                                </div>
                            </div>

                            <!-- Info o korisniku koji je dodao u favorite -->
                            @if($selectedNotification->sender && $selectedNotification->sender->id !== auth()->id())
                                <div class="notification-user-info">
                                    <strong>Korisnik:</strong>
                                    <span>{{ $selectedNotification->sender->name }}</span>
                                    @if($selectedNotification->sender->city)
                                        <div style="font-size: 12px; color: #6b7280; margin-top: 2px;">
                                            {{ $selectedNotification->sender->city }}
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @elseif($selectedNotification->subject)
                            <!-- Admin obaveštenje -->
                            <div class="notification-listing-info">
                                <strong>Naslov:</strong>
                                <span>{{ $selectedNotification->subject }}</span>
                            </div>
                        @endif

                        <div class="notification-time-info">
                            <strong>Datum:</strong>
                            {{ $selectedNotification->created_at->format('d.m.Y. H:i') }}
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    @if($selectedNotification->listing)
                        <a href="{{ route('listings.show', $selectedNotification->listing) }}" class="view-listing-btn"
                            wire:navigate>
                            Pogledaj oglas
                        </a>
                    @endif
                    
                    @if(str_contains($selectedNotification->subject, 'balans') || 
                        str_contains($selectedNotification->subject, 'kredita') || 
                        str_contains($selectedNotification->subject, 'plan ističe'))
                        <a href="{{ route('balance.payment-options') }}" class="view-listing-btn" 
                            style="background-color: #10b981; border-color: #10b981;" wire:navigate>
                            <i class="fas fa-plus mr-1"></i>
                            Dopuna kredita
                        </a>
                        
                        <a href="{{ route('balance.plan-selection') }}" class="view-listing-btn"
                            style="background-color: #8b5cf6; border-color: #8b5cf6;" wire:navigate>
                            <i class="fas fa-calendar-alt mr-1"></i>
                            Promeni plan
                        </a>
                    @endif
                    
                    @if(str_contains($selectedNotification->subject, 'oglas ističe'))
                        <a href="{{ route('listings.my') }}" class="view-listing-btn"
                            style="background-color: #f59e0b; border-color: #f59e0b;" wire:navigate>
                            <i class="fas fa-redo mr-1"></i>
                            Obnovi oglas
                        </a>
                        
                        <a href="{{ route('balance.payment-options') }}" class="view-listing-btn" 
                            style="background-color: #10b981; border-color: #10b981;" wire:navigate>
                            <i class="fas fa-plus mr-1"></i>
                            Dopuna kredita
                        </a>
                    @endif
                    
                    @if(str_contains($selectedNotification->subject, 'Ponuda nadmašena') || str_contains($selectedNotification->subject, 'Aukcija'))
                        @if($selectedNotification->listing)
                            <a href="{{ route('auction.show', $selectedNotification->listing->auction) }}" class="view-listing-btn"
                                style="background-color: #dc2626; border-color: #dc2626;" wire:navigate>
                                <i class="fas fa-gavel mr-1"></i>
                                Idi na aukciju
                            </a>
                        @endif
                    @endif
                    
                    <button onclick="closeModal()" class="close-btn" type="button">
                        Zatvori
                    </button>
                </div>
            </div>
        </div>
    @endif
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
