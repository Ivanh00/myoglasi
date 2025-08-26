<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\ListingCondition;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        // Proveri da li ima filtera
        $hasFilters = $request->anyFilled(['query', 'city', 'category', 'condition', 'price_min', 'price_max']);
        
        // Ako nema filtera, redirect na listings.index
        if (!$hasFilters) {
            return redirect()->route('listings.index');
        }
        
        // Kreiraj query string za Livewire komponentu
        $queryParams = [];
        if ($request->filled('query')) $queryParams['query'] = $request->input('query');
        if ($request->filled('city')) $queryParams['city'] = $request->input('city');
        if ($request->filled('category')) $queryParams['search_category'] = $request->input('category');
        if ($request->filled('condition')) $queryParams['condition_id'] = $request->input('condition');
        if ($request->filled('price_min')) $queryParams['price_min'] = $request->input('price_min');
        if ($request->filled('price_max')) $queryParams['price_max'] = $request->input('price_max');
        
        // Redirect na listings.index sa search parametrima
        return redirect()->route('listings.index', $queryParams);
    }
}