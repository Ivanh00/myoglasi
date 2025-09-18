<?php

namespace App\Livewire\Balance;

use Livewire\Component;
use App\Models\Transaction;

class BankTransfer extends Component
{
    public $transaction;
    public $bankDetails;
    
    public function mount($transaction)
    {
        $this->transaction = Transaction::findOrFail($transaction);
        
        // Verify that this transaction belongs to authenticated user
        if ($this->transaction->user_id !== auth()->id()) {
            abort(403);
        }
        
        // Verify transaction is pending
        if ($this->transaction->status !== 'pending') {
            session()->flash('error', 'Ova transakcija je već obrađena.');
            return redirect()->route('balance.index');
        }
        
        // Set bank details
        $this->bankDetails = [
            'recipient_name' => 'PazAriO d.o.o.',
            'recipient_address' => 'Bulevar Oslobođenja 123, 11000 Beograd',
            'recipient_pib' => '123456789',
            'recipient_account' => '265-0000000003456-78',
            'bank_name' => 'Intesa Banka a.d. Beograd',
            'bank_code' => '265',
            'swift' => 'DBDBRSBG',
            'payment_code' => '289', // Ostale uplate
            'reference_number' => 'PazAriO-' . $this->transaction->id . '-' . auth()->id(),
            'payment_purpose' => 'Dopuna kredita na PazAriO platformi',
        ];
    }

    public function downloadPaymentSlip()
    {
        // Generate PDF payment slip
        // In real implementation, you would use a PDF library to generate the slip
        return response()->json([
            'message' => 'Preuzimanje uplatnice će biti implementirano kada integrišemo PDF generator'
        ]);
    }
    
    public function printPaymentSlip()
    {
        // Trigger browser print dialog
        $this->dispatch('print-payment-slip');
    }
    
    public function markAsPaid()
    {
        // This would be used when user manually reports payment completion
        // In practice, this should be handled by webhook from bank or manual admin verification
        $this->transaction->update([
            'status' => 'awaiting_verification',
            'payment_details' => [
                'payment_method' => 'bank_transfer',
                'reference_number' => $this->bankDetails['reference_number'],
                'marked_paid_at' => now()
            ]
        ]);
        
        session()->flash('info', 'Označili ste uplatu kao izvršenu. Kredit će biti uveličan kada verifikujemo transakciju.');
        return redirect()->route('balance.index');
    }
    
    public function cancelPayment()
    {
        $this->transaction->update([
            'status' => 'cancelled',
            'cancelled_at' => now()
        ]);
        
        session()->flash('info', 'Transakcija je otkazana.');
        return redirect()->route('balance.payment-options');
    }

    public function render()
    {
        return view('livewire.balance.bank-transfer')
            ->layout('layouts.app');
    }
}