<?php

namespace App\Livewire;

use Livewire\Component;

class CategoryListingsComponent extends Component
{
    public function render()
    {
        return view('livewire.category-listings-component')
        ->layout('layouts.app');
    }
}
