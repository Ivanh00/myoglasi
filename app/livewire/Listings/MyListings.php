<?php

namespace App\Livewire\Listings;

use Livewire\Component;
use App\Models\Listing;
use Livewire\WithPagination;

class MyListings extends Component
{
    use WithPagination;
    
    public function deleteListing($id)
    {
        $listing = Listing::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
            
        $listing->delete();
        
        session()->flash('message', 'Oglas je uspešno obrisan.');
    }

    public function render()
    {
        $listings = Listing::where('user_id', auth()->id())
            ->with(['category', 'condition', 'images'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('livewire.listings.my-listings', [
            'listings' => $listings
        ])->layout('layouts.app');
    }
}