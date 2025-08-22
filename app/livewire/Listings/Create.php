<?php

namespace App\Livewire\Listings;

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

    public function mount()
    {
        
        $this->categories = Category::whereNull('parent_id')
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->get() ?? collect();

    $this->conditions = ListingCondition::where('is_active', true)
        ->orderBy('name')
        ->get() ?? collect();
        // učitaj sve aktivne glavne kategorije
        $this->categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get() ?? collect();
    }

    public function render()
    {
        return view('livewire.listings.create')
            ->layout('layouts.app');
    }
}
