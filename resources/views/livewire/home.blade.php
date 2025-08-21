<!-- resources/views/livewire/home.blade.php -->
<div>
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-700 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold mb-4">Dobrodošli na MarketPlace</h1>
            <p class="text-xl mb-8">Kupuj i prodavaj bez ograničenja</p>

            <!-- Search Bar -->
            <div class="max-w-2xl mx-auto relative">
                <input wire:model="searchTerm" type="text" placeholder="Pretražite oglase..."
                    class="w-full px-6 py-4 text-gray-800 rounded-lg focus:outline-none focus:ring-2 focus:ring-white">
                <button class="absolute right-2 top-2 bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                    🔍
                </button>
            </div>

            @guest
                <div class="mt-6 space-x-4">
                    <a href="{{ route('register') }}"
                        class="bg-white text-blue-600 px-6 py-3 rounded-lg hover:bg-gray-100 transition">
                        Registruj se besplatno
                    </a>
                    <a href="{{ route('login') }}"
                        class="border border-white px-6 py-3 rounded-lg hover:bg-white hover:text-blue-600 transition">
                        Prijavi se
                    </a>
                </div>
            @endguest
        </div>
    </div>

    <!-- Categories -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold mb-6">Kategorije</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach ($categories as $category)
                <a href="{{ route('category.show', $category) }}"
                    class="bg-white p-4 rounded-lg shadow hover:shadow-md transition text-center">
                    <div class="text-2xl mb-2">{{ $category->icon ?? '📦' }}</div>
                    <h3 class="font-medium">{{ $category->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $category->listings_count ?? 0 }} oglasa</p>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Featured Listings -->
    @if ($featuredListings->count() > 0)
        <div class="bg-gray-50 py-8">
            <div class="max-w-7xl mx-auto px-4">
                <h2 class="text-2xl font-bold mb-6">Izdvojeni oglasi</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($featuredListings as $listing)
                        @include('components.listing-card', compact('listing'))
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Latest Listings -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Najnoviji oglasi</h2>
            <a href="{{ route('search') }}" class="text-blue-600 hover:text-blue-800">
                Vidi sve →
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($latestListings as $listing)
                @include('components.listing-card', compact('listing'))
            @endforeach
        </div>
    </div>

    <!-- Call to Action for Guests -->
    @guest
        <div class="bg-blue-600 text-white py-12">
            <div class="max-w-4xl mx-auto px-4 text-center">
                <h2 class="text-3xl font-bold mb-4">Želiš da prodaš nešto?</h2>
                <p class="text-xl mb-6">Registruj se i počni da objavljuješ oglase već danas!</p>
                <div class="space-x-4">
                    <a href="{{ route('register') }}"
                        class="bg-white text-blue-600 px-8 py-3 rounded-lg hover:bg-gray-100 transition inline-block">
                        Registruj se besplatno
                    </a>
                </div>
                <p class="text-sm mt-4 opacity-90">
                    ✅ Objava oglasa - samo 10 RSD<br>
                    ✅ Neograničene poruke<br>
                    ✅ Upravljanje oglasima
                </p>
            </div>
        </div>
    @endguest
</div>
