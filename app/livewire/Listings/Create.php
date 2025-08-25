<?php

namespace App\Livewire\Listings;

use id;
use Pest\Support\Str;
use App\Models\Listing;
use Livewire\Component;
use App\Models\Category;
use Livewire\WithFileUploads;
use App\Models\ListingCondition;

class Create extends Component
{
    use WithFileUploads;

    public $categories; // ovo će držati glavne kategorije
    public $category_id;
    public $title;
    public $description;
    public $price;
    public $condition_id;
    public $location;
    public $contact_phone;
    public $images = [];
    public $conditions = [];
    public $subcategory_id;
    public $subcategories = [];

    public function mount()
{
    $this->categories = Category::whereNull('parent_id')
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->get() ?? collect();

    $this->conditions = ListingCondition::where('is_active', true)
        ->orderBy('name')
        ->get() ?? collect();
}


    // Kada se promeni glavna kategorija
    public function updatedCategoryId($value)
    {
        $this->subcategory_id = null;
        $this->subcategories = Category::where('parent_id', $value)->get();
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
            'condition_id' => $this->condition_id,
            'location' => $this->location,
            'contact_phone' => $this->contact_phone,
            'slug' => Str::slug($this->title) . '-' . Str::random(6),
        ]);

        session()->flash('success', 'Oglas je uspešno kreiran!');
        return redirect()->route('listings.show', $listing->slug);
    }

    public function render()
    {
        return view('livewire.listings.create')
            ->layout('layouts.app');
    }
}
