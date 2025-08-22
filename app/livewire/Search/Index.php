<?php

namespace App\Livewire\Search;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.search.index')
        ->layout('layouts.app');
    }
}
