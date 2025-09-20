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

        // Track unique user view
        if ($this->service) {
            $this->trackUniqueView();
            $this->loadRecommendedListings();
        }
    }

    protected function trackUniqueView()
    {
        $userId = auth()->id();
        $ipAddress = request()->ip();
        $sessionId = session()->getId();

        if ($userId) {
            // For logged-in users, check if they've already viewed this service
            $existingView = \DB::table('service_views')
                ->where('service_id', $this->service->id)
                ->where('user_id', $userId)
                ->first();

            if (!$existingView) {
                // Record the view
                \DB::table('service_views')->insert([
                    'service_id' => $this->service->id,
                    'user_id' => $userId,
                    'ip_address' => $ipAddress,
                    'session_id' => $sessionId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Increment the view count
                $this->service->increment('views');
            }
        } else {
            // For guests, check by session and IP combination
            $existingView = \DB::table('service_views')
                ->where('service_id', $this->service->id)
                ->where('session_id', $sessionId)
                ->where('ip_address', $ipAddress)
                ->whereNull('user_id')
                ->first();

            if (!$existingView) {
                // Record the view
                \DB::table('service_views')->insert([
                    'service_id' => $this->service->id,
                    'user_id' => null,
                    'ip_address' => $ipAddress,
                    'session_id' => $sessionId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Increment the view count
                $this->service->increment('views');
            }
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