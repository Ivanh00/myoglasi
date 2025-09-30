<div class="min-h-screen bg-slate-50 py-8">
    <div class="max-w-2xl mx-auto px-4">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-slate-900 mb-2">Prijavite uslugu</h1>
                <p class="text-slate-600 dark:text-slate-400">Pomoći ćete nam da održimo sigurnu i pouzdanu platformu</p>
            </div>

            <!-- Service Info -->
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0 h-16 w-16 mr-4">
                        @if ($service->images->count() > 0)
                            <img src="{{ $service->images->first()->url }}" alt="{{ $service->title }}"
                                class="h-16 w-16 rounded-lg object-cover">
                        @else
                            <div class="h-16 w-16 rounded-lg bg-slate-200 flex items-center justify-center">
                                <i class="fas fa-tools text-slate-400"></i>
                            </div>
                        @endif
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-red-900">{{ $service->title }}</h3>
                        <p class="text-red-700 dark:text-red-200 text-sm">Pružalac usluge: {{ $service->user->name }}</p>
                        <p class="text-red-600 dark:text-red-400 text-sm">
                            @if ($service->price_type === 'negotiable')
                                Po dogovoru
                            @else
                                {{ number_format($service->price, 0, ',', '.') }} RSD
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Report Form -->
            <form wire:submit.prevent="submitReport">
                <!-- Report Reason -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-3">
                        Razlog prijave <span class="text-red-500">*</span>
                    </label>

                    <div class="space-y-2">
                        @foreach ($reportReasons as $key => $reason)
                            <label
                                class="flex items-center cursor-pointer p-3 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors {{ $reportReason === $key ? 'border-red-500 bg-red-50' : '' }}">
                                <input type="radio" wire:model.live="reportReason" value="{{ $key }}"
                                    class="mr-3 text-red-600 focus:ring-red-500">
                                <div class="flex-1">
                                    <div class="font-medium text-slate-900">{{ $reason }}</div>
                                    @switch($key)
                                        @case('inappropriate_content')
                                            <div class="text-sm text-slate-500 dark:text-slate-300">Uvredljiv, diskriminirajući
                                                ili neprikladan
                                                sadržaj</div>
                                        @break

                                        @case('fake_service')
                                            <div class="text-sm text-slate-500 dark:text-slate-300">Lažne informacije ili
                                                nepostojеća usluga</div>
                                        @break

                                        @case('scam')
                                            <div class="text-sm text-slate-500 dark:text-slate-300">Sumnja na prevaru ili
                                                malicioznu aktivnost</div>
                                        @break

                                        @case('duplicate')
                                            <div class="text-sm text-slate-500 dark:text-slate-300">Ista usluga je već postavljena
                                            </div>
                                        @break
                                    @endswitch
                                </div>
                            </label>
                        @endforeach
                    </div>

                    @error('reportReason')
                        <p class="text-red-600 dark:text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Report Details -->
                <div class="mb-8">
                    <label for="reportDetails"
                        class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                        Detaljno objašnjenje <span class="text-red-500">*</span>
                    </label>
                    <textarea wire:model="reportDetails" id="reportDetails" rows="5"
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                        placeholder="Molimo opišite detaljno problem sa ovom uslugom..."></textarea>
                    <div class="flex justify-between items-center mt-1">
                        @error('reportDetails')
                            <p class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</p>
                        @else
                            <p class="text-slate-500 dark:text-slate-300 text-sm">Vaš izveštaj će biti poslat
                                administratorima</p>
                        @enderror
                        <p class="text-slate-400 text-sm">{{ strlen($reportDetails ?? '') }}/1000</p>
                    </div>
                </div>

                <!-- Warning Notice -->
                <div class="mb-8 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-amber-600 dark:text-amber-400 mr-3 mt-1"></i>
                        <div>
                            <h4 class="text-amber-900 font-semibold mb-1">Napomena</h4>
                            <ul class="text-amber-800 text-sm space-y-1">
                                <li>• Lažne prijave mogu dovesti do ograničavanja vašeg naloga</li>
                                <li>• Administratori će pregledati vašu prijavu u najkraćem roku</li>
                                <li>• Za hitne slučajeve kontaktirajte nas direktno</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between items-center">
                    <a href="{{ route('services.show', $service) }}"
                        class="px-6 py-3 border border-slate-300 text-slate-700 font-semibold rounded-lg hover:bg-slate-50 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Nazad na uslugu
                    </a>

                    <button type="submit"
                        class="px-6 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-flag mr-2"></i>
                        Pošaljite prijavu
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Success Modal -->
    @if ($showSuccessModal)
        <div class="fixed inset-0 bg-slate-500 bg-opacity-75 z-50 flex items-center justify-center">
            <div class="bg-white rounded-lg shadow-xl p-6 max-w-md mx-4">
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check text-green-600 text-2xl"></i>
                    </div>

                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Prijava je poslata</h3>
                    <p class="text-slate-600 dark:text-slate-400 mb-6">
                        Hvala vam na ukazanoj pažnji. Administratori će pregledati vašu prijavu u najkraćem roku.
                    </p>

                    <button wire:click="closeSuccessModal"
                        class="w-full px-4 py-2 bg-sky-600 text-white font-semibold rounded-lg hover:bg-sky-700 transition-colors">
                        Nazad na uslugu
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
