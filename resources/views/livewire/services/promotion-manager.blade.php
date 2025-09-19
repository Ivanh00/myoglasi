<div>
    <!-- Promotion Modal -->
    @if ($showModal && $service)
        <div class="fixed inset-0 bg-slate-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-4 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-slate-900">
                            <i class="fas fa-bullhorn text-amber-500 mr-2"></i>
                            Promocija usluge: {{ $service->title }}
                        </h3>
                        <button wire:click="closeModal" class="text-slate-400 hover:text-slate-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <!-- Current Balance -->
                    <div class="bg-sky-50 border border-sky-200 p-4 rounded-lg mb-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-wallet text-sky-600 dark:text-sky-400 mr-2"></i>
                                <span class="text-sky-900 font-medium">Vaš trenutni balans:</span>
                            </div>
                            <span class="text-sky-900 font-bold text-lg">
                                {{ number_format(auth()->user()->balance, 0, ',', '.') }} RSD
                            </span>
                        </div>
                    </div>

                    <!-- Promotion Options -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        @foreach ($this->getPromotionTypes() as $type => $name)
                            @php
                                $price = $this->getPromotionPrice($type);
                                $duration = $this->getPromotionDuration($type);
                                $isSelected = in_array($type, $selectedPromotions);
                                $hasActive = $this->hasActivePromotion($type);

                                $colors = [
                                    'featured_category' => 'blue',
                                    'featured_homepage' => 'red',
                                    'highlighted' => 'yellow',
                                    'auto_refresh' => 'green',
                                    'double_images' => 'purple',
                                    'extended_duration' => 'orange',
                                ];
                                $color = $colors[$type] ?? 'gray';
                            @endphp

                            <div class="border-2 rounded-lg p-4 cursor-pointer transition-all {{ $isSelected ? 'border-' . $color . '-500 bg-' . $color . '-50' : 'border-slate-200 hover:border-' . $color . '-300' }}"
                                wire:click="togglePromotion('{{ $type }}')">

                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2">
                                            @if ($type === 'featured_category')
                                                <i class="fas fa-arrow-up text-sky-600 dark:text-sky-400 mr-2"></i>
                                            @elseif($type === 'featured_homepage')
                                                <i class="fas fa-star text-red-600 dark:text-red-400 mr-2"></i>
                                            @elseif($type === 'highlighted')
                                                <i
                                                    class="fas fa-highlighter text-amber-600 dark:text-amber-400 mr-2"></i>
                                            @elseif($type === 'auto_refresh')
                                                <i class="fas fa-sync text-green-600 dark:text-green-400 mr-2"></i>
                                            @elseif($type === 'double_images')
                                                <i class="fas fa-images text-purple-600 dark:text-purple-400 mr-2"></i>
                                            @else
                                                <i class="fas fa-clock text-orange-600 dark:text-orange-400 mr-2"></i>
                                            @endif

                                            <h4 class="font-semibold text-slate-900">{{ $name }}</h4>

                                            @if ($hasActive)
                                                <span
                                                    class="ml-2 bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">AKTIVNA</span>
                                            @endif
                                        </div>

                                        <p class="text-sm text-slate-600 mb-3">
                                            @if ($type === 'featured_category')
                                                Usluga se prikazuje na vrhu liste u svojoj kategoriji
                                            @elseif($type === 'featured_homepage')
                                                Usluga se prikazuje na vrhu glavne strane sajta
                                            @elseif($type === 'highlighted')
                                                Usluga ima drugačiju boju pozadine i istaknuta je
                                            @elseif($type === 'auto_refresh')
                                                Usluga se automatski "podiže" na vrh svaki dan
                                            @elseif($type === 'double_images')
                                                Udvostručuje dozvoljen broj slika za uslugu
                                            @else
                                                Produžava vreme isteka usluge za {{ $duration }} dana
                                            @endif
                                        </p>

                                        <div class="flex items-center justify-between">
                                            <span class="text-lg font-bold text-{{ $color }}-600">
                                                {{ number_format($price, 0, ',', '.') }} RSD
                                            </span>
                                            <span class="text-sm text-slate-500">{{ $duration }} dana</span>
                                        </div>
                                    </div>

                                    <div class="ml-4">
                                        <div
                                            class="w-6 h-6 border-2 rounded-full flex items-center justify-center {{ $isSelected ? 'border-' . $color . '-500 bg-' . $color . '-500' : 'border-slate-300' }}">
                                            @if ($isSelected)
                                                <i class="fas fa-check text-white text-sm"></i>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Total Cost -->
                    @if (!empty($selectedPromotions))
                        <div class="bg-green-50 border border-green-200 p-4 rounded-lg mb-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-green-900 font-medium">Ukupna cena:</span>
                                    <div class="text-xs text-green-700 mt-1">
                                        Izabrano {{ count($selectedPromotions) }} promocija
                                    </div>
                                </div>
                                <span class="text-green-900 font-bold text-2xl">
                                    {{ number_format($totalCost, 0, ',', '.') }} RSD
                                </span>
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-4">
                        <button wire:click="closeModal"
                            class="px-6 py-3 bg-slate-300 text-slate-700 font-semibold rounded-lg hover:bg-slate-400 transition-colors">
                            Otkaži
                        </button>

                        <button wire:click="purchasePromotions"
                            class="px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-lg hover:from-amber-600 hover:to-orange-600 transition-all {{ empty($selectedPromotions) || auth()->user()->balance < $totalCost ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ empty($selectedPromotions) || auth()->user()->balance < $totalCost ? 'disabled' : '' }}>
                            <i class="fas fa-shopping-cart mr-2"></i>
                            Kupi promocije
                            @if (!empty($selectedPromotions))
                                ({{ number_format($totalCost, 0, ',', '.') }} RSD)
                            @endif
                        </button>
                    </div>

                    <!-- Insufficient Balance Warning -->
                    @if (!empty($selectedPromotions) && auth()->user()->balance < $totalCost)
                        <div class="mt-4 bg-red-50 border border-red-200 p-4 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                                <span class="text-red-800 text-sm">
                                    Nemate dovoljno kredita. Potrebno je još
                                    {{ number_format($totalCost - auth()->user()->balance, 0, ',', '.') }} RSD.
                                    <a href="{{ route('balance.payment-options') }}"
                                        class="underline font-medium">Dopunite balans</a>
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Success/Error Messages -->
    @if (session()->has('success'))
        <div class="mt-4 bg-green-50 border border-green-200 p-4 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-600 mr-2"></i>
                <span class="text-green-800 text-sm">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mt-4 bg-red-50 border border-red-200 p-4 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                <span class="text-red-800 text-sm">{{ session('error') }}</span>
            </div>
        </div>
    @endif
</div>
