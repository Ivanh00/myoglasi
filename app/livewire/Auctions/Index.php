<?php

namespace App\Livewire\Auctions;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Auction;

class Index extends Component
{
    use WithPagination;
    
    public $viewMode = 'list'; // list or grid
    public $sortBy = 'ending_soon'; // ending_soon, newest, highest_price, most_bids
    public $perPage = 20;

    protected $queryString = [
        'viewMode' => ['except' => 'list'],
        'sortBy' => ['except' => 'ending_soon'],
        'perPage' => ['except' => 20]
    ];

    public function setViewMode($mode)
    {
        $this->viewMode = $mode;
        $this->resetPage();
    }

    public function updatedSortBy()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Active auctions query
        $activeQuery = Auction::with(['listing.images', 'listing.user', 'listing.category', 'winningBid.user'])
            ->where('status', 'active')
            ->where(function($query) {
                $query->where('starts_at', '<=', now())
                      ->where('ends_at', '>', now());
            });

        // Sorting for active auctions
        switch ($this->sortBy) {
            case 'ending_soon':
                $activeQuery->orderBy('ends_at', 'asc');
                break;
            case 'newest':
                $activeQuery->orderBy('created_at', 'desc');
                break;
            case 'highest_price':
                $activeQuery->orderBy('current_price', 'desc');
                break;
            case 'most_bids':
                $activeQuery->orderBy('total_bids', 'desc');
                break;
        }

        $auctions = $activeQuery->paginate($this->perPage);

        // Scheduled auctions (not yet started)
        $scheduledAuctions = Auction::with(['listing.images', 'listing.user', 'listing.category'])
            ->where('status', 'active')
            ->where('starts_at', '>', now())
            ->orderBy('starts_at', 'asc')
            ->get();

        // Ended auctions (last 5)
        $endedAuctions = Auction::with(['listing.images', 'listing.user', 'listing.category', 'winningBid.user', 'winner'])
            ->where(function($query) {
                $query->where('status', 'ended')
                      ->orWhere('ends_at', '<=', now());
            })
            ->orderBy('ends_at', 'desc')
            ->limit(5)
            ->get();

        return view('livewire.auctions.index', [
            'auctions' => $auctions,
            'scheduledAuctions' => $scheduledAuctions,
            'endedAuctions' => $endedAuctions
        ])->layout('layouts.app');
    }
}