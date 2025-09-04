<?php

namespace App\Livewire\Balance;

use Livewire\Component;
use App\Models\Setting;
use App\Models\Transaction;
use Carbon\Carbon;

class PlanSelection extends Component
{
    public $currentPlan;
    public $selectedPlan = '';
    public $planPrices = [];
    
    protected $rules = [
        'selectedPlan' => 'required|in:per_listing,monthly,yearly',
    ];

    public function mount()
    {
        $this->currentPlan = auth()->user()->payment_plan;
        $this->selectedPlan = $this->currentPlan === 'free' ? 'per_listing' : $this->currentPlan;
        
        $this->loadPlanPrices();
    }

    public function loadPlanPrices()
    {
        $this->planPrices = [
            'per_listing' => [
                'price' => Setting::get('listing_fee_amount', 10),
                'enabled' => Setting::get('listing_fee_enabled', true),
                'title' => 'Plaćanje po oglasu',
                'description' => 'Plaćate samo kada postavite oglas',
            ],
            'monthly' => [
                'price' => Setting::get('monthly_plan_price', 500),
                'enabled' => Setting::get('monthly_plan_enabled', false),
                'title' => 'Mesečni plan',
                'description' => 'Neograničeno oglasa jedan mesec',
            ],
            'yearly' => [
                'price' => Setting::get('yearly_plan_price', 5000),
                'enabled' => Setting::get('yearly_plan_enabled', false),
                'title' => 'Godišnji plan',
                'description' => 'Neograničeno oglasa jednu godinu',
            ],
        ];
    }

    public function purchasePlan()
    {
        $this->validate();
        
        $user = auth()->user();
        
        // If user doesn't have payment enabled, they can't purchase plans
        if (!$user->payment_enabled) {
            session()->flash('error', 'Kontaktirajte administratora da aktivira plaćanje za vaš nalog.');
            return;
        }
        
        // If selecting the same plan as current, no need to do anything
        if ($this->selectedPlan === $user->payment_plan && $user->hasActivePlan()) {
            session()->flash('info', 'Već imate aktiviran ovaj plan.');
            return;
        }
        
        $planData = $this->planPrices[$this->selectedPlan];
        
        if (!$planData['enabled']) {
            session()->flash('error', 'Ovaj plan trenutno nije dostupan.');
            return;
        }
        
        // For per_listing plan, just change the plan without charging
        if ($this->selectedPlan === 'per_listing') {
            $user->update([
                'payment_plan' => 'per_listing',
                'plan_expires_at' => null,
            ]);
            
            session()->flash('success', 'Uspešno ste prešli na plaćanje po oglasu.');
            return redirect()->route('balance.index');
        }
        
        // For monthly/yearly plans, check balance and charge
        $price = $planData['price'];
        
        if ($user->balance < $price) {
            session()->flash('error', 'Nemate dovoljno kredita. Potrebno: ' . number_format($price, 0, ',', '.') . ' RSD, a imate: ' . number_format($user->balance, 0, ',', '.') . ' RSD');
            return redirect()->route('balance.payment-options');
        }
        
        // Deduct balance
        $user->decrement('balance', $price);
        
        // Set plan and expiry
        $expiryDate = $this->selectedPlan === 'monthly' ? 
            Carbon::now()->addMonth() : 
            Carbon::now()->addYear();
            
        $user->update([
            'payment_plan' => $this->selectedPlan,
            'plan_expires_at' => $expiryDate,
        ]);
        
        // Create transaction record
        Transaction::create([
            'user_id' => $user->id,
            'type' => 'plan_purchase',
            'amount' => $price,
            'status' => 'completed',
            'description' => 'Kupljen ' . $planData['title'] . ' - ' . $price . ' RSD',
        ]);
        
        session()->flash('success', 'Uspešno ste aktivirali ' . $planData['title'] . '! Vaš plan važi do ' . $expiryDate->format('d.m.Y') . '.');
        
        return redirect()->route('balance.index');
    }
    
    public function cancelPlanSelection()
    {
        return redirect()->route('balance.index');
    }

    public function render()
    {
        return view('livewire.balance.plan-selection')
            ->layout('layouts.app');
    }
}