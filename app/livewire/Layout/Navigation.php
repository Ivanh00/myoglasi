<?php

namespace App\Livewire\Layout;

use Livewire\Component;
use App\Models\Category;
use App\Models\ListingCondition;

class Navigation extends Component
{
    public function render()
    {
        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'name']);

        $conditions = ListingCondition::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('livewire.layout.navigation', compact('categories', 'conditions'))
            ->layout('layouts.app');
    }
}
