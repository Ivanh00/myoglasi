<?php

namespace App\Livewire\Listings;

use Livewire\Component;

class Create extends Component
{
    public function render()
    {
        return view('livewire.listings.create')
        ->layout('layouts.app');
    }
}
