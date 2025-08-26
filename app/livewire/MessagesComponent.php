<?php

namespace App\Livewire;

use id;
use App\Models\Listing;
use Livewire\Component;

class MessagesComponent extends Component
{
    public function render()
    {
        return view('livewire.messages-component')
        ->layout('layouts.app');

    }

    public $conversations = [];
    public $selectedConversation = null;
    public $messages = [];
    public $newMessage = '';
    
    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Morate se registrovati da biste pristupili porukama.');
        }
        
        $this->loadConversations();
    }
    
    public function loadConversations()
    {
        $userId = auth()->id();
        
        // Uzmi sve razgovore gde je korisnik učestvovao
        $this->conversations = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['listing', 'sender', 'receiver'])
            ->get()
            ->groupBy('listing_id')
            ->map(function ($messages) use ($userId) {
                $lastMessage = $messages->sortByDesc('created_at')->first();
                $otherUser = $lastMessage->sender_id === $userId 
                    ? $lastMessage->receiver 
                    : $lastMessage->sender;
                
                return [
                    'listing' => $lastMessage->listing,
                    'other_user' => $otherUser,
                    'last_message' => $lastMessage,
                    'unread_count' => $messages->where('receiver_id', $userId)
                                              ->where('is_read', false)
                                              ->count()
                ];
            })
            ->sortByDesc('last_message.created_at');
    }
    
    public function selectConversation($listingId)
    {
        $this->selectedConversation = Listing::find($listingId);
        $this->loadMessages($listingId);
        $this->markAsRead($listingId);
    }
    
    private function loadMessages($listingId)
    {
        $this->messages = Message::where('listing_id', $listingId)
            ->where(function($query) {
                $query->where('sender_id', auth()->id())
                      ->orWhere('receiver_id', auth()->id());
            })
            ->with(['sender'])
            ->orderBy('created_at', 'asc')
            ->get();
    }
    
    private function markAsRead($listingId)
    {
        Message::where('listing_id', $listingId)
            ->where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }
    
    // public function sendMessage()
    // {
    //     if (empty($this->newMessage) || !$this->selectedConversation) {
    //         return;
    //     }
        
    //     Message::create([
    //         'sender_id' => auth()->id(),
    //         'receiver_id' => $this->selectedConversation->user_id,
    //         'listing_id' => $this->selectedConversation->id,
    //         'message' => $this->newMessage
    //     ]);
        
    //     $this->newMessage = '';
    //     $this->loadMessages($this->selectedConversation->id);
    //     $this->loadConversations(); // Refresh conversations list
    // }
}
