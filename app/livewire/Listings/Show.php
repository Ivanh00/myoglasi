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
        
        // Check if listing is accessible (not deleted or inactive to public)
        if ($this->listing && $this->listing->status === 'inactive') {
            // Only allow access for admins and listing owner
            if (!auth()->check() || (!auth()->user()->is_admin && auth()->id() !== $this->listing->user_id)) {
                abort(404, 'Oglas nije dostupan.');
            }
        }
        
        // Track unique user view
        if ($this->listing) {
            $this->trackUniqueView();
            $this->loadRecommendedListings();
        }
    }

    protected function trackUniqueView()
    {
        $userId = auth()->id();
        $ipAddress = request()->ip();
        $sessionId = session()->getId();

        if ($userId) {
            // For logged-in users, check if they've already viewed this listing
            $existingView = \DB::table('listing_views')
                ->where('listing_id', $this->listing->id)
                ->where('user_id', $userId)
                ->first();

            if (!$existingView) {
                // Record the view
                \DB::table('listing_views')->insert([
                    'listing_id' => $this->listing->id,
                    'user_id' => $userId,
                    'ip_address' => $ipAddress,
                    'session_id' => $sessionId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Increment the view count
                $this->listing->increment('views');
            }
        } else {
            // For guests, check by session and IP combination
            $existingView = \DB::table('listing_views')
                ->where('listing_id', $this->listing->id)
                ->where('session_id', $sessionId)
                ->where('ip_address', $ipAddress)
                ->whereNull('user_id')
                ->first();

            if (!$existingView) {
                // Record the view
                \DB::table('listing_views')->insert([
                    'listing_id' => $this->listing->id,
                    'user_id' => null,
                    'ip_address' => $ipAddress,
                    'session_id' => $sessionId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Increment the view count
                $this->listing->increment('views');
            }
        }
    }

    protected function loadRecommendedListings()
    {
        if (auth()->check()) {
            // Za ulogovane korisnike - prikaži ostale oglase istog prodavca
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
                // Ako prodavac nema drugih oglasa, ne prikazuj ništa
                $this->recommendedListings = collect();
                $this->recommendationType = null;
            }
        } else {
            // Za neulogovane korisnike - prikaži slične oglase iz iste kategorije
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