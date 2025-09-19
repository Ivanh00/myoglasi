<div class="max-w-6xl mx-auto px-4 py-6">
    <!-- Breadcrumbs -->
    <nav class="text-sm mb-4">
        <a href="{{ route('home') }}" class="text-sky-600 dark:text-sky-400">Početna</a> >
        <a href="{{ route('category.show', $listing->category) }}" class="text-sky-600 dark:text-sky-400">
            {{ $listing->category->name }}
        </a> >
        <span class="text-slate-500">{{ $listing->title }}</span>
    </nav>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Images -->
        <div class="md:col-span-2">
            @if ($listing->images->count() > 0)
                <div x-data="{ activeImage: 0 }" class="space-y-4">
                    <!-- Main Image -->
                    <img :src="images[activeImage]" class="w-full h-96 object-cover rounded-lg">

                    <!-- Thumbnails -->
                    @if ($listing->images->count() > 1)
                        <div class="grid grid-cols-5 gap-2">
                            @foreach ($listing->images as $index => $image)
                                <img src="{{ Storage::url($image->image_path) }}"
                                    @click="activeImage = {{ $index }}"
                                    :class="{ 'ring-2 ring-sky-500': activeImage === {{ $index }} }"
                                    class="w-full h-16 object-cover rounded cursor-pointer">
                            @endforeach
                        </div>
                    @endif
                </div>
            @else
                <div class="bg-slate-200 h-96 rounded-lg flex items-center justify-center">
                    <span class="text-slate-500">Nema slika</span>
                </div>
            @endif
        </div>

        <!-- Info -->
        <div class="space-y-6">
            <div>
                <h1 class="text-2xl font-bold mb-2">{{ $listing->title }}</h1>
                <p class="text-3xl font-bold text-green-600 mb-4">
                    {{ number_format($listing->price, 0) }} RSD
                </p>
            </div>

            <!-- Seller Info -->
            <div class="bg-slate-50 p-4 rounded-lg">
                <h3 class="font-semibold mb-2">Prodavac</h3>
                <p class="text-slate-700">{{ $listing->user->name }}</p>

                @if ($showPhoneNumber)
                    <p class="text-slate-700 mt-1">
                        📞 {{ $listing->user->phone }}
                    </p>
                @endif

                <p class="text-sm text-slate-500 mt-2">
                    Član od {{ $listing->user->created_at->format('M Y') }}
                </p>
            </div>

            <!-- Contact Buttons -->
            <div class="space-y-3">
                @if ($canContact)
                    <button wire:click="contactSeller"
                        class="w-full bg-sky-600 text-white py-3 px-4 rounded-lg hover:bg-sky-700 transition">
                        💬 Pošalji poruku
                    </button>
                @elseif(!auth()->check())
                    <a href="{{ route('login') }}"
                        class="block w-full bg-sky-600 text-white py-3 px-4 rounded-lg hover:bg-sky-700 transition text-center">
                        Prijaviť se za kontakt
                    </a>
                @elseif(auth()->id() === $listing->user_id)
                    <a href="{{ route('listings.edit', $listing) }}"
                        class="block w-full bg-slate-600 text-white py-3 px-4 rounded-lg hover:bg-slate-700 transition text-center">
                        ✏️ Edituj oglas
                    </a>
                @endif

                @if (!$showPhoneNumber && !auth()->check())
                    <div class="text-center text-sm text-slate-500 bg-amber-50 p-3 rounded">
                        <p>📞 Registruj se da vidiš broj telefona</p>
                    </div>
                @endif
            </div>

            <!-- Listing Details -->
            <div class="bg-slate-50 p-4 rounded-lg space-y-2">
                <div class="flex justify-between">
                    <span class="text-slate-600">Kategorija:</span>
                    <span class="font-medium">{{ $listing->category->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Objavljeno:</span>
                    <span class="font-medium">{{ $listing->created_at->diffForHumans() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Ističe:</span>
                    <span class="font-medium">{{ $listing->expires_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Description -->
    <div class="mt-8">
        <h2 class="text-xl font-bold mb-4">Opis</h2>
        <div class="prose max-w-none">
            {!! nl2br(e($listing->description)) !!}
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('listingDetail', () => ({
                    images: @json($listing->images->pluck('image_path')->map(fn($path) => Storage::url($path))),
                    activeImage: 0
                }));
            });
        </script>
    @endpush
</div>
