<?php

namespace App\Livewire\Businesses;

use Livewire\Component;

class Show extends Component
{
    public function render()
    {
        return view('livewire.businesses.show')
            ->layout('layouts.app');
    }
}
