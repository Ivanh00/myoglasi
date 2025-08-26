<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
{
    $query = Listing::query()->with('category', 'condition');
    
    // Pretraga po tekstu
    if ($request->has('query') && !empty($request->query)) {
        $query->where('title', 'like', '%' . $request->query . '%')
              ->orWhere('description', 'like', '%' . $request->query . '%');
    }
    
    // Filter po gradu
    if ($request->has('city') && !empty($request->city)) {
        $query->where('location', $request->city);
    }
    
    // Filter po kategoriji
    if ($request->has('category') && !empty($request->category)) {
        $query->where('category_id', $request->category);
    }
    
    // Filter po stanju
    if ($request->has('condition') && !empty($request->condition)) {
        $query->where('condition_id', $request->condition);
    }
    
    // Filter po ceni
    if ($request->has('price_min') && !empty($request->price_min)) {
        $query->where('price', '>=', $request->price_min);
    }
    
    if ($request->has('price_max') && !empty($request->price_max)) {
        $query->where('price', '<=', $request->price_max);
    }
    
    $listings = $query->paginate(20);
    
    return view('search.index', compact('listings'));
}
}
