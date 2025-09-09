<?php

namespace App\Livewire\Auctions;

use Livewire\Component;
use App\Models\Listing;
use App\Models\Auction;
use Carbon\Carbon;

class Setup extends Component
{
    public $listing;
    public $startingPrice = '';
    public $buyNowPrice = '';
    public $duration = 7; // default 7 dana
    public $startType = 'immediately'; // immediately ili scheduled
    public $startDate = '';
    public $startTime = '';

    public $durationOptions = [
        1 => '1 dan',
        3 => '3 dana', 
        5 => '5 dana',
        7 => '7 dana',
        10 => '10 dana'
    ];

    protected $rules = [
        'startingPrice' => 'required|numeric|min:1|max:1000000',
        'buyNowPrice' => 'nullable|numeric|min:1|max:1000000|gt:startingPrice',
        'duration' => 'required|in:1,3,5,7,10',
        'startType' => 'required|in:immediately,scheduled',
        'startDate' => 'required_if:startType,scheduled|date|after_or_equal:today',
        'startTime' => 'required_if:startType,scheduled'
    ];

    protected $messages = [
        'startingPrice.required' => 'Početna cena je obavezna.',
        'startingPrice.min' => 'Početna cena mora biti najmanje 1 RSD.',
        'startingPrice.max' => 'Početna cena ne može biti veća od 1.000.000 RSD.',
        'buyNowPrice.gt' => 'Kupi odmah cena mora biti veća od početne cene.',
        'startDate.after_or_equal' => 'Datum početka ne može biti u prošlosti.',
        'startTime.required_if' => 'Vreme početka je obavezno za zakazanu aukciju.'
    ];

    public function mount(Listing $listing)
    {
        $this->listing = $listing;
        
        // Check if user owns the listing
        if ($listing->user_id !== auth()->id()) {
            session()->flash('error', 'Možete postaviti aukciju samo za svoje oglase.');
            return redirect()->route('listings.show', $listing);
        }

        // Check if auction already exists
        if ($listing->auction) {
            session()->flash('info', 'Ovaj oglas već ima aktivnu aukciju.');
            return redirect()->route('auction.show', $listing->auction);
        }
        
        // Set default starting price based on listing price
        $this->startingPrice = $listing->price * 0.7; // 70% od listing cene
        $this->buyNowPrice = $listing->price;
        
        // Set default start time for scheduled auctions
        $this->startDate = Carbon::tomorrow()->format('Y-m-d');
        $this->startTime = '09:00';
    }

    public function createAuction()
    {
        $this->validate();

        try {
            $startDateTime = $this->startType === 'immediately' 
                ? now() 
                : Carbon::parse($this->startDate . ' ' . $this->startTime);

            $endDateTime = $startDateTime->copy()->addDays($this->duration);

            $auction = Auction::create([
                'listing_id' => $this->listing->id,
                'user_id' => auth()->id(),
                'starting_price' => $this->startingPrice,
                'buy_now_price' => $this->buyNowPrice ?: null,
                'current_price' => $this->startingPrice,
                'starts_at' => $startDateTime,
                'ends_at' => $endDateTime,
                'status' => 'active'
            ]);

            session()->flash('success', 'Aukcija je uspešno kreirana!');
            return redirect()->route('auction.show', $auction);

        } catch (\Exception $e) {
            session()->flash('error', 'Greška pri kreiranju aukcije: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.auctions.setup')
            ->layout('layouts.app');
    }
}