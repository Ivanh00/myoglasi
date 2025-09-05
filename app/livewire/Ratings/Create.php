<?php

namespace App\Livewire\Ratings;

use Livewire\Component;
use App\Models\Rating;
use App\Models\User;
use App\Models\Listing;
use App\Models\Message;

class Create extends Component
{
    public $user;
    public $listing;
    public $rating = null;
    public $comment = '';

    protected $rules = [
        'rating' => 'required|in:positive,neutral,negative',
        'comment' => 'nullable|string|max:500'
    ];

    protected $messages = [
        'rating.required' => 'Molimo odaberite ocenu.',
        'rating.in' => 'Neispravna ocena.',
        'comment.max' => 'Komentar može imati maksimalno 500 karaktera.'
    ];

    public function mount(User $user, Listing $listing)
    {
        $this->user = $user;
        $this->listing = $listing;
        
        // Check if user is trying to rate themselves
        if ($user->id == auth()->id()) {
            session()->flash('error', 'Ne možete oceniti sebe.');
            return redirect()->route('messages.inbox');
        }
        
        // Check if user can rate
        if (!$this->user->canBeRatedBy(auth()->id(), $this->listing->id)) {
            session()->flash('error', 'Već ste ocenili ovog korisnika za ovaj oglas.');
            return redirect()->route('messages.inbox');
        }
        
        // Check if users had conversation about this listing
        $hasConversation = Message::where('listing_id', $this->listing->id)
            ->where('is_system_message', false)
            ->where(function($query) {
                $query->where(function($q) {
                    $q->where('sender_id', auth()->id())
                      ->where('receiver_id', $this->user->id);
                })->orWhere(function($q) {
                    $q->where('sender_id', $this->user->id)
                      ->where('receiver_id', auth()->id());
                });
            })
            ->exists();
            
        if (!$hasConversation) {
            session()->flash('error', 'Možete oceniti samo korisnike sa kojima ste imali komunikaciju.');
            return redirect()->route('messages.inbox');
        }
    }

    public function submitRating()
    {
        $this->validate();
        
        try {
            $rating = Rating::create([
                'rater_id' => auth()->id(),
                'rated_user_id' => $this->user->id,
                'listing_id' => $this->listing->id,
                'rating' => $this->rating,
                'comment' => $this->comment
            ]);
            
            // Send notification to rated user
            Message::create([
                'sender_id' => auth()->id(),
                'receiver_id' => $this->user->id,
                'listing_id' => $this->listing->id,
                'message' => 'Korisnik ' . auth()->user()->name . ' vas je ocenio kao ' . 
                            match($this->rating) {
                                'positive' => 'pozitivno 😊',
                                'neutral' => 'neutralno 😐',
                                'negative' => 'negativno 😞'
                            } . ' za oglas "' . $this->listing->title . '".',
                'subject' => 'Nova ocena od korisnika ' . auth()->user()->name,
                'is_system_message' => true,
                'is_read' => false
            ]);
            
            session()->flash('success', 'Uspešno ste ocenili korisnika!');
            return redirect()->route('messages.inbox');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Greška pri čuvanju ocene. Molimo pokušajte ponovo.');
        }
    }

    public function render()
    {
        return view('livewire.ratings.create')
            ->layout('layouts.app');
    }
}