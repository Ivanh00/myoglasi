<?php

namespace App\Livewire\Services;

use App\Models\Service;
use Livewire\Component;

class Show extends Component
{
    public Service $service;

    public function mount(Service $service)
    {
        $this->service = $service;

        // Increment views
        $service->increment('views');
    }

    public function render()
    {
        return view('livewire.services.show')
            ->layout('layouts.app');
    }
}