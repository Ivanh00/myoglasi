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
        
        $this->conversations = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['listing', 'sender', 'receiver'])
            ->get()
            ->groupBy(function ($message) {
                return $message->listing_id . '-' . 
                       min($message->sender_id, $message->receiver_id) . '-' . 
                       max($message->sender_id, $message->receiver_id);
            })
            ->map(function ($messages) use ($userId) {
                $lastMessage = $messages->sortByDesc('created_at')->first();
                $otherUser = $lastMessage->sender_id === $userId 
                    ? $lastMessage->receiver 
                    : $lastMessage->sender;
                
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
            ->sortByDesc('created_at')
            ->values();
    }

    public function selectConversation($conversationKey)
    {
        $conversation = $this->conversations[$conversationKey] ?? null;
        
        if ($conversation) {
            return redirect()->route('listing.chat', [
                'slug' => $conversation['listing']->slug
            ]);
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

    protected $listeners = ['refreshConversations' => 'loadConversations'];

public function getListeners()
{
    return [
        "echo-private:conversation.*." . Auth::id() . ",NewMessage" => 'refreshConversations',
        "echo-private:conversation.*." . Auth::id() . ",MessageRead" => 'refreshConversations',
    ];
}

    public function render()
    {
        return view('livewire.messages-list')
            ->layout('layouts.app');
    }
}