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

    protected $listeners = [
        'favoriteRemoved' => '$refresh'
    ];

    public function removeFromFavorites($listingId)
    {
        if (!Auth::check()) {
            return;
        }

        $favorite = Favorite::where('user_id', Auth::id())
                           ->where('listing_id', $listingId)
                           ->first();

        if ($favorite) {
            $favorite->delete();
            session()->flash('success', 'Oglas je uklonjen iz omiljenih.');
        }

        $this->dispatch('favoriteRemoved');
    }

    public function setSortBy($sort)
    {
        $this->sortBy = $sort;
        $this->resetPage();
    }

    public function getFavoritesProperty()
    {
        if (!Auth::check()) {
            return collect([]);
        }

        $query = Auth::user()->favoriteListings()
                           ->with(['images', 'category', 'user'])
                           ->where('status', 'active');

        switch ($this->sortBy) {
            case 'oldest':
                $query->orderBy('favorites.created_at', 'asc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            default: // newest
                $query->orderBy('favorites.created_at', 'desc');
                break;
        }

        return $query->paginate(12);
    }

    public function render()
    {
        return view('livewire.favorites.index', [
            'favorites' => $this->favorites
        ])->layout('layouts.app');
    }
}