<div class="space-y-6">
    @unless ($embedded)
        <!-- Header -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">Upravljanje obaveštenjima</h2>
                    <p class="text-slate-600">Pošaljite obaveštenja korisnicima individualno ili grupno</p>
                </div>
                <button wire:click="openSendModal" class="bg-sky-600 text-white px-4 py-2 rounded-lg hover:bg-sky-700">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Pošalji obaveštenje
                </button>
            </div>
        </div>
    @endunless

    @unless ($embedded)
        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-sky-50 dark:bg-sky-900 p-4 rounded-lg border border-sky-200">
                <h3 class="text-sm font-medium text-sky-800 dark:text-sky-200">Ukupno korisnika</h3>
                <p class="text-2xl font-bold text-sky-700 dark:text-sky-300">
                    {{ \App\Models\User::where('is_banned', false)->count() }}</p>
            </div>
            <div class="bg-green-50 dark:bg-green-900 p-4 rounded-lg border border-green-100">
                <h3 class="text-sm font-medium text-green-800 dark:text-green-200">Sa oglasima</h3>
                <p class="text-2xl font-bold text-green-700 dark:text-green-300">
                    {{ \App\Models\User::where('is_banned', false)->has('listings')->count() }}</p>
            </div>
            <div class="bg-purple-50 dark:bg-purple-900 p-4 rounded-lg border border-purple-200">
                <h3 class="text-sm font-medium text-purple-800 dark:text-purple-200">Sa kreditima</h3>
                <p class="text-2xl font-bold text-purple-700 dark:text-purple-300">
                    {{ \App\Models\User::where('is_banned', false)->where('balance', '>', 0)->count() }}</p>
            </div>
            <div class="bg-orange-50 dark:bg-orange-900 p-4 rounded-lg border border-orange-200">
                <h3 class="text-sm font-medium text-orange-800 dark:text-orange-200">Novi (30 dana)</h3>
                <p class="text-2xl font-bold text-orange-700 dark:text-orange-300">
                    {{ \App\Models\User::where('is_banned', false)->where('created_at', '>=', now()->subDays(30))->count() }}
                </p>
            </div>
        </div>
    @endunless

    @unless ($embedded)
        <!-- Sent Notifications -->
        <div class="hidden lg:block bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-slate-200">
                <h3 class="text-lg font-medium text-slate-900">Poslana obaveštenja</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Naslov</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Prima
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Poruka</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Datum
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @forelse($sentNotifications as $notification)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-slate-900">
                                        {{ $notification->subject ?? 'Admin obaveštenje' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if ($notification->receiver->avatar)
                                            <img src="{{ $notification->receiver->avatar_url }}"
                                                alt="{{ $notification->receiver->name }}"
                                                class="w-8 h-8 rounded-full object-cover">
                                        @else
                                            <div
                                                class="w-8 h-8 rounded-full bg-slate-500 flex items-center justify-center text-white font-medium text-sm">
                                                {{ strtoupper(substr($notification->receiver->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-slate-900">
                                                {{ $notification->receiver->name }}</div>
                                            <div class="text-sm text-slate-500">{{ $notification->receiver->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-slate-900">{{ Str::limit($notification->message, 50) }}</div>
                                    @if ($notification->listing)
                                        <div class="text-xs text-slate-500 mt-1">Vezano za:
                                            {{ $notification->listing->title }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if ($notification->is_read)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Pročitano
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                            Nepročitano
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    {{ $notification->created_at->format('d.m.Y H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-slate-500">
                                    Nema poslanih obaveštenja.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-slate-200">
                {{ $sentNotifications->links() }}
            </div>
        </div>

        <!-- Mobile Notifications Cards -->
        <div class="lg:hidden space-y-4">
            @forelse($sentNotifications as $notification)
                <div class="bg-white shadow rounded-lg p-4">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-2">
                            @if ($notification->is_read)
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>
                                    Pročitano
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                    <i class="fas fa-clock mr-1"></i>
                                    Nepročitano
                                </span>
                            @endif
                            <div class="text-xs text-slate-500">{{ $notification->created_at->format('d.m.Y H:i') }}</div>
                        </div>
                    </div>

                    <!-- Notification Title -->
                    <div class="mb-4">
                        <div class="text-lg font-semibold text-slate-900">
                            {{ $notification->subject ?? 'Admin obaveštenje' }}</div>
                    </div>

                    <!-- Recipient Info -->
                    <div class="bg-slate-50 p-3 rounded-lg mb-4">
                        <div class="flex items-center justify-between mb-2">
                            <div class="text-xs font-medium text-slate-500 uppercase tracking-wider">Prima</div>
                            <div class="flex items-center">
                                @if ($notification->receiver->avatar)
                                    <img src="{{ $notification->receiver->avatar_url }}"
                                        alt="{{ $notification->receiver->name }}"
                                        class="w-8 h-8 rounded-full object-cover mr-2">
                                @else
                                    <div
                                        class="w-8 h-8 bg-sky-500 rounded-full flex items-center justify-center text-white text-xs mr-2">
                                        {{ strtoupper(substr($notification->receiver->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="text-sm font-medium text-slate-900">{{ $notification->receiver->name }}</div>
                        <div class="text-xs text-slate-500">{{ $notification->receiver->email }}</div>
                    </div>

                    <!-- Message Preview -->
                    <div class="mb-4">
                        <div class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">Poruka</div>
                        <div class="text-sm text-slate-900">{{ Str::limit($notification->message, 120) }}</div>
                        @if (strlen($notification->message) > 120)
                            <button class="text-sky-600 dark:text-sky-400 text-xs mt-1">
                                Prikaži više...
                            </button>
                        @endif
                    </div>

                    <!-- Related Listing -->
                    @if ($notification->listing)
                        <div class="mb-4">
                            <div class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">Vezano za oglas
                            </div>
                            <div class="bg-amber-50 p-3 rounded-lg border border-amber-200">
                                <div class="text-sm font-medium text-slate-900">
                                    {{ Str::limit($notification->listing->title, 40) }}</div>
                                <div class="text-xs text-green-600 font-semibold">
                                    {{ number_format($notification->listing->price, 2) }} RSD</div>
                            </div>
                        </div>
                    @endif

                    <!-- Notification Type Badge -->
                    <div class="mb-4">
                        <div class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">Tip obaveštenja</div>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            <i class="fas fa-bell mr-1"></i>
                            Admin obaveštenje
                        </span>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow p-8 text-center">
                    <i class="fas fa-bell text-slate-400 text-5xl mb-4"></i>
                    <h3 class="text-xl font-semibold text-slate-800 mb-2">Nema poslanih obaveštenja</h3>
                    <p class="text-slate-600">Još uvek nema poslanih obaveštenja korisnicima.</p>
                </div>
            @endforelse

            <!-- Mobile Pagination -->
            <div class="mt-6">
                {{ $sentNotifications->links() }}
            </div>
        </div>
    @endunless

    <!-- Send Notification Modal -->
    @if ($showSendModal)
        <div class="fixed inset-0 bg-slate-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-medium text-slate-900">Pošalji obaveštenje</h3>
                        <button wire:click="$set('showSendModal', false)" class="text-slate-400 hover:text-slate-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form wire:submit="sendNotification">
                        <div class="space-y-6">
                            <!-- Recipient Selection -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Prima</label>
                                <div class="space-y-3">
                                    <label class="flex items-center">
                                        <input type="radio" wire:model.live="notificationData.recipient_type"
                                            value="single" class="mr-2">
                                        <span class="text-sm">Pojedinačno korisniku</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" wire:model.live="notificationData.recipient_type"
                                            value="all" class="mr-2">
                                        <span class="text-sm">Svim korisnicima</span>
                                        <span class="text-xs text-slate-500 ml-2">(samo aktivni, ne banovani)</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" wire:model.live="notificationData.recipient_type"
                                            value="filtered" class="mr-2">
                                        <span class="text-sm">Filtriranim korisnicima</span>
                                        <span class="text-xs text-slate-500 ml-2">(custom grupe)</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" wire:model.live="notificationData.recipient_type"
                                            value="public" class="mr-2">
                                        <span class="text-sm text-green-600 font-medium">Javno obaveštenje</span>
                                        <span class="text-xs text-green-500 ml-2">(prikazuje se svim korisnicima u
                                            navigation bar-u)</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Single User Search -->
                            @if ($notificationData['recipient_type'] === 'single')
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Pretraži
                                        korisnika</label>
                                    <input type="text" wire:model.live="searchUser"
                                        class="w-full px-3 py-2 border border-slate-300 rounded-md"
                                        placeholder="Unesite ime ili email korisnika...">

                                    @if ($foundUsers->count() > 0)
                                        <div class="mt-2 max-h-40 overflow-y-auto border border-slate-200 rounded-md">
                                            @foreach ($foundUsers as $user)
                                                <div wire:click="selectUser({{ $user->id }})"
                                                    class="p-2 hover:bg-slate-100 cursor-pointer flex items-center">
                                                    @if ($user->avatar)
                                                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                                                            class="w-6 h-6 rounded-full object-cover mr-2">
                                                    @else
                                                        <div
                                                            class="w-6 h-6 bg-sky-500 rounded-full flex items-center justify-center text-white text-xs mr-2">
                                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="font-medium text-sm">{{ $user->name }}</div>
                                                        <div class="text-xs text-slate-500">{{ $user->email }}</div>
                                                        @if ($user->is_banned)
                                                            <span class="text-red-500 text-xs">Banovan</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    @error('notificationData.recipient_id')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif

                            <!-- Filtered Users Options -->
                            @if ($notificationData['recipient_type'] === 'filtered')
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Filter
                                        korisnika</label>
                                    <select wire:model.live="notificationData.filter_criteria.user_type"
                                        class="w-full px-3 py-2 border border-slate-300 rounded-md">
                                        <option value="all">Svi aktivni korisnici</option>
                                        <option value="with_listings">Korisnici sa oglasima</option>
                                        <option value="with_balance">Korisnici sa kreditima</option>
                                        <option value="recent">Novi korisnici</option>
                                    </select>

                                    @if ($notificationData['filter_criteria']['user_type'] === 'recent')
                                        <div class="mt-2">
                                            <label class="block text-xs text-slate-600">Registrovani u poslednih X
                                                dana</label>
                                            <input type="number"
                                                wire:model.live="notificationData.filter_criteria.days"
                                                class="w-full px-3 py-2 border border-slate-300 rounded-md"
                                                min="1" max="365">
                                        </div>
                                    @endif

                                    <div class="mt-2 text-sm text-slate-600">
                                        Biće poslano na <strong>{{ $this->getRecipientsCount() }}</strong> korisnika
                                    </div>
                                </div>
                            @endif

                            @if ($notificationData['recipient_type'] === 'all')
                                <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                                    <div class="flex items-center">
                                        <i class="fas fa-exclamation-triangle text-amber-500 mr-2"></i>
                                        <span class="text-amber-800 text-sm">
                                            Obaveštenje će biti poslano na
                                            <strong>{{ $this->getRecipientsCount() }}</strong> korisnika
                                        </span>
                                    </div>
                                </div>
                            @endif

                            <!-- Title -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Naslov obaveštenja</label>
                                <input type="text" wire:model="notificationData.title"
                                    class="w-full px-3 py-2 border border-slate-300 rounded-md"
                                    placeholder="npr. Važna obaveštenja za korisnike">
                                @error('notificationData.title')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Message -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Poruka</label>
                                <textarea wire:model="notificationData.message" rows="4"
                                    class="w-full px-3 py-2 border border-slate-300 rounded-md" placeholder="Unesite poruku obaveštenja..."></textarea>
                                @error('notificationData.message')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                                <div class="text-xs text-slate-500 mt-1">
                                    {{ strlen($notificationData['message'] ?? '') }}/1000 karaktera</div>
                            </div>

                            <!-- Optional Listing - only for single user notifications -->
                            @if ($notificationData['recipient_type'] === 'single')
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Vezano za oglas
                                        (opciono)</label>
                                    <select wire:model="notificationData.listing_id"
                                        class="w-full px-3 py-2 border border-slate-300 rounded-md">
                                        <option value="">Nije vezano za oglas</option>
                                        @foreach ($availableListings as $listing)
                                            <option value="{{ $listing->id }}">{{ Str::limit($listing->title, 50) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($notificationData['recipient_id'])
                                        <div class="text-xs text-slate-500 mt-1">Prikazuju se oglasi odabranog
                                            korisnika
                                        </div>
                                    @else
                                        <div class="text-xs text-slate-500 mt-1">Prvo odaberite korisnika da vidite
                                            njegove oglase</div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="flex justify-between items-center mt-8">
                            <div class="text-sm text-slate-600">
                                @if ($notificationData['recipient_type'] !== 'single')
                                    Broj recipijenata: <strong>{{ $this->getRecipientsCount() }}</strong>
                                @elseif($notificationData['recipient_type'] === 'single' && $notificationData['recipient_id'])
                                    Šalje se korisniku: <strong>{{ $searchUser }}</strong>
                                @endif
                            </div>
                            <div class="flex space-x-3">
                                <button type="button" wire:click="$set('showSendModal', false)"
                                    class="px-4 py-2 text-slate-600 border border-slate-300 rounded-lg hover:bg-slate-50">
                                    Otkaži
                                </button>
                                <button type="submit" wire:loading.attr="disabled"
                                    class="px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 disabled:opacity-50">
                                    <span wire:loading.remove>
                                        <i class="fas fa-paper-plane mr-2"></i>
                                        Pošalji obaveštenje
                                    </span>
                                    <span wire:loading>
                                        <i class="fas fa-spinner fa-spin mr-2"></i>
                                        Šalje se...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    // Close modal on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && @this.showSendModal) {
            @this.set('showSendModal', false);
        }
    });
</script>
