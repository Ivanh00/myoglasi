<div class="w-full">
    @auth
        @if (auth()->id() !== $listing->user_id)
            <button wire:click="toggleFavorite"
                class="w-full flex items-center justify-center px-4 py-3 border rounded-lg transition-colors
                           {{ $isFavorited ? 'bg-red-50 text-red-600 border-red-300 hover:bg-red-100' : 'border-slate-300 text-slate-700 hover:bg-slate-50' }}">
                @if ($isFavorited)
                    <svg class="w-5 h-5 mr-1 fill-current" viewBox="0 0 24 24">
                        <path
                            d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                    </svg>
                    Ukloni iz omiljenih
                @else
                    <svg class="w-5 h-5 mr-1 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    Dodaj u omiljene
                @endif
            </button>
        @endif
    @else
        <a href="{{ route('login') }}"
            class="w-full flex items-center justify-center px-4 py-3 border border-slate-300 text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
            <svg class="w-5 h-5 mr-1 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
            Dodaj u omiljene
        </a>
    @endauth

    {{-- Flash poruke inside the main container --}}
    @if (session()->has('success'))
        <div class="mt-2 p-2 bg-green-100 border border-green-400 text-green-700 rounded text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mt-2 p-2 bg-red-100 border border-red-400 text-red-700 dark:text-red-200 rounded text-sm">
            {{ session('error') }}
        </div>
    @endif
</div>
