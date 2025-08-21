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

### Kreiranje oglasa sa naplatom
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

public function createListing()
{
$this->validate();

$user = auth()->user();
$fee = 10; // 10 dinara

if (!$user->deductBalance($fee)) {
session()->flash('error', 'Nemate dovoljno sredstava na računu.');
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

### Messaging sistem
```php
class MessagesComponent extends Component
{
public $selectedListing;
public $messages = [];
public $newMessage = '';

public function mount($listingId = null)
{
if ($listingId) {
$this->selectedListing = Listing::find($listingId);
$this->loadMessages();
}
}

public function sendMessage()
{
if (empty($this->newMessage) || !$this->selectedListing) {
return;
}

Message::create([
'sender_id' => auth()->id(),
'receiver_id' => $this->selectedListing->user_id,
'listing_id' => $this->selectedListing->id,
'message' => $this->newMessage
]);

$this->newMessage = '';
$this->loadMessages();
}

private function loadMessages()
{
$this->messages = Message::where('listing_id', $this->selectedListing->id)
->where(function($query) {
$query->where('sender_id', auth()->id())
->orWhere('receiver_id', auth()->id());
})
->with('sender')
->orderBy('created_at', 'asc')
->get();
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
Route::get('/', HomeComponent::class)->name('home');

Route::middleware(['auth'])->group(function () {
Route::get('/dashboard', UserDashboard::class)->name('dashboard');
Route::get('/listings/create', CreateListingComponent::class)->name('listings.create');
Route::get('/messages', MessagesComponent::class)->name('messages');
Route::get('/balance', BalanceComponent::class)->name('balance');
});

Route::get('/listings/{listing}', ListingDetailComponent::class)->name('listings.show');
Route::get('/category/{category}', CategoryListingsComponent::class)->name('category.show');
Route::get('/search', SearchComponent::class)->name('search');
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

## 9. Frontend (Tailwind + Alpine.js)

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
