<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listing;
use App\Models\Category;
use App\Models\ListingCondition;


class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = Listing::query()->with('category', 'condition', 'user');
        
        // Filtriraj po tekstu (naslov ili opis)
        if ($request->has('query') && !empty($request->query)) {
            $searchTerm = $request->input('query');
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }
        
        // Filtriraj po gradu
        if ($request->has('city') && !empty($request->city)) {
            $query->where('location', $request->city);
        }
        
        // Filtriraj po kategoriji
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category_id', $request->category);
        }
        
        // Filtriraj po stanju
        if ($request->has('condition') && !empty($request->condition)) {
            $query->where('condition_id', $request->condition);
        }
        
        // Filtriraj po ceni (od)
        if ($request->has('price_min') && !empty($request->price_min)) {
            $query->where('price', '>=', $request->price_min);
        }
        
        // Filtriraj po ceni (do)
        if ($request->has('price_max') && !empty($request->price_max)) {
            $query->where('price', '<=', $request->price_max);
        }
        
        // Filtriraj samo aktivne oglase
        $query->where('status', 'active');
        
        // Sortiraj po datumu (najnoviji prvi)
        $query->orderBy('created_at', 'desc');

        // Za debug - prikazi SQL upit
if (app()->environment('local')) {
    logger($query->toSql());
    logger($query->getBindings());
}
        
        $listings = $query->paginate(20);
        
        // Uzmi kategorije i uslove za filtere
        $categories = Category::whereNull('parent_id')->get();
        $conditions = ListingCondition::all();
        
        return view('search.index', compact('listings', 'categories', 'conditions'));
    }
}