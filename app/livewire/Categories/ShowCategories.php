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
    public $sortBy = 'newest';
    public $perPage = 20;

    protected $queryString = [
        'sortBy' => ['except' => 'newest'],
        'perPage' => ['except' => 20]
    ];

    public function mount($category, $subcategory = null)
    {
        $this->category = Category::where('slug', $category)->firstOrFail();
        
        if ($subcategory) {
            $this->subcategory = Category::where('slug', $subcategory)
                ->where('parent_id', $this->category->id)
                ->firstOrFail();
        }
    }

    public function updatedSortBy()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
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
        
        // Sortiranje
        switch ($this->sortBy) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
            
        $listings = $query->paginate($this->perPage);
        
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