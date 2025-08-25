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
    
    // DEBUG: Prikažite sve dostupne kategorije sa brojem podkategorija
    logger()->info('=== DOSTUPNE KATEGORIJE ===');
    foreach($this->categories as $category) {
        $subcatCount = Category::where('parent_id', $category->id)
            ->where('is_active', true)
            ->count();
        
        logger()->info("ID: {$category->id} - {$category->name} - Podkategorije: {$subcatCount}");
    }
    logger()->info('=== KRAJ DOSTUPNIH KATEGORIJA ===');
}

public function updatedCategory_id($value)
{
    logger()->info('=== PROMENA KATEGORIJE ===');
    logger()->info('Odabrana kategorija ID: ' . ($value ?? 'null'));
    
    $this->subcategory_id = null;
    
    if ($value) {
        // Proverite da li kategorija postoji
        $selectedCategory = Category::find($value);
        if ($selectedCategory) {
            logger()->info('Odabrana kategorija: ' . $selectedCategory->name);
        } else {
            logger()->info('GREŠKA: Kategorija sa ID ' . $value . ' ne postoji!');
        }
        
        // Učitajte podkategorije
        $this->subcategories = Category::where('parent_id', $value)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
            
        logger()->info('Broj pronađenih podkategorija: ' . $this->subcategories->count());
        
        if ($this->subcategories->count() > 0) {
            logger()->info('Podkategorije:');
            foreach($this->subcategories as $sub) {
                logger()->info("  - ID: {$sub->id} - {$sub->name}");
            }
        } else {
            logger()->info('Nema aktivnih podkategorija za ovu kategoriju.');
            
            // Proverite da li postoje neaktivne podkategorije
            $inactiveSubcats = Category::where('parent_id', $value)
                ->where('is_active', false)
                ->count();
            if ($inactiveSubcats > 0) {
                logger()->info("NAPOMENA: Postoji {$inactiveSubcats} neaktivnih podkategorija.");
            }
        }
    } else {
        $this->subcategories = collect();
        logger()->info('Kategorija resetovana.');
    }
    
    logger()->info('=== KRAJ PROMENE KATEGORIJE ===');
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
        ]);

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
        ]);

        session()->flash('success', 'Oglas je uspešno kreiran!');
        return redirect()->route('listings.show', $listing->slug);
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

    // Dodajte ovu metodu privremeno za testiranje
    public function testSubcategories()
    {
        $categoryId = 1; // ili bilo koji ID koji testirate
        $subcats = Category::where('parent_id', $categoryId)->get();
        
        logger()->info('Test subcategories for category ' . $categoryId, [
            'count' => $subcats->count(),
            'data' => $subcats->toArray()
        ]);
    }
}
