<?php

namespace App\Livewire\Listings;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.listings.index')
        ->layout('layouts.app');
    }
}
