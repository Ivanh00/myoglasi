<?php

namespace App\Livewire\Giveaways;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Listing;
use App\Models\Category;

class Index extends Component
{
    use WithPagination;
    
    public $selectedCategory = null;
    public $categories;
    public $sortBy = 'newest';
    public $perPage = 20;
    public $viewMode = 'list';

    public function mount()
    {
        $this->categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function setCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
        $this->resetPage();
    }

    public function setSorting($sort)
    {
        $this->sortBy = $sort;
        $this->resetPage();
    }

    public function setViewMode($mode)
    {
        $this->viewMode = $mode;
    }

    public function markAsTaken($giveawayId)
    {
        $giveaway = Listing::find($giveawayId);
        if ($giveaway && $giveaway->listing_type === 'giveaway' && auth()->check()) {
            $giveaway->update(['status' => 'taken']);
            session()->flash('success', 'Označeno kao uzeto!');
        }
    }

    public function render()
    {
        $query = Listing::where('status', 'active')
            ->where('listing_type', 'giveaway')
            ->with(['category', 'subcategory', 'images', 'user', 'condition']);
            
        if ($this->selectedCategory) {
            $category = Category::find($this->selectedCategory);
            
            if ($category) {
                $categoryIds = $category->getAllCategoryIds();
                $query->where(function($q) use ($categoryIds) {
                    $q->whereIn('category_id', $categoryIds)
                      ->orWhereIn('subcategory_id', $categoryIds);
                });
            }
        }
        
        // Sorting
        switch ($this->sortBy) {
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
        
        $giveaways = $query->paginate($this->perPage);
        
        return view('livewire.giveaways.index', [
            'giveaways' => $giveaways,
            'categories' => $this->categories
        ])->layout('layouts.app');
    }
}
