<?php

namespace App\Livewire\Categories;

use Livewire\Component;
use App\Models\Category;

class Index extends Component
{
    public function render()
    {
        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->withCount(['listings' => function($query) {
                $query->where('status', 'active');
            }])
            ->orderBy('sort_order')
            ->get();

        return view('livewire.categories.index', compact('categories'))
            ->layout('layouts.app');
    }
}
