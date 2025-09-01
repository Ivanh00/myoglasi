<?php
// app/Livewire/FavoriteButton.php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Listing;
use App\Models\Message;
use App\Notifications\ListingFavorited;

class FavoriteButton extends Component
{
    public $listing;
    public $isFavorited;
    public $favoritesCount;

    public function mount(Listing $listing)
    {
        $this->listing = $listing;
        $this->updateFavoriteStatus();
    }

    public function toggleFavorite()
{
    if (!Auth::check()) {
        session()->flash('error', 'Morate se prijaviti da biste dodali oglas u omiljene.');
        return redirect()->route('login');
    }

    if (Auth::id() === $this->listing->user_id) {
        session()->flash('error', 'Ne možete dodati svoj oglas u omiljene.');
        return;
    }

    if ($this->isFavorited) {
        // Ukloni iz omiljenih
        Auth::user()->removeFromFavorites($this->listing);
        session()->flash('success', 'Oglas je uklonjen iz omiljenih.');
    } else {
        // Dodaj u omiljene
        Auth::user()->addToFavorites($this->listing);
        
        // Pošalji obaveštenje vlasniku oglasa
        if ($this->listing->user_id !== Auth::id()) {
            Message::create([
                'sender_id' => Auth::id(),
                'receiver_id' => $this->listing->user_id,
                'listing_id' => $this->listing->id,
                'message' => "Korisnik " . Auth::user()->name . " je dodao vaš oglas '" . $this->listing->title . "' u omiljene.",
                'is_system_message' => true,
                'is_read' => false,
            ]);
        }
        
        session()->flash('success', 'Oglas je dodat u omiljene.');
    }

    $this->updateFavoriteStatus();
}

    private function updateFavoriteStatus()
    {
        $this->isFavorited = Auth::check() ? Auth::user()->hasFavorited($this->listing) : false;
        $this->favoritesCount = $this->listing->favorites_count;
    }

    public function render()
    {
        return view('livewire.favorite-button');
    }
}