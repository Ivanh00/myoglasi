<div class="conversation-container">
    <!-- Navigation -->
    <section class="navigation-holder">
        <a class="back-button" href="{{ route('home') }}">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M10 15L3.63057 8.32978C3.54697 8.24236 3.5 8.12372 3.5 8C3.5 7.87628 3.54697 7.75764 3.63057 7.67022L10 1"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
            Nazad
        </a>
    </section>

    <!-- Admin Info -->
    <section class="user-info-section">
        <div class="user-info-holder">
            <div class="user-name-holder" style="display: flex; align-items: center; gap: 0.5rem;">
                <span class="user-avatar">
                    @if ($admin->avatar)
                        <img src="{{ $admin->avatar_url }}" alt="{{ $admin->name }}"
                            style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                    @else
                        <div
                            style="width: 40px; height: 40px; background-color: #dc2626; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                            <i class="fas fa-user-shield"></i>
                        </div>
                    @endif
                </span>
                <div>
                    <div class="user-name">{{ $admin->name }}</div>
                    <div style="font-size: 0.8rem; color: #6b7280;">Administrator</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Chat Box -->
    <div class="chat-box-holder">
        <div class="chat-box" id="chat-box">
            <section class="user-response-info text-center">
                <b>{{ $admin->name }}</b>
                <div>Administrator PazAriO platforme</div>
                <div style="font-size: 0.9rem; color: #6b7280; margin-top: 0.5rem;">
                    Pišite nam za podršku, pitanja ili predloge
                </div>
            </section>

            @forelse($conversation as $message)
                <div class="message-item {{ $message->sender_id == auth()->id() ? 'my-message' : 'other-message' }}">
                    <div class="message-bubble">
                        @if ($message->subject && $loop->first)
                            <div
                                style="font-weight: bold; margin-bottom: 0.5rem; padding-bottom: 0.5rem; border-bottom: 1px solid rgba(255,255,255,0.2);">
                                {{ $message->subject }}
                            </div>
                        @endif
                        <div>{{ $message->message }}</div>
                        <div class="message-info">
                            <div>{{ $message->created_at->format('H:i') }}h</div>
                            @if ($message->sender_id == auth()->id())
                                <!-- Status poruke -->
                                <div class="message-status">
                                    @if ($message->is_read)
                                        <!-- Pročitana poruka - zeleno oko -->
                                        <i class="fas fa-eye text-green-600 dark:text-green-300 text-sm"
                                            title="Pročitano"></i>
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
            @empty
                <div class="text-center py-8 text-slate-500 dark:text-slate-400">
                    <i class="fas fa-comments text-4xl mb-4"></i>
                    <p>Počnite konverzaciju sa administratorom</p>
                    <p class="text-sm">Pišite za podršku, prijave problema ili predloge</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Message Form -->
    <div class="message-form-holder">
        <!-- Subject field for first message -->
        @if (count($conversation) == 0)
            <div class="text-field-holder">
                <input type="text" wire:model="subject"
                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg mb-3 focus:ring-2 focus:ring-sky-500 focus:border-sky-500"
                    placeholder="Naslov poruke (npr. Pitanje o funkcionalnosti, Prijava problema...)">
                @error('subject')
                    <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        @endif

        <div class="text-field-holder">
            <textarea wire:model="message" id="message" name="message" class="message-textfield"
                wire:keydown.enter.prevent="sendMessage" wire:keydown.enter.ctrl="sendMessage"
                placeholder="Napišite vašu poruku administratoru..."></textarea>
            @error('message')
                <p class="text-red-600 dark:text-red-400  text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <section class="form-buttons">
            <div class="flex items-center text-sm text-slate-500 dark:text-slate-400">
                <i class="fas fa-shield-alt mr-2"></i>
                Direktna komunikacija sa administratorom
            </div>

            <button wire:click="sendMessage" wire:loading.attr="disabled" wire:target="sendMessage">
                <span wire:loading.remove wire:target="sendMessage">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Pošaljite poruku
                </span>
                <span wire:loading wire:target="sendMessage" class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    Slanje...
                </span>
            </button>
        </section>
    </div>

    <!-- Debug info (remove later) -->
    @if (session()->has('success') || session()->has('error'))
        <div
            class="fixed bottom-4 right-4 p-3 rounded-lg shadow-lg z-50
            {{ session()->has('success') ? 'bg-green-200 dark:bg-green-800 text-green-800 dark:text-green-200' : 'bg-red-200 dark:bg-red-800 text-red-800 dark:text-red-200' }}">
            {{ session('success') ?? session('error') }}
        </div>
    @endif
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('message-sent', () => {
            setTimeout(() => {
                const chatBox = document.getElementById('chat-box');
                if (chatBox) {
                    chatBox.scrollTop = chatBox.scrollHeight;
                }
            }, 100);
        });
    });

    // Auto scroll on page load
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            const chatBox = document.getElementById('chat-box');
            if (chatBox) {
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        }, 200);
    });
</script>
