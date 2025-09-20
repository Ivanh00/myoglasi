<?php

namespace App\Livewire\Giveaways;

use Livewire\Component;
use App\Models\GiveawayReservation;
use App\Models\Listing;

class ReservationManager extends Component
{
    public $showModal = false;
    public $listing = null;
    public $reservations = [];
    public $selectedReservation = null;
    public $response = '';

    protected $listeners = ['openReservationManager' => 'openModal'];

    public function openModal($listingId)
    {
        $this->listing = Listing::with(['giveawayReservations.requester'])->find($listingId);

        if (!$this->listing || $this->listing->user_id !== auth()->id()) {
            session()->flash('error', 'Nemate dozvolu da vidite ove zahteve.');
            return;
        }

        if ($this->listing->listing_type !== 'giveaway') {
            session()->flash('error', 'Ovo nije poklon.');
            return;
        }

        $this->reservations = $this->listing->giveawayReservations()
            ->with('requester')
            ->orderBy('created_at', 'asc')
            ->get();

        $this->showModal = true;
    }

    public function selectReservation($reservationId)
    {
        $this->selectedReservation = $this->reservations->find($reservationId);
        $this->response = '';
    }

    public function approveReservation()
    {
        if (!$this->selectedReservation) {
            return;
        }

        $reservation = GiveawayReservation::find($this->selectedReservation->id);

        if (!$reservation || $reservation->listing->user_id !== auth()->id()) {
            session()->flash('error', 'Nemate dozvolu za ovu akciju.');
            return;
        }

        // Reject all other pending reservations
        $this->listing->giveawayReservations()
            ->where('status', 'pending')
            ->where('id', '!=', $reservation->id)
            ->update([
                'status' => 'rejected',
                'response' => 'Poklon je dat drugom korisniku.',
                'responded_at' => now()
            ]);

        // Approve selected reservation
        $reservation->approve($this->response);

        // Update listing status
        $this->listing->update(['status' => 'inactive']);

        // TODO: Send notification to approved user
        // TODO: Send notifications to rejected users

        session()->flash('success', 'Poklon je uspešno dat korisniku ' . $reservation->requester->name);

        $this->closeModal();
        $this->dispatch('$refresh');
    }

    public function rejectReservation()
    {
        if (!$this->selectedReservation) {
            return;
        }

        $reservation = GiveawayReservation::find($this->selectedReservation->id);

        if (!$reservation || $reservation->listing->user_id !== auth()->id()) {
            session()->flash('error', 'Nemate dozvolu za ovu akciju.');
            return;
        }

        $reservation->reject($this->response);

        // TODO: Send notification to rejected user

        session()->flash('success', 'Zahtev je odbijen.');

        // Reload reservations
        $this->openModal($this->listing->id);
        $this->selectedReservation = null;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->listing = null;
        $this->reservations = [];
        $this->selectedReservation = null;
        $this->response = '';
    }

    public function render()
    {
        return view('livewire.giveaways.reservation-manager');
    }
}