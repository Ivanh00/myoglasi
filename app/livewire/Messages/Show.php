<?php

namespace App\Livewire\Messages;

use Livewire\Component;

class Show extends Component
{
    public function render()
    {
        return view('livewire.messages.show')
        ->layout('layouts.app');
    }
}
