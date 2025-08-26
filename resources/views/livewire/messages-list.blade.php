<div class="messages-container">
    <!-- Header -->
    <section class="navigation-holder">
        <div class="messages-header">
            <h1>Poruke</h1>

            <!-- Sort Options -->
            <div class="sort-options">
                <div class="sort-item">
                    <span class="checkbox-holder">
                        <input type="checkbox" id="selectAll">
                        <label for="selectAll"></label>
                    </span>
                </div>

                <div class="sort-item">
                    <p>Prikaži:</p>
                    <select wire:model="sortBy" wire:change="setSort($event.target.value)" class="sort-select">
                        <option value="all">Sve</option>
                        <option value="unread">Nepročitane</option>
                        <option value="starred">Zvezdice</option>
                    </select>
                </div>
            </div>
        </div>
    </section>

    <div class="divider"></div>

    <!-- Conversations List -->
    <div class="conversations-list">
        @forelse($conversations as $key => $conversation)
            <div class="conversation-item" wire:key="conversation-{{ $key }}">
                <span class="checkbox-holder">
                    <input type="checkbox" id="conversation-{{ $key }}">
                    <label for="conversation-{{ $key }}"></label>
                </span>

                <div class="conversation-info" wire:click="selectConversation({{ $key }})">
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

                        <!-- Star Button -->
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
                                {{ $conversation['last_message']->created_at->format('d.m.Y. H:i') }}
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
</div>
