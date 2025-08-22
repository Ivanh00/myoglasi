<?php

namespace App\Services;


use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class CategoryService
{
    public function getPopularCategories()
    {
        return Cache::remember('popular_categories', 3600, function () {
            return Category::withCount('listings')
                ->orderBy('listings_count', 'desc')
                ->take(10)
                ->get();
        });
    }

    public function getAllCategories()
    {
        return Category::with('children')->whereNull('parent_id')->get();
    }

    public function getCategoriesWithListingCount()
    {
        return Category::withCount('listings')->get();
    }
}
