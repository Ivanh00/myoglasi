<?php

namespace App\Livewire\Categories;

use Livewire\Component;
use App\Models\Category;
use App\Models\Listing;
use Livewire\WithPagination;

class ShowCategories extends Component
{
    use WithPagination;
    
    public $category;
    public $subcategory;

    public function mount($category, $subcategory = null)
    {
        $this->category = Category::where('slug', $category)->firstOrFail();
        
        if ($subcategory) {
            $this->subcategory = Category::where('slug', $subcategory)
                ->where('parent_id', $this->category->id)
                ->firstOrFail();
        }
    }

    public function render()
    {
        $query = Listing::where('status', 'active')
            ->with(['category', 'condition', 'images']);
            
        if ($this->subcategory) {
            $query->where('subcategory_id', $this->subcategory->id);
        } else {
            $query->where('category_id', $this->category->id);
        }
            
        $listings = $query->orderBy('created_at', 'desc')->paginate(12);
        
        // Učitaj podkategorije ako nije izabrana podkategorija
        $subcategories = [];
        if (!$this->subcategory) {
            $subcategories = Category::where('parent_id', $this->category->id)
                ->withCount(['listings' => function($query) {
                    $query->where('status', 'active');
                }])
                ->get();
        }
            
        return view('livewire.categories.show-categories', [
            'listings' => $listings,
            'subcategories' => $subcategories,
            'category' => $this->category,
            'subcategory' => $this->subcategory ?? null
        ])->layout('layouts.app');
    }
}