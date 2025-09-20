<?php

namespace App\Livewire\Services;

use Livewire\Component;
use App\Models\Service;
use App\Models\ListingPromotion;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class PromotionManager extends Component
{
    public $service;
    public $serviceId;
    public $showModal = false;
    public $selectedPromotions = [];
    public $totalCost = 0;

    protected $listeners = ['openServicePromotionModal'];

    public function openServicePromotionModal($serviceId)
    {
        $this->serviceId = $serviceId;
        $this->service = Service::find($serviceId);

        if (!$this->service || $this->service->user_id !== auth()->id()) {
            session()->flash('error', 'Nemate dozvolu da upravljate ovom uslugom.');
            return;
        }

        $this->showModal = true;
        $this->selectedPromotions = [];
        $this->calculateTotal();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedPromotions = [];
        $this->totalCost = 0;
    }

    public function togglePromotion($type)
    {
        // Check if this promotion is already active
        if ($this->hasActivePromotion($type)) {
            // Don't allow toggle if promotion is active
            return;
        }

        if (in_array($type, $this->selectedPromotions)) {
            $this->selectedPromotions = array_filter($this->selectedPromotions, fn($t) => $t !== $type);
        } else {
            $this->selectedPromotions[] = $type;
        }

        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->totalCost = 0;
        foreach ($this->selectedPromotions as $type) {
            $this->totalCost += ListingPromotion::getDefaultPrice($type);
        }
    }

    public function purchasePromotions()
    {
        // Validation
        if (empty($this->selectedPromotions)) {
            session()->flash('error', 'Izaberite barem jednu promociju.');
            return;
        }

        if (auth()->user()->balance < $this->totalCost) {
            session()->flash('error', 'Nemate dovoljno kredita. Potrebno: ' . number_format($this->totalCost, 0, ',', '.') . ' RSD, a imate: ' . number_format(auth()->user()->balance, 0, ',', '.') . ' RSD.');
            return;
        }

        try {
            DB::transaction(function () {
                $user = auth()->user();

                // Deduct balance
                $user->decrement('balance', $this->totalCost);

                $promotionDetails = [];

                foreach ($this->selectedPromotions as $type) {
                    $price = ListingPromotion::getDefaultPrice($type);
                    $duration = ListingPromotion::getDefaultDuration($type);

                    // Check if promotion already exists and extend it, or create new one
                    $existingPromotion = $this->service->promotions()
                        ->where('type', $type)
                        ->where('expires_at', '>', now())
                        ->first();

                    if ($existingPromotion) {
                        // Extend existing promotion
                        $existingPromotion->expires_at = $existingPromotion->expires_at->addDays($duration);
                        $existingPromotion->save();
                    } else {
                        // Create new promotion
                        ListingPromotion::create([
                            'service_id' => $this->service->id,
                            'type' => $type,
                            'starts_at' => now(),
                            'expires_at' => now()->addDays($duration),
                            'price_paid' => $price,
                            'is_active' => true
                        ]);
                    }

                    $promotionDetails[] = ListingPromotion::getPromotionTypes()[$type] . ' (' . $duration . ' dana)';
                }

                // Create transaction record
                Transaction::create([
                    'user_id' => $user->id,
                    'type' => 'service_promotion_purchase',
                    'amount' => $this->totalCost,
                    'status' => 'completed',
                    'description' => 'Kupovina promocije za uslugu "' . $this->service->title . '"',
                    'reference_number' => 'PROMO-SVC-' . now()->timestamp,
                    'notes' => 'Promocije: ' . implode(', ', $promotionDetails)
                ]);
            });

            session()->flash('success', 'Promocije su uspešno kupljene! Vaša usluga će biti istaknuta za ' . number_format($this->totalCost, 0, ',', '.') . ' RSD.');
            $this->closeModal();

            // Refresh the service to show new promotions
            $this->service = $this->service->fresh();

        } catch (\Exception $e) {
            session()->flash('error', 'Greška pri kupovini promocije: ' . $e->getMessage());
        }
    }

    public function getPromotionTypes()
    {
        return ListingPromotion::getPromotionTypes();
    }

    public function getPromotionPrice($type)
    {
        return ListingPromotion::getDefaultPrice($type);
    }

    public function getPromotionDuration($type)
    {
        return ListingPromotion::getDefaultDuration($type);
    }

    public function hasActivePromotion($type)
    {
        return $this->service->hasActivePromotion($type);
    }

    public function render()
    {
        return view('livewire.services.promotion-manager');
    }

    // Helper method for views when service is not loaded yet
    public function getServiceProperty()
    {
        return $this->service;
    }
}