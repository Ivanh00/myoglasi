<?php

namespace App\Livewire\Listings;

use App\Models\Listing;
use Livewire\Component;
use App\Models\Category;
use Livewire\WithFileUploads;
use App\Models\ListingCondition;
use App\Models\Auction;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Create extends Component
{
    use WithFileUploads;

    public $categories;
    public $title;
    public $description;
    public $price;
    public $condition_id;
    public $location;
    public $contact_phone;
    public $images = [];
    public $tempImages = [];
    public $conditions = [];
    public $category_id;
    public $subcategory_id;
    public $listingType = 'listing'; // listing, giveaway, or auction allowed

    public $subcategories;

    // Auction-specific properties
    public $startingPrice = '';
    public $buyNowPrice = '';
    public $duration = 7;
    public $startType = 'immediately';
    public $startDate = '';
    public $startTime = '';

    protected $messages = [
        'startingPrice.required' => 'Početna cena je obavezna.',
        'startingPrice.min' => 'Početna cena mora biti najmanje 1 RSD.',
        'startingPrice.max' => 'Početna cena ne može biti veća od 1.000.000 RSD.',
        'buyNowPrice.gt' => 'Kupi odmah cena mora biti veća od početne cene.',
        'startDate.after_or_equal' => 'Datum početka ne može biti u prošlosti.',
        'startTime.required' => 'Vreme početka je obavezno za zakazanu aukciju.'
    ];

    public function updatedTempImages()
    {
        $maxImages = \App\Models\Setting::get('max_images_per_listing', 10);
        
        if (!empty($this->tempImages)) {
            // Add new images to existing images array
            foreach ($this->tempImages as $tempImage) {
                if (count($this->images) < $maxImages) {
                    $this->images[] = $tempImage;
                } else {
                    session()->flash('error', "Možete dodati maksimalno {$maxImages} slika.");
                    break;
                }
            }
            
            // Clear temp images
            $this->tempImages = [];
        }
    }

    public function removeImage($index)
    {
        unset($this->images[$index]);
        $this->images = array_values($this->images); // Re-index array
    }

    public function mount()
    {
        $this->categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get() ?? collect();

        $this->conditions = ListingCondition::where('is_active', true)
            ->orderBy('name')
            ->get() ?? collect();

        $this->subcategories = collect();
        
        // Automatsko popunjavanje lokacije i telefona iz korisničkog profila
        $user = auth()->user();
        $this->location = $user->city; // Koristimo city iz profila
        $this->contact_phone = $user->phone; // Koristimo phone iz profila

        // Default auction values
        $this->duration = 7;
        $this->startType = 'immediately';

        // Set listing type from URL parameter
        $type = request('type');
        if (in_array($type, ['auction', 'giveaway'])) {
            $this->listingType = $type;
        }
        
    }

    public function updatedCategory_id($value)
    {
        $this->subcategory_id = null;
        
        if ($value) {
            // Učitajte podkategorije
            $this->subcategories = Category::where('parent_id', $value)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        } else {
            $this->subcategories = collect();
            logger()->info('Kategorija resetovana.');
        }
    }

    public function save()
    {
        $maxImages = \App\Models\Setting::get('max_images_per_listing', 10);
        
        $rules = [
            'listingType' => 'required|in:listing,giveaway,auction',
            'title' => 'required|string|min:5|max:100',
            'description' => 'required|string|min:10|max:2000',
            'category_id' => 'required|exists:categories,id',
            'location' => 'required|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'images' => "nullable|array|max:{$maxImages}",
            'images.*' => 'nullable|image|max:5120',
            'tempImages.*' => 'nullable|image|max:5120',
        ];
        
        // Price is required only for regular listings (not giveaways or auctions)
        if ($this->listingType === 'listing') {
            $rules['price'] = 'required|numeric|min:1';
        }

        // Condition is required only for regular listings and auctions
        if (in_array($this->listingType, ['listing', 'auction'])) {
            $rules['condition_id'] = 'required|exists:listing_conditions,id';
        }

        // Auction-specific validation
        if ($this->listingType === 'auction') {
            $rules['startingPrice'] = 'required|numeric|min:1|max:1000000';
            $rules['buyNowPrice'] = 'nullable|numeric|min:1|max:1000000|gt:startingPrice';
            $rules['duration'] = 'required|in:1,3,5,7,10';
            $rules['startType'] = 'required|in:immediately,scheduled';

            if ($this->startType === 'scheduled') {
                $rules['startDate'] = 'required|date|after_or_equal:today';
                $rules['startTime'] = 'required';
            }
        }
        
        $this->validate($rules);

        $user = auth()->user();
        
        // Check active listing limit for users with payment disabled
        if (!$user->payment_enabled && !$user->canCreateListing()) {
            $activeLimit = \App\Models\Setting::get('monthly_listing_limit', 50);
            $currentCount = $user->getActiveListingsCount();
            $remaining = $user->getRemainingListings();
            
            session()->flash('error', "Dostigli ste limit aktivnih oglasa ({$currentCount}/{$activeLimit}). Obrišite ili sačekajte da isteknu postojeći oglasi, ili aktivirajte plaćanje za neograničene oglase.");
            return redirect()->route('listings.my');
        }
        
        // Calculate fee based on listing type
        $fee = 0;
        if ($this->listingType === 'listing' && !$user->canCreateListingForFree() && $user->payment_plan === 'per_listing') {
            $fee = \App\Models\Setting::get('listing_fee_amount', 10);
        }
        // Giveaways are always free
        
        // Check balance if fee is required
        if ($fee > 0 && $user->balance < $fee) {
            session()->flash('error', 'Nemate dovoljno kredita za postavljanje oglasa. Potrebno: ' . number_format($fee, 0, ',', '.') . ' RSD, a imate: ' . number_format($user->balance, 0, ',', '.') . ' RSD');
            return redirect()->route('balance.payment-options');
        }

        // Charge fee if required
        if ($fee > 0) {
            $user->decrement('balance', $fee);

            // Create transaction record
            \App\Models\Transaction::create([
                'user_id' => $user->id,
                'type' => 'listing_fee',
                'amount' => $fee,
                'status' => 'completed',
                'description' => 'Naplaćivanje za objavljivanje oglasa: ' . $this->title,
                'reference_number' => 'LISTING-FEE-' . now()->timestamp,
            ]);
        }

        // Sačuvaj slike
        $imagePaths = [];
        if (!empty($this->images)) {
            foreach ($this->images as $image) {
                $path = $image->store('listings', 'public');
                $imagePaths[] = $path;
            }
        }

        $expiryDays = \App\Models\Setting::get('listing_auto_expire_days', 60);
        
        $listing = Listing::create([
            'user_id' => auth()->id(),
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->listingType === 'giveaway' ? null : ($this->listingType === 'auction' ? $this->startingPrice : $this->price),
            'listing_type' => $this->listingType === 'auction' ? 'listing' : $this->listingType, // Auctions are stored as listings
            'category_id' => $this->category_id,
            'subcategory_id' => in_array($this->listingType, ['listing', 'auction']) ? $this->subcategory_id : null,
            'condition_id' => in_array($this->listingType, ['listing', 'auction']) ? $this->condition_id : null,
            'location' => $this->location,
            'contact_phone' => $this->contact_phone,
            'slug' => Str::slug($this->title) . '-' . Str::random(6),
            'status' => 'active',
            'expires_at' => now()->addDays($expiryDays),
        ]);

        // Sačuvaj slike u bazi
        foreach ($imagePaths as $path) {
            $listing->images()->create([
                'image_path' => $path,
                'order' => 0
            ]);
        }

        // Create auction if listing type is auction
        if ($this->listingType === 'auction') {
            $startsAt = $this->startType === 'immediately'
                ? now()
                : Carbon::createFromFormat('Y-m-d H:i', $this->startDate . ' ' . $this->startTime);

            $endsAt = $startsAt->copy()->addDays((int)$this->duration);

            $auction = Auction::create([
                'listing_id' => $listing->id,
                'user_id' => $listing->user_id,
                'starting_price' => $this->startingPrice,
                'buy_now_price' => $this->buyNowPrice ?: null,
                'current_price' => $this->startingPrice,
                'total_bids' => 0,
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
                'status' => 'active', // Always active, scheduling is handled by starts_at timestamp
            ]);

            session()->flash('success', 'Aukcija je uspešno kreirana!');
            return redirect()->route('auction.show', $auction);
        }

        $successMessage = $this->listingType === 'giveaway' ? 'Poklon je uspešno kreiran!' : 'Oglas je uspešno kreiran!';
        session()->flash('success', $successMessage);
        return redirect()->route('listings.show', $listing);
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'category_id') {
            logger()->info('=== UPDATED CATEGORY_ID ===');
            logger()->info('Nova vrednost: ' . $this->category_id);
            
            $this->subcategory_id = null;
            
            if ($this->category_id) {
                $this->subcategories = Category::where('parent_id', $this->category_id)
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->get();
                    
                logger()->info('Pronađenih podkategorija: ' . $this->subcategories->count());
                
                if ($this->subcategories->count() > 0) {
                    foreach($this->subcategories as $sub) {
                        logger()->info("  - {$sub->name} (ID: {$sub->id})");
                    }
                }
            } else {
                $this->subcategories = collect();
            }
            
            logger()->info('=== KRAJ UPDATED ===');
        }
    }

    public function render()
    {
        logger()->info('Rendering create component', [
            'category_id' => $this->category_id,
            'subcategory_id' => $this->subcategory_id,
            'subcategories_count' => $this->subcategories->count(),
        ]);
        
        return view('livewire.listings.create')
            ->layout('layouts.app');
    }
}