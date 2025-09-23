<?php

namespace App\Livewire\Services;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Traits\HasViewMode;

class Index extends Component
{
    use WithPagination, HasViewMode;

    public $selectedCategory = null;
    public $selectedSubcategory = null;
    public $categories;
    public $subcategories = [];
    public $sortBy = 'newest';
    public $perPage = 20;

    protected $queryString = [
        'selectedCategory' => ['except' => ''],
        'selectedSubcategory' => ['except' => ''],
        'sortBy' => ['except' => 'newest'],
        'perPage' => ['except' => 20]
    ];

    public function mount()
    {
        $this->mountHasViewMode(); // Initialize view mode from session

        // Get selectedCategory from URL parameter
        $this->selectedCategory = request()->get('selectedCategory');
        $this->selectedSubcategory = request()->get('selectedSubcategory');

        $this->categories = ServiceCategory::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Load subcategories if category is selected
        if ($this->selectedCategory) {
            $this->subcategories = ServiceCategory::where('parent_id', $this->selectedCategory)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        }
    }

    public function setCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
        $this->selectedSubcategory = null; // Reset subcategory when category changes

        // Load subcategories for the selected category
        if ($this->selectedCategory) {
            $this->subcategories = ServiceCategory::where('parent_id', $this->selectedCategory)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        } else {
            $this->subcategories = [];
        }

        $this->resetPage();
    }

    public function setSubcategory($subcategoryId)
    {
        $this->selectedSubcategory = $subcategoryId;
        $this->resetPage();
    }

    public function setSorting($sort)
    {
        $this->sortBy = $sort;
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }


    public function render()
    {
        $query = Service::where('status', 'active')
            ->with(['category', 'subcategory', 'images', 'user', 'promotions']);

        $currentCategory = null;
        if ($this->selectedSubcategory) {
            // If subcategory is selected, filter by subcategory
            $subcategory = ServiceCategory::find($this->selectedSubcategory);
            $currentCategory = $subcategory;

            if ($subcategory) {
                // Get all category IDs (including children)
                $subcategoryIds = [$subcategory->id];
                foreach ($subcategory->children as $child) {
                    $subcategoryIds[] = $child->id;
                }

                $query->where(function($q) use ($subcategoryIds) {
                    $q->whereIn('service_category_id', $subcategoryIds)
                      ->orWhereIn('subcategory_id', $subcategoryIds);
                });
            }
        } elseif ($this->selectedCategory) {
            // If only category is selected, filter by category and its children
            $category = ServiceCategory::find($this->selectedCategory);
            $currentCategory = $category;

            if ($category) {
                // Get all category IDs (including children)
                $categoryIds = [$category->id];
                foreach ($category->children as $child) {
                    $categoryIds[] = $child->id;
                }

                $query->where(function($q) use ($categoryIds) {
                    $q->whereIn('service_category_id', $categoryIds)
                      ->orWhereIn('subcategory_id', $categoryIds);
                });
            }
        }

        // Sorting
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

        $services = $query->paginate($this->perPage);

        return view('livewire.services.index', [
            'services' => $services,
            'categories' => $this->categories,
            'currentCategory' => $currentCategory
        ])->layout('layouts.app');
    }
}
