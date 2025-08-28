<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Message;
use App\Models\Listing;
use Illuminate\Support\Facades\Auth;

class MessagesList extends Component
{
    public $conversations = [];
    public $selectedConversation = null;
    public $sortBy = 'all'; // all, unread, starred

    public function mount()
    {
        $this->loadConversations();
    }

    public function loadConversations()
    {
        $userId = Auth::id();
        
        $this->conversations = Message::where(function($query) use ($userId) {
                $query->where('sender_id', $userId)
                    ->orWhere('receiver_id', $userId);
            })
            ->with(['listing', 'sender', 'receiver'])
            ->get()
            ->groupBy(function ($message) use ($userId) {
                // Grupiši po kombinaciji oglas + drugi korisnik
                $otherUserId = $message->sender_id == $userId 
                    ? $message->receiver_id 
                    : $message->sender_id;
                    
                return $message->listing_id . '-' . $otherUserId;
            })
            ->map(function ($messages) use ($userId) {
                $lastMessage = $messages->sortByDesc('created_at')->first();
                
                // Odredi drugog korisnika u konverzaciji
                $otherUser = $lastMessage->sender_id == $userId 
                    ? $lastMessage->receiver 
                    : $lastMessage->sender;
                
                // PROVERA: Da li otherUser nije null i da li je različit od trenutnog korisnika
                if (!$otherUser || $otherUser->id == $userId) {
                    \Log::error('Invalid other user in conversation', [
                        'user_id' => $userId,
                        'other_user' => $otherUser,
                        'last_message' => $lastMessage->toArray()
                    ]);
                }
                
                $unreadCount = $messages->where('receiver_id', $userId)
                                    ->where('is_read', false)
                                    ->count();

                return [
                    'listing' => $lastMessage->listing,
                    'other_user' => $otherUser,
                    'last_message' => $lastMessage,
                    'unread_count' => $unreadCount,
                    'message_count' => $messages->count(),
                    'created_at' => $lastMessage->created_at
                ];
            })
            ->filter(function ($conversation) use ($userId) {
                // Filtriraj konverzacije gde other_user postoji i nije trenutni korisnik
                return $conversation['other_user'] && $conversation['other_user']->id != $userId;
            })
            ->sortByDesc('created_at')
            ->values();
    }

    public function selectConversation($conversationKey)
{
    $conversation = $this->conversations[$conversationKey] ?? null;
    
    if ($conversation) {
        if (Auth::id() === $conversation['listing']->user_id) {
            return redirect()->route('listing.chat', [
                'slug' => $conversation['listing']->slug,
                'user' => $conversation['other_user']->id
            ]);
        } else {
            return redirect()->to('/oglasi/'.$conversation['listing']->slug.'/poruka?user='.$conversation['other_user']->id);
        }
    }
}

    public function markAsRead($conversationKey)
    {
        $conversation = $this->conversations[$conversationKey] ?? null;
        
        if ($conversation) {
            Message::where('listing_id', $conversation['listing']->id)
                ->where('receiver_id', Auth::id())
                ->update(['is_read' => true]);
            
            $this->loadConversations();
        }
    }

    public function setSort($sort)
    {
        $this->sortBy = $sort;
        $this->loadConversations();
    }

    public function render()
    {
        return view('livewire.messages-list')
            ->layout('layouts.app');
    }
}