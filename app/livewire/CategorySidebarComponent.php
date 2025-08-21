<?php

namespace App\Livewire;

use Livewire\Component;

class CategorySidebarComponent extends Component
{
    public function render()
    {
        return view('livewire.category-sidebar')
        ->layout('layouts.app');
    }
}
