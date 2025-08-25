<?php

namespace App\Livewire\Listings;

use Livewire\Component;
use App\Models\Listing;

class Show extends Component
{
    public $listing;
    public $recommendedListings;
    public $recommendationType; // 'seller' ili 'similar'

    public function mount($listing)
    {
        // Ako je prosleđen slug, nađi listing po slug-u
        if (is_string($listing)) {
            $this->listing = Listing::where('slug', $listing)
                ->with(['category', 'subcategory', 'condition', 'images', 'user'])
                ->firstOrFail();
        } 
        // Ako je prosleđen Listing model
        else if ($listing instanceof Listing) {
            $this->listing = $listing->load(['category', 'subcategory', 'condition', 'images', 'user']);
        }
        
        // Povećaj broj pregleda
        if ($this->listing) {
            $this->listing->increment('views');
            $this->loadRecommendedListings();
        }
    }

    protected function loadRecommendedListings()
    {
        // Prvo pokušajte da nađete oglase istog prodavca
        $sellerListings = Listing::where('user_id', $this->listing->user_id)
            ->where('id', '!=', $this->listing->id)
            ->where('status', 'active')
            ->with(['category', 'condition', 'images'])
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        if ($sellerListings->count() > 0) {
            $this->recommendedListings = $sellerListings;
            $this->recommendationType = 'seller';
        } else {
            // Ako nema oglasa istog prodavca, uzmite slične oglase
            $this->recommendedListings = Listing::where('id', '!=', $this->listing->id)
                ->where(function($query) {
                    $query->where('category_id', $this->listing->category_id)
                          ->orWhere('subcategory_id', $this->listing->subcategory_id);
                })
                ->where('status', 'active')
                ->with(['category', 'condition', 'images'])
                ->orderBy('created_at', 'desc')
                ->take(4)
                ->get();
                
            $this->recommendationType = 'similar';
        }
    }

    public function render()
    {
        return view('livewire.listings.show', [
            'listing' => $this->listing,
            'recommendedListings' => $this->recommendedListings ?? collect(),
            'recommendationType' => $this->recommendationType ?? null
        ])->layout('layouts.app');
    }
}