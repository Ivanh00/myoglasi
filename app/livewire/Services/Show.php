<?php

namespace App\Livewire\Services;

use App\Models\Service;
use App\Models\Listing;
use Livewire\Component;

class Show extends Component
{
    public $service;
    public $recommendedListings;
    public $recommendationType;

    public function mount($service)
    {
        // Ako je prosleđen slug, nađi service po slug-u
        if (is_string($service)) {
            $this->service = Service::where('slug', $service)
                ->with(['user', 'category', 'subcategory', 'images'])
                ->firstOrFail();
        }
        // Ako je prosleđen Service model
        else if ($service instanceof Service) {
            $this->service = $service->load(['user', 'category', 'subcategory', 'images']);
        }

        // Increment views
        if ($this->service) {
            $this->service->increment('views');
            $this->loadRecommendedListings();
        }
    }

    protected function loadRecommendedListings()
    {
        if (!$this->service) {
            return;
        }

        if (auth()->check()) {
            // Za ulogovane korisnike - prikaži samo ostale usluge istog korisnika
            $userServices = Service::where('user_id', $this->service->user_id)
                ->where('id', '!=', $this->service->id)
                ->where('status', 'active')
                ->with(['category', 'images'])
                ->orderBy('created_at', 'desc')
                ->take(4)
                ->get();

            if ($userServices->count() > 0) {
                $this->recommendedListings = $userServices;
                $this->recommendationType = 'seller';
            } else {
                // Ako korisnik nema drugih usluga, ne prikazuj ništa
                $this->recommendedListings = collect();
                $this->recommendationType = null;
            }
        } else {
            // Za neulogovane korisnike - prikaži slične usluge iz iste kategorije
            $similarServices = Service::where('id', '!=', $this->service->id)
                ->where('service_category_id', $this->service->service_category_id)
                ->where('status', 'active')
                ->with(['category', 'images'])
                ->orderBy('created_at', 'desc')
                ->take(4)
                ->get();

            $this->recommendedListings = $similarServices;
            $this->recommendationType = 'similar';
        }
    }

    public function render()
    {
        return view('livewire.services.show')
            ->layout('layouts.app');
    }
}