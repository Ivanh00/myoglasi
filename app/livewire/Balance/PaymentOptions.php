<?php

namespace App\Livewire\Balance;

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Setting;

class PaymentOptions extends Component
{
    public $selectedMethod = null;
    public $amount = '';
    public $customAmount = '';
    public $predefinedAmounts = [100, 200, 500, 1000, 2000, 5000];
    public $acceptedTerms = false;
    public $payerType = 'physical'; // physical or legal
    public $showPaymentSlip = false;
    
    protected $rules = [
        'selectedMethod' => 'required|in:card,bank,mobile',
        'amount' => 'required|numeric|min:100|max:50000',
        'acceptedTerms' => 'required|accepted',
    ];

    protected $messages = [
        'selectedMethod.required' => 'Molimo odaberite način plaćanja.',
        'amount.required' => 'Molimo unesite iznos za dopunu.',
        'amount.numeric' => 'Iznos mora biti broj.',
        'amount.min' => 'Minimalni iznos za dopunu je 100 dinara.',
        'amount.max' => 'Maksimalni iznos za dopunu je 50.000 dinara.',
        'acceptedTerms.accepted' => 'Molimo prihvatite uslove korišćenja.',
    ];

    public function selectMethod($method)
    {
        $this->selectedMethod = $method;
        $this->amount = '';
        $this->customAmount = '';
        $this->acceptedTerms = false;
        $this->showPaymentSlip = false;
        
        if ($method === 'bank') {
            $this->payerType = 'physical'; // Default to physical person
        }
    }

    public function selectAmount($amount)
    {
        $this->amount = $amount;
        $this->customAmount = '';
        
        // Automatically show payment slip for bank transfer
        if ($this->selectedMethod === 'bank' && $this->amount) {
            $this->showPaymentSlip = true;
        }
    }

    public function setCustomAmount()
    {
        if ($this->customAmount) {
            $this->amount = $this->customAmount;
            
            // Automatically show payment slip for bank transfer
            if ($this->selectedMethod === 'bank' && $this->amount) {
                $this->showPaymentSlip = true;
            }
        }
    }

    public function proceedToPayment()
    {
        $this->validate();

        switch ($this->selectedMethod) {
            case 'card':
                return $this->processCardPayment();
            case 'bank':
                return $this->processBankTransfer();
            case 'mobile':
                return $this->processMobileBanking();
        }
    }

    private function processCardPayment()
    {
        // Create pending transaction
        $transaction = Transaction::create([
            'user_id' => auth()->id(),
            'type' => 'credit_topup',
            'amount' => $this->amount,
            'status' => 'pending',
            'payment_method' => 'card',
            'description' => 'Dopuna kredita karticom - ' . $this->amount . ' RSD',
        ]);

        // Redirect to Intesa bank payment gateway
        session()->flash('transaction_id', $transaction->id);
        return redirect()->route('balance.card-payment', ['transaction' => $transaction->id]);
    }

    private function processBankTransfer()
    {
        // Just show the payment slip on the same page
        $this->showPaymentSlip = true;
        return;
    }

    private function processMobileBanking()
    {
        session()->flash('error', 'Mobilno bankarstvo će biti dostupno uskoro sa NBS IPS QR kodom.');
        return;
    }
    
    public function markAsPaid()
    {
        if (!$this->showPaymentSlip || !$this->amount) {
            return;
        }
        
        // Create transaction record
        $transaction = Transaction::create([
            'user_id' => auth()->id(),
            'type' => 'credit_topup',
            'amount' => $this->amount,
            'status' => 'awaiting_verification',
            'payment_method' => 'bank_transfer',
            'description' => 'Dopuna kredita bankovnim nalogom - ' . $this->amount . ' RSD',
            'payment_details' => [
                'payer_type' => $this->payerType,
                'reference_number' => $this->getReferenceNumber(),
                'marked_paid_at' => now()
            ]
        ]);
        
        session()->flash('info', 'Označili ste uplatu kao izvršenu. Kredit će biti uvećan kada verifikujemo transakciju.');
        return redirect()->route('balance.index');
    }
    
    public function printPaymentSlip()
    {
        $this->dispatch('print-payment-slip');
    }
    
    private function getReferenceNumber()
    {
        $template = Setting::get('reference_number_template', '20-10-{user_id}');
        return str_replace('{user_id}', auth()->id(), $template);
    }
    
    #[Computed]
    public function paymentSlipData()
    {
        if (!$this->showPaymentSlip || !$this->amount) {
            return null;
        }
        
        $isLegal = $this->payerType === 'legal';
        
        return [
            'company_name' => Setting::get('company_name', 'MyOglasi d.o.o.'),
            'company_address' => Setting::get('company_address', 'Bulevar Oslobođenja 123, 11000 Beograd'),
            'company_pib' => Setting::get('company_pib', '123456789'),
            'bank_name' => Setting::get('bank_name', 'Intesa Banka a.d. Beograd'),
            'bank_account' => Setting::get('bank_account_number', '265-0000000003456-78'),
            'payment_code' => $isLegal ? 
                Setting::get('payment_code_legal', '221') : 
                Setting::get('payment_code_physical', '289'),
            'model_number' => $isLegal ? 
                Setting::get('model_number_legal', '97') : 
                Setting::get('model_number_physical', '97'),
            'reference_number' => $this->getReferenceNumber(),
            'amount' => $this->amount,
            'slip_title' => $isLegal ? 'NALOG ZA PRENOS' : 'NALOG ZA UPLATU',
            'payer_name' => auth()->user()->name,
            'payment_purpose' => 'Dopuna kredita na MyOglasi platformi',
            'date' => now()->format('d.m.Y'),
            'time' => now()->format('H:i:s'),
        ];
    }

    public function render()
    {
        return view('livewire.balance.payment-options')
            ->layout('layouts.app');
    }
}