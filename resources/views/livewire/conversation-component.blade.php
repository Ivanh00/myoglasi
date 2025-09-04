<div class="conversation-container">
    <!-- Navigacija -->
    <section class="navigation-holder">
        <a class="back-button" href="{{ route('messages.inbox') }}">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M10 15L3.63057 8.32978C3.54697 8.24236 3.5 8.12372 3.5 8C3.5 7.87628 3.54697 7.75764 3.63057 7.67022L10 1"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
            Nazad na poruke
        </a>
    </section>

    <!-- Informacije o korisniku -->
    <section class="user-info-section">
        <div class="user-info-holder">
            <div class="user-info-holder">
                <div class="user-name-holder">
                    <span class="user-avatar">
                        @if($otherUser->avatar)
                            <img src="{{ $otherUser->avatar_url }}" alt="{{ $otherUser->name }}" 
                                style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                        @else
                            <div style="width: 40px; height: 40px; background-color: #3b82f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                            </div>
                        @endif
                    </span>
                    <div class="user-name">
                        {{ $otherUser->name }}
                    </div>
                </div>
            </div>
    </section>

    <!-- Informacije o oglasu -->
    <article class="ad-info-holder">
        <!-- Desktop layout (original) -->
        <div class="hidden md:flex justify-between items-center">
            <a class="ad-link" href="{{ route('listings.show', $listing) }}">
                <div class="ad-image-holder">
                    <img src="{{ $listing->images->first()->url ?? 'https://via.placeholder.com/80x60' }}"
                        alt="{{ $listing->title }}" width="80" height="60">
                </div>
            </a>

            <a class="ad-title" href="{{ route('listings.show', $listing) }}">
                <div>{{ $listing->title }}</div>
            </a>

            <div class="ad-price">
                <div>{{ number_format($listing->price, 0, ',', '.') }} RSD</div>
            </div>

            <section class="ad-stats flex space-x-4">
                <div class="stat-item flex items-center">
                    <svg width="18" height="18" viewBox="0 0 16 16" fill="none" stroke="#6b7280" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M8.00001 3.50067C5.31267 3.45533 2.53334 5.33333 0.786007 7.25667C0.406129 7.6784 0.406129 8.31893 0.786007 8.74067C2.49534 10.6233 5.26667 12.5447 8.00001 12.4987C10.7333 12.5447 13.5053 10.6233 15.216 8.74067C15.5959 8.31893 15.5959 7.6784 15.216 7.25667C13.4667 5.33333 10.6873 3.45533 8.00001 3.50067Z"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M10.5 8C10.4996 9.38062 9.38018 10.4996 7.99956 10.4993C6.61893 10.4991 5.49988 9.37973 5.5 7.99911C5.50012 6.61848 6.61937 5.49933 8 5.49933C8.66321 5.49915 9.2993 5.76258 9.7682 6.2316C10.2371 6.70063 10.5004 7.33678 10.5 8V8Z"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"></path>
                    </svg>
                    <span class="stat-count ml-1">{{ $listing->views }}</span>
                </div>

                <div class="stat-item flex items-center">
                    <i class="fas fa-heart text-red-500 mr-1"></i>
                    <span class="stat-count">{{ $listing->favorites_count }}</span>
                </div>
            </section>

            <section class="ad-posted-date">
                {{ $listing->created_at->diffForHumans() }}
            </section>

            <section class="ad-location">
                {{ $listing->location }}
            </section>
        </div>

        <!-- Mobile layout (new organized structure) -->
        <div class="md:hidden flex justify-between">
            <!-- Left column: Image + Title + Price -->
            <div class="mobile-left-column">
                <a class="ad-link" href="{{ route('listings.show', $listing) }}">
                    <div class="ad-image-holder">
                        <img src="{{ $listing->images->first()->url ?? 'https://via.placeholder.com/80x60' }}"
                            alt="{{ $listing->title }}" width="80" height="60">
                    </div>
                </a>
                
                <a class="ad-title" href="{{ route('listings.show', $listing) }}">
                    {{ $listing->title }}
                </a>
                
                <div class="ad-price">
                    {{ number_format($listing->price, 0, ',', '.') }} RSD
                </div>
            </div>

            <!-- Right column: Stats + Date + Location -->
            <div class="mobile-right-column">
                <section class="ad-stats">
                    <div class="stat-item flex items-center">
                        <i class="fas fa-eye text-gray-500 mr-1" style="font-size: 14px;"></i>
                        <span class="stat-count">{{ $listing->views }}</span>
                    </div>

                    <div class="stat-item flex items-center">
                        <i class="fas fa-heart text-red-500 mr-1" style="font-size: 14px;"></i>
                        <span class="stat-count">{{ $listing->favorites_count }}</span>
                    </div>
                </section>

                <section class="ad-posted-date">
                    {{ $listing->created_at->diffForHumans() }}
                </section>

                <section class="ad-location">
                    {{ $listing->location }}
                </section>
            </div>
        </div>
    </article>

    <!-- Chat prozor -->
    <div class="chat-box-holder">
        <div class="chat-box" id="chat-box">
            <section class="user-response-info text-center">
                <b>{{ $otherUser->name }}</b>
                <div>Član od {{ $otherUser->created_at->format('d.m.Y.') }}</div>
            </section>

            @foreach ($messages as $message)
                <div class="message-item {{ $message->sender_id == auth()->id() ? 'my-message' : 'other-message' }}">
                    <div class="message-bubble">
                        <div>{{ $message->message }}</div>
                        <div class="message-info">
                            <div>{{ $message->created_at->format('H:i') }}h</div>
                            @if ($message->sender_id == auth()->id())
                                <!-- Status poruke -->
                                <div class="message-status">
                                    @if ($message->is_read)
                                        <!-- Dve zelene kvakice - poruka je pročitana -->
                                        <svg width="16" height="11" viewBox="0 0 18 11" fill="none"
                                            class="read-status">
                                            <path d="M10.5 1L4.5 9.5L1.5 6.5" stroke="#4CAF50" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M16.5 1L10.5 9.5L9 8" stroke="#4CAF50" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    @else
                                        <!-- Dve sive kvakice - poruka je dostavljena ali nije pročitana -->
                                        <svg width="16" height="11" viewBox="0 0 18 11" fill="none"
                                            class="delivered-status">
                                            <path d="M10.5 1L4.5 9.5L1.5 6.5" stroke="#9E9E9E" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M16.5 1L10.5 9.5L9 8" stroke="#9E9E9E" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                @if ($loop->iteration % 5 === 0)
                    <div class="message-date">
                        {{ $message->created_at->format('d.m.Y.') }}
                    </div>
                @endif
            @endforeach

        </div>

        <div class="scroll-to-bottom">
            <button wire:click="scrollToBottom">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M22.5 7L12.4947 16.7991C12.3635 16.9277 12.1856 17 12 17C11.8144 17 11.6365 16.9277 11.5053 16.7991L1.5 7"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Lista adresa (sakrivena po defaultu) -->
    @if ($showAddressList && !$isSystemConversation)
        <div class="address-list">
            <div class="address-list-container">
                @foreach ($addresses as $address)
                    <div class="address-item">
                        <button wire:click="sendAddress({{ $address->id }})" class="address-button">
                            <div>
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M0.933367 2.15456C0.719728 1.77229 0.779665 1.29549 1.08127 0.977984C1.38287 0.660483 1.85597 0.576154 2.2487 0.769892L15 7C15.1677 7.0853 15.2733 7.36618 15.2733 7.55433C15.2733 7.74248 15.1677 7.9147 15 8L2.2487 14.5486C1.85597 14.7423 1.38287 14.658 1.08127 14.3405C0.779665 14.023 0.719728 13.5462 0.933367 13.1639L4.5 7.5L0.933367 2.15456Z"
                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M15.2294 7.5H4.33337" stroke-linecap="round" stroke-linejoin="round">
                                    </path>
                                </svg>
                            </div>
                            <div class="address-content">
                                <div class="address-title">{{ $address->street }}</div>
                                <div class="address-description">{{ $address->city }}, {{ $address->phone }}</div>
                            </div>
                        </button>
                    </div>
                @endforeach

                <div class="address-item">
                    <button class="address-button">
                        <div>
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M8 5V11" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M5 8H11" stroke-linecap="round" stroke-linejoin="round"></path>
                                <circle cx="8" cy="8" r="7.5"></circle>
                            </svg>
                        </div>
                        <div class="address-content">
                            <div class="address-title">Dodajte novu adresu</div>
                            <div class="address-description">Svaki naredni put možete je poslati u dva klika</div>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Forma za slanje poruke (samo za regularne konverzacije) -->
    <div class="message-form-holder">
        <div class="text-field-holder">
            <textarea wire:model="newMessage" id="message" name="message" class="message-textfield"
                wire:keydown.enter.prevent="sendMessage" placeholder="Unesite Vašu poruku. Za brže slanje pritisnite CTRL + ENTER"
                wire:keydown.enter.ctrl="sendMessage"></textarea>

            <div class="emoji-picker">
                <button class="emoji-button">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M8.99998 16.6666C13.2342 16.6666 16.6666 13.2341 16.6666 8.99989C16.6666 4.76571 13.2342 1.33322 8.99998 1.33322C4.7658 1.33322 1.33331 4.76571 1.33331 8.99989C1.33331 13.2341 4.7658 16.6666 8.99998 16.6666Z"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M6.66665 6.33322C6.85074 6.33322 6.99998 6.48246 6.99998 6.66655C6.99998 6.85065 6.85074 6.99989 6.66665 6.99989C6.48255 6.99989 6.33331 6.85065 6.33331 6.66655C6.33331 6.48246 6.48255 6.33322 6.66665 6.33322Z"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M11.3333 6.33322C11.5174 6.33322 11.6667 6.48246 11.6667 6.66655C11.6667 6.85065 11.5174 6.99989 11.3333 6.99989C11.1492 6.99989 11 6.85065 11 6.66655C11 6.48246 11.1492 6.33322 11.3333 6.33322Z"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                        <path
                            d="M13.3334 10.9999C12.5173 12.429 10.8381 13.3332 9.00002 13.3332C7.16195 13.3332 5.48277 12.429 4.66669 10.9999"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </button>
            </div>
        </div>

        <section class="form-buttons">
            <button wire:click="sendMessage" wire:loading.attr="disabled" wire:target="sendMessage">
                <span wire:loading.remove wire:target="sendMessage">Pošaljite poruku</span>
                <span wire:loading wire:target="sendMessage">Slanje...</span>
            </button>
        </section>
    </div>
</div>

@push('scripts')
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
        // Inicijalizacija Echo/Pusher
        const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            encrypted: true
        });

        // Funkcija za automatsko skrolovanje na dno chata
        function scrollToBottom() {
            const chatBox = document.getElementById('chat-box');
            if (chatBox) {
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        }

        // Poziv kada se komponenta učita
        document.addEventListener('DOMContentLoaded', function() {
            scrollToBottom();

            // Pratite skrolovanje i označite poruke kao pročitane
            const chatBox = document.getElementById('chat-box');
            if (chatBox) {
                chatBox.addEventListener('scroll', function() {
                    // Proverite da li je korisnik skrolovao do dna
                    if (chatBox.scrollTop + chatBox.clientHeight >= chatBox.scrollHeight - 50) {
                        // Emitujte event da su poruke pročitane
                        Livewire.dispatch('markAllMessagesAsRead');
                    }
                });
            }

            // Pretplata na kanal za real-time poruke
            @auth
            const channel = pusher.subscribe('private-conversation.{{ $listing->id }}.{{ Auth::id() }}');

            // Slušaj nove poruke
            channel.bind('App\\Events\\NewMessage', function(data) {
                // Ažuriraj poruke
                Livewire.dispatch('refreshMessages');

                // Prikaz obaveštenja
                if (Notification.permission === 'granted') {
                    new Notification('Nova poruka', {
                        body: data.message.sender.name + ': ' + data.message.message,
                        icon: '/icon.png'
                    });
                }

                // Ažuriraj broj nepročitanih poruka
                updateUnreadCount(data.unread_count);
            });

            // Slušaj kada su poruke pročitane
            channel.bind('App\\Events\\MessageRead', function(data) {
                // Ažuriraj status poruke
                const messageElement = document.querySelector('[data-message-id="' + data.message_id +
                    '"]');
                if (messageElement) {
                    messageElement.querySelector('.delivered-status').classList.add('hidden');
                    messageElement.querySelector('.read-status').classList.remove('hidden');
                }

                // Ažuriraj broj nepročitanih poruka
                updateUnreadCount(data.unread_count);
            });
        @endauth
        });

        // Funkcija za ažuriranje broja nepročitanih poruka
        function updateUnreadCount(count) {
            const unreadBadge = document.getElementById('unread-messages-count');
            if (unreadBadge) {
                if (count > 0) {
                    unreadBadge.textContent = count;
                    unreadBadge.classList.remove('hidden');
                } else {
                    unreadBadge.classList.add('hidden');
                }
            }
        }

        // Livewire event listeneri
        Livewire.on('scrollToBottom', () => {
            setTimeout(scrollToBottom, 100);
        });

        Livewire.on('refreshMessages', () => {
            Livewire.dispatch('loadMessages');
        });

        // Traži dozvolu za notifikacije
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
    </script>
@endpush
