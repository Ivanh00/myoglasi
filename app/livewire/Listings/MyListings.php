<?php

namespace App\Livewire\Listings;

use Livewire\Component;

class MyListings extends Component
{
    public function render()
    {
        return view('livewire.listings.my-listings')
        ->layout('layouts.app');
    }
}
