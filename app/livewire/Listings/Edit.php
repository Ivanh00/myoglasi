<?php

namespace App\Livewire\Listings;

use Livewire\Component;
use App\Models\Listing;
use App\Models\Category;
use App\Models\ListingCondition;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Edit extends Component
{
    use WithFileUploads;

    public $listing;
    public $categories;
    public $conditions;
    public $title;
    public $description;
    public $price;
    public $condition_id;
    public $location;
    public $contact_phone;
    public $category_id;
    public $subcategory_id;
    public $subcategories;
    public $newImages = [];

    // Auction-specific properties
    public $hasAuction = false;
    public $startingPrice = '';
    public $buyNowPrice = '';
    public $duration = 7;
    public $startType = 'immediately';
    public $startDate = '';
    public $startTime = '';

    public function updatedNewImages()
    {
        $maxImages = $this->listing->getMaxImagesCount();
        $currentImages = $this->listing->images->count();
        $totalImages = $currentImages + count($this->newImages);
        
        if ($totalImages > $maxImages) {
            $allowedNew = max(0, $maxImages - $currentImages);
            $this->newImages = array_slice($this->newImages, 0, $allowedNew);
            session()->flash('error', "Možete dodati maksimalno {$maxImages} slika ukupno. Imate {$currentImages} postojećih, možete dodati još {$allowedNew}.");
        }
        
        // Force re-render to update UI
        $this->dispatch('$refresh');
    }

    public function mount(Listing $listing)
{
    $this->listing = $listing;

    // Prevent editing of inactive listings (except by admins)
    if ($listing->status === 'inactive' && (!auth()->check() || !auth()->user()->is_admin)) {
        session()->flash('error', 'Ovaj oglas je uklonjen i ne može se uređivati.');
        return redirect()->route('listings.my');
    }

    // Prevent editing if listing has an auction with bids
    if ($listing->auction && $listing->auction->total_bids > 0) {
        session()->flash('error', 'Ne možete uređivati oglas koji ima aktivnu aukciju sa ponudama.');
        return redirect()->route('auction.show', $listing->auction);
    }
    
    // Popuni polja sa postojećim vrednostima iz oglasa
    $this->title = $listing->title;
    $this->description = $listing->description;
    $this->price = $listing->price;
    $this->category_id = $listing->category_id;
    $this->subcategory_id = $listing->subcategory_id;
    $this->condition_id = $listing->condition_id;
    $this->location = $listing->location;
    $this->contact_phone = $listing->contact_phone;

    // Load auction data if exists
    if ($listing->auction) {
        $this->hasAuction = true;
        $this->startingPrice = $listing->auction->starting_price;
        $this->buyNowPrice = $listing->auction->buy_now_price;
        $this->duration = $listing->auction->ends_at->diffInDays($listing->auction->starts_at);
        $this->startType = $listing->auction->starts_at->isPast() ? 'immediately' : 'scheduled';
        if ($this->startType === 'scheduled') {
            $this->startDate = $listing->auction->starts_at->format('Y-m-d');
            $this->startTime = $listing->auction->starts_at->format('H:i');
        }
    }

    $this->categories = Category::whereNull('parent_id')
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->get();
    
    // Ensure categories is never null
    if (!$this->categories) {
        $this->categories = collect();
    }

    $this->conditions = ListingCondition::where('is_active', true)
        ->orderBy('name')
        ->get();
    
    // Ensure conditions is never null
    if (!$this->conditions) {
        $this->conditions = collect();
    }

    // Učitaj podkategorije ako postoji kategorija
    if ($this->category_id) {
        $this->subcategories = Category::where('parent_id', $this->category_id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    } else {
        $this->subcategories = collect();
    }
    
    // Initialize subcategories as empty collection if null
    if (!$this->subcategories) {
        $this->subcategories = collect();
    }
    
    // PROVERA DA LI JE POTREBNO AŽURIRATI PODATKE IZ PROFILA
    $user = auth()->user();
    
    // Provera da li je lokacija popunjena u profilu
    if (empty($user->city)) {
        session()->flash('warning', 'Molimo vas da popunite vašu lokaciju u profilu. <a href="'.route('profile').'" class="underline">Ažuriraj profil</a>');
    }
    
    // Provera da li je telefon popunjen u profilu
    if (empty($user->phone)) {
        session()->flash('info', 'Molimo vas da popunite vaš telefon u profilu kako bi kupci mogli da vas kontaktiro. <a href="'.route('profile').'" class="underline">Ažuriraj profil</a>');
    }
}

    public function updatedCategoryId($value)
    {
        $this->subcategory_id = null;
        $this->loadSubcategories();
    }

    protected function loadSubcategories()
    {
        $this->subcategories = Category::where('parent_id', $this->category_id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function removeImage($imageId)
    {
        $image = \App\Models\ListingImage::find($imageId);
        if ($image && $image->listing_id == $this->listing->id) {
            // Obriši fizički fajl
            Storage::disk('public')->delete($image->image_path);
            // Obriši zapis iz baze
            $image->delete();
            
            // Refresh the listing relationship to get updated count
            $this->listing->load('images');
            
            session()->flash('message', 'Slika je uspešno obrisana.');
            
            // Force re-render to update UI
            $this->dispatch('$refresh');
        }
    }

    public function removeNewImage($index)
    {
        unset($this->newImages[$index]);
        $this->newImages = array_values($this->newImages);
        
        // Force re-render to update UI
        $this->dispatch('$refresh');
    }

    public function getRemainingImageSlotsProperty()
    {
        $maxImages = $this->listing->getMaxImagesCount();
        $currentCount = $this->listing->images()->count(); // Fresh count from database
        $newCount = count($this->newImages ?? []);
        $totalCount = $currentCount + $newCount;
        return max(0, $maxImages - $totalCount);
    }

    public function getMaxImagesProperty()
    {
        return $this->listing->getMaxImagesCount();
    }

    public function update()
    {
        // Double-check: Prevent updating if listing has an auction with bids
        if ($this->listing->auction && $this->listing->auction->total_bids > 0) {
            session()->flash('error', 'Ne možete uređivati oglas koji ima aktivnu aukciju sa ponudama.');
            return redirect()->route('auction.show', $this->listing->auction);
        }

        $maxImages = $this->listing->getMaxImagesCount();
        $currentImagesCount = $this->listing->images->count();
        $maxNewImages = max(0, $maxImages - $currentImagesCount);

        $rules = [
            'title' => 'required|string|min:5|max:100',
            'description' => 'required|string|min:10|max:2000',
            'category_id' => 'required|exists:categories,id',
            'location' => 'required|string|max:255',
            'newImages' => "nullable|array|max:{$maxNewImages}",
            'newImages.*' => 'nullable|image|max:5120',
        ];

        // Price validation (not for giveaways)
        if ($this->listing->listing_type !== 'giveaway') {
            $rules['price'] = 'required|numeric|min:1';
        }

        // Condition validation (not for giveaways)
        if ($this->listing->listing_type !== 'giveaway') {
            $rules['condition_id'] = 'required|exists:listing_conditions,id';
        }

        // Auction validation if has auction
        if ($this->hasAuction) {
            $rules['startingPrice'] = 'required|numeric|min:1|max:1000000';
            $rules['buyNowPrice'] = 'nullable|numeric|min:1|max:1000000|gt:startingPrice';
        }

        $this->validate($rules);

        // Ažuriraj osnovne podatke
        $this->listing->update([
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'category_id' => $this->category_id,
            'subcategory_id' => $this->subcategory_id,
            'condition_id' => $this->condition_id,
            'location' => $this->location,
            'contact_phone' => $this->contact_phone,
        ]);

        // Dodaj nove slike ako postoje
        if (!empty($this->newImages)) {
            foreach ($this->newImages as $image) {
                $path = $image->store('listings', 'public');
                
                $this->listing->images()->create([
                    'image_path' => $path,
                    'order' => 0
                ]);
            }
        }

        // Update auction if exists
        if ($this->hasAuction && $this->listing->auction) {
            $this->listing->auction->update([
                'starting_price' => $this->startingPrice,
                'buy_now_price' => $this->buyNowPrice ?: null,
                'current_price' => $this->startingPrice, // Reset current price to new starting price
            ]);
        }

        $successMessage = $this->hasAuction ? 'Aukcija je uspešno ažurirana!' : 'Oglas je uspešno ažuriran!';
        session()->flash('success', $successMessage);

        if ($this->hasAuction) {
            return redirect()->route('auction.show', $this->listing->auction);
        }

        return redirect()->route('listings.show', $this->listing);
    }

    public function render()
    {
        return view('livewire.listings.edit')->layout('layouts.app');
    }
}