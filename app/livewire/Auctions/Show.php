<?php

namespace App\Livewire\Auctions;

use Livewire\Component;
use App\Models\Auction;
use App\Models\Bid;
use App\Models\Message;
use App\Models\Listing;

class Show extends Component
{
    public $auction;
    public $bidAmount = '';
    public $isAutoBid = false;
    public $maxBidAmount = '';
    public $showBidForm = false;
    public $recommendedListings;
    public $recommendationType;
    public $isFavorited = false;
    public $isNotificationSet = false;

    protected $listeners = ['refreshAuction' => '$refresh'];

    protected $rules = [
        'bidAmount' => 'required|numeric|min:1',
        'maxBidAmount' => 'required_if:isAutoBid,true|nullable|numeric|gt:bidAmount'
    ];

    public function mount($auction)
    {
        // If admin, include soft-deleted auctions
        if (auth()->check() && auth()->user()->is_admin) {
            $this->auction = Auction::withTrashed()->findOrFail($auction);
        } else {
            $this->auction = Auction::findOrFail($auction);
        }

        $this->auction->load(['listing.images', 'listing.user', 'seller', 'bids.user']);

        // Set minimum bid amount
        $this->bidAmount = $this->auction->minimum_bid;

        // Check if user has favorited this auction listing
        if (auth()->check()) {
            $this->isFavorited = auth()->user()->hasFavorited($this->auction->listing);
            // Check if user has set notification for auction start
            $this->isNotificationSet = \App\Models\AuctionNotification::where('auction_id', $this->auction->id)
                ->where('user_id', auth()->id())
                ->exists();
        }

        // Check if user has existing auto-bid
        if (auth()->check()) {
            $existingAutoBid = Bid::where('auction_id', $this->auction->id)
                ->where('user_id', auth()->id())
                ->where('is_auto_bid', true)
                ->whereNotNull('max_bid')
                ->latest()
                ->first();

            if ($existingAutoBid) {
                $this->isAutoBid = true;
                $this->maxBidAmount = $existingAutoBid->max_bid;
            }
        }

        // Load recommended listings
        $this->loadRecommendedListings();
    }

    public function placeBid()
    {
        // Prevent manual bidding when auto-bid is enabled
        if ($this->isAutoBid) {
            session()->flash('error', 'Ne možete ručno licitirati kada je uključena automatska ponuda. Koristite "Postavi automatsku ponudu" dugme.');
            return;
        }
        
        // Auto-bid priority logic is now handled in Bid::placeBid() method

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
            
            // Send notification to listing owner
            $this->sendBidNotificationToOwner();
            
            // Trigger auto-bid response to manual bids (separate from auto-bid setup)
            $this->processManualBidResponse();

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
    
    public function removeAutoBid()
    {
        if (!auth()->check()) return;
        
        // Only remove auto-bid settings, preserve history bids
        // Set max_bid to null to deactivate auto-bidding
        Bid::where('auction_id', $this->auction->id)
            ->where('user_id', auth()->id())
            ->where('is_auto_bid', true)
            ->whereNotNull('max_bid')
            ->update(['max_bid' => null]);
            
        $this->isAutoBid = false;
        $this->maxBidAmount = '';
        
        session()->flash('info', 'Automatska ponuda je deaktivirana. Istorija ponuda je očuvana.');
    }
    
    private function processManualBidResponse()
    {
        // Only respond to manual bids, not auto-bid setup
        if (!$this->isAutoBid) {
            Bid::processAutoBids($this->auction->fresh(), auth()->id());
        }
    }

    public function toggleFavorite()
    {
        if (!auth()->check()) {
            session()->flash('error', 'Morate se prijaviti da biste dodali aukciju u omiljene.');
            return;
        }

        if (auth()->id() === $this->auction->user_id) {
            session()->flash('error', 'Ne možete dodati svoju aukciju u omiljene.');
            return;
        }

        if ($this->isFavorited) {
            auth()->user()->removeFromFavorites($this->auction->listing);
            $this->isFavorited = false;
            session()->flash('success', 'Aukcija je uklonjena iz omiljenih.');
        } else {
            auth()->user()->addToFavorites($this->auction->listing);
            $this->isFavorited = true;

            // Send notification to auction owner
            if ($this->auction->user_id !== auth()->id()) {
                Message::create([
                    'sender_id' => auth()->id(),
                    'receiver_id' => $this->auction->user_id,
                    'listing_id' => $this->auction->listing_id,
                    'message' => "Korisnik " . auth()->user()->name . " je dodao vašu aukciju '" . $this->auction->listing->title . "' u omiljene.",
                    'is_system_message' => true,
                    'is_read' => false,
                ]);
            }

            session()->flash('success', 'Aukcija je dodata u omiljene.');
        }
    }

    public function toggleNotification()
    {
        if (!auth()->check()) {
            session()->flash('error', 'Morate se prijaviti da biste postavili obaveštenja.');
            return;
        }

        if (auth()->id() === $this->auction->user_id) {
            session()->flash('error', 'Ne možete postaviti obaveštenje za svoju aukciju.');
            return;
        }

        if ($this->isNotificationSet) {
            \App\Models\AuctionNotification::where('auction_id', $this->auction->id)
                ->where('user_id', auth()->id())
                ->delete();
            $this->isNotificationSet = false;
            session()->flash('success', 'Obaveštenje o početku aukcije je uklonjeno.');
        } else {
            \App\Models\AuctionNotification::create([
                'auction_id' => $this->auction->id,
                'user_id' => auth()->id(),
                'type' => 'start',
            ]);
            $this->isNotificationSet = true;
            session()->flash('success', 'Bićete obavešteni kada aukcija počne.');
        }
    }
    
    public function updatedMaxBidAmount()
    {
        // Removed auto-save to prevent double calls
        // Auto-bid is now only saved when user clicks "Postavi automatsku ponudu" button
    }
    
    public function updatedIsAutoBid()
    {
        if (!$this->isAutoBid) {
            $this->removeAutoBid();
        }
    }
    
    public function setAutoBid()
    {
        
        // Manual method to set auto-bid
        if ($this->isAutoBid && $this->maxBidAmount) {
            $this->saveAutoBid();
        } else {
            session()->flash('error', 'Molimo unesite maksimalnu cenu za automatsku ponudu.');
        }
    }
    
    private function saveAutoBid()
    {
        if (!auth()->check() || !$this->isAutoBid || !$this->maxBidAmount) {
            return;
        }
        
        $this->validate([
            'maxBidAmount' => 'required|numeric|min:' . ($this->auction->current_price + $this->auction->bid_increment)
        ]);

        // Check if this auto-bid can win against existing competition
        $existingAutoBids = Bid::where('auction_id', $this->auction->id)
            ->where('user_id', '!=', auth()->id())
            ->where('is_auto_bid', true)
            ->whereNotNull('max_bid')
            ->get();
            
        if ($existingAutoBids->count() > 0) {
            $highestCompetitorMax = $existingAutoBids->max('max_bid');
            $minimumToWin = $highestCompetitorMax + $this->auction->bid_increment;
            
            // Only show warning if user can't improve their position at all
            if ($this->maxBidAmount <= $this->auction->current_price) {
                session()->flash('warning', 'Vaša maksimalna ponuda (' . number_format($this->maxBidAmount, 0, ',', '.') . ' RSD) mora biti veća od trenutne cene (' . number_format($this->auction->current_price, 0, ',', '.') . ' RSD).');
                return;
            }
            
            // Allow auto-bid even if it can't win - it will trigger competitor's response
            if ($this->maxBidAmount < $minimumToWin) {
                session()->flash('info', 'Vaša ponuda će pokrenuti auto-bid konkurencije. Konkurent će verovatno prebiti vašu ponudu do ' . number_format($minimumToWin, 0, ',', '.') . ' RSD.');
            }
        }

        // Remove existing auto-bid entries for this user
        Bid::where('auction_id', $this->auction->id)
            ->where('user_id', auth()->id())
            ->where('is_auto_bid', true)
            ->delete();

        // Calculate first auto-bid amount (current price + increment)
        $firstAutoBidAmount = $this->auction->current_price + $this->auction->bid_increment;
        
        try {
            // First, create auto-bid entry  
            $autoBidEntry = Bid::create([
                'auction_id' => $this->auction->id,
                'user_id' => auth()->id(),
                'amount' => $this->auction->current_price, // Placeholder
                'is_winning' => false,
                'is_auto_bid' => true,
                'max_bid' => $this->maxBidAmount,
                'ip_address' => request()->ip()
            ]);
            
            // Wait a moment for database commit
            usleep(50000); // 50ms delay
            
            // Now trigger competitive auto-bid resolution
            Bid::processAutoBids($this->auction->fresh(), null);
            
            // Refresh auction data
            $this->auction = $this->auction->fresh(['bids.user']);
            
            // Don't reset form - keep auto-bid status visible
            // $this->isAutoBid = false; // Keep checked
            // $this->maxBidAmount = ''; // Keep value
            
            session()->flash('success', "Automatska ponuda aktivirana do " . number_format((float)$this->maxBidAmount, 0, ',', '.') . " RSD!");
            
        } catch (\Exception $e) {
            session()->flash('error', 'Greška pri postavljanju automatske ponude: ' . $e->getMessage());
        }
    }

    public function confirmBuyNow()
    {
        // Ova metoda se poziva kad korisnik potvrdi kupovinu
        $this->buyNow();
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

    private function sendBidNotificationToOwner()
    {
        // Don't send notification if the owner is bidding on their own auction (shouldn't happen, but safety check)
        if (auth()->id() === $this->auction->user_id) {
            return;
        }

        Message::create([
            'sender_id' => 1, // System
            'receiver_id' => $this->auction->user_id,
            'listing_id' => $this->auction->listing_id,
            'message' => "Nova ponuda na vašoj aukciji '{$this->auction->listing->title}'. " . 
                        "Ponuda: " . number_format($this->bidAmount, 0, ',', '.') . ' RSD od korisnika ' . auth()->user()->name . '.',
            'subject' => 'Nova ponuda - ' . $this->auction->listing->title,
            'is_system_message' => true,
            'is_read' => false
        ]);
    }

    public function removeFromAuction()
    {
        try {
            // Check if current price exceeds starting price (minimum protection rule)
            // Only applies to regular users, not admins
            if (!auth()->user()->is_admin && $this->auction->current_price > $this->auction->starting_price) {
                session()->flash('error', 'Ne možete ukloniti aukciju jer je trenutna cena (' .
                    number_format($this->auction->current_price, 0, ',', '.') .
                    ' RSD) veća od početne cene aukcije (' .
                    number_format($this->auction->starting_price, 0, ',', '.') . ' RSD).');
                return;
            }

            // Send notifications to all bidders before deleting
            if ($this->auction->bids->count() > 0) {
                $this->notifyBiddersOfAuctionCancellation($this->auction);
            }

            // Delete all auction data
            $this->auction->bids()->delete();
            $this->auction->delete();

            session()->flash('success', 'Aukcija je uspešno uklonjena. Oglas je vraćen u redovan prodajni režim.');

            // Redirect to listings page
            return redirect()->route('listings.show', $this->auction->listing);

        } catch (\Exception $e) {
            session()->flash('error', 'Greška pri uklanjanju iz aukcije: ' . $e->getMessage());
        }
    }

    private function notifyBiddersOfAuctionCancellation($auction)
    {
        // Get all unique bidders
        $bidders = $auction->bids()->with('user')->get()->unique('user_id');

        foreach ($bidders as $bid) {
            Message::create([
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

    protected function loadRecommendedListings()
    {
        if (!$this->auction || !$this->auction->listing) {
            return;
        }

        if (auth()->check()) {
            // Za ulogovane korisnike - prikaži ostale oglase istog prodavca
            $sellerListings = Listing::where('user_id', $this->auction->listing->user_id)
                ->where('id', '!=', $this->auction->listing_id)
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
            $this->recommendedListings = Listing::where('id', '!=', $this->auction->listing_id)
                ->where('category_id', $this->auction->listing->category_id)
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
        return view('livewire.auctions.show')
            ->layout('layouts.app');
    }
}