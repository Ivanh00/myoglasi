<?php

namespace App\Livewire\Listings;

use Livewire\Component;
use App\Models\Listing;
use App\Models\Setting;
use Livewire\WithPagination;

class MyListings extends Component
{
    use WithPagination;
    
    public $filter = 'all'; // all, active, expired
    
    public function deleteListing($id)
    {
        $listing = Listing::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
            
        $listing->delete();
        
        session()->flash('message', 'Oglas je uspešno obrisan.');
    }

    public function removeFromAuction($id)
    {
        $listing = Listing::where('id', $id)
            ->where('user_id', auth()->id())
            ->with('auction')
            ->firstOrFail();
            
        if (!$listing->auction) {
            session()->flash('error', 'Ovaj oglas nije na aukciji.');
            return;
        }

        // Don't allow removal if auction has bids
        if ($listing->auction->total_bids > 0) {
            session()->flash('error', 'Ne možete ukloniti oglas iz aukcije koja ima ponude.');
            return;
        }
        
        // Delete all auction data (bids should be 0, but just in case)
        $listing->auction->bids()->delete();
        $listing->auction->delete();
        
        session()->flash('success', 'Oglas je uspešno uklonjen iz aukcije.');
    }

    public function renewListing($id)
    {
        $listing = Listing::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
            
        if (!$listing->canBeRenewed()) {
            session()->flash('error', 'Ovaj oglas ne može biti obnovljen.');
            return;
        }
        
        $user = auth()->user();
        
        // Check if user can renew based on their plan
        if ($user->payment_plan === 'per_listing') {
            $fee = Setting::get('listing_fee_amount', 10);
            if ($user->balance < $fee) {
                session()->flash('error', 'Nemate dovoljno kredita za obnavljanje oglasa. Potrebno: ' . number_format($fee, 0, ',', '.') . ' RSD');
                return redirect()->route('balance.payment-options');
            }
        } elseif (in_array($user->payment_plan, ['monthly', 'yearly'])) {
            if (!$user->hasActivePlan()) {
                session()->flash('error', 'Vaš plan je istekao. Molimo obnovite plan ili promenite na plaćanje po oglasu.');
                return redirect()->route('balance.plan-selection');
            }
        }
        
        if ($listing->renewListing()) {
            session()->flash('success', 'Oglas je uspešno obnovljen i važi narednih 60 dana!');
        } else {
            session()->flash('error', 'Greška pri obnavljanju oglasa. Molimo pokušajte ponovo.');
        }
    }

    public function render()
    {
        $query = Listing::where('user_id', auth()->id())
            ->with(['category', 'condition', 'images', 'auction.bids', 'auction.winner']);
            
        // Apply filters
        if ($this->filter === 'active') {
            $query->where('status', 'active')
                  ->where(function ($q) {
                      $q->whereNull('expires_at')
                        ->orWhere('expires_at', '>', now());
                  });
        } elseif ($this->filter === 'expired') {
            $query->where(function ($q) {
                $q->where('status', 'expired')
                  ->orWhere(function ($subQ) {
                      $subQ->where('status', 'active')
                           ->where('expires_at', '<', now());
                  });
            });
        }
        
        $listings = $query->orderBy('created_at', 'desc')->paginate(10);
            
        return view('livewire.listings.my-listings', [
            'listings' => $listings
        ])->layout('layouts.app');
    }
}