<?php

namespace App\Livewire\Ratings;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Rating;
use App\Models\User;

class UserRatings extends Component
{
    use WithPagination;
    
    public $user;
    public $filter = 'all'; // all, positive, neutral, negative

    public function mount($user)
    {
        $this->user = User::findOrFail($user);
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function getRatings()
    {
        $query = $this->user->ratingsReceived()
            ->with(['rater', 'listing'])
            ->orderBy('created_at', 'desc');
            
        if ($this->filter !== 'all') {
            $query->where('rating', $this->filter);
        }
        
        return $query->paginate(20);
    }

    public function render()
    {
        $ratings = $this->getRatings();
        
        return view('livewire.ratings.user-ratings', [
            'ratings' => $ratings
        ])->layout('layouts.app');
    }
}