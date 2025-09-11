<?php

namespace App\Livewire\Transactions;

use Livewire\Component;

class Balance extends Component
{
    protected $listeners = ['transactionUpdated' => 'handleTransactionUpdate'];
    
    // Credit transfer properties
    public $showTransferModal = false;
    public $transferAmount = '';
    public $recipientName = '';
    public $transferNote = '';
    public $selectedRecipient = null;
    public $userSearchResults = [];

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

    // Credit transfer methods
    public function openTransferModal()
    {
        $this->resetTransferForm();
        $this->showTransferModal = true;
    }
    
    public function updatedRecipientName()
    {
        if (strlen($this->recipientName) >= 2) {
            $this->userSearchResults = \App\Models\User::where('name', 'like', '%' . $this->recipientName . '%')
                ->where('id', '!=', auth()->id())
                ->where('is_banned', false)
                ->limit(5)
                ->get(['id', 'name', 'email']);
        } else {
            $this->userSearchResults = [];
        }
    }
    
    public function selectRecipient($userId)
    {
        $user = \App\Models\User::find($userId);
        $this->selectedRecipient = $user;
        $this->recipientName = $user->name;
        $this->userSearchResults = [];
    }
    
    public function transferCredit()
    {
        // Check if user has any balance first
        if (auth()->user()->balance <= 0) {
            session()->flash('error', 'Nemate dovoljno kredita za transfer. Dopunite vaš balans da biste mogli da delite kredit.');
            return;
        }
        
        // Custom validation with better error messages
        if (empty($this->transferAmount) || !is_numeric($this->transferAmount)) {
            $this->addError('transferAmount', 'Unesite valjan iznos za transfer.');
            return;
        }
        
        if ($this->transferAmount < 10) {
            $this->addError('transferAmount', 'Minimalni transfer je 10 RSD.');
            return;
        }
        
        if ($this->transferAmount > auth()->user()->balance) {
            $this->addError('transferAmount', 'Nemate dovoljno kredita za ovaj transfer. Vaš balans: ' . number_format(auth()->user()->balance, 0, ',', '.') . ' RSD, a pokušavate da pošaljete: ' . number_format($this->transferAmount, 0, ',', '.') . ' RSD.');
            return;
        }
        
        if (!$this->selectedRecipient) {
            $this->addError('selectedRecipient', 'Izaberite korisnika kome šaljete kredit.');
            return;
        }
        
        if ($this->transferNote && strlen($this->transferNote) > 255) {
            $this->addError('transferNote', 'Napomena može imati maksimalno 255 karaktera.');
            return;
        }

        try {
            \DB::transaction(function () {
                $sender = auth()->user();
                $recipient = $this->selectedRecipient;
                $amount = $this->transferAmount;

                // Deduct from sender
                $sender->decrement('balance', $amount);
                
                // Add to recipient  
                $recipient->increment('balance', $amount);

                // Create transaction records
                \App\Models\Transaction::create([
                    'user_id' => $sender->id,
                    'type' => 'credit_transfer_sent',
                    'amount' => -$amount,
                    'status' => 'completed',
                    'description' => "Transfer kredita korisniku {$recipient->name}",
                    'reference_number' => 'TRF-' . now()->timestamp,
                    'notes' => $this->transferNote ?: null
                ]);

                \App\Models\Transaction::create([
                    'user_id' => $recipient->id,
                    'type' => 'credit_transfer_received',
                    'amount' => $amount,
                    'status' => 'completed',
                    'description' => "Primljen kredit od korisnika {$sender->name}",
                    'reference_number' => 'TRF-' . now()->timestamp,
                    'notes' => $this->transferNote ?: null
                ]);

                // Send notification to recipient
                \App\Models\Message::create([
                    'sender_id' => 1, // System
                    'receiver_id' => $recipient->id,
                    'listing_id' => null,
                    'message' => "Primili ste kredit od korisnika {$sender->name}!\n\nIznos: " . number_format($amount, 0, ',', '.') . " RSD" . 
                                ($this->transferNote ? "\nNapomena: {$this->transferNote}" : ''),
                    'subject' => 'Kredit primljen',
                    'is_system_message' => true,
                    'is_read' => false
                ]);

                // Dispatch real-time notification event
                event(new \App\Events\CreditTransferReceived(
                    $recipient,
                    $sender,
                    $amount,
                    $this->transferNote
                ));
            });

            session()->flash('success', "Uspešno ste poslali " . number_format($this->transferAmount, 0, ',', '.') . " RSD korisniku {$this->selectedRecipient->name}!");
            $this->resetTransferForm();
            $this->showTransferModal = false;

        } catch (\Exception $e) {
            session()->flash('error', 'Greška pri transferu kredita: ' . $e->getMessage());
        }
    }
    
    private function resetTransferForm()
    {
        $this->transferAmount = '';
        $this->recipientName = '';
        $this->transferNote = '';
        $this->selectedRecipient = null;
        $this->userSearchResults = [];
    }

    public function render()
    {
        return view('livewire.transactions.balance')
        ->layout('layouts.app');
    }
}
