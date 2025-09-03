<!-- resources/views/livewire/notifications.blade.php -->
<div class="messages-container">
    <!-- Header -->
    <section class="navigation-holder">
        <div class="messages-header">
            <h1>Obaveštenja</h1>

            <!-- Sort Options -->
            <div class="sort-options">
                <div class="sort-item">
                    <span class="checkbox-holder">
                        <input type="checkbox" id="selectAll" wire:click="markAllAsRead">
                        <label for="selectAll"></label>
                    </span>
                </div>

                <div class="sort-item">
                    <p>Prikaži:</p>
                    <select wire:model="filter" wire:change="setFilter($event.target.value)" class="sort-select">
                        <option value="all">Sve</option>
                        <option value="unread">Nepročitane</option>
                    </select>
                </div>
            </div>
        </div>
    </section>

    <div class="divider"></div>

    <!-- Notifications List -->
    <div class="conversations-list">
        @forelse($notifications as $notification)
            <div class="conversation-item {{ $notification->is_read ? '' : 'unread' }}"
                wire:key="notification-{{ $notification->id }}">
                <span class="checkbox-holder">
                    <input type="checkbox" id="notification-{{ $notification->id }}"
                        wire:click="markAsRead({{ $notification->id }})">
                    <label for="notification-{{ $notification->id }}"></label>
                </span>

                <div class="conversation-info" wire:click="selectNotification({{ $notification->id }})">
                    <div class="conversation-inner">
                        <!-- Notification Info -->
                        <div class="user-info">
                            <div class="user-name">
                                Obaveštenje
                                @if (!$notification->is_read)
                                    <span class="unread-badge">1</span>
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

                        <!-- Star Button (možete dodati funkcionalnost kasnije) -->
                        <div class="star-button">
                            <button>
                                <svg width="20" height="20" viewBox="0 0 16 16" fill="none"
                                    stroke="var(--text-secondary)" class="star-icon">
                                    <path
                                        d="M8.486 0.800001L10.7167 5.21933L15.01 5.64467C15.2187 5.66201 15.3983 5.79917 15.4699 5.99597C15.5415 6.19277 15.4921 6.41325 15.3433 6.56067L11.81 10.0627L13.12 14.8213C13.1748 15.0275 13.1035 15.2466 12.9379 15.3811C12.7723 15.5156 12.5433 15.5405 12.3527 15.4447L8 13.2893L3.65334 15.442C3.46276 15.5378 3.23368 15.513 3.06811 15.3785C2.90254 15.244 2.83126 15.0248 2.886 14.8187L4.196 10.06L0.660004 6.558C0.511258 6.41058 0.461853 6.1901 0.533468 5.9933C0.605083 5.79651 0.784634 5.65934 0.993337 5.642L5.28667 5.21667L7.514 0.800001C7.6074 0.617591 7.79507 0.502838 8 0.502838C8.20493 0.502838 8.39261 0.617591 8.486 0.800001Z" />
                                </svg>
                            </button>
                        </div>

                        <!-- Date Info -->
                        <div class="date-info">
                            <div class="full-date">
                                {{ $notification->created_at->format('d.m.Y. H:i') }}
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

    <!-- Pagination -->
    @if ($notifications->hasPages())
        <div class="pagination-container">
            {{ $notifications->links() }}
        </div>
    @endif

    <!-- Selected Notification Modal -->
    @if ($selectedNotification)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" 
             onclick="@this.set('selectedNotification', null)">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white" 
                 onclick="event.stopPropagation()">
                <div class="mt-3">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Obaveštenje</h3>
                        <button wire:click="$set('selectedNotification', null)" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Message -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-4">
                        <p class="text-gray-800">{{ $selectedNotification->message }}</p>
                    </div>

                    @if($selectedNotification->listing)
                        <!-- Vezano za listing obaveštenje (favorites) -->
                        <div class="border-t pt-4 mb-4">
                            <h4 class="font-medium text-gray-900 mb-2">
                                <i class="fas fa-tag mr-2 text-blue-500"></i>Vezano za oglas:
                            </h4>
                            <div class="bg-blue-50 p-3 rounded-lg">
                                <a href="{{ route('listings.show', $selectedNotification->listing) }}" 
                                   class="text-blue-600 hover:text-blue-800 font-medium"
                                   wire:navigate>
                                    {{ $selectedNotification->listing->title }}
                                </a>
                                <div class="text-sm text-blue-600 mt-1">
                                    {{ number_format($selectedNotification->listing->price, 0) }} RSD
                                </div>
                            </div>
                        </div>

                        <!-- Info o korisniku koji je dodao u favorite -->
                        @if($selectedNotification->sender && $selectedNotification->sender->id !== auth()->id())
                            <div class="border-t pt-4 mb-4">
                                <h4 class="font-medium text-gray-900 mb-2">
                                    <i class="fas fa-user mr-2 text-green-500"></i>Korisnik:
                                </h4>
                                <div class="flex items-center bg-green-50 p-3 rounded-lg">
                                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white text-sm mr-3">
                                        {{ strtoupper(substr($selectedNotification->sender->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-green-800">{{ $selectedNotification->sender->name }}</div>
                                        @if($selectedNotification->sender->city)
                                            <div class="text-sm text-green-600">{{ $selectedNotification->sender->city }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    @elseif($selectedNotification->subject)
                        <!-- Admin obaveštenje -->
                        <div class="border-t pt-4 mb-4">
                            <h4 class="font-medium text-gray-900 mb-2">
                                <i class="fas fa-crown mr-2 text-purple-500"></i>Admin obaveštenje:
                            </h4>
                            <div class="bg-purple-50 p-3 rounded-lg">
                                <div class="font-medium text-purple-800">{{ $selectedNotification->subject }}</div>
                            </div>
                        </div>
                    @endif

                    <!-- Date Info -->
                    <div class="border-t pt-4 text-sm text-gray-500">
                        <i class="fas fa-clock mr-2"></i>
                        <strong>Datum:</strong> {{ $selectedNotification->created_at->format('d.m.Y. H:i') }}
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">
                    @if($selectedNotification->listing)
                        <a href="{{ route('listings.show', $selectedNotification->listing) }}" 
                           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                           wire:navigate>
                            <i class="fas fa-eye mr-2"></i>
                            Pogledaj oglas
                        </a>
                    @endif
                    <button wire:click="$set('selectedNotification', null)" 
                            class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                        <i class="fas fa-times mr-2"></i>
                        Zatvori
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
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
                @this.set('selectedNotification', null);
            }
        });
    });
</script>
