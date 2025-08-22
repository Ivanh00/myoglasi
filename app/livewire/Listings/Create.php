<?php

namespace App\Livewire\Listings;

use App\Models\Category;
use App\Models\ListingCondition;
use App\Models\Listing;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Create extends Component
{
    use WithFileUploads;

    public $title = '';
    public $description = '';
    public $price = '';
    public $category_id = '';
    public $condition_id = '';
    public $location = '';
    public $contact_phone = '';
    public $images = [];
    public $existingImages = [];

    public $categories;
    public $subcategories = [];
    public $conditions;
    public $selectedCategory = null;

    protected $rules = [
        'title' => 'required|string|min:5|max:100',
        'description' => 'required|string|min:10|max:2000',
        'price' => 'required|numeric|min:1|max:999999999',
        'category_id' => 'required|exists:categories,id',
        'condition_id' => 'required|exists:listing_conditions,id',
        'location' => 'required|string|max:100',
        'contact_phone' => 'nullable|string|max:20',
        'images.*' => 'nullable|image|max:5120', // 5MB max per image
        'images' => 'max:10' // Maximum 10 images
    ];

    protected $messages = [
        'title.required' => 'Naslov oglasa je obavezan.',
        'title.min' => 'Naslov mora imati najmanje 5 karaktera.',
        'title.max' => 'Naslov može imati maksimalno 100 karaktera.',
        'description.required' => 'Opis oglasa je obavezan.',
        'description.min' => 'Opis mora imati najmanje 10 karaktera.',
        'description.max' => 'Opis može imati maksimalno 2000 karaktera.',
        'price.required' => 'Cena je obavezna.',
        'price.numeric' => 'Cena mora biti broj.',
        'price.min' => 'Cena mora biti veća od 0.',
        'category_id.required' => 'Morate odabrati kategoriju.',
        'condition_id.required' => 'Morate odabrati stanje.',
        'location.required' => 'Lokacija je obavezna.',
        'images.*.image' => 'Datoteka mora biti slika.',
        'images.*.max' => 'Slika može biti maksimalno 5MB.',
        'images.max' => 'Možete dodati maksimalno 10 slika.',
    ];

    public function mount()
    {
        $this->categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        
        $this->conditions = ListingCondition::where('is_active', true)->get();
        
        // Set user's location and phone if available
        $user = auth()->user();
        $this->location = $user->city ?? '';
        $this->contact_phone = $user->phone ?? '';
    }

    public function updatedCategoryId($value)
    {
        if ($value) {
            $this->selectedCategory = Category::find($value);
            $this->subcategories = Category::where('parent_id', $value)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
            
            // If selected category has subcategories, reset category_id to force subcategory selection
            if ($this->subcategories->count() > 0) {
                $this->category_id = '';
            }
        } else {
            $this->subcategories = [];
            $this->selectedCategory = null;
        }
    }

    public function removeImage($index)
    {
        if (isset($this->images[$index])) {
            unset($this->images[$index]);
            $this->images = array_values($this->images); // Reindex array
        }
    }

    public function save()
    {
        // Check if user has enough balance
        if (auth()->user()->balance < 10) {
            session()->flash('error', 'Nemate dovoljno sredstava na računu. Potrebno je najmanje 10 dinara za objavljivanje oglasa.');
            return;
        }

        $this->validate();

        try {
            // Create listing
            $listing = Listing::create([
                'title' => $this->title,
                'description' => $this->description,
                'price' => $this->price,
                'category_id' => $this->category_id,
                'condition_id' => $this->condition_id,
                'location' => $this->location,
                'contact_phone' => $this->contact_phone,
                'user_id' => auth()->id(),
                'slug' => Str::slug($this->title) . '-' . Str::random(6),
                'status' => 'active',
                'expires_at' => now()->addDays(30), // Oglas važi 30 dana
            ]);

            // Upload images
            if (!empty($this->images)) {
                foreach ($this->images as $index => $image) {
                    $filename = 'listings/' . $listing->id . '/' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('public', $filename);
                    
                    $listing->images()->create([
                        'image_path' => str_replace('public/', '', $filename),
                        'order' => $index + 1
                    ]);
                }
            }

            // Charge fee
            auth()->user()->chargeFee(10);

            session()->flash('success', 'Oglas je uspešno kreiran! Sa vašeg računa je naplaćeno 10 dinara.');
            
            return redirect()->route('listings.show', $listing->slug);

        } catch (\Exception $e) {
            session()->flash('error', 'Došlo je do greške pri kreiranju oglasa. Pokušajte ponovo.');
            \Log::error('Listing creation error: ' . $e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.listings.create')
        ->layout('layouts.app');
    }
}
