<?php

namespace App\Livewire\Search;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Listing;
use App\Models\Auction;
use App\Models\Service;
use App\Models\Category;
use App\Models\ServiceCategory;
use App\Models\ListingCondition;

class UnifiedSearch extends Component
{
    use WithPagination;
    
    // Search parameters
    public $query = '';
    public $city = '';
    public $search_category = '';
    public $search_subcategory = ''; // For listing subcategories
    public $service_category = ''; // For service categories
    public $service_subcategory = ''; // For service subcategories
    public $condition_id = '';
    public $auction_type = '';
    public $price_min = '';
    public $price_max = '';
    public $content_type = 'all'; // all, listings, auctions, services
    
    // Display options
    public $viewMode = 'list';
    public $perPage = 20;
    public $sortBy = 'newest';
    public $show_filters = '';
    
    protected $queryString = [
        'query' => ['except' => ''],
        'city' => ['except' => ''],
        'search_category' => ['except' => ''],
        'search_subcategory' => ['except' => ''],
        'service_category' => ['except' => ''],
        'service_subcategory' => ['except' => ''],
        'condition_id' => ['except' => ''],
        'auction_type' => ['except' => ''],
        'content_type' => ['except' => 'all'],
        'price_min' => ['except' => ''],
        'price_max' => ['except' => ''],
        'viewMode' => ['except' => 'list'],
        'perPage' => ['except' => 20],
        'sortBy' => ['except' => 'newest'],
        'show_filters' => ['except' => '']
    ];

    public function mount()
    {
        // Initialize from request parameters
        $this->query = request('query', '');
        $this->city = request('city', '');
        $this->search_category = request('search_category', '');
        $this->condition_id = request('condition_id', '');
        $this->auction_type = request('auction_type', '');
        $this->content_type = request('content_type', 'all');
        $this->price_min = request('price_min', '');
        $this->price_max = request('price_max', '');
        $this->viewMode = request('viewMode', 'list');
        $this->perPage = request('perPage', 20);
        $this->sortBy = request('sortBy', 'newest');
    }

    public function setViewMode($mode)
    {
        $this->viewMode = $mode;
        $this->resetPage();
    }

    // Livewire lifecycle hooks - auto-trigger when properties change
    public function updatedAuctionType()
    {
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

    public function updatedServiceCategory()
    {
        $this->service_subcategory = ''; // Reset subcategory when category changes
        $this->resetPage();
    }

    public function updatedSearchCategory()
    {
        $this->search_subcategory = ''; // Reset subcategory when category changes
        $this->resetPage();
    }

    public function updatedSearchSubcategory()
    {
        $this->resetPage();
    }

    public function updatedServiceSubcategory()
    {
        $this->resetPage();
    }

    public function setServiceCategory($categoryId)
    {
        $this->service_category = $categoryId;
        $this->service_subcategory = ''; // Reset subcategory
        $this->resetPage();
    }

    public function updatedContentType()
    {
        // Reset type-specific filters when switching content types
        if ($this->content_type !== 'auctions') {
            $this->auction_type = '';
        }

        // Clear category filters when switching between listing types
        if ($this->content_type === 'services') {
            $this->search_category = '';  // Clear listing category
            $this->search_subcategory = ''; // Clear listing subcategory
        } elseif ($this->content_type === 'listings' || $this->content_type === 'giveaways') {
            $this->service_category = '';  // Clear service category
            $this->service_subcategory = ''; // Clear service subcategory
        }

        $this->resetPage();
    }

    public function render()
    {
        $results = collect();
        
        // Get listings if content_type is 'all' or 'listings'
        if (in_array($this->content_type, ['all', 'listings'])) {
            $listings = $this->getListings();
            $results = $results->merge($listings->items());
        }
        
        // Get services if content_type is 'all' or 'services'
        if (in_array($this->content_type, ['all', 'services'])) {
            $services = $this->getServices();
            $results = $results->merge($services->items());
        }
        
        // Get giveaways if content_type is 'all' or 'giveaways'
        if (in_array($this->content_type, ['all', 'giveaways'])) {
            $giveaways = $this->getGiveaways();
            $results = $results->merge($giveaways->items());
        }
        
        // Get auctions if content_type is 'all' or 'auctions'
        if (in_array($this->content_type, ['all', 'auctions'])) {
            $auctions = $this->getAuctions();
            // Transform auctions to unified format
            $auctionListings = $auctions->getCollection()->map(function ($auction) {
                $listing = $auction->listing;
                $listing->is_auction = true;
                $listing->auction_data = $auction;
                return $listing;
            });
            $results = $results->merge($auctionListings);
        }
        
        // Sort unified results
        $results = $this->sortUnifiedResults($results);
        
        // Paginate manually
        $perPage = $this->perPage;
        $page = request('page', 1);
        $offset = ($page - 1) * $perPage;
        $paginatedResults = $results->slice($offset, $perPage);
        
        // Create paginator
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedResults,
            $results->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'pageName' => 'page']
        );
        $paginator->withQueryString();

        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Get subcategories if a category is selected
        $subcategories = collect();
        if ($this->search_category) {
            $subcategories = Category::where('parent_id', $this->search_category)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        }

        $serviceCategories = ServiceCategory::whereNull('parent_id')
            ->where('is_active', true)
            ->with('children')
            ->orderBy('sort_order')
            ->get();

        // Get service subcategories if a service category is selected
        $serviceSubcategories = collect();
        if ($this->service_category) {
            $serviceSubcategories = ServiceCategory::where('parent_id', $this->service_category)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        }

        $conditions = ListingCondition::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('livewire.search.unified-search', [
            'results' => $paginator,
            'categories' => $categories,
            'subcategories' => $subcategories,
            'serviceCategories' => $serviceCategories,
            'serviceSubcategories' => $serviceSubcategories,
            'conditions' => $conditions
        ])->layout('layouts.app');
    }

    private function getListings()
    {
        $query = Listing::where('status', 'active')
            ->where(function($q) {
                $q->where('listing_type', 'listing')
                  ->orWhereNull('listing_type'); // For backward compatibility
            })
            ->whereDoesntHave('auction') // Exclude listings that have been converted to auctions
            ->with(['category', 'condition', 'images', 'subcategory', 'user']);

        $this->applyFiltersToQuery($query, 'listing');

        return $query->paginate($this->perPage);
    }

    private function getServices()
    {
        $query = Service::where('status', 'active')
            ->with(['category', 'subcategory', 'images', 'user', 'promotions']);

        // Apply service-specific filters
        if ($this->query) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->query . '%')
                  ->orWhere('description', 'like', '%' . $this->query . '%');
            });
        }

        if ($this->city) {
            $query->where('location', 'like', '%' . $this->city . '%');
        }

        if ($this->service_category) {
            // Get all category IDs including children
            $category = ServiceCategory::find($this->service_category);
            if ($category) {
                $categoryIds = collect([$category->id]);
                if ($category->children->count() > 0) {
                    $categoryIds = $categoryIds->merge($category->children->pluck('id'));
                }

                $query->where(function($q) use ($categoryIds) {
                    $q->whereIn('service_category_id', $categoryIds)
                      ->orWhereIn('subcategory_id', $categoryIds);
                });
            }
        }

        // Apply subcategory filter for services
        if ($this->service_subcategory) {
            $query->where('subcategory_id', $this->service_subcategory);
        }

        if ($this->price_min) {
            $query->where('price', '>=', $this->price_min);
        }

        if ($this->price_max) {
            $query->where('price', '<=', $this->price_max);
        }

        // Apply sorting
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

        return $query->paginate($this->perPage);
    }

    private function getGiveaways()
    {
        $query = Listing::where('status', 'active')
            ->where('listing_type', 'giveaway')
            ->with(['category', 'condition', 'images', 'subcategory', 'user']);
            
        $this->applyFiltersToQuery($query, 'listing');
        
        return $query->paginate($this->perPage);
    }

    private function getAuctions()
    {
        $query = Auction::where('status', 'active');

        // Filter based on auction type - separate scheduled from active
        if ($this->auction_type === 'scheduled') {
            // Only scheduled auctions (not yet started)
            $query->where('starts_at', '>', now());
        } else {
            // Only active auctions (started but not ended) - exclude scheduled
            $query->where('starts_at', '<=', now())
                  ->where('ends_at', '>', now());
        }

        $query->with(['listing.category', 'listing.condition', 'listing.images', 'listing.subcategory', 'listing.user', 'bids']);

        $this->applyFiltersToQuery($query, 'auction');

        // Auction-specific sorting
        $auctionType = $this->auction_type ?: 'ending_soon';

        switch ($auctionType) {
            case 'scheduled':
                // For scheduled auctions, sort by start date
                $query->orderBy('starts_at', 'asc');
                break;
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
            default:
                $query->orderBy('ends_at', 'asc');
                break;
        }
        
        return $query->paginate($this->perPage);
    }

    private function applyFiltersToQuery($query, $type)
    {
        if ($type === 'auction') {
            // Auction filters (through listing relationship)
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

            // Apply subcategory filter for auctions
            if ($this->search_subcategory) {
                $query->whereHas('listing', function($q) {
                    $q->where('subcategory_id', $this->search_subcategory);
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
        } else {
            // Listing filters (direct)
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

            // Apply subcategory filter for listings
            if ($this->search_subcategory) {
                $query->where('subcategory_id', $this->search_subcategory);
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
    }

    private function sortUnifiedResults($results)
    {
        return $results->sortBy(function ($item) {
            switch ($this->sortBy) {
                case 'price_asc':
                    return $item->price ?? $item->auction_data->current_price ?? 0;
                case 'price_desc':
                    return -($item->price ?? $item->auction_data->current_price ?? 0);
                case 'newest':
                default:
                    return -$item->created_at->timestamp;
            }
        })->values();
    }
}