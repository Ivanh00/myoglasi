<div>
    @if ($showModal && $listing)
        <div class="fixed inset-0 bg-slate-600/50 dark:bg-slate-900/75 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
            <div class="relative mx-auto p-6 border border-slate-200 dark:border-slate-600 w-full max-w-4xl shadow-lg rounded-xl bg-white dark:bg-slate-800">
                <!-- Modal Header -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100">
                            <i class="fas fa-gift text-green-500 mr-2"></i>
                            Zahtevi za poklon
                        </h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                            {{ $listing->title }}
                        </p>
                    </div>
                    <button wire:click="closeModal"
                        class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                @if ($reservations->count() > 0)
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Left: List of Requests -->
                        <div>
                            <h4 class="text-lg font-semibold text-slate-800 dark:text-slate-200 mb-3">
                                Pristigli zahtevi ({{ $reservations->where('status', 'pending')->count() }}/{{ \App\Models\Setting::get('max_giveaway_requests', 9) }})
                                @if($reservations->where('status', 'pending')->count() >= \App\Models\Setting::get('max_giveaway_requests', 9))
                                    <span class="text-xs text-amber-600 dark:text-amber-400 ml-2">
                                        <i class="fas fa-info-circle"></i> Max dostignut
                                    </span>
                                @endif
                            </h4>
                            <div class="space-y-2 max-h-96 overflow-y-auto">
                                @foreach ($reservations as $reservation)
                                    <div wire:click="selectReservation({{ $reservation->id }})"
                                        class="p-4 border rounded-lg cursor-pointer transition-all
                                        @if($selectedReservation && $selectedReservation->id === $reservation->id)
                                            border-green-500 bg-green-50 dark:bg-green-900/20
                                        @else
                                            border-slate-200 dark:border-slate-600 hover:border-green-300 dark:hover:border-green-600
                                        @endif
                                        @if($reservation->status === 'approved')
                                            bg-green-100 dark:bg-green-900/30 border-green-300 dark:border-green-700
                                        @elseif($reservation->status === 'rejected')
                                            bg-red-100 dark:bg-red-900/30 border-red-300 dark:border-red-700
                                        @endif">

                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center mb-2">
                                                    <span class="font-medium text-slate-900 dark:text-slate-100">
                                                        {{ $reservation->requester->name }}
                                                    </span>
                                                    @if ($reservation->status === 'pending')
                                                        <span class="ml-2 px-2 py-1 bg-amber-100 dark:bg-amber-800 text-amber-700 dark:text-amber-200 text-xs rounded-full">
                                                            Na čekanju
                                                        </span>
                                                    @elseif ($reservation->status === 'approved')
                                                        <span class="ml-2 px-2 py-1 bg-green-100 dark:bg-green-800 text-green-700 dark:text-green-200 text-xs rounded-full">
                                                            Odobreno
                                                        </span>
                                                    @elseif ($reservation->status === 'rejected')
                                                        <span class="ml-2 px-2 py-1 bg-red-100 dark:bg-red-800 text-red-700 dark:text-red-200 text-xs rounded-full">
                                                            Odbijeno
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="text-sm text-slate-600 dark:text-slate-300 line-clamp-2">
                                                    {{ $reservation->message }}
                                                </p>
                                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    {{ $reservation->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                            @if ($selectedReservation && $selectedReservation->id === $reservation->id)
                                                <i class="fas fa-check-circle text-green-500 ml-2"></i>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Right: Selected Request Details -->
                        <div>
                            @if ($selectedReservation)
                                <h4 class="text-lg font-semibold text-slate-800 dark:text-slate-200 mb-3">
                                    Detalji zahteva
                                </h4>
                                <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-4">
                                    <!-- Requester Info -->
                                    <div class="mb-4">
                                        <h5 class="font-medium text-slate-700 dark:text-slate-200 mb-2">
                                            <i class="fas fa-user mr-2"></i>
                                            Podnosilac zahteva
                                        </h5>
                                        <div class="space-y-1">
                                            <p class="text-sm text-slate-600 dark:text-slate-300">
                                                <span class="font-medium">Ime:</span> {{ $selectedReservation->requester->name }}
                                            </p>
                                            <p class="text-sm text-slate-600 dark:text-slate-300">
                                                <span class="font-medium">Email:</span> {{ $selectedReservation->requester->email }}
                                            </p>
                                            <p class="text-sm text-slate-600 dark:text-slate-300">
                                                <span class="font-medium">Član od:</span> {{ $selectedReservation->requester->created_at->format('d.m.Y') }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Message -->
                                    <div class="mb-4">
                                        <h5 class="font-medium text-slate-700 dark:text-slate-200 mb-2">
                                            <i class="fas fa-envelope mr-2"></i>
                                            Poruka
                                        </h5>
                                        <div class="bg-white dark:bg-slate-600 rounded p-3">
                                            <p class="text-sm text-slate-700 dark:text-slate-200">
                                                {{ $selectedReservation->message }}
                                            </p>
                                        </div>
                                    </div>

                                    @if ($selectedReservation->status === 'pending')
                                        <!-- Response Field -->
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                                Vaš odgovor (opciono)
                                            </label>
                                            <textarea wire:model="response" rows="3"
                                                placeholder="Možete dodati poruku uz vašu odluku..."
                                                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-700 focus:outline-none focus:border-green-500 dark:focus:border-green-400 transition-colors"></textarea>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="flex items-center space-x-3">
                                            <button x-data @click="$dispatch('open-approve-modal')"
                                                class="flex-1 px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors">
                                                <i class="fas fa-check mr-2"></i>
                                                Odobri poklon
                                            </button>
                                            <button x-data @click="$dispatch('open-reject-modal')"
                                                class="flex-1 px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
                                                <i class="fas fa-times mr-2"></i>
                                                Odbij zahtev
                                            </button>
                                        </div>
                                    @else
                                        <!-- Already Responded -->
                                        <div class="bg-slate-100 dark:bg-slate-600 rounded-lg p-4">
                                            <p class="text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                                Status:
                                                @if ($selectedReservation->status === 'approved')
                                                    <span class="text-green-600 dark:text-green-400">Odobreno</span>
                                                @else
                                                    <span class="text-red-600 dark:text-red-400">Odbijeno</span>
                                                @endif
                                            </p>
                                            @if ($selectedReservation->response)
                                                <p class="text-sm text-slate-600 dark:text-slate-300">
                                                    <span class="font-medium">Vaš odgovor:</span> {{ $selectedReservation->response }}
                                                </p>
                                            @endif
                                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                                                Odgovoreno: {{ $selectedReservation->responded_at->format('d.m.Y H:i') }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-8 text-center">
                                    <i class="fas fa-hand-pointer text-slate-400 text-4xl mb-3"></i>
                                    <p class="text-slate-600 dark:text-slate-300">
                                        Izaberite zahtev sa leve strane da vidite detalje
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-8 text-center">
                        <i class="fas fa-inbox text-slate-400 text-5xl mb-4"></i>
                        <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-200 mb-2">
                            Nema zahteva
                        </h3>
                        <p class="text-slate-600 dark:text-slate-400">
                            Još uvek nema zahteva za ovaj poklon.
                        </p>
                    </div>
                @endif

                <!-- Modal Footer -->
                <div class="mt-6 flex justify-end">
                    <button wire:click="closeModal"
                        class="px-6 py-2 bg-slate-300 dark:bg-slate-600 text-slate-700 dark:text-slate-200 font-medium rounded-lg hover:bg-slate-400 dark:hover:bg-slate-500 transition-colors">
                        Zatvori
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Approve Confirmation Modal -->
    <div x-data="{ showApproveModal: false }"
         @open-approve-modal.window="showApproveModal = true"
         x-show="showApproveModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;"
         class="fixed inset-0 z-[60] overflow-y-auto">

        <!-- Background overlay -->
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="showApproveModal = false"></div>

        <!-- Modal content -->
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative inline-block align-bottom bg-white dark:bg-slate-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                <!-- Modal header with icon -->
                <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-white bg-opacity-20">
                                <i class="fas fa-gift text-white text-xl"></i>
                            </div>
                            <h3 class="ml-3 text-xl font-bold text-white">Odobri poklon</h3>
                        </div>
                        <button @click="showApproveModal = false" class="text-white hover:text-slate-200">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal body -->
                <div class="px-6 py-5">
                    <p class="text-lg text-slate-900 dark:text-slate-100 mb-4">
                        Da li ste sigurni da želite da date poklon ovom korisniku?
                    </p>
                    @if ($selectedReservation)
                        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg p-3">
                            <p class="text-sm text-green-800 dark:text-green-200">
                                <i class="fas fa-info-circle mr-2"></i>
                                Poklon će biti označen kao poklonjen korisniku <strong>{{ $selectedReservation->requester->name }}</strong>.
                                Svi ostali zahtevi će biti automatski odbijeni.
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Modal footer with actions -->
                <div class="bg-slate-50 dark:bg-slate-700/50 px-6 py-4">
                    <div class="flex space-x-3">
                        <button type="button" @click="showApproveModal = false"
                            class="flex-1 px-4 py-2.5 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-medium rounded-lg hover:bg-slate-100 dark:hover:bg-slate-600 transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            Otkaži
                        </button>
                        <button type="button" wire:click="approveReservation" @click="showApproveModal = false"
                            class="flex-1 px-4 py-2.5 bg-gradient-to-r from-green-600 to-green-700 text-white font-medium rounded-lg hover:from-green-700 hover:to-green-800 transition-all transform hover:scale-105">
                            <i class="fas fa-check mr-2"></i>
                            Da, odobri poklon
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Confirmation Modal -->
    <div x-data="{ showRejectModal: false }"
         @open-reject-modal.window="showRejectModal = true"
         x-show="showRejectModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;"
         class="fixed inset-0 z-[60] overflow-y-auto">

        <!-- Background overlay -->
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="showRejectModal = false"></div>

        <!-- Modal content -->
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative inline-block align-bottom bg-white dark:bg-slate-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                <!-- Modal header with icon -->
                <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-white bg-opacity-20">
                                <i class="fas fa-times-circle text-white text-xl"></i>
                            </div>
                            <h3 class="ml-3 text-xl font-bold text-white">Odbij zahtev</h3>
                        </div>
                        <button @click="showRejectModal = false" class="text-white hover:text-slate-200">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal body -->
                <div class="px-6 py-5">
                    <p class="text-lg text-slate-900 dark:text-slate-100 mb-4">
                        Da li ste sigurni da želite da odbijete ovaj zahtev?
                    </p>
                    @if ($selectedReservation)
                        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-lg p-3">
                            <p class="text-sm text-red-800 dark:text-red-200">
                                <i class="fas fa-info-circle mr-2"></i>
                                Zahtev korisnika <strong>{{ $selectedReservation->requester->name }}</strong> će biti odbijen.
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Modal footer with actions -->
                <div class="bg-slate-50 dark:bg-slate-700/50 px-6 py-4">
                    <div class="flex space-x-3">
                        <button type="button" @click="showRejectModal = false"
                            class="flex-1 px-4 py-2.5 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-medium rounded-lg hover:bg-slate-100 dark:hover:bg-slate-600 transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            Otkaži
                        </button>
                        <button type="button" wire:click="rejectReservation" @click="showRejectModal = false"
                            class="flex-1 px-4 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white font-medium rounded-lg hover:from-red-700 hover:to-red-800 transition-all transform hover:scale-105">
                            <i class="fas fa-times-circle mr-2"></i>
                            Da, odbij zahtev
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>