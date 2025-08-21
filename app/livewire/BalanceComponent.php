<?php

namespace App\Livewire;

use Livewire\Component;

class BalanceComponent extends Component
{
    public function render()
    {
        return view('livewire.balance-component')
        ->layout('layouts.app');
    }

    public $amount = 0;
    public $user;
    
    public function mount()
    {
        $this->user = auth()->user();
    }
    
    public function addFunds()
    {
        $this->validate([
            'amount' => 'required|numeric|min:100|max:10000'
        ]);
        
        // Ovde bi bio poziv ka payment gateway-u
        // Za demo verziju samo dodaj sredstva
        
        $this->user->increment('balance', $this->amount);
        
        Transaction::create([
            'user_id' => $this->user->id,
            'type' => 'deposit',
            'amount' => $this->amount,
            'description' => 'Dodavanje sredstava'
        ]);
        
        session()->flash('success', 'Uspešno dodato ' . $this->amount . ' RSD');
        $this->amount = 0;
    }
}
