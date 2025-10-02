<?php

namespace App\Livewire\Businesses;

use App\Models\Business;
use Livewire\Component;

class Show extends Component
{
    public Business $business;

    public function mount(Business $business)
    {
        // Load business with relationships
        $this->business = $business->load(['category', 'subcategory', 'images', 'user']);

        // Increment views
        $this->business->increment('views');
    }

    public function render()
    {
        return view('livewire.businesses.show')
            ->layout('layouts.app');
    }
}
