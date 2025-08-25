<?php

namespace App\Livewire\Listings;

use Livewire\Component;
use App\Models\Listing;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;
    
    public $listing;

    public function mount($listing)
    {
        $this->listing = Listing::where('slug', $listing)
            ->with(['category', 'condition', 'images', 'user'])
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.listings.show', [
            'listing' => $this->listing
        ]);
    }
}