<?php

namespace App\Livewire\Listings;

use Livewire\Component;
use App\Models\Listing;
use App\Models\Category;
use App\Models\ListingCondition;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Traits\HasViewMode;

class Index extends Component
{
    use WithPagination, HasViewMode;

    public $selectedCategory = null;
    public $categories;
    public $sortBy = 'newest';
    public $perPage = 20;
    public $categoryTree = [];
    public $currentCategory = null;
    
    // Search parametri
    public $query = '';
    public $city = '';
    public $search_category = '';
    public $condition_id = '';
    public $auction_type = '';
    public $price_min = '';
    public $price_max = '';

    protected $queryString = [
        'selectedCategory' => ['except' => ''],
        'sortBy' => ['except' => 'newest'],
        'perPage' => ['except' => 20],
        'query' => ['except' => ''],
        'city' => ['except' => ''],
        'search_category' => ['except' => ''],
        'condition_id' => ['except' => ''],
        'auction_type' => ['except' => ''],
        'price_min' => ['except' => ''],
        'price_max' => ['except' => '']
    ];

    public function mount()
    {
        $this->mountHasViewMode(); // Initialize view mode from session

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
        if (request()->has('auction_type')) {
            $this->auction_type = request('auction_type');
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

    
    public function clearSearchFilters()
    {
        $this->reset(['query', 'city', 'search_category', 'condition_id', 'auction_type', 'price_min', 'price_max']);
        $this->resetPage();
    }

    private function applyAuctionFilters($query)
    {
        // Apply filters to auction query (using listing relationship)
        if ($this->query) {
            $query->whereHas('listing', function($q) {
                $q->where('title', 'like', '%' . $this->query . '%')
                  ->orWhere('description', 'like', '%' . $this->query . '%');
            });
        }
        
        if ($this->city) {
            $query->whereHas('listing', function($q) {
                $q->where('location', 'like', '%' . $this->city . '%');
            });
        }
        
        if ($this->search_category) {
            $query->whereHas('listing', function($q) {
                $q->where('category_id', $this->search_category);
            });
        }
        
        if ($this->condition_id) {
            $query->whereHas('listing', function($q) {
                $q->where('condition_id', $this->condition_id);
            });
        }
        
        if ($this->price_min) {
            $query->where('current_price', '>=', $this->price_min);
        }
        
        if ($this->price_max) {
            $query->where('current_price', '<=', $this->price_max);
        }
    }

    private function applyListingFilters($query)
    {
        // Apply filters to regular listing query
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
    }

    public function render()
    {
        // Determine if we're filtering for auctions only
        if ($this->auction_type) {
            // Query auctions instead of regular listings
            $query = \App\Models\Auction::where('status', 'active')
                ->with(['listing.category', 'listing.condition', 'listing.images', 'listing.subcategory', 'listing.user', 'bids']);
        } else {
            // Regular listings query - EXCLUDE auctions and giveaways
            $query = Listing::whereIn('status', ['active', 'inactive'])
                ->whereDoesntHave('auction') // Exclude listings that have auctions
                ->where('listing_type', '!=', 'giveaway') // Exclude giveaways
                ->with(['category', 'condition', 'images', 'subcategory', 'user']);
        }
            
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
        
        // Apply filters based on query type
        if ($this->auction_type) {
            // Auction-specific filters
            $this->applyAuctionFilters($query);
            
            // Auction-specific sorting
            switch ($this->auction_type) {
                case 'ending_soon':
                    $query->orderBy('ends_at', 'asc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'highest_price':
                    $query->orderBy('current_price', 'desc');
                    break;
                case 'most_bids':
                    $query->orderBy('total_bids', 'desc');
                    break;
            }
            
            $auctions = $query->paginate($this->perPage);
            // Convert auctions to listings for unified view
            $listings = $auctions;
            $listings->getCollection()->transform(function ($auction) {
                return $auction->listing;
            });
        } else {
            // Regular listing filters
            $this->applyListingFilters($query);
            
            // Load promotions for sorting
            $query->with('promotions');
            
            // Add custom sorting for promoted listings using subqueries to avoid GROUP BY issues
            $query->addSelect([
                'has_featured_homepage' => DB::table('listing_promotions')
                    ->selectRaw('CASE WHEN COUNT(*) > 0 THEN 1 ELSE 0 END')
                    ->whereColumn('listing_promotions.listing_id', 'listings.id')
                    ->where('listing_promotions.type', 'featured_homepage')
                    ->where('listing_promotions.is_active', true)
                    ->where('listing_promotions.starts_at', '<=', now())
                    ->where('listing_promotions.expires_at', '>', now()),
                    
                'has_featured_category' => DB::table('listing_promotions')
                    ->selectRaw('CASE WHEN COUNT(*) > 0 THEN 1 ELSE 0 END')
                    ->whereColumn('listing_promotions.listing_id', 'listings.id')
                    ->where('listing_promotions.type', 'featured_category')
                    ->where('listing_promotions.is_active', true)
                    ->where('listing_promotions.starts_at', '<=', now())
                    ->where('listing_promotions.expires_at', '>', now())
            ]);
            
            // Regular sorting with promotion priority
            switch ($this->sortBy) {
                case 'price_asc':
                    $query->orderBy('has_featured_homepage', 'desc')
                          ->orderBy('has_featured_category', 'desc')
                          ->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('has_featured_homepage', 'desc')
                          ->orderBy('has_featured_category', 'desc')
                          ->orderBy('price', 'desc');
                    break;
                case 'newest':
                default:
                    $query->orderBy('has_featured_homepage', 'desc')
                          ->orderBy('has_featured_category', 'desc')
                          ->orderBy('listings.created_at', 'desc');
                    break;
            }
            
            $listings = $query->paginate($this->perPage);
        }
        
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