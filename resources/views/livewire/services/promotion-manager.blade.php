<div>
    <!-- Promotion Modal -->
    @if($showModal && $service)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-4 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-md bg-white dark:bg-gray-800">
                <div class="mt-3">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                            <i class="fas fa-bullhorn text-yellow-500 mr-2"></i>
                            Promocija usluge: {{ $service->title }}
                        </h3>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 dark:text-gray-300 dark:hover:text-gray-100">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <!-- Current Balance -->
                    <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 p-4 rounded-lg mb-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-wallet text-blue-600 dark:text-blue-400 mr-2"></i>
                                <span class="text-blue-900 dark:text-blue-100 font-medium">Vaš trenutni balans:</span>
                            </div>
                            <span class="text-blue-900 dark:text-blue-100 font-bold text-lg">
                                {{ number_format(auth()->user()->balance, 0, ',', '.') }} RSD
                            </span>
                        </div>
                    </div>

                    <!-- Promotion Options -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        @foreach($this->getPromotionTypes() as $type => $name)
                            @php
                                $price = $this->getPromotionPrice($type);
                                $duration = $this->getPromotionDuration($type);
                                $isSelected = in_array($type, $selectedPromotions);
                                $hasActive = $this->hasActivePromotion($type);

                                $colors = [
                                    'featured' => 'blue',
                                    'highlighted' => 'yellow',
                                    'urgent' => 'red',
                                    'top' => 'purple'
                                ];
                                $color = $colors[$type] ?? 'gray';
                            @endphp

                            <div class="border-2 rounded-lg p-4 cursor-pointer transition-all {{ $isSelected ? 'border-' . $color . '-500 bg-' . $color . '-50 dark:bg-' . $color . '-900' : 'border-gray-200 dark:border-gray-600 hover:border-' . $color . '-300 dark:hover:border-' . $color . '-500' }}"
                                wire:click="togglePromotion('{{ $type }}')">

                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2">
                                            @if($type === 'featured')
                                                <i class="fas fa-star text-blue-600 mr-2"></i>
                                            @elseif($type === 'highlighted')
                                                <i class="fas fa-highlighter text-yellow-600 mr-2"></i>
                                            @elseif($type === 'urgent')
                                                <i class="fas fa-exclamation-circle text-red-600 mr-2"></i>
                                            @elseif($type === 'top')
                                                <i class="fas fa-arrow-up text-purple-600 mr-2"></i>
                                            @endif

                                            <h4 class="font-semibold text-gray-900 dark:text-gray-100">{{ $name }}</h4>

                                            @if($hasActive)
                                                <span class="ml-2 bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-200 text-xs px-2 py-1 rounded-full">AKTIVNA</span>
                                            @endif
                                        </div>

                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                            @if($type === 'featured')
                                                Usluga se prikazuje na početnoj strani
                                            @elseif($type === 'highlighted')
                                                Usluga ima drugačiju boju pozadine i istaknuta je
                                            @elseif($type === 'urgent')
                                                Usluga dobija "HITNO" oznaku
                                            @elseif($type === 'top')
                                                Usluga se prikazuje na vrhu rezultata pretrage
                                            @endif
                                        </p>

                                        <div class="flex items-center justify-between">
                                            <span class="text-lg font-bold text-{{ $color }}-600">
                                                {{ number_format($price, 0, ',', '.') }} RSD
                                            </span>
                                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $duration }} dana</span>
                                        </div>
                                    </div>

                                    <div class="ml-4">
                                        <div class="w-6 h-6 border-2 rounded-full flex items-center justify-center {{ $isSelected ? 'border-' . $color . '-500 bg-' . $color . '-500' : 'border-gray-300 dark:border-gray-500' }}">
                                            @if($isSelected)
                                                <i class="fas fa-check text-white text-sm"></i>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Total Cost -->
                    @if(!empty($selectedPromotions))
                        <div class="bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 p-4 rounded-lg mb-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-green-900 dark:text-green-100 font-medium">Ukupna cena:</span>
                                    <div class="text-xs text-green-700 dark:text-green-300 mt-1">
                                        Izabrano {{ count($selectedPromotions) }} promocija
                                    </div>
                                </div>
                                <span class="text-green-900 dark:text-green-100 font-bold text-2xl">
                                    {{ number_format($totalCost, 0, ',', '.') }} RSD
                                </span>
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-4">
                        <button wire:click="closeModal"
                            class="px-6 py-3 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 font-semibold rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors">
                            Otkaži
                        </button>

                        <button wire:click="purchasePromotions"
                            class="px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white font-semibold rounded-lg hover:from-yellow-600 hover:to-orange-600 transition-all {{ empty($selectedPromotions) || auth()->user()->balance < $totalCost ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ empty($selectedPromotions) || auth()->user()->balance < $totalCost ? 'disabled' : '' }}>
                            <i class="fas fa-shopping-cart mr-2"></i>
                            Kupi promocije
                            @if(!empty($selectedPromotions))
                                ({{ number_format($totalCost, 0, ',', '.') }} RSD)
                            @endif
                        </button>
                    </div>

                    <!-- Insufficient Balance Warning -->
                    @if(!empty($selectedPromotions) && auth()->user()->balance < $totalCost)
                        <div class="bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 p-4 rounded-lg mt-4">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 mr-2"></i>
                                <div class="flex-1">
                                    <p class="text-red-800 dark:text-red-200 font-medium">Nemate dovoljno kredita!</p>
                                    <p class="text-red-600 dark:text-red-300 text-sm mt-1">
                                        Trebate još {{ number_format($totalCost - auth()->user()->balance, 0, ',', '.') }} RSD da biste kupili ove promocije.
                                    </p>
                                    <a href="{{ route('balance.topup') }}" class="text-blue-600 dark:text-blue-400 text-sm underline hover:text-blue-800 dark:hover:text-blue-200 inline-block mt-2">
                                        Dopunite balans →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Success/Error Messages -->
    @if (session()->has('success'))
        <div class="fixed bottom-4 right-4 bg-green-100 dark:bg-green-800 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-100 px-6 py-4 rounded-lg shadow-lg z-50">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed bottom-4 right-4 bg-red-100 dark:bg-red-800 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-100 px-6 py-4 rounded-lg shadow-lg z-50">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif
</div>