## 9. Frontend implementacija

### Guest vs Auth funkcionalnosti
```php
// Middleware za proveru gost pristupa
class EnsureGuestCanView
{
public function handle($request, Closure $next)
{
// Dozvoli pristup listing detail svima
if ($request->routeIs('listings.show') ||
$request->routeIs('home') ||
$request->routeIs('category.show') ||
$request->routeIs('search')) {
return $next($request);
}

// Zahtevaj autentifikaciju za ostale rute
if (!auth()->check()) {
return redirect()->route('login')
->with('error', 'Potrebna je registracija za ovu funkcionalnost.');
}

return $next($request);
}
}
```

### Conditional UI komponente
```blade
<!-- U listing card komponenti -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <!-- Image -->
    <img src="{{ $listing->primaryImage ? Storage::url($listing->primaryImage->image_path) : '/images/no-image.jpg' }}"
        class="w-full h-48 object-cover">

    <!-- Content -->
    <div class="p-4">
        <h3 class="font-semibold text-lg mb-2">{{ $listing->title }}</h3>
        # TALL Stack Marketplace - Vodič za implementaciju

        ## 1. Osnovna struktura i setup

        ### Laravel instalacija i setup
        ```bash
        composer create-project laravel/laravel marketplace
        cd marketplace
        composer require livewire/livewire
        npm install -D tailwindcss @tailwindcss/forms @tailwindcss/typography
        npm install alpinejs
        ```

        ### Osnovne konfiguracije
        - Podesi `.env` fajl sa bazom podataka
        - Konfiguriši mail driver za notifikacije
        - Podesi filesystem za upload slika

        ## 2. Database struktura (migracije)

        ### Korisnici (users)
        ```php
        // users table (proširi default Laravel)
        - id
        - name
        - email
        - email_verified_at
        - password
        - phone
        - phone_visible (boolean) // da li je telefon javno vidljiv
        - avatar
        - balance (decimal) // za novac na računu
        - created_at, updated_at
        ```

        ### Kategorije (categories)
        ```php
        - id
        - name
        - slug
        - parent_id (za pod-kategorije)
        - icon
        - created_at, updated_at
        ```

        ### Oglasi (listings)
        ```php
        - id
        - user_id
        - category_id
        - title
        - description
        - price
        - condition (novo/polovno/ne korišćeno)
        - status (active/sold/expired)
        - location
        - is_featured
        - expires_at
        - created_at, updated_at
        ```

        ### Slike oglasa (listing_images)
        ```php
        - id
        - listing_id
        - image_path
        - is_primary
        - order
        - created_at, updated_at
        ```

        ### Poruke (messages)
        ```php
        - id
        - sender_id
        - receiver_id
        - listing_id
        - message
        - is_read
        - created_at, updated_at
        ```

        ### Transakcije (transactions)
        ```php
        - id
        - user_id
        - type (deposit/withdrawal/listing_fee)
        - amount
        - description
        - status
        - created_at, updated_at
        ```

        ## 3. Laravel Models i relationshi

        ### User Model
        ```php
        class User extends Authenticatable
        {
        public function listings()
        {
        return $this->hasMany(Listing::class);
        }

        public function sentMessages()
        {
        return $this->hasMany(Message::class, 'sender_id');
        }

        public function receivedMessages()
        {
        return $this->hasMany(Message::class, 'receiver_id');
        }

        public function transactions()
        {
        return $this->hasMany(Transaction::class);
        }

        public function deductBalance($amount)
        {
        if ($this->balance >= $amount) {
        $this->decrement('balance', $amount);
        return true;
        }
        return false;
        }
        }
        ```

        ### Listing Model
        ```php
        class Listing extends Model
        {
        public function user()
        {
        return $this->belongsTo(User::class);
        }

        public function category()
        {
        return $this->belongsTo(Category::class);
        }

        public function images()
        {
        return $this->hasMany(ListingImage::class);
        }

        public function messages()
        {
        return $this->hasMany(Message::class);
        }

        public function primaryImage()
        {
        return $this->hasOne(ListingImage::class)->where('is_primary', true);
        }
        }
        ```

        ## 4. Livewire komponente

        ### Glavni layout komponente
        - `NavigationComponent` - header sa kategorijama
        - `SearchComponent` - pretraga oglasa
        - `CategorySidebar` - bočni meni sa kategorijama

        ### Oglas komponente
        - `ListingForm` - kreiranje/editovanje oglasa
        - `ListingCard` - prikaz oglasa u listi
        - `ListingDetail` - detaljni prikaz oglasa
        - `ImageUpload` - upload slika za oglas

        ### Komunikacija komponente
        - `MessagesList` - lista poruka
        - `MessageForm` - slanje poruke
        - `ChatWindow` - chat prozor

        ### Korisnik komponente
        - `UserProfile` - profil korisnika
        - `UserDashboard` - kontrolna tabla
        - `BalanceManager` - upravljanje novcem

        ## 5. Implementacija ključnih funkcionalnosti

        ### Implementacija javnog pristupa oglasima

        ```php
        class ListingDetailComponent extends Component
        {
        public $listing;
        public $canContact = false;
        public $showPhoneNumber = false;

        public function mount(Listing $listing)
        {
        $this->listing = $listing->load(['user', 'category', 'images']);

        // Proverava da li je korisnik ulogovan i može da kontaktira
        $this->canContact = auth()->check() && auth()->id() !== $this->listing->user_id;

        // Proverava da li treba prikazati broj telefona
        $this->showPhoneNumber = $this->listing->user->phone_visible &&
        !empty($this->listing->user->phone);
        }

        public function contactSeller()
        {
        if (!auth()->check()) {
        session()->flash('error', 'Morate se registrovati da biste kontaktirali prodavca.');
        return redirect()->route('login');
        }

        return redirect()->route('listing.chat', ['slug' => $this->listing->slug, 'user' => $this->listing->user_id]);
        }
        }
        ```

        ### Registracija sa phone_visible opcijom

        ```php
        class RegisterComponent extends Component
        {
        public $name, $email, $password, $password_confirmation, $phone;
        public $phone_visible = false;

        protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:8|confirmed',
        'phone' => 'nullable|string|max:20',
        'phone_visible' => 'boolean'
        ];

        public function register()
        {
        $this->validate();

        User::create([
        'name' => $this->name,
        'email' => $this->email,
        'password' => Hash::make($this->password),
        'phone' => $this->phone,
        'phone_visible' => $this->phone_visible,
        'balance' => 0
        ]);

        return redirect()->route('login')
        ->with('success', 'Uspešno ste se registrovali! Možete se ulogovati.');
        }
        }
        ```
        ### Kreiranje oglasa sa naplatom (samo za registrovane)
        ```php
        class CreateListingComponent extends Component
        {
        public $title, $description, $price, $category_id;
        public $images = [];

        protected $rules = [
        'title' => 'required|min:10',
        'description' => 'required|min:20',
        'price' => 'required|numeric|min:1',
        'category_id' => 'required|exists:categories,id'
        ];

        public function mount()
        {
        // Proveri da li je korisnik autentifikovan
        if (!auth()->check()) {
        return redirect()->route('login')
        ->with('error', 'Morate se registrovati da biste postavljali oglase.');
        }
        }

        public function createListing()
        {
        $this->validate();

        $user = auth()->user();
        $fee = 10; // 10 dinara

        if (!$user->deductBalance($fee)) {
        session()->flash('error', 'Nemate dovoljno sredstava na računu. Potrebno je najmanje 10 RSD.');
        return;
        }

        $listing = Listing::create([
        'user_id' => $user->id,
        'title' => $this->title,
        'description' => $this->description,
        'price' => $this->price,
        'category_id' => $this->category_id,
        'status' => 'active',
        'expires_at' => now()->addDays(30)
        ]);

        // Sačuvaj slike
        foreach ($this->images as $index => $image) {
        $path = $image->store('listings', 'public');
        ListingImage::create([
        'listing_id' => $listing->id,
        'image_path' => $path,
        'is_primary' => $index === 0
        ]);
        }

        // Zabeleži transakciju
        Transaction::create([
        'user_id' => $user->id,
        'type' => 'listing_fee',
        'amount' => -$fee,
        'description' => 'Naknada za oglas: ' . $this->title
        ]);

        session()->flash('success', 'Oglas je uspešno kreiran!');
        return redirect()->route('listings.show', $listing);
        }
        }
        ```

        ### Upload slika komponenta
        ```php
        class ImageUploadComponent extends Component
        {
        public $images = [];
        public $maxImages = 10;

        protected $rules = [
        'images.*' => 'image|max:2048'
        ];

        public function updatedImages()
        {
        $this->validate();

        if (count($this->images) > $this->maxImages) {
        session()->flash('error', "Maksimalno {$this->maxImages} slika.");
        $this->images = array_slice($this->images, 0, $this->maxImages);
        }
        }

        public function removeImage($index)
        {
        unset($this->images[$index]);
        $this->images = array_values($this->images);
        }
        }
        ```

        ### Messaging sistem (samo za registrovane)
        ```php
        class MessagesComponent extends Component
        {
        public $conversations = [];
        public $selectedConversation = null;
        public $messages = [];
        public $newMessage = '';

        public function mount()
        {
        if (!auth()->check()) {
        return redirect()->route('login')
        ->with('error', 'Morate se registrovati da biste pristupili porukama.');
        }

        $this->loadConversations();
        }

        public function loadConversations()
        {
        $userId = auth()->id();

        // Uzmi sve razgovore gde je korisnik učestvovao
        $this->conversations = Message::where('sender_id', $userId)
        ->orWhere('receiver_id', $userId)
        ->with(['listing', 'sender', 'receiver'])
        ->get()
        ->groupBy('listing_id')
        ->map(function ($messages) use ($userId) {
        $lastMessage = $messages->sortByDesc('created_at')->first();
        $otherUser = $lastMessage->sender_id === $userId
        ? $lastMessage->receiver
        : $lastMessage->sender;

        return [
        'listing' => $lastMessage->listing,
        'other_user' => $otherUser,
        'last_message' => $lastMessage,
        'unread_count' => $messages->where('receiver_id', $userId)
        ->where('is_read', false)
        ->count()
        ];
        })
        ->sortByDesc('last_message.created_at');
        }

        public function selectConversation($listingId)
        {
        $this->selectedConversation = Listing::find($listingId);
        $this->loadMessages($listingId);
        $this->markAsRead($listingId);
        }

        private function loadMessages($listingId)
        {
        $this->messages = Message::where('listing_id', $listingId)
        ->where(function($query) {
        $query->where('sender_id', auth()->id())
        ->orWhere('receiver_id', auth()->id());
        })
        ->with(['sender'])
        ->orderBy('created_at', 'asc')
        ->get();
        }

        private function markAsRead($listingId)
        {
        Message::where('listing_id', $listingId)
        ->where('receiver_id', auth()->id())
        ->where('is_read', false)
        ->update(['is_read' => true]);
        }

        public function sendMessage()
        {
        if (empty($this->newMessage) || !$this->selectedConversation) {
        return;
        }

        Message::create([
        'sender_id' => auth()->id(),
        'receiver_id' => $this->selectedConversation->user_id,
        'listing_id' => $this->selectedConversation->id,
        'message' => $this->newMessage
        ]);

        $this->newMessage = '';
        $this->loadMessages($this->selectedConversation->id);
        $this->loadConversations(); // Refresh conversations list
        }
        }
        ```

        ### Profile komponenta sa phone_visible opcijom
        ```php
        class ProfileComponent extends Component
        {
        public $name, $email, $phone, $phone_visible;
        public $currentPassword, $newPassword, $newPasswordConfirmation;

        protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'phone' => 'nullable|string|max:20',
        'phone_visible' => 'boolean'
        ];

        public function mount()
        {
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->phone_visible = $user->phone_visible;
        }

        public function updateProfile()
        {
        $this->validate();

        $user = auth()->user();
        $user->update([
        'name' => $this->name,
        'email' => $this->email,
        'phone' => $this->phone,
        'phone_visible' => $this->phone_visible
        ]);

        session()->flash('success', 'Profil je uspešno ažuriran!');
        }

        public function updatePassword()
        {
        $this->validate([
        'currentPassword' => 'required',
        'newPassword' => 'required|min:8|confirmed'
        ]);

        $user = auth()->user();

        if (!Hash::check($this->currentPassword, $user->password)) {
        $this->addError('currentPassword', 'Trenutna lozinka nije tačna.');
        return;
        }

        $user->update([
        'password' => Hash::make($this->newPassword)
        ]);

        $this->currentPassword = '';
        $this->newPassword = '';
        $this->newPasswordConfirmation = '';

        session()->flash('success', 'Lozinka je uspešno promenjena!');
        }
        }
        ```

        ## 6. Upravljanje novcem

        ### Balance Management
        ```php
        class BalanceComponent extends Component
        {
        public $amount = 0;
        public $user;

        public function mount()
        {
        $this->user = auth()->user();
        }

        public function addFunds()
        {
        $this->validate([
        'amount' => 'required|numeric|min:100|max:10000'
        ]);

        // Ovde bi bio poziv ka payment gateway-u
        // Za demo verziju samo dodaj sredstva

        $this->user->increment('balance', $this->amount);

        Transaction::create([
        'user_id' => $this->user->id,
        'type' => 'deposit',
        'amount' => $this->amount,
        'description' => 'Dodavanje sredstava'
        ]);

        session()->flash('success', 'Uspešno dodato ' . $this->amount . ' RSD');
        $this->amount = 0;
        }
        }
        ```

        ## 7. Route struktura

        ```php
        // routes/web.php

        // Javne rute (dostupne svima)
        Route::get('/', HomeComponent::class)->name('home');
        Route::get('/listings/{listing}', ListingDetailComponent::class)->name('listings.show');
        Route::get('/category/{category}', CategoryListingsComponent::class)->name('category.show');
        Route::get('/search', SearchComponent::class)->name('search');

        // Autentificirane rute (samo za registrovane korisnike)
        Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', UserDashboard::class)->name('dashboard');
        Route::get('/listings/create', CreateListingComponent::class)->name('listings.create');
        Route::get('/listings/{listing}/edit', EditListingComponent::class)->name('listings.edit');
        Route::get('/messages', MessagesComponent::class)->name('messages');
        Route::get('/messages/{listing}', MessageDetailComponent::class)->name('messages.listing');
        Route::get('/balance', BalanceComponent::class)->name('balance');
        Route::get('/profile', ProfileComponent::class)->name('profile');
        });

        // Auth rute
        Route::get('/login', LoginComponent::class)->name('login');
        Route::get('/register', RegisterComponent::class)->name('register');
        ```

        ## 8. Bezbednost i optimizacija

        ### Middleware za validaciju
        - Proveri da korisnik ima dovoljno sredstava
        - Rate limiting za kreiranje oglasa
        - Image validation i compression

        ### Performance
        - Eager loading relationshipa
        - Image optimization (webp format)
        - Caching za kategorije i popularne oglase
        - Queue jobs za email notifikacije

        ### Security
        - CSRF protection
        - XSS prevention
        - File upload security
        - User authorization za oglase

        ### Listing Detail Blade view primer
        ```blade
        <div class="max-w-6xl mx-auto px-4 py-6">
            <!-- Breadcrumbs -->
            <nav class="text-sm mb-4">
                <a href="{{ route('home') }}" class="text-sky-600 dark:text-sky-400">Početna</a> >
                <a href="{{ route('category.show', $listing->category) }}" class="text-sky-600 dark:text-sky-400">
                    {{ $listing->category->name }}
                </a> >
                <span class="text-slate-500 dark:text-slate-300">{{ $listing->title }}</span>
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
                            <span class="text-slate-500 dark:text-slate-300">Nema slika</span>
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
                        <p class="text-slate-700 dark:text-slate-200">{{ $listing->user->name }}</p>

                        @if ($showPhoneNumber)
                            <p class="text-slate-700 dark:text-slate-200 mt-1">
                                📞 {{ $listing->user->phone }}
                            </p>
                        @endif

                        <p class="text-sm text-slate-500 dark:text-slate-300 mt-2">
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
                            <span class="text-slate-600 dark:text-slate-400">Kategorija:</span>
                            <span class="font-medium">{{ $listing->category->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-600 dark:text-slate-400">Objavljeno:</span>
                            <span class="font-medium">{{ $listing->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-600 dark:text-slate-400">Ističe:</span>
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
        ```

        ### Responsive design
        - Mobile-first approach
        - Grid layout za oglase
        - Modal prozori za slike
        - Infinite scroll za listing

        ### JavaScript funkcionalnosti
        - Image gallery sa zoom
        - Real-time search suggestions
        - Chat scroll automation
        - Form validation

        ## 10. Testiranje

        ### Feature tests
        - User registration/login
        - Listing creation with fee deduction
        - Message sending
        - Image upload

        ### Unit tests
        - Balance calculation
        - Category hierarchy
        - Listing expiration

        ## Sledeći koraci za implementaciju:

        1. **Setup Laravel projekta** sa TALL stack
        2. **Kreiraj migracije** i modele
        3. **Implementiraj autentifikaciju** (Laravel Breeze + Livewire)
        4. **Kreiraj osnovne Livewire komponente**
        5. **Implementiraj upload slika** funkcionalnost
        6. **Dodaj messaging sistem**
        7. **Kreiraj balance management**
        8. **Stilizuj sa Tailwind CSS**
        9. **Testiraj funkcionalnosti**
        10. **Deploy na server**

        Ovaj projekat je prilično kompleksan ali potpuno moguć sa TALL stack-om. Preporučujem da kreneš sa osnovnim
        funkcionalnostima (registracija, kreiranje oglasa) pa postupno dodaješ naprednije features.
