<div class="conversation-container bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100">
    <style>
        /* Dark mode fixes for conversation */
        .dark .conversation-container {
            background-color: rgb(17 24 39) !important; /* slate-900 */
            color: rgb(229 231 235) !important; /* slate-200 */
        }
        .dark .chat-box {
            background-color: rgb(31 41 55) !important; /* slate-800 */
        }
        .dark .message-bubble {
            background-color: rgb(55 65 81) !important; /* slate-700 */
            color: rgb(229 231 235) !important;
        }
        .dark .my-message .message-bubble {
            background-color: rgb(30 64 175) !important; /* sky-800 */
        }
        .dark .message-textfield {
            background-color: rgb(55 65 81) !important; /* slate-700 */
            border-color: rgb(75 85 99) !important; /* slate-600 */
            color: rgb(229 231 235) !important;
        }
        .dark .back-button {
            color: rgb(156 163 175) !important; /* slate-400 */
        }
        .dark .user-name, .dark .listing-name {
            color: rgb(229 231 235) !important;
        }

        /* Chat box holder needs to be positioned for the button */
        .chat-box-holder {
            position: relative;
            width: 100%;
            overflow: visible;
        }

        .chat-box {
            position: relative;
            height: 500px;
            max-height: 60vh;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 1rem;
        }

        /* Ensure the scroll button is visible in dark mode */
        .dark #scrollToBottomBtn {
            background-color: #3b82f6 !important;
        }
    </style>
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
            <div class="user-name-holder" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
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
                
                <!-- Rating button -->
                @if($otherUser->canBeRatedBy(auth()->id(), $listing->id))
                    <a href="{{ route('ratings.create') }}?user={{ $otherUser->id }}&listing={{ $listing->id }}" 
                       class="inline-flex items-center px-3 py-1.5 bg-amber-100 text-amber-700 rounded-lg hover:bg-amber-200 transition-colors text-sm">
                        <i class="fas fa-star mr-1"></i>
                        Ocenite
                    </a>
                @else
                    <span class="text-xs text-slate-500">Već ste ocenili</span>
                @endif
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
                        <i class="fas fa-eye text-slate-500 mr-1" style="font-size: 14px;"></i>
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
                                        <!-- Pročitana poruka - zeleno oko -->
                                        <i class="fas fa-eye text-green-500 text-sm" title="Pročitano"></i>
                                    @else
                                        <!-- Dostavljena poruka - sivo oko -->
                                        <i class="fas fa-eye text-slate-400 text-sm" title="Dostavljeno"></i>
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

        <!-- Scroll to bottom button -->
        <button id="scrollToBottomBtn"
                onclick="scrollToBottom()"
                style="position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); width: 45px; height: 45px; border-radius: 50%; background-color: rgba(203, 213, 225, 0.7) !important; backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); color: rgb(51, 65, 85); border: 1px solid rgba(203, 213, 225, 0.5); cursor: pointer; display: flex !important; align-items: center; justify-content: center; box-shadow: rgba(0, 0, 0, 0.1) 0px 2px 8px; z-index: 1; transition: all 0.2s ease;"
                onmouseover="this.style.transform='translateX(-50%) scale(1.1)'; this.style.backgroundColor='rgba(148, 163, 184, 0.8)'; this.style.borderColor='rgba(148, 163, 184, 0.6)';"
                onmouseout="this.style.transform='translateX(-50%) scale(1)'; this.style.backgroundColor='rgba(203, 213, 225, 0.7)'; this.style.borderColor='rgba(203, 213, 225, 0.5)';">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor" style="pointer-events: none;">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
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

<script>
    // Define scrollToBottom function immediately so it's available for onclick
    function scrollToBottom() {
        const chatBox = document.getElementById('chat-box');
        if (chatBox) {
            chatBox.scrollTo({
                top: chatBox.scrollHeight,
                behavior: 'smooth'
            });
            // Update button visibility after scrolling
            setTimeout(() => {
                if (typeof updateScrollButton === 'function') {
                    updateScrollButton();
                }
            }, 300);
        }
    }

    // Make it globally accessible
    window.scrollToBottom = scrollToBottom;
</script>

@push('scripts')
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
        // Inicijalizacija Echo/Pusher
        const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            encrypted: true
        });


        // Funkcija za prikaz/sakrivanje dugmeta za scroll
        function updateScrollButton() {
            const chatBox = document.getElementById('chat-box');
            const scrollBtn = document.getElementById('scrollToBottomBtn');

            if (chatBox && scrollBtn) {
                // Proveri da li chat box ima scrollbar
                const hasScrollbar = chatBox.scrollHeight > chatBox.clientHeight;
                const scrolledFromBottom = chatBox.scrollHeight - chatBox.scrollTop - chatBox.clientHeight;

                // TEMPORARILY ALWAYS SHOW FOR TESTING
                scrollBtn.style.display = 'flex';

                // Prikaži dugme ako ima scrollbar i korisnik je skrolovao više od 50px od dna
                // if (hasScrollbar && scrolledFromBottom > 50) {
                //     scrollBtn.style.display = 'flex';
                // } else {
                //     scrollBtn.style.display = 'none';
                // }
            }
        }
        window.updateScrollButton = updateScrollButton;

        // Poziv kada se komponenta učita
        document.addEventListener('DOMContentLoaded', function() {

            scrollToBottom();

            // Initialize scroll button visibility
            setTimeout(() => {
                updateScrollButton();
            }, 100);

            // Pratite skrolovanje i označite poruke kao pročitane
            const chatBox = document.getElementById('chat-box');
            if (chatBox) {
                chatBox.addEventListener('scroll', function() {
                    // Update scroll button visibility
                    updateScrollButton();

                    // Proverite da li je korisnik skrolovao do dna
                    if (chatBox.scrollTop + chatBox.clientHeight >= chatBox.scrollHeight - 50) {
                        // Emitujte event da su poruke pročitane
                        Livewire.dispatch('markAllMessagesAsRead');
                    }
                });

                // Dodaj event listener za resize da bi se ažurirao button pri promeni veličine prozora
                window.addEventListener('resize', updateScrollButton);

                // Dodaj MutationObserver za praćenje promena u chat boxu (nove poruke)
                const observer = new MutationObserver(() => {
                    setTimeout(() => {
                        updateScrollButton();
                    }, 100);
                });

                observer.observe(chatBox, {
                    childList: true,
                    subtree: true
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
            setTimeout(() => {
                scrollToBottom();
                updateScrollButton();
            }, 100);
        });

        Livewire.on('refreshMessages', () => {
            Livewire.dispatch('loadMessages');
            setTimeout(() => {
                scrollToBottom();
                updateScrollButton();
            }, 200);
        });

        // Traži dozvolu za notifikacije
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
    </script>
@endpush
