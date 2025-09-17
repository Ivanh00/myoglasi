<?php

namespace App\Livewire\Categories;

use Livewire\Component;
use App\Models\Category;
use App\Models\Listing;
use Livewire\WithPagination;

class ShowCategories extends Component
{
    use WithPagination;
    
    public $selectedCategory = null;
    public $categories;
    public $sortBy = 'newest';
    public $perPage = 20;
    public $categoryTree = [];
    public $currentCategory = null;

    protected $queryString = [
        'selectedCategory' => ['except' => ''],
        'sortBy' => ['except' => 'newest'],
        'perPage' => ['except' => 20]
    ];

    public function mount()
    {
        $this->categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Učitaj celu hijerarhiju kategorija za sidebar
        $this->categoryTree = Category::with(['children' => function($query) {
                $query->where('is_active', true)->orderBy('sort_order');
            }])
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Učitaj trenutnu kategoriju ako je selektovana
        if ($this->selectedCategory) {
            $this->currentCategory = Category::with('parent')->find($this->selectedCategory);
        }
    }

    public function updatedSelectedCategory()
    {
        $this->currentCategory = Category::with('parent')->find($this->selectedCategory);
        $this->resetPage();
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
        $query = Listing::whereIn('status', ['active', 'inactive'])
            ->whereDoesntHave('auction') // Exclude listings that have been converted to auctions
            ->with(['category', 'condition', 'images', 'subcategory']);
            
        if ($this->selectedCategory) {
            $category = Category::find($this->selectedCategory);
            
            if ($category) {
                // Koristimo novu metodu iz Category modela
                $categoryIds = $category->getAllCategoryIds();
                
                $query->where(function($q) use ($categoryIds) {
                    $q->whereIn('category_id', $categoryIds)
                      ->orWhereIn('subcategory_id', $categoryIds);
                });
            }
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
            
        return view('livewire.listings.index', [
            'listings' => $listings,
            'categories' => $this->categories,
            'categoryTree' => $this->categoryTree,
            'currentCategory' => $this->currentCategory
        ])->layout('layouts.app');
    }
}