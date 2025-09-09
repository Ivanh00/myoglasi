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
        try {
            $listing = Listing::where('id', $id)
                ->where('user_id', auth()->id())
                ->with(['auction.bids.user'])
                ->firstOrFail();
                
            if (!$listing->auction) {
                session()->flash('error', 'Ovaj oglas nije na aukciji.');
                return;
            }

            $auction = $listing->auction;

            // Check if current price exceeds starting price (minimum protection rule)
            // Only applies to regular users, not admins
            if (!auth()->user()->is_admin && $auction->current_price > $auction->starting_price) {
                session()->flash('error', 'Ne možete ukloniti aukciju jer je trenutna cena (' . 
                    number_format($auction->current_price, 0, ',', '.') . 
                    ' RSD) veća od početne cene aukcije (' . 
                    number_format($auction->starting_price, 0, ',', '.') . ' RSD).');
                return;
            }
            
            // Send notifications to all bidders before deleting
            if ($auction->bids->count() > 0) {
                $this->notifyBiddersOfAuctionCancellation($auction, $listing);
            }
            
            // Delete all auction data
            $auction->bids()->delete();
            $auction->delete();
            
            session()->flash('success', 'Aukcija je uspešno uklonjena. Oglas je vraćen u redovan prodajni režim.');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Greška pri uklanjanju iz aukcije: ' . $e->getMessage());
        }
    }

    private function notifyBiddersOfAuctionCancellation($auction, $listing)
    {
        // Get all unique bidders
        $bidders = $auction->bids()->with('user')->get()->unique('user_id');
        
        foreach ($bidders as $bid) {
            \App\Models\Message::create([
                'sender_id' => 1, // System
                'receiver_id' => $bid->user_id,
                'listing_id' => $listing->id,
                'message' => "Aukcija za '{$listing->title}' je otkazana od strane vlasnika oglasa. " . 
                            "Oglas možete i dalje pronaći u njihovim oglasima po ceni od " . 
                            number_format($listing->price, 2, ',', '.') . ' RSD.',
                'subject' => 'Aukcija otkazana - ' . $listing->title,
                'is_system_message' => true,
                'is_read' => false
            ]);
        }
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