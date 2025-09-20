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

        // Get all other pending reservations before rejecting them
        $otherReservations = $this->listing->giveawayReservations()
            ->where('status', 'pending')
            ->where('id', '!=', $reservation->id)
            ->get();

        // Reject all other pending reservations
        foreach ($otherReservations as $otherReservation) {
            $otherReservation->update([
                'status' => 'rejected',
                'response' => 'Poklon je dat drugom korisniku.',
                'responded_at' => now()
            ]);
            // Send rejection notification as system message
            \App\Models\Message::create([
                'sender_id' => auth()->id(),
                'receiver_id' => $otherReservation->requester_id,
                'listing_id' => $this->listing->id,
                'subject' => 'Vaš zahtev je odbijen',
                'message' => 'Nažalost, vaš zahtev za poklon "' . $this->listing->title . '" je odbijen. Poklon je dat drugom korisniku.',
                'is_system_message' => true,
                'is_read' => false
            ]);
        }

        // Approve selected reservation
        $reservation->approve($this->response);

        // Update listing status
        $this->listing->update(['status' => 'inactive']);

        // Send approval notification as system message
        \App\Models\Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $reservation->requester_id,
            'listing_id' => $this->listing->id,
            'subject' => 'Vaš zahtev je odobren! 🎉',
            'message' => 'Čestitamo! Vaš zahtev za poklon "' . $this->listing->title . '" je odobren. ' . ($this->response ? 'Poruka od vlasnika: ' . $this->response : ''),
            'is_system_message' => true,
            'is_read' => false
        ]);

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

        // Send rejection notification as system message
        \App\Models\Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $reservation->requester_id,
            'listing_id' => $this->listing->id,
            'subject' => 'Vaš zahtev je odbijen',
            'message' => 'Nažalost, vaš zahtev za poklon "' . $this->listing->title . '" je odbijen.' . ($this->response ? ' Poruka od vlasnika: ' . $this->response : ''),
            'is_system_message' => true,
            'is_read' => false
        ]);

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