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

    public function updatedNewImages()
    {
        $maxImages = \App\Models\Setting::get('max_images_per_listing', 10);
        $currentImages = $this->listing->images->count();
        $totalImages = $currentImages + count($this->newImages);
        
        if ($totalImages > $maxImages) {
            $allowedNew = max(0, $maxImages - $currentImages);
            $this->newImages = array_slice($this->newImages, 0, $allowedNew);
            session()->flash('error', "Možete dodati maksimalno {$maxImages} slika ukupno. Imate {$currentImages} postojećih, možete dodati još {$allowedNew}.");
        }
    }

    public function mount(Listing $listing)
{
    $this->listing = $listing;
    
    // Popuni polja sa postojećim vrednostima iz oglasa
    $this->title = $listing->title;
    $this->description = $listing->description;
    $this->price = $listing->price;
    $this->category_id = $listing->category_id;
    $this->subcategory_id = $listing->subcategory_id;
    $this->condition_id = $listing->condition_id;
    $this->location = $listing->location;
    $this->contact_phone = $listing->contact_phone;
    
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
            
            session()->flash('message', 'Slika je uspešno obrisana.');
        }
    }

    public function removeNewImage($index)
    {
        unset($this->newImages[$index]);
        $this->newImages = array_values($this->newImages);
    }

    public function update()
    {
        $maxImages = \App\Models\Setting::get('max_images_per_listing', 10);
        $currentImagesCount = $this->listing->images->count();
        $maxNewImages = max(0, $maxImages - $currentImagesCount);
        
        $this->validate([
            'title' => 'required|string|min:5|max:100',
            'description' => 'required|string|min:10|max:2000',
            'price' => 'required|numeric|min:1',
            'category_id' => 'required|exists:categories,id',
            'condition_id' => 'required|exists:listing_conditions,id',
            'location' => 'required|string|max:255',
            'newImages' => "nullable|array|max:{$maxNewImages}",
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