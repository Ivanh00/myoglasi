<?php

namespace App\Livewire\Transactions;

use Livewire\Component;

class Balance extends Component
{
    protected $listeners = ['transactionUpdated' => 'handleTransactionUpdate'];

    public function handleTransactionUpdate($userId)
    {
        // Only refresh if this is the current user
        if ($userId == auth()->id()) {
            // Force refresh all computed properties by calling $refresh
            $this->dispatch('$refresh');
        }
    }

    public function getTransactionsProperty()
    {
        return auth()->user()->transactions()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    public function getTotalTopupProperty()
    {
        return auth()->user()->transactions()
            ->where('type', 'credit_topup')
            ->where('status', 'completed')
            ->sum('amount');
    }

    public function getTotalSpentProperty()
    {
        return auth()->user()->transactions()
            ->where('type', 'listing_fee')
            ->sum('amount');
    }

    public function getActiveListingsCountProperty()
    {
        return auth()->user()->listings()
            ->where('status', 'active')
            ->count();
    }

    public function render()
    {
        return view('livewire.transactions.balance')
        ->layout('layouts.app');
    }
}
