<?php

namespace App\Livewire\Businesses;

use Livewire\Component;

class Edit extends Component
{
    public function render()
    {
        return view('livewire.businesses.edit')
            ->layout('layouts.app');
    }
}
