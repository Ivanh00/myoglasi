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

    public function mount($listing)
    {
        
        // Ako je prosleđen slug, nađi listing po slug-u
        if (is_string($listing)) {
            $this->listing = Listing::where('slug', $listing)
                ->with(['images'])
                ->firstOrFail();
        } 
        // Ako je prosleđen Listing model
        else if ($listing instanceof Listing) {
            $this->listing = $listing->load(['images']);
        }

        // Proveri da li je korisnik vlasnik oglasa
        if (auth()->id() !== $this->listing->user_id) {
            abort(403, 'Niste ovlašćeni da menjate ovaj oglas.');
        }

        $this->categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $this->conditions = ListingCondition::where('is_active', true)
            ->orderBy('name')
            ->get();

        // Popuni polja sa postojećim podacima
        $this->title = $this->listing->title;
        $this->description = $this->listing->description;
        $this->price = $this->listing->price;
        $this->condition_id = $this->listing->condition_id;
        $this->location = $this->listing->location;
        $this->contact_phone = $this->listing->contact_phone;
        $this->category_id = $this->listing->category_id;
        $this->subcategory_id = $this->listing->subcategory_id;

        // Učitaj podkategorije
        $this->loadSubcategories();
    }

    // Ostale metode ostaju iste...
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
            
            session()->flash('message', 'Slika je uspešno obrisana.');
            $this->listing->load('images'); // Osveži podatke
        }
    }

    public function removeNewImage($index)
    {
        unset($this->newImages[$index]);
        $this->newImages = array_values($this->newImages);
    }

    public function update()
    {
        $this->validate([
            'title' => 'required|string|min:5|max:100',
            'description' => 'required|string|min:10|max:2000',
            'price' => 'required|numeric|min:1',
            'category_id' => 'required|exists:categories,id',
            'condition_id' => 'required|exists:listing_conditions,id',
            'location' => 'required|string|max:255',
            'newImages.*' => 'nullable|image|max:5120',
        ]);

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

        session()->flash('success', 'Oglas je uspešno ažuriran!');
        return redirect()->route('listings.show', $this->listing);
    }

    public function render()
    {
        return view('livewire.listings.edit')->layout('layouts.app');
    }
}