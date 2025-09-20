<?php

namespace App\Livewire\Giveaways;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Listing;
use App\Models\Category;
use App\Traits\HasViewMode;

class Index extends Component
{
    use WithPagination, HasViewMode;

    public $selectedCategory = null;
    public $categories;
    public $sortBy = 'newest';
    public $perPage = 20;

    public function mount()
    {
        $this->mountHasViewMode(); // Initialize view mode from session

        $this->categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function setCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
        $this->resetPage();
    }

    public function setSorting($sort)
    {
        $this->sortBy = $sort;
        $this->resetPage();
    }


    public $showReservationModal = false;
    public $selectedGiveaway = null;
    public $reservationMessage = '';

    public function requestGiveaway($giveawayId)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $this->selectedGiveaway = Listing::with(['user', 'giveawayReservations'])->find($giveawayId);

        if (!$this->selectedGiveaway || $this->selectedGiveaway->listing_type !== 'giveaway') {
            session()->flash('error', 'Poklon nije pronađen.');
            return;
        }

        // Check if user is the owner
        if ($this->selectedGiveaway->user_id === auth()->id()) {
            session()->flash('error', 'Ne možete rezervisati svoj poklon.');
            return;
        }

        // Check if already has a reservation
        if ($this->selectedGiveaway->hasReservationFrom(auth()->id())) {
            session()->flash('error', 'Već ste poslali zahtev za ovaj poklon.');
            return;
        }

        // Check if giveaway is still active
        if ($this->selectedGiveaway->status !== 'active') {
            session()->flash('error', 'Ovaj poklon više nije dostupan.');
            return;
        }

        $this->showReservationModal = true;
    }

    public function submitReservation()
    {
        $this->validate([
            'reservationMessage' => 'required|string|min:10|max:500'
        ], [
            'reservationMessage.required' => 'Poruka je obavezna.',
            'reservationMessage.min' => 'Poruka mora imati najmanje 10 karaktera.',
            'reservationMessage.max' => 'Poruka može imati maksimalno 500 karaktera.'
        ]);

        try {
            $reservation = \App\Models\GiveawayReservation::create([
                'listing_id' => $this->selectedGiveaway->id,
                'requester_id' => auth()->id(),
                'message' => $this->reservationMessage,
                'status' => 'pending'
            ]);

            // Send notification to giveaway owner as system message
            \App\Models\Message::create([
                'sender_id' => auth()->id(),
                'receiver_id' => $this->selectedGiveaway->user_id,
                'listing_id' => $this->selectedGiveaway->id,
                'subject' => 'Novi zahtev za poklon',
                'message' => auth()->user()->name . ' želi vaš poklon "' . $this->selectedGiveaway->title . '". Poruka: ' . $this->reservationMessage,
                'is_system_message' => true,
                'is_read' => false,
                'giveaway_reservation_id' => $reservation->id
            ]);

            session()->flash('success', 'Vaš zahtev je uspešno poslat! Sačekajte odgovor vlasnika.');

            $this->closeReservationModal();
            $this->dispatch('$refresh');
        } catch (\Exception $e) {
            session()->flash('error', 'Greška pri slanju zahteva. Pokušajte ponovo.');
        }
    }

    public function closeReservationModal()
    {
        $this->showReservationModal = false;
        $this->selectedGiveaway = null;
        $this->reservationMessage = '';
    }

    public function render()
    {
        $query = Listing::where('status', 'active')
            ->where('listing_type', 'giveaway')
            ->with(['category', 'subcategory', 'images', 'user', 'condition', 'pendingReservation', 'approvedReservation', 'giveawayReservations' => function($q) {
                $q->where('requester_id', auth()->id() ?? 0);
            }]);
            
        if ($this->selectedCategory) {
            $category = Category::find($this->selectedCategory);
            
            if ($category) {
                $categoryIds = $category->getAllCategoryIds();
                $query->where(function($q) use ($categoryIds) {
                    $q->whereIn('category_id', $categoryIds)
                      ->orWhereIn('subcategory_id', $categoryIds);
                });
            }
        }
        
        // Sorting
        switch ($this->sortBy) {
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
        
        $giveaways = $query->paginate($this->perPage);
        
        return view('livewire.giveaways.index', [
            'giveaways' => $giveaways,
            'categories' => $this->categories
        ])->layout('layouts.app');
    }
}
