<?php

namespace App\Livewire\Listings;

use App\Models\Listing;
use Livewire\Component;
use App\Models\Category;
use Livewire\WithFileUploads;
use App\Models\ListingCondition;
use Illuminate\Support\Str;

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
    public $conditions = [];
    public $category_id;
    public $subcategory_id;

    public $subcategories;

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
        $this->validate([
            'title' => 'required|string|min:5|max:100',
            'description' => 'required|string|min:10|max:2000',
            'price' => 'required|numeric|min:1',
            'category_id' => 'required|exists:categories,id',
            'condition_id' => 'required|exists:listing_conditions,id',
            'location' => 'required|string|max:255',
            'contact_phone' => 'nullable|string|max:20', // Dodajte validaciju za telefon
            'images.*' => 'nullable|image|max:5120',
        ]);

        $user = auth()->user();
        
        // Check if user can create listing (payment check)  
        if (!$user->canCreateListingForFree() && $user->payment_plan === 'per_listing') {
            $fee = \App\Models\Setting::get('listing_fee_amount', 10);
            if ($user->balance < $fee) {
                session()->flash('error', 'Nemate dovoljno kredita za postavljanje oglasa. Potrebno: ' . number_format($fee, 0, ',', '.') . ' RSD, a imate: ' . number_format($user->balance, 0, ',', '.') . ' RSD');
                return redirect()->route('balance.payment-options');
            }
        }
        
        // Now charge for listing
        if (!$user->chargeForListing()) {
            session()->flash('error', 'Greška pri naplaćivanju oglasa. Molimo pokušajte ponovo.');
            return redirect()->route('balance.index');
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
            'price' => $this->price,
            'category_id' => $this->category_id,
            'subcategory_id' => $this->subcategory_id,
            'condition_id' => $this->condition_id,
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

        session()->flash('success', 'Oglas je uspešno kreiran!');
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