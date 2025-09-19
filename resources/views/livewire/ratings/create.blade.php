<div class="min-h-screen bg-slate-50 py-8">
    <div class="max-w-2xl mx-auto px-4">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-slate-900 mb-2">Ocenite korisnika</h1>
                <p class="text-slate-600">Podelite vaše iskustvo sa drugim korisnicima</p>
            </div>

            <!-- User and Listing Info -->
            <div class="bg-sky-50 border border-sky-200 rounded-lg p-4 mb-6">
                <div class="flex items-center mb-3">
                    <div class="h-12 w-12 bg-sky-100 rounded-full flex items-center justify-center mr-4">
                        @if ($user->avatar)
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                                class="h-12 w-12 rounded-full object-cover">
                        @else
                            <span
                                class="text-sky-600 dark:text-sky-400 font-semibold text-lg">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        @endif
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-sky-900">{{ $user->name }}</h3>
                        <p class="text-sky-700 text-sm">Član od {{ $user->created_at->format('m/Y') }}</p>
                    </div>
                </div>

                <div class="border-t border-sky-200 pt-3">
                    <p class="text-sky-800 text-sm">
                        <i class="fas fa-tag mr-1"></i>
                        <strong>Oglas:</strong> {{ $listing->title }}
                    </p>
                </div>
            </div>

            <!-- Rating Form -->
            <form wire:submit.prevent="submitRating">
                <!-- Rating Selection -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Kako ocenjujete vašu saradnju?</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Positive -->
                        <div class="relative">
                            <input type="radio" wire:model.live="rating" value="positive" id="positive"
                                class="sr-only">
                            <label for="positive"
                                class="block p-6 border-2 rounded-lg cursor-pointer text-center transition-all transform hover:scale-105
                                {{ $rating === 'positive' ? 'border-green-500 bg-green-100 shadow-lg scale-105' : 'bg-white border-slate-300 hover:border-green-300 hover:bg-green-50' }}">
                                <div class="text-6xl mb-3 {{ $rating === 'positive' ? 'animate-bounce' : '' }}">😊</div>
                                <div
                                    class="text-lg font-semibold {{ $rating === 'positive' ? 'text-green-800' : 'text-slate-700' }}">
                                    Pozitivno
                                </div>
                                <div class="text-sm {{ $rating === 'positive' ? 'text-green-700' : 'text-slate-500' }}">
                                    Odličo iskustvo
                                </div>
                                @if ($rating === 'positive')
                                    <div class="absolute top-2 right-2">
                                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                                    </div>
                                @endif
                            </label>
                        </div>

                        <!-- Neutral -->
                        <div class="relative">
                            <input type="radio" wire:model.live="rating" value="neutral" id="neutral"
                                class="sr-only">
                            <label for="neutral"
                                class="block p-6 border-2 rounded-lg cursor-pointer text-center transition-all transform hover:scale-105
                                {{ $rating === 'neutral' ? 'border-amber-500 bg-amber-100 shadow-lg scale-105' : 'bg-white border-slate-300 hover:border-amber-300 hover:bg-amber-50' }}">
                                <div class="text-6xl mb-3 {{ $rating === 'neutral' ? 'animate-bounce' : '' }}">😐</div>
                                <div
                                    class="text-lg font-semibold {{ $rating === 'neutral' ? 'text-amber-800' : 'text-slate-700' }}">
                                    Neutralno
                                </div>
                                <div class="text-sm {{ $rating === 'neutral' ? 'text-amber-700' : 'text-slate-500' }}">
                                    Prosečno iskustvo
                                </div>
                                @if ($rating === 'neutral')
                                    <div class="absolute top-2 right-2">
                                        <i class="fas fa-check-circle text-amber-600 text-xl"></i>
                                    </div>
                                @endif
                            </label>
                        </div>

                        <!-- Negative -->
                        <div class="relative">
                            <input type="radio" wire:model.live="rating" value="negative" id="negative"
                                class="sr-only">
                            <label for="negative"
                                class="block p-6 border-2 rounded-lg cursor-pointer text-center transition-all transform hover:scale-105
                                {{ $rating === 'negative' ? 'border-red-500 bg-red-100 shadow-lg scale-105' : 'bg-white border-slate-300 hover:border-red-300 hover:bg-red-50' }}">
                                <div class="text-6xl mb-3 {{ $rating === 'negative' ? 'animate-bounce' : '' }}">😞
                                </div>
                                <div
                                    class="text-lg font-semibold {{ $rating === 'negative' ? 'text-red-800' : 'text-slate-700' }}">
                                    Negativno
                                </div>
                                <div class="text-sm {{ $rating === 'negative' ? 'text-red-700' : 'text-slate-500' }}">
                                    Loše iskustvo
                                </div>
                                @if ($rating === 'negative')
                                    <div class="absolute top-2 right-2">
                                        <i class="fas fa-check-circle text-red-600 text-xl"></i>
                                    </div>
                                @endif
                            </label>
                        </div>
                    </div>

                    @error('rating')
                        <p class="text-red-600 dark:text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Comment -->
                <div class="mb-8">
                    <label for="comment" class="block text-sm font-medium text-slate-700 mb-2">
                        Komentar (opciono)
                    </label>
                    <textarea wire:model="comment" id="comment" rows="4"
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500"
                        placeholder="Opišite vaše iskustvo sa ovim korisnikom..."></textarea>
                    <div class="flex justify-between items-center mt-1">
                        @error('comment')
                            <p class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</p>
                        @else
                            <p class="text-slate-500 text-sm">Vaš komentar će biti javan</p>
                        @enderror
                        <p class="text-slate-400 text-sm">{{ strlen($comment ?? '') }}/500</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between items-center">
                    <a href="{{ route('listing.chat', ['slug' => $listing->slug, 'user' => $user->id]) }}"
                        class="px-6 py-3 border border-slate-300 text-slate-700 font-semibold rounded-lg hover:bg-slate-50 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Nazad na poruke
                    </a>

                    <button type="submit"
                        class="px-6 py-3 bg-sky-600 text-white font-semibold rounded-lg hover:bg-sky-700 transition-colors">
                        <i class="fas fa-star mr-2"></i>
                        Pošaljite ocenu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
