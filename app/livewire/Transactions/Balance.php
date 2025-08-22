<?php

namespace App\Livewire\Transactions;

use Livewire\Component;

class Balance extends Component
{
    public function render()
    {
        return view('livewire.transactions.balance')
        ->layout('layouts.app');
    }
}
