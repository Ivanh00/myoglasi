<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ServiceCategory;
use App\Models\BusinessCategory;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    public function getListingSubcategories($categoryId)
    {
        $subcategories = Category::where('parent_id', $categoryId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'name', 'icon']);

        return response()->json($subcategories);
    }

    public function getServiceSubcategories($categoryId)
    {
        $subcategories = ServiceCategory::where('parent_id', $categoryId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'name', 'icon']);

        return response()->json($subcategories);
    }

    public function getBusinessSubcategories($categoryId)
    {
        $subcategories = BusinessCategory::where('parent_id', $categoryId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'name', 'icon']);

        return response()->json($subcategories);
    }
}