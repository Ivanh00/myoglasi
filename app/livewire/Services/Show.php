<?php

namespace App\Livewire\Services;

use App\Models\Service;
use App\Models\Listing;
use Livewire\Component;

class Show extends Component
{
    public Service $service;
    public $recommendedListings;
    public $recommendationType;

    public function mount(Service $service)
    {
        $this->service = $service;

        // Increment views
        $service->increment('views');

        // Load recommended listings
        $this->loadRecommendedListings();
    }

    protected function loadRecommendedListings()
    {
        if (!$this->service) {
            return;
        }

        if (auth()->check()) {
            // Za ulogovane korisnike - prikaži ostale oglase/usluge istog korisnika
            $userListings = Listing::where('user_id', $this->service->user_id)
                ->where('status', 'active')
                ->with(['category', 'condition', 'images'])
                ->orderBy('created_at', 'desc')
                ->take(4)
                ->get();

            // Takođe učitaj i druge usluge istog korisnika
            $userServices = Service::where('user_id', $this->service->user_id)
                ->where('id', '!=', $this->service->id)
                ->where('status', 'active')
                ->with(['category', 'images'])
                ->orderBy('created_at', 'desc')
                ->take(4)
                ->get();

            // Kombinuj oglase i usluge
            $allUserItems = $userListings->concat($userServices)
                ->sortByDesc('created_at')
                ->take(4);

            if ($allUserItems->count() > 0) {
                $this->recommendedListings = $allUserItems;
                $this->recommendationType = 'seller';
            } else {
                // Ako korisnik nema drugih oglasa/usluga, ne prikazuj ništa
                $this->recommendedListings = collect();
                $this->recommendationType = null;
            }
        } else {
            // Za neulogovane korisnike - prikaži slične usluge iz iste kategorije
            $similarServices = Service::where('id', '!=', $this->service->id)
                ->where('category_id', $this->service->category_id)
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