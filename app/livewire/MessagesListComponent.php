<?php

namespace App\Livewire;

use Livewire\Component;

class MessagesListComponent extends Component
{
    public function render()
    {
        return view('livewire.messages-list')
        ->layout('layouts.app');
    }
}
