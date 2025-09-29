<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Service;
use App\Models\Message;

class ServiceFavoriteButton extends Component
{
    public $service;
    public $isFavorited;
    public $favoritesCount;

    public function mount(Service $service)
    {
        $this->service = $service;
        $this->updateFavoriteStatus();
    }

    public function toggleFavorite()
    {
        if (!Auth::check()) {
            session()->flash('error', 'Morate se prijaviti da biste dodali uslugu u omiljene.');
            return redirect()->route('login');
        }

        if (Auth::id() === $this->service->user_id) {
            session()->flash('error', 'Ne možete dodati svoju uslugu u omiljene.');
            return;
        }

        if ($this->isFavorited) {
            // Ukloni iz omiljenih
            Auth::user()->removeServiceFromFavorites($this->service);
            session()->flash('success', 'Usluga je uklonjena iz omiljenih.');
        } else {
            // Dodaj u omiljene
            Auth::user()->addServiceToFavorites($this->service);

            // Pošalji obaveštenje vlasniku usluge (samo ako već nije poslato)
            if ($this->service->user_id !== Auth::id()) {
                $this->sendFavoriteNotification();
            }

            session()->flash('success', 'Usluga je dodata u omiljene.');
        }

        $this->updateFavoriteStatus();
    }

    private function sendFavoriteNotification()
    {
        // Proveri da li je obaveštenje već poslato za ovog korisnika i uslugu
        $existingNotification = Message::where('service_id', $this->service->id)
            ->where('sender_id', Auth::id())
            ->where('receiver_id', $this->service->user_id)
            ->where('is_system_message', true)
            ->where('message', 'like', '%dodao vašu uslugu%')
            ->first();

        // Ako obaveštenje već postoji, ne šalji ponovo
        if ($existingNotification) {
            return;
        }

        // Kreiraj novo obaveštenje
        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $this->service->user_id,
            'service_id' => $this->service->id,
            'message' => "Korisnik " . Auth::user()->name . " je dodao vašu uslugu '" . $this->service->title . "' u omiljene.",
            'is_system_message' => true,
            'is_read' => false,
        ]);
    }

    private function updateFavoriteStatus()
    {
        $this->isFavorited = Auth::check() ? Auth::user()->hasServiceFavorited($this->service) : false;
        $this->favoritesCount = $this->service->favorites()->count();
    }

    public function render()
    {
        return view('livewire.service-favorite-button');
    }
}