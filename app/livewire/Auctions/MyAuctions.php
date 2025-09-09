<?php

namespace App\Livewire\Auctions;

use Livewire\Component;
use App\Models\Auction;
use Livewire\WithPagination;

class MyAuctions extends Component
{
    use WithPagination;
    
    public $filter = 'all'; // all, active, ended
    
    public function deleteAuction($id)
    {
        $auction = Auction::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
            
        // Only allow deletion if auction hasn't started or has no bids
        if ($auction->isActive() && $auction->total_bids > 0) {
            session()->flash('error', 'Ne možete obrisati aukciju koja ima ponude.');
            return;
        }
            
        $auction->delete();
        
        session()->flash('message', 'Aukcija je uspešno obrisana.');
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