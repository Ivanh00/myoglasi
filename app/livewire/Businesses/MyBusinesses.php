<?php

namespace App\Livewire\Businesses;

use Livewire\Component;
use App\Models\Business;
use Livewire\WithPagination;

class MyBusinesses extends Component
{
    use WithPagination;

    public $filter = 'all'; // all, active, expired
    public $showActivateModal = false;
    public $businessToActivate = null;

    public function deleteBusiness($id)
    {
        $business = Business::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $business->delete();

        session()->flash('message', 'Business je uspešno obrisan.');
    }

    public function renewBusiness($id)
    {
        $business = Business::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($business->status !== 'active') {
            session()->flash('error', 'Ovaj business nije aktivan.');
            return;
        }

        // Renew business for 60 days from now
        $business->update([
            'expires_at' => now()->addDays(60),
            'renewed_at' => now(),
            'renewal_count' => $business->renewal_count + 1
        ]);

        session()->flash('success', 'Business je uspešno obnovljen i važi narednih 60 dana!');
    }

    public function openActivateModal($id)
    {
        $this->businessToActivate = Business::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $this->showActivateModal = true;
    }

    public function closeActivateModal()
    {
        $this->showActivateModal = false;
        $this->businessToActivate = null;
    }

    public function activateWithPlan()
    {
        if (!$this->businessToActivate) {
            return;
        }

        $user = auth()->user();

        // Check if user has active business plan with available slots
        $hasActiveBusinessPlan = $user->payment_plan === 'business'
            && $user->plan_expires_at
            && $user->plan_expires_at->isFuture()
            && $user->business_plan_total > 0;

        if (!$hasActiveBusinessPlan) {
            session()->flash('error', 'Nemate aktivan biznis plan.');
            return;
        }

        // Count only businesses from business plan
        $activeBusinessCount = $user->businesses()->where('status', 'active')->where('is_from_business_plan', true)->count();
        $businessLimit = $user->business_plan_total;

        if ($activeBusinessCount >= $businessLimit) {
            session()->flash('error', 'Dostigli ste limit biznis plana (' . $businessLimit . ' aktivnih biznis kartica).');
            return;
        }

        // Activate the business with business plan
        // Business from plan should expire when the plan expires
        $this->businessToActivate->update([
            'status' => 'active',
            'is_from_business_plan' => true,
            'paid_until' => null,
            'expires_at' => $user->plan_expires_at,
        ]);

        \App\Models\Transaction::create([
            'user_id' => $user->id,
            'type' => 'business_plan_usage',
            'amount' => 0,
            'status' => 'completed',
            'description' => 'Aktivacija biznis kartica preko biznis plana: ' . $this->businessToActivate->name,
            'reference_number' => 'BUSINESS-ACTIVATION-' . now()->timestamp,
        ]);

        session()->flash('success', 'Biznis kartice su uspešno aktivirane preko biznis plana!');
        $this->closeActivateModal();
    }

    public function activateWithPayment()
    {
        if (!$this->businessToActivate) {
            return;
        }

        $user = auth()->user();

        // Check if business fee is enabled
        if (!\App\Models\Setting::get('business_fee_enabled', false)) {
            session()->flash('error', 'Plaćanje po biznis kartici nije omogućeno.');
            return;
        }

        $fee = \App\Models\Setting::get('business_fee_amount', 2000);

        // Check balance
        if ($user->balance < $fee) {
            session()->flash('error', 'Nemate dovoljno kredita. Potrebno: ' . number_format($fee, 0, ',', '.') . ' RSD');
            return redirect()->route('balance.payment-options');
        }

        // Charge fee
        $user->decrement('balance', $fee);

        // Set paid_until and expires_at
        $businessDuration = \App\Models\Setting::get('business_auto_expire_days', 365);
        $paidUntil = now()->addDays($businessDuration);

        // Activate the business
        $this->businessToActivate->update([
            'status' => 'active',
            'is_from_business_plan' => false,
            'paid_until' => $paidUntil,
            'expires_at' => $paidUntil,
        ]);

        \App\Models\Transaction::create([
            'user_id' => $user->id,
            'type' => 'business_fee',
            'amount' => $fee,
            'status' => 'completed',
            'description' => 'Aktivacija biznis kartice plaćanjem: ' . $this->businessToActivate->name,
            'reference_number' => 'BUSINESS-ACTIVATION-' . now()->timestamp,
        ]);

        session()->flash('success', 'Business je uspešno aktiviran! Važi do ' . $paidUntil->format('d.m.Y'));
        $this->closeActivateModal();
    }

    public function render()
    {
        $query = Business::where('user_id', auth()->id())
            ->with(['category', 'subcategory', 'images']);

        // Apply filters
        if ($this->filter === 'active') {
            $query->where('status', 'active')
                  ->where(function ($q) {
                      $q->whereNull('expires_at')
                        ->orWhere('expires_at', '>', now());
                  });
        } elseif ($this->filter === 'expired') {
            $query->where(function ($q) {
                $q->where('status', 'expired')
                  ->orWhere(function ($subQ) {
                      $subQ->where('status', 'active')
                           ->where('expires_at', '<', now());
                  });
            });
        }

        $businesses = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.businesses.my-businesses', [
            'businesses' => $businesses
        ])->layout('layouts.app');
    }
}
