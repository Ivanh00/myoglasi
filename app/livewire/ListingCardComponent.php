<?php

namespace App\Livewire;

use Livewire\Component;

class ListingCardComponent extends Component
{
    public function render()
    {
        return view('livewire.listing-card')
        ->layout('layouts.app');
    }
}
