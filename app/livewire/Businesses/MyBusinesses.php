<?php

namespace App\Livewire\Businesses;

use Livewire\Component;
use App\Models\Business;
use Livewire\WithPagination;

class MyBusinesses extends Component
{
    use WithPagination;

    public $filter = 'all'; // all, active, expired

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
