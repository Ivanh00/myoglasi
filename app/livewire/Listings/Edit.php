<?php

namespace App\Livewire\Listings;

use Livewire\Component;

class Edit extends Component
{
    public function render()
    {
        return view('livewire.listings.edit')
        ->layout('layouts.app');
    }
}
