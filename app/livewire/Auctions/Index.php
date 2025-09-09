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
        $query = Auction::with(['listing.images', 'listing.user', 'listing.category', 'winningBid.user'])
            ->where('status', 'active');

        // Sorting
        switch ($this->sortBy) {
            case 'ending_soon':
                $query->orderBy('ends_at', 'asc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'highest_price':
                $query->orderBy('current_price', 'desc');
                break;
            case 'most_bids':
                $query->orderBy('total_bids', 'desc');
                break;
        }

        $auctions = $query->paginate($this->perPage);

        return view('livewire.auctions.index', [
            'auctions' => $auctions
        ])->layout('layouts.app');
    }
}