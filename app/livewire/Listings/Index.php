<?php

namespace App\Livewire\Listings;

use Livewire\Component;
use App\Models\Listing;
use App\Models\Category;
use App\Models\ListingCondition;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    
    public $selectedCategory = null;
    public $categories;
    public $sortBy = 'newest';
    public $perPage = 20;
    public $categoryTree = [];
    public $currentCategory = null;
    public $viewMode = 'list'; // list or grid
    
    // Search parametri
    public $query = '';
    public $city = '';
    public $search_category = '';
    public $condition_id = '';
    public $price_min = '';
    public $price_max = '';

    protected $queryString = [
        'selectedCategory' => ['except' => ''],
        'sortBy' => ['except' => 'newest'],
        'perPage' => ['except' => 20],
        'viewMode' => ['except' => 'list'],
        'query' => ['except' => ''],
        'city' => ['except' => ''],
        'search_category' => ['except' => ''],
        'condition_id' => ['except' => ''],
        'price_min' => ['except' => ''],
        'price_max' => ['except' => '']
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
        
        // Učitaj search parametre ako postoje
        if (request()->has('query')) {
            $this->query = request('query');
        }
        if (request()->has('city')) {
            $this->city = request('city');
        }
        if (request()->has('category')) {
            $this->search_category = request('category');
        }
        if (request()->has('condition')) {
            $this->condition_id = request('condition');
        }
        if (request()->has('price_min')) {
            $this->price_min = request('price_min');
        }
        if (request()->has('price_max')) {
            $this->price_max = request('price_max');
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

    public function setViewMode($mode)
    {
        $this->viewMode = $mode;
        $this->resetPage();
    }
    
    public function clearSearchFilters()
    {
        $this->reset(['query', 'city', 'search_category', 'condition_id', 'price_min', 'price_max']);
        $this->resetPage();
    }

    public function render()
    {
        $query = Listing::where('status', 'active')
            ->with(['category', 'condition', 'images', 'subcategory', 'user']);
            
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
        
        // Search filteri
        if ($this->query) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->query . '%')
                  ->orWhere('description', 'like', '%' . $this->query . '%');
            });
        }
        
        if ($this->city) {
            $query->where('location', 'like', '%' . $this->city . '%');
        }
        
        if ($this->search_category) {
            $query->where('category_id', $this->search_category);
        }
        
        if ($this->condition_id) {
            $query->where('condition_id', $this->condition_id);
        }
        
        if ($this->price_min) {
            $query->where('price', '>=', $this->price_min);
        }
        
        if ($this->price_max) {
            $query->where('price', '<=', $this->price_max);
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
        
        $conditions = ListingCondition::all();
            
        return view('livewire.listings.index', [
            'listings' => $listings,
            'categories' => $this->categories,
            'categoryTree' => $this->categoryTree,
            'currentCategory' => $this->currentCategory,
            'conditions' => $conditions
        ])->layout('layouts.app');
    }
}