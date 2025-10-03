<?php
// app/Livewire/Favorites/Index.php

namespace App\Livewire\Favorites;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\Favorite;

class Index extends Component
{
    use WithPagination;

    public $sortBy = 'newest'; // newest, oldest, price_asc, price_desc
    public $filterType = 'all'; // all, listings, services, businesses

    protected $listeners = [
        'favoriteRemoved' => '$refresh'
    ];

    public function removeFromFavorites($listingId, $type = 'listing')
    {
        if (!Auth::check()) {
            return;
        }

        if ($type === 'listing') {
            $favorite = Favorite::where('user_id', Auth::id())
                               ->where('listing_id', $listingId)
                               ->first();

            if ($favorite) {
                $favorite->delete();
                session()->flash('success', 'Oglas je uklonjen iz omiljenih.');
            }
        } else if ($type === 'service') {
            Auth::user()->removeServiceFromFavorites(\App\Models\Service::find($listingId));
            session()->flash('success', 'Usluga je uklonjena iz omiljenih.');
        } else if ($type === 'business') {
            Auth::user()->removeBusinessFromFavorites(\App\Models\Business::find($listingId));
            session()->flash('success', 'Biznis kartica je uklonjena iz omiljenih.');
        }

        $this->dispatch('favoriteRemoved');
    }

    public function setSortBy($sort)
    {
        $this->sortBy = $sort;
        $this->resetPage();
    }

    public function setFilterType($type)
    {
        $this->filterType = $type;
        $this->resetPage();
    }

    public function getFavoritesProperty()
    {
        if (!Auth::check()) {
            return collect([]);
        }

        $favorites = collect();

        // Get favorite listings
        if ($this->filterType === 'all' || $this->filterType === 'listings') {
            $listings = Auth::user()->favoriteListings()
                                   ->with(['images', 'category', 'user'])
                                   ->where('status', 'active')
                                   ->get()
                                   ->map(function($listing) {
                                       $listing->item_type = 'listing';
                                       $listing->sort_price = $listing->price;
                                       $listing->sort_date = $listing->pivot->created_at;
                                       return $listing;
                                   });
            $favorites = $favorites->concat($listings);
        }

        // Get favorite services
        if ($this->filterType === 'all' || $this->filterType === 'services') {
            $services = Auth::user()->serviceFavorites()
                                   ->with(['images', 'category', 'user'])
                                   ->where('status', 'active')
                                   ->get()
                                   ->map(function($service) {
                                       $service->item_type = 'service';
                                       $service->sort_price = $service->price_type === 'negotiable' ? 0 : $service->price;
                                       $service->sort_date = $service->pivot->created_at;
                                       return $service;
                                   });
            $favorites = $favorites->concat($services);
        }

        // Get favorite businesses
        if ($this->filterType === 'all' || $this->filterType === 'businesses') {
            $businesses = Auth::user()->businessFavorites()
                                     ->with(['business.images', 'business.category', 'business.user'])
                                     ->whereHas('business', function($query) {
                                         $query->where('status', 'active');
                                     })
                                     ->get()
                                     ->map(function($favorite) {
                                         $business = $favorite->business;
                                         $business->item_type = 'business';
                                         $business->title = $business->name; // Map name to title for consistency
                                         $business->sort_price = 0; // Businesses don't have price
                                         $business->sort_date = $favorite->created_at;
                                         $business->pivot = (object)['created_at' => $favorite->created_at];
                                         return $business;
                                     });
            $favorites = $favorites->concat($businesses);
        }

        // Sort the combined collection
        switch ($this->sortBy) {
            case 'oldest':
                $favorites = $favorites->sortBy('sort_date');
                break;
            case 'price_asc':
                $favorites = $favorites->sortBy('sort_price');
                break;
            case 'price_desc':
                $favorites = $favorites->sortByDesc('sort_price');
                break;
            default: // newest
                $favorites = $favorites->sortByDesc('sort_date');
                break;
        }

        // Manual pagination
        $perPage = 12;
        $page = request()->get('page', 1);
        $total = $favorites->count();

        $items = $favorites->slice(($page - 1) * $perPage, $perPage)->values();

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => request()->url()]
        );
    }

    public function render()
    {
        return view('livewire.favorites.index', [
            'favorites' => $this->favorites
        ])->layout('layouts.app');
    }
}