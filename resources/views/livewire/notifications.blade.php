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
                                {{ $notification->listing->title }}
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
        <div class="notification-modal" x-data="{ open: true }" x-show="open" x-on:click.away="open = false"
            x-on:keydown.escape.window="open = false">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Obaveštenje</h3>
                    <button wire:click="$set('selectedNotification', null)" class="modal-close">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
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

                        <div class="notification-listing-info">
                            <strong>Oglas:</strong>
                            <a href="{{ route('listings.show', $selectedNotification->listing) }}" class="listing-link"
                                wire:navigate>
                                {{ $selectedNotification->listing->title }}
                            </a>
                        </div>

                        <div class="notification-time-info">
                            <strong>Datum:</strong>
                            {{ $selectedNotification->created_at->format('d.m.Y. H:i') }}
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <a href="{{ route('listings.show', $selectedNotification->listing) }}" class="view-listing-btn"
                        wire:navigate>
                        Pogledaj oglas
                    </a>
                    <button wire:click="$set('selectedNotification', null)" class="close-btn">
                        Zatvori
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>


<script>
    // Automatski označi sva obaveštenja kao pročitana kada se stranica učita
    document.addEventListener('DOMContentLoaded', function() {
        // Možete pozvati Livewire metod za označavanje svih kao pročitanih
        // @this.markAllAsRead();

        // Ili možete koristiti event listener za checkbox
        const selectAllCheckbox = document.getElementById('selectAll');
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    // Pozovi Livewire metod za označavanje svih kao pročitanih
                    Livewire.dispatch('markAllAsRead');
                }
            });
        }
    });
</script>
