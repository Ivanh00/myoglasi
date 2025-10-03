<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Business;
use App\Models\Message;

class BusinessFavoriteButton extends Component
{
    public $business;
    public $isFavorited;
    public $favoritesCount;

    public function mount(Business $business)
    {
        $this->business = $business;
        $this->updateFavoriteStatus();
    }

    public function toggleFavorite()
    {
        if (!Auth::check()) {
            session()->flash('error', 'Morate se prijaviti da biste dodali biznis karticu u omiljene.');
            return redirect()->route('login');
        }

        if (Auth::id() === $this->business->user_id) {
            session()->flash('error', 'Ne možete dodati svoju biznis karticu u omiljene.');
            return;
        }

        if ($this->isFavorited) {
            // Ukloni iz omiljenih
            Auth::user()->removeBusinessFromFavorites($this->business);
            session()->flash('success', 'Biznis kartica je uklonjena iz omiljenih.');
        } else {
            // Dodaj u omiljene
            Auth::user()->addBusinessToFavorites($this->business);

            // Pošalji obaveštenje vlasniku biznisa (samo ako već nije poslato)
            if ($this->business->user_id !== Auth::id()) {
                $this->sendFavoriteNotification();
            }

            session()->flash('success', 'Biznis kartica je dodata u omiljene.');
        }

        $this->updateFavoriteStatus();
    }

    private function sendFavoriteNotification()
    {
        // Proveri da li je obaveštenje već poslato za ovog korisnika i biznis
        $existingNotification = Message::where('sender_id', Auth::id())
            ->where('receiver_id', $this->business->user_id)
            ->where('is_system_message', true)
            ->where('message', 'like', '%dodao vaš biznis%')
            ->first();

        // Ako obaveštenje već postoji, ne šalji ponovo
        if ($existingNotification) {
            return;
        }

        // Kreiraj novo obaveštenje
        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $this->business->user_id,
            'message' => "Korisnik " . Auth::user()->name . " je dodao vašu biznis karticu '" . $this->business->name . "' u omiljene.",
            'is_system_message' => true,
            'is_read' => false,
        ]);
    }

    private function updateFavoriteStatus()
    {
        $this->isFavorited = Auth::check() ? Auth::user()->hasBusinessFavorited($this->business) : false;
        $this->favoritesCount = $this->business->favorites_count;
    }

    public function render()
    {
        return view('livewire.business-favorite-button');
    }
}
