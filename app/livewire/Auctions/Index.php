<?php

namespace App\Livewire\Auctions;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Auction;
use App\Models\Category;
use App\Traits\HasViewMode;

class Index extends Component
{
    use WithPagination, HasViewMode;

    public $sortBy = 'ending_soon'; // ending_soon, newest, highest_price, most_bids
    public $perPage = 20;
    public $selectedCategory = null;
    public $selectedSubcategory = null;
    public $categories;
    public $subcategories = [];
    public $currentCategory = null;

    protected $queryString = [
        'sortBy' => ['except' => 'ending_soon'],
        'perPage' => ['except' => 20],
        'selectedCategory' => ['except' => ''],
        'selectedSubcategory' => ['except' => '']
    ];

    public function mount()
    {
        $this->mountHasViewMode(); // Initialize view mode from session

        // Load categories
        $this->categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Load current category if selected
        if ($this->selectedCategory) {
            $this->currentCategory = Category::with('parent')->find($this->selectedCategory);
            // Load subcategories for the selected category
            $this->subcategories = Category::where('parent_id', $this->selectedCategory)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
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

    public function updatedSelectedCategory()
    {
        $this->currentCategory = Category::with('parent')->find($this->selectedCategory);
        $this->selectedSubcategory = null; // Reset subcategory when category changes

        // Load subcategories for the selected category
        if ($this->selectedCategory) {
            $this->subcategories = Category::where('parent_id', $this->selectedCategory)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        } else {
            $this->subcategories = [];
        }

        $this->resetPage();
    }

    public function updatedSelectedSubcategory()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Active auctions query
        $activeQuery = Auction::with(['listing.images', 'listing.user', 'listing.category', 'winningBid.user'])
            ->where('status', 'active')
            ->where(function($query) {
                $query->where('starts_at', '<=', now())
                      ->where('ends_at', '>', now());
            });

        // Apply category filter
        if ($this->selectedSubcategory) {
            // If subcategory is selected, filter by subcategory
            $subcategory = Category::find($this->selectedSubcategory);

            if ($subcategory) {
                $subcategoryIds = [$subcategory->id];
                $subcategoryIds = array_merge($subcategoryIds, $subcategory->children->pluck('id')->toArray());

                $activeQuery->whereHas('listing', function($q) use ($subcategoryIds) {
                    $q->where(function($subQ) use ($subcategoryIds) {
                        $subQ->whereIn('category_id', $subcategoryIds)
                             ->orWhereIn('subcategory_id', $subcategoryIds);
                    });
                });
            }
        } elseif ($this->selectedCategory) {
            // If only category is selected, filter by category and its children
            $category = Category::find($this->selectedCategory);

            if ($category) {
                $categoryIds = [$category->id];
                $categoryIds = array_merge($categoryIds, $category->children->pluck('id')->toArray());

                $activeQuery->whereHas('listing', function($q) use ($categoryIds) {
                    $q->where(function($subQ) use ($categoryIds) {
                        $subQ->whereIn('category_id', $categoryIds)
                             ->orWhereIn('subcategory_id', $categoryIds);
                    });
                });
            }
        }

        // Sorting for active auctions
        switch ($this->sortBy) {
            case 'ending_soon':
                $activeQuery->orderBy('ends_at', 'asc');
                break;
            case 'newest':
                $activeQuery->orderBy('created_at', 'desc');
                break;
            case 'highest_price':
                $activeQuery->orderBy('current_price', 'desc');
                break;
            case 'most_bids':
                $activeQuery->orderBy('total_bids', 'desc');
                break;
            default:
                $activeQuery->orderBy('ends_at', 'asc');
                break;
        }

        $auctions = $activeQuery->paginate($this->perPage);

        // Scheduled auctions (not yet started)
        $scheduledQuery = Auction::with(['listing.images', 'listing.user', 'listing.category'])
            ->where('status', 'active')
            ->where('starts_at', '>', now());

        // Apply category filter to scheduled auctions
        if ($this->selectedCategory) {
            $category = Category::find($this->selectedCategory);

            if ($category) {
                $categoryIds = [$category->id];
                $categoryIds = array_merge($categoryIds, $category->children->pluck('id')->toArray());

                $scheduledQuery->whereHas('listing', function($q) use ($categoryIds) {
                    $q->where(function($subQ) use ($categoryIds) {
                        $subQ->whereIn('category_id', $categoryIds)
                             ->orWhereIn('subcategory_id', $categoryIds);
                    });
                });
            }
        }

        $scheduledAuctions = $scheduledQuery->orderBy('starts_at', 'asc')->get();

        // Ended auctions (last 5)
        $endedQuery = Auction::with(['listing.images', 'listing.user', 'listing.category', 'winningBid.user', 'winner'])
            ->where(function($query) {
                $query->where('status', 'ended')
                      ->orWhere('ends_at', '<=', now());
            });

        // Apply category filter to ended auctions
        if ($this->selectedCategory) {
            $category = Category::find($this->selectedCategory);

            if ($category) {
                $categoryIds = [$category->id];
                $categoryIds = array_merge($categoryIds, $category->children->pluck('id')->toArray());

                $endedQuery->whereHas('listing', function($q) use ($categoryIds) {
                    $q->where(function($subQ) use ($categoryIds) {
                        $subQ->whereIn('category_id', $categoryIds)
                             ->orWhereIn('subcategory_id', $categoryIds);
                    });
                });
            }
        }

        $endedAuctions = $endedQuery->orderBy('ends_at', 'desc')->limit(5)->get();

        return view('livewire.auctions.index', [
            'auctions' => $auctions,
            'scheduledAuctions' => $scheduledAuctions,
            'endedAuctions' => $endedAuctions
        ])->layout('layouts.app');
    }
}