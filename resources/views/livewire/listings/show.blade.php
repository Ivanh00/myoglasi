<div class="p-4 border rounded shadow">
    <h2 class="text-2xl font-bold mb-2">{{ $listing->title }}</h2>
    <p class="mb-2"><strong>Kategorija:</strong> {{ $listing->category->name }}</p>
    <p class="mb-2"><strong>Cena:</strong> {{ number_format($listing->price, 2) }} RSD</p>
    <p class="mb-2"><strong>Korisnik:</strong> {{ $listing->user->name }}</p>
    <p class="mb-4">{{ $listing->description }}</p>

    <div class="grid grid-cols-3 gap-2">
        @foreach ($listing->images as $image)
            <img src="{{ asset('storage/' . $image->image_path) }}" alt=""
                class="w-full h-32 object-cover rounded">
        @endforeach
    </div>
</div>
