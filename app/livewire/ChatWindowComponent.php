<?php

namespace App\Livewire;

use Livewire\Component;

class ChatWindowComponent extends Component
{
    public function render()
    {
        return view('livewire.chat-window')
        ->layout('layouts.app');
    }
}
