<?php

namespace App\Livewire\Balance;

use Livewire\Component;
use App\Models\Transaction;
use Illuminate\Support\Str;

class CardPayment extends Component
{
    public $transaction;
    public $paymentData = [
        'card_number' => '',
        'expiry_month' => '',
        'expiry_year' => '',
        'cvv' => '',
        'cardholder_name' => '',
    ];
    
    public $processingPayment = false;
    
    protected $rules = [
        'paymentData.card_number' => 'required|regex:/^\d{4}\s?\d{4}\s?\d{4}\s?\d{4}$/',
        'paymentData.expiry_month' => 'required|numeric|between:1,12',
        'paymentData.expiry_year' => 'required|numeric|min:2025',
        'paymentData.cvv' => 'required|numeric|digits_between:3,4',
        'paymentData.cardholder_name' => 'required|string|min:2',
    ];

    protected $messages = [
        'paymentData.card_number.required' => 'Broj kartice je obavezan.',
        'paymentData.card_number.regex' => 'Molimo unesite važeći broj kartice (16 cifara).',
        'paymentData.expiry_month.required' => 'Mesec isteka je obavezan.',
        'paymentData.expiry_month.between' => 'Mesec mora biti između 1 i 12.',
        'paymentData.expiry_year.required' => 'Godina isteka je obavezna.',
        'paymentData.expiry_year.min' => 'Kartica ne može biti istekla.',
        'paymentData.cvv.required' => 'CVV kod je obavezan.',
        'paymentData.cvv.digits_between' => 'CVV mora imati 3 ili 4 cifre.',
        'paymentData.cardholder_name.required' => 'Ime vlasnika kartice je obavezno.',
        'paymentData.cardholder_name.min' => 'Ime mora imati najmanje 2 karaktera.',
    ];

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
        
        // Set cardholder name to user's name by default
        $this->paymentData['cardholder_name'] = auth()->user()->name;
    }

    public function formatCardNumber()
    {
        // Remove all non-digits
        $this->paymentData['card_number'] = preg_replace('/\D/', '', $this->paymentData['card_number']);
        
        // Add spaces every 4 digits
        $this->paymentData['card_number'] = preg_replace('/(\d{4})(?=\d)/', '$1 ', $this->paymentData['card_number']);
    }

    public function processPayment()
    {
        $this->validate();
        
        $this->processingPayment = true;
        
        try {
            // In a real implementation, this would integrate with Intesa Banka's payment gateway
            // For now, we'll simulate the payment process
            
            // Generate payment reference
            $paymentReference = 'IB' . strtoupper(Str::random(10));
            
            // Update transaction with payment details
            $this->transaction->update([
                'payment_reference' => $paymentReference,
                'payment_details' => [
                    'card_last_four' => substr(str_replace(' ', '', $this->paymentData['card_number']), -4),
                    'cardholder_name' => $this->paymentData['cardholder_name'],
                    'payment_method' => 'card',
                    'gateway' => 'intesa_banka'
                ]
            ]);
            
            // Simulate payment processing delay
            sleep(2);
            
            // In real implementation, you would:
            // 1. Send payment data to Intesa bank API
            // 2. Handle response and verification
            // 3. Update transaction status based on bank response
            
            // For demo purposes, we'll mark as completed
            $this->completePayment($paymentReference);
            
        } catch (\Exception $e) {
            $this->processingPayment = false;
            $this->transaction->update([
                'status' => 'failed',
                'failure_reason' => 'Payment processing error: ' . $e->getMessage()
            ]);
            
            session()->flash('error', 'Greška pri obradi plaćanja. Molimo pokušajte ponovo.');
        }
    }
    
    private function completePayment($paymentReference)
    {
        // Update user balance
        $user = auth()->user();
        $user->increment('balance', $this->transaction->amount);
        
        // Mark transaction as completed
        $this->transaction->update([
            'status' => 'completed',
            'completed_at' => now(),
            'payment_reference' => $paymentReference
        ]);
        
        $this->processingPayment = false;
        
        session()->flash('success', 'Plaćanje je uspešno završeno! Vaš kredit je uvećan za ' . number_format($this->transaction->amount, 0, ',', '.') . ' RSD.');
        
        return redirect()->route('balance.index');
    }
    
    public function cancelPayment()
    {
        $this->transaction->update([
            'status' => 'cancelled',
            'cancelled_at' => now()
        ]);
        
        session()->flash('info', 'Plaćanje je otkazano.');
        return redirect()->route('balance.payment-options');
    }

    public function render()
    {
        return view('livewire.balance.card-payment')
            ->layout('layouts.app');
    }
}