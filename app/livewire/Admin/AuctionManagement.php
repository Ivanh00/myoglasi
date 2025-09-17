<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Auction;
use App\Models\Listing;
use App\Models\User;

class AuctionManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $selectedAuction = null;
    public $showEditModal = false;
    public $showDeleteModal = false;

    public $statusOptions = [
        'pending' => 'Na čekanju',
        'active' => 'Aktivna',
        'ended' => 'Završena',
        'cancelled' => 'Otkazana',
        'deleted' => 'Obrisana'
    ];

    public $editState = [
        'starting_price' => '',
        'buy_now_price' => '',
        'current_price' => '',
        'starts_at' => '',
        'ends_at' => '',
        'status' => 'pending'
    ];

    public $filters = [
        'status' => '',
        'has_bids' => ''
    ];

    protected $listeners = ['refreshAuctions' => '$refresh'];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function applyFilters($query)
    {
        return $query
            ->when($this->filters['status'], function ($query, $status) {
                if ($status === 'deleted') {
                    return $query->whereNotNull('deleted_at');
                } else {
                    return $query->where('status', $status);
                }
            })
            ->when($this->filters['has_bids'] !== '', function ($query) {
                if ($this->filters['has_bids'] == '1') {
                    return $query->where('total_bids', '>', 0);
                } else {
                    return $query->where('total_bids', 0);
                }
            });
    }

    public function editAuction($auctionId)
    {
        $this->selectedAuction = Auction::with(['listing', 'seller', 'winner'])->find($auctionId);
        
        $this->editState = [
            'starting_price' => $this->selectedAuction->starting_price,
            'buy_now_price' => $this->selectedAuction->buy_now_price,
            'current_price' => $this->selectedAuction->current_price,
            'starts_at' => $this->selectedAuction->starts_at->format('Y-m-d\TH:i'),
            'ends_at' => $this->selectedAuction->ends_at->format('Y-m-d\TH:i'),
            'status' => $this->selectedAuction->status
        ];

        $this->showEditModal = true;
    }

    public function updateAuction()
    {
        $validated = $this->validate([
            'editState.starting_price' => 'required|numeric|min:1',
            'editState.buy_now_price' => 'nullable|numeric|min:1|gt:editState.starting_price',
            'editState.current_price' => 'required|numeric|min:' . $this->editState['starting_price'],
            'editState.starts_at' => 'required|date',
            'editState.ends_at' => 'required|date|after:editState.starts_at',
            'editState.status' => 'required|in:pending,active,ended,cancelled'
        ]);

        $this->selectedAuction->update($validated['editState']);
        
        $this->showEditModal = false;
        $this->dispatch('notify', type: 'success', message: 'Aukcija uspešno ažurirana!');
    }

    public function confirmDelete($auctionId)
    {
        $this->selectedAuction = Auction::find($auctionId);
        $this->showDeleteModal = true;
    }

    public function deleteAuction()
    {
        if ($this->selectedAuction) {
            // Delete all bids first
            $this->selectedAuction->bids()->delete();
            
            // Delete only auction (listing remains intact)
            $this->selectedAuction->delete();
            
            $this->showDeleteModal = false;
            $this->dispatch('notify', type: 'success', message: 'Aukcija je uspešno obrisana! Oglas je zadržan.');
        }
    }

    public function updateStatus($auctionId, $status)
    {
        $auction = Auction::find($auctionId);
        $auction->status = $status;
        $auction->save();

        $this->dispatch('notify', type: 'success', message: 'Status aukcije ažuriran na: ' . $this->statusOptions[$status]);
    }

    public function endAuction($auctionId)
    {
        $auction = Auction::find($auctionId);
        
        if ($auction->isActive()) {
            $auction->status = 'ended';
            
            // Set winner if there are bids
            if ($auction->total_bids > 0) {
                $winningBid = $auction->bids()->where('is_winning', true)->first();
                if ($winningBid) {
                    $auction->winner_id = $winningBid->user_id;
                }
            }
            
            $auction->save();
            
            $this->dispatch('notify', type: 'success', message: 'Aukcija je uspešno završena!');
        }
    }

    public function resetFilters()
    {
        $this->filters = [
            'status' => '',
            'has_bids' => ''
        ];
    }

    public function render()
    {
        $auctions = Auction::withTrashed()
            ->with(['listing.images', 'listing.user', 'seller', 'winner', 'bids'])
            ->when($this->search, function ($query) {
                $query->whereHas('listing', function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('seller', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filters, function ($query) {
                return $this->applyFilters($query);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.auction-management', compact('auctions'))
            ->layout('layouts.admin');
    }
}