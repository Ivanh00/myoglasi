<?php

namespace App\Livewire\Auctions;

use Livewire\Component;
use App\Models\Auction;
use Livewire\WithPagination;

class MyAuctions extends Component
{
    use WithPagination;
    
    public $filter = 'all'; // all, active, ended
    
    public function removeFromAuction($id)
    {
        try {
            $auction = Auction::where('id', $id)
                ->where('user_id', auth()->id())
                ->with(['bids.user', 'listing'])
                ->firstOrFail();

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
                $this->notifyBiddersOfAuctionCancellation($auction);
            }
            
            // Delete all auction data
            $auction->bids()->delete();
            $auction->delete();
            
            session()->flash('success', 'Aukcija je uspešno uklonjena. Oglas je vraćen u redovan prodajni režim.');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Greška pri uklanjanju iz aukcije: ' . $e->getMessage());
        }
    }

    private function notifyBiddersOfAuctionCancellation($auction)
    {
        // Get all unique bidders
        $bidders = $auction->bids()->with('user')->get()->unique('user_id');
        
        foreach ($bidders as $bid) {
            \App\Models\Message::create([
                'sender_id' => 1, // System
                'receiver_id' => $bid->user_id,
                'listing_id' => $auction->listing_id,
                'message' => "Aukcija za '{$auction->listing->title}' je otkazana od strane vlasnika oglasa. " . 
                            "Oglas možete i dalje pronaći u njihovim oglasima po ceni od " . 
                            number_format($auction->listing->price, 2, ',', '.') . ' RSD.',
                'subject' => 'Aukcija otkazana - ' . $auction->listing->title,
                'is_system_message' => true,
                'is_read' => false
            ]);
        }
    }

    public function render()
    {
        $query = Auction::where('user_id', auth()->id())
            ->with(['listing.images', 'listing.category', 'winningBid.user']);
            
        // Apply filters
        if ($this->filter === 'active') {
            $query->where('status', 'active');
        } elseif ($this->filter === 'ended') {
            $query->where('status', 'ended');
        }
        
        $auctions = $query->orderBy('created_at', 'desc')->paginate(10);
            
        return view('livewire.auctions.my-auctions', [
            'auctions' => $auctions
        ])->layout('layouts.app');
    }
}