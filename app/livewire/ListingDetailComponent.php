<?php

namespace App\Livewire;

use App\Models\Listing;
use Livewire\Component;

class ListingDetailComponent extends Component
{
    public function render()
    {
        return view('livewire.listing-detail')
        ->layout('layouts.app');
    }

    public $listing;
    public $canContact = false;
    public $showPhoneNumber = false;
    
    public function mount(Listing $listing)
    {
        $this->listing = $listing->load(['user', 'category', 'images']);
        
        // Proverava da li je korisnik ulogovan i može da kontaktira
        $this->canContact = auth()->check() && auth()->id() !== $this->listing->user_id;
        
        // Proverava da li treba prikazati broj telefona
        $this->showPhoneNumber = $this->listing->user->phone_visible && 
                                 !empty($this->listing->user->phone);
    }
    
    public function contactSeller()
    {
        if (!auth()->check()) {
            session()->flash('error', 'Morate se registrovati da biste kontaktirali prodavca.');
            return redirect()->route('login');
        }
        
        return redirect()->route('messages.listing', $this->listing);
    }
}
