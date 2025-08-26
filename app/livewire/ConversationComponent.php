<?php

namespace App\Livewire;

use Log;
use App\Models\User;
use App\Models\Listing;
use App\Models\Message;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ConversationComponent extends Component
{
    public $listing;
    public $otherUser;
    public $messages;
    public $newMessage = '';
    public $conversationId;
    public $showAddressList = false;
    public $addresses = [];

    protected $listeners = ['messageSent' => 'loadMessages'];

    public function mount($slug = null)
    {
        if (!$slug) {
            $slug = request()->route('slug') ?? 
                   request()->route('listingSlug') ?? 
                   request()->route('listing');
        }

        if (!$slug) {
            abort(404, 'Oglas nije pronađen');
        }

        $this->listing = Listing::where('slug', $slug)->with('user')->first();

        if (!$this->listing) {
            abort(404, 'Oglas nije pronađen');
        }
        
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Morate se prijaviti da biste slali poruke.');
        }

        if (Auth::id() === $this->listing->user_id) {
            abort(403, 'Ne možete slati poruke samom sebi.');
        }

        $this->otherUser = $this->listing->user;
        $this->conversationId = "conversation_{$this->listing->id}_{$this->otherUser->id}";
        
        // Inicijalizujte messages kao praznu kolekciju
        $this->messages = collect([]);
        
        $this->loadMessages();
        $this->markMessagesAsRead();
        $this->loadAddresses();
    }

    public function loadMessages()
    {
        try {
            $this->messages = Message::where('listing_id', $this->listing->id)
                ->where(function($query) {
                    $query->where('sender_id', Auth::id())
                          ->orWhere('receiver_id', Auth::id());
                })
                ->with(['sender', 'receiver'])
                ->orderBy('created_at', 'asc')
                ->get(); // Ovo vraća Collection
                
            $this->dispatch('scrollToBottom');
        } catch (\Exception $e) {
            \Log::error('Error loading messages: ' . $e->getMessage());
            $this->messages = collect([]); // Vratite praznu Collection
        }
    }

    public function markMessagesAsRead()
    {
        try {
            Message::where('listing_id', $this->listing->id)
                ->where('receiver_id', Auth::id())
                ->where('is_read', false)
                ->update(['is_read' => true]);
        } catch (\Exception $e) {
            \Log::error('Error marking messages as read: ' . $e->getMessage());
        }
    }

    public function sendMessage()
{
    $this->validate([
        'newMessage' => 'required|string|max:1000',
    ]);

    try {
        // Umesto Message::create(), koristite new Message()
        $message = new Message();
        $message->sender_id = Auth::id();
        $message->receiver_id = $this->listing->user_id;
        $message->listing_id = $this->listing->id;
        $message->message = $this->newMessage;
        $message->save();

        $this->newMessage = '';
        $this->loadMessages();
        $this->dispatch('messageSent');

    } catch (\Exception $e) {
        \Log::error('Error sending message: ' . $e->getMessage());
        \Log::error('Error trace: ' . $e->getTraceAsString());
        session()->flash('error', 'Došlo je do greške pri slanju poruke.');
    }
}

    public function loadAddresses()
    {
        $this->addresses = [];
    }

    public function toggleAddressList()
    {
        $this->showAddressList = !$this->showAddressList;
    }

    public function sendAddress($addressId)
    {
        $addressText = "Moja adresa: Primer adrese 123, Beograd, 063123456";
        
        try {
            Message::create([
                'sender_id' => Auth::id(),
                'receiver_id' => $this->listing->user_id,
                'listing_id' => $this->listing->id,
                'message' => $addressText,
            ]);
            
            $this->loadMessages();
            $this->showAddressList = false;
        } catch (\Exception $e) {
            \Log::error('Error sending address: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.conversation-component')
            ->layout('layouts.app');
    }
}