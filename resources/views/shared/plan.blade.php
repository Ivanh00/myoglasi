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
        <p class="text-2xl font-bold text-green-600 mb-2">
            {{ number_format($listing->price, 0) }} RSD
        </p>
        <p class="text-slate-600 dark:text-slate-400 text-sm mb-3">{{ $listing->category->name }}</p>

        <!-- Seller info - visible to all -->
        <div class="flex items-center justify-between">
            <span class="text-sm text-slate-500 dark:text-slate-300">{{ $listing->user->name }}</span>
            <span class="text-xs text-slate-400">{{ $listing->created_at->diffForHumans() }}</span>
        </div>

        <!-- Phone number - only if visible and user allows it -->
        @if ($listing->user->phone_visible && $listing->user->phone)
            <div class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                @auth
                    📞 {{ $listing->user->phone }}
                @else
                    📞 <span class="text-sky-600 dark:text-sky-400">Registruj se za broj</span>
                @endauth
            </div>
        @endif

        <!-- Action button -->
        <div class="mt-3">
            @auth
                @if (auth()->id() !== $listing->user_id)
                    <a href="{{ route('listings.show', $listing) }}"
                        class="block w-full bg-sky-600 text-white text-center py-2 rounded hover:bg-sky-700 transition">
                        Pogledaj detaljno
                    </a>
                @else
                    <a href="{{ route('listings.edit', $listing) }}"
                        class="block w-full bg-slate-600 text-white text-center py-2 rounded hover:bg-slate-700 transition">
                        Edituj oglas
                    </a>
                @endif
            @else
                <a href="{{ route('listings.show', $listing) }}"
                    class="block w-full bg-sky-600 text-white text-center py-2 rounded hover:bg-sky-700 transition">
                    Pogledaj detaljno
                </a>
            @endauth
        </div>
    </div>
</div>
```

### Home page komponenta za gost korisnike
```blade
<!-- resources/views/livewire/home.blade.php -->
<div>
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-sky-600 to-purple-700 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold mb-4">Dobrodošli na MarketPlace</h1>
            <p class="text-xl mb-8">Kupuj i prodavaj bez ograničenja</p>

            <!-- Search Bar -->
            <div class="max-w-2xl mx-auto relative">
                <input wire:model="searchTerm" type="text" placeholder="Pretražite oglase..."
                    class="w-full px-6 py-4 text-slate-800 rounded-lg focus:outline-none focus:ring-2 focus:ring-white">
                <button class="absolute right-2 top-2 bg-sky-600 text-white px-6 py-2 rounded hover:bg-sky-700">
                    🔍
                </button>
            </div>

            @guest
                <div class="mt-6 space-x-4">
                    <a href="{{ route('register') }}"
                        class="bg-white text-sky-600 px-6 py-3 rounded-lg hover:bg-slate-100 transition">
                        Registruj se besplatno
                    </a>
                    <a href="{{ route('login') }}"
                        class="border border-white px-6 py-3 rounded-lg hover:bg-white hover:text-sky-600 transition">
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
                    <p class="text-sm text-slate-500 dark:text-slate-300">{{ $category->listings_count ?? 0 }} oglasa
                    </p>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Featured Listings -->
    @if ($featuredListings->count() > 0)
        <div class="bg-slate-50 py-8">
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
            <a href="{{ route('search') }}" class="text-sky-600 hover:text-sky-800">
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
        <div class="bg-sky-600 text-white py-12">
            <div class="max-w-4xl mx-auto px-4 text-center">
                <h2 class="text-3xl font-bold mb-4">Želiš da prodaš nešto?</h2>
                <p class="text-xl mb-6">Registruj se i počni da objavljuješ oglase već danas!</p>
                <div class="space-x-4">
                    <a href="{{ route('register') }}"
                        class="bg-white text-sky-600 px-8 py-3 rounded-lg hover:bg-slate-100 transition inline-block">
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
```

### Registration form sa phone_visible
```blade
<!-- resources/views/livewire/register.blade.php -->
<div class="min-h-screen flex items-center justify-center bg-slate-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-slate-900">
                Kreiraj nalog
            </h2>
            <p class="mt-2 text-center text-sm text-slate-600 dark:text-slate-400">
                Ili se
                <a href="{{ route('login') }}" class="font-medium text-sky-600 hover:text-sky-500">
                    prijavi ako imaš nalog
                </a>
            </p>
        </div>

        <form wire:submit.prevent="register" class="mt-8 space-y-6">
            <div class="space-y-4">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                        Ime i prezime *
                    </label>
                    <input wire:model="name" type="text" id="name"
                        class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500">
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                        Email adresa *
                    </label>
                    <input wire:model="email" type="email" id="email"
                        class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500">
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                        Broj telefona
                    </label>
                    <input wire:model="phone" type="text" id="phone" placeholder="064/123-456"
                        class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500">
                    @error('phone')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror

                    <!-- Phone visibility checkbox -->
                    <div class="mt-2">
                        <label class="flex items-center">
                            <input wire:model="phone_visible" type="checkbox"
                                class="rounded border-slate-300 text-sky-600 shadow-sm focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-slate-600 dark:text-slate-400">
                                Prikaži broj telefona u oglasima (preporučeno)
                            </span>
                        </label>
                        <p class="text-xs text-slate-500 dark:text-slate-300 mt-1">
                            Kupci će moći direktno da te kontaktiraju
                        </p>
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                        Lozinka *
                    </label>
                    <input wire:model="password" type="password" id="password"
                        class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500">
                    @error('password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation"
                        class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                        Potvrdi lozinku *
                    </label>
                    <input wire:model="password_confirmation" type="password" id="password_confirmation"
                        class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500">
                    @error('password_confirmation')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div>
                <button type="submit"
                    class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                    Registruj se
                </button>
            </div>

            <div class="text-center">
                <p class="text-sm text-slate-600 dark:text-slate-400">
                    Registracijom se slažeš sa našim uslovima korišćenja
                </p>
            </div>
        </form>
    </div>
</div>
```
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

## 10. Sigurnosne mere i validacije

### Guest Access Policy
```php
// app/Policies/ListingPolicy.php
class ListingPolicy
{
public function viewAny(?User $user)
{
// Svi mogu da vide oglase
return true;
}

public function view(?User $user, Listing $listing)
{
// Svi mogu da vide pojedinačne oglase
return true;
}

public function create(User $user)
{
// Samo registrovani mogu da kreiraju
return $user !== null;
}

public function update(User $user, Listing $listing)
{
// Samo vlasnik može da edituje
return $user->id === $listing->user_id;
}

public function contact(?User $user, Listing $listing)
{
// Samo registrovani mogu da kontaktiraju
// Ali ne mogu sami sebe
return $user !== null && $user->id !== $listing->user_id;
}
}
```

### Phone Number Visibility Logic
```php
// U User model
class User extends Authenticatable
{
public function getVisiblePhoneAttribute()
{
return $this->phone_visible && $this->phone ? $this->phone : null;
}

public function canViewPhone(?User $viewer)
{
// Phone je vidljiv ako:
// 1. Korisnik je označio da bude vidljiv
// 2. I viewer je registrovan
return $this->phone_visible && $this->phone && $viewer !== null;
}
}
```

### Middleware za guest pristup
```php
// app/Http/Middleware/OptionalAuth.php
class OptionalAuth
{
public function handle($request, Closure $next)
{
// Ne zahteva autentifikaciju, ali je dodeli ako postoji
if (auth()->check()) {
$request->merge(['user' => auth()->user()]);
}

return $next($request);
}
}
```

## 11. Database seeders za test podatke

```php
// database/seeders/DatabaseSeeder.php
public function run()
{
// Kreiraj kategorije
$categories = [
['name' => 'Automobili', 'slug' => 'automobili', 'icon' => '🚗'],
['name' => 'Nekretnine', 'slug' => 'nekretnine', 'icon' => '🏠'],
['name' => 'Elektronika', 'slug' => 'elektronika', 'icon' => '📱'],
['name' => 'Odeća', 'slug' => 'odeca', 'icon' => '👕'],
['name' => 'Sport', 'slug' => 'sport', 'icon' => '⚽'],
['name' => 'Knjige', 'slug' => 'knjige', 'icon' => '📚'],
];

foreach ($categories as $category) {
Category::create($category);
}

// Kreiraj test korisnike
User::factory(10)->create()->each(function ($user) {
// Neki korisnici imaju vidljiv telefon, neki ne
$user->update([
'phone' => '064/' . rand(100, 999) . '-' . rand(100, 999),
'phone_visible' => rand(0, 1),
'balance' => rand(0, 5000)
]);

// Kreiraj oglase za korisnika
Listing::factory(rand(1, 5))->create([
'user_id' => $user->id,
'category_id' => Category::inRandomOrder()->first()->id
]);
});
}
```

## 12. Testing strategije

### Feature tests za guest funkcionalnost
```php
// tests/Feature/GuestAccessTest.php
class GuestAccessTest extends TestCase
{
public function test_guest_can_view_home_page()
{
$response = $this->get('/');
$response->assertStatus(200);
}

public function test_guest_can_view_listing_detail()
{
$listing = Listing::factory()->create();

$response = $this->get(route('listings.show', $listing));
$response->assertStatus(200);
$response->assertSee($listing->title);
}

public function test_guest_cannot_see_phone_if_not_visible()
{
$user = User::factory()->create(['phone_visible' => false, 'phone' => '064/123-456']);
$listing = Listing::factory()->create(['user_id' => $user->id]);

$response = $this->get(route('listings.show', $listing));
$response->assertDontSee('064/123-456');
}

public function test_guest_cannot_contact_seller()
{
$listing = Listing::factory()->create();

$response = $this->post(route('messages.store'), [
'listing_id' => $listing->id,
'message' => 'Test message'
]);

$response->assertRedirect(route('login'));
}
}
```

### Unit tests za phone visibility
```php
// tests/Unit/UserPhoneVisibilityTest.php
class UserPhoneVisibilityTest extends TestCase
{
public function test_phone_visible_when_enabled_and_user_authenticated()
{
$user = User::factory()->create([
'phone' => '064/123-456',
'phone_visible' => true
]);

$viewer = User::factory()->create();

$this->assertTrue($user->canViewPhone($viewer));
}

public function test_phone_not_visible_when_disabled()
{
$user = User::factory()->create([
'phone' => '064/123-456',
'phone_visible' => false
]);

$viewer = User::factory()->create();

$this->assertFalse($user->canViewPhone($viewer));
}

public function test_phone_not_visible_to_guest()
{
$user = User::factory()->create([
'phone' => '064/123-456',
'phone_visible' => true
]);

$this->assertFalse($user->canViewPhone(null));
}
}
```

## 13. Performance optimizacije

### Caching strategije
```php
// Cachiranje popularnih kategorija
class CategoryService
{
public function getPopularCategories()
{
return Cache::remember('popular_categories', 3600, function () {
return Category::withCount('listings')
->orderBy('listings_count', 'desc')
->take(10)
->get();
});
}
}

// Eager loading za performance
class ListingController
{
public function index()
{
$listings = Listing::with([
'user:id,name,phone,phone_visible',
'category:id,name',
'primaryImage'
])->paginate(20);

return view('listings.index', compact('listings'));
}
}
```

## Završni koraci implementacije:

1. **Setup osnovne infrastrukture**
- Laravel instalacija sa TALL stack
- Database migracije
- Basic auth (Breeze)

2. **Implementacija javnog pristupa**
- Home page za sve korisnike
- Listing detail za sve
- Phone visibility logika

3. **Auth-only funkcionalnosti**
- Kreiranje oglasa
- Messaging sistem
- Balance management

4. **UI/UX poboljšanja**
- Responsive design
- Guest call-to-action
- Clear registration benefits

5. **Testiranje i optimizacija**
- Feature tests
- Performance optimization
- Security checks

Ovakav pristup omogućava da sajt bude koristan i za neregistrovane korisnike (mogu pregledati oglase i videti osnovne
informacije), dok registracija donosi dodatne benefite kao što su kontaktiranje prodavaca, postavljanje oglasa i pristup
brojevima telefona.

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
