<?php

namespace App\Livewire\Auctions;

use Livewire\Component;
use App\Models\Auction;
use App\Models\Bid;
use App\Models\Message;

class Show extends Component
{
    public $auction;
    public $bidAmount = '';
    public $isAutoBid = false;
    public $maxBidAmount = '';
    public $showBidForm = false;

    protected $listeners = ['refreshAuction' => '$refresh'];

    protected $rules = [
        'bidAmount' => 'required|numeric|min:1',
        'maxBidAmount' => 'required_if:isAutoBid,true|nullable|numeric|gt:bidAmount'
    ];

    public function mount(Auction $auction)
    {
        $this->auction = $auction->load(['listing.images', 'listing.user', 'seller', 'bids.user']);
        
        // Set minimum bid amount
        $this->bidAmount = $auction->minimum_bid;
    }

    public function placeBid()
    {
        $this->validate([
            'bidAmount' => 'required|numeric|min:' . $this->auction->minimum_bid
        ]);

        if (!$this->auction->canBid()) {
            session()->flash('error', 'Aukcija nije aktivna za ponude.');
            return;
        }

        if (auth()->id() === $this->auction->user_id) {
            session()->flash('error', 'Ne možete licitirati na svojoj aukciji.');
            return;
        }

        try {
            Bid::placeBid(
                $this->auction->id,
                auth()->id(),
                $this->bidAmount,
                $this->isAutoBid,
                $this->isAutoBid ? $this->maxBidAmount : null
            );

            // Send notification to previous highest bidder
            $this->sendOutbidNotification();

            // Refresh auction data
            $this->auction = $this->auction->fresh(['bids.user']);
            $this->bidAmount = $this->auction->minimum_bid;
            $this->maxBidAmount = '';
            $this->isAutoBid = false;
            $this->showBidForm = false;

            // Emit event for JavaScript to handle
            $this->dispatch('bid-placed');
            
            session()->flash('success', 'Vaša ponuda je uspešno postavljena!');

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function buyNow()
    {
        if (!$this->auction->buy_now_price) {
            session()->flash('error', 'Kupi odmah opcija nije dostupna.');
            return;
        }

        try {
            Bid::placeBid(
                $this->auction->id,
                auth()->id(),
                $this->auction->buy_now_price
            );

            // End auction immediately
            $this->auction->update([
                'status' => 'ended',
                'winner_id' => auth()->id()
            ]);

            session()->flash('success', 'Čestitamo! Uspešno ste kupili proizvod.');
            
            // Send notification to seller
            $this->sendSoldNotification();

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    private function sendOutbidNotification()
    {
        $previousWinningBid = $this->auction->bids()
            ->where('user_id', '!=', auth()->id())
            ->where('is_winning', false)
            ->latest()
            ->first();

        if ($previousWinningBid) {
            Message::create([
                'sender_id' => 1, // System
                'receiver_id' => $previousWinningBid->user_id,
                'listing_id' => $this->auction->listing_id,
                'message' => "Vaša ponuda za '{$this->auction->listing->title}' je nadmašena. Nova najviša ponuda: " . 
                            number_format($this->bidAmount, 0, ',', '.') . ' RSD.',
                'subject' => 'Ponuda nadmašena - ' . $this->auction->listing->title,
                'is_system_message' => true,
                'is_read' => false
            ]);
        }
    }

    private function sendSoldNotification()
    {
        Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $this->auction->user_id,
            'listing_id' => $this->auction->listing_id,
            'message' => "Vaš proizvod '{$this->auction->listing->title}' je prodat putem aukcije za " . 
                        number_format($this->auction->buy_now_price, 0, ',', '.') . ' RSD.',
            'subject' => 'Proizvod prodat - ' . $this->auction->listing->title,
            'is_system_message' => true,
            'is_read' => false
        ]);
    }

    public function render()
    {
        return view('livewire.auctions.show')
            ->layout('layouts.app');
    }
}