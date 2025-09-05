<?php

namespace App\Livewire\Ratings;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Rating;

class MyRatings extends Component
{
    use WithPagination;
    
    public $filter = 'all'; // all, positive, neutral, negative

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function getRatings()
    {
        $query = auth()->user()->ratingsReceived()
            ->with(['rater', 'listing'])
            ->orderBy('created_at', 'desc');
            
        if ($this->filter !== 'all') {
            $query->where('rating', $this->filter);
        }
        
        return $query->paginate(20);
    }

    public function render()
    {
        $user = auth()->user();
        $ratings = $this->getRatings();
        
        return view('livewire.ratings.my-ratings', [
            'ratings' => $ratings,
            'user' => $user
        ])->layout('layouts.app');
    }
}