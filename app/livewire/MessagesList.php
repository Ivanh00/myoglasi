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
    public $sortBy = 'all'; // all, unread
    public $search = ''; // Search by user name or listing title

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
            ->where('is_system_message', false) // Samo regularne poruke
            ->where(function($query) use ($userId) {
                // Show messages that aren't deleted by current user
                $query->where(function($q) use ($userId) {
                    $q->where('sender_id', $userId)
                      ->where('deleted_by_sender', false);
                })->orWhere(function($q) use ($userId) {
                    $q->where('receiver_id', $userId)
                      ->where('deleted_by_receiver', false);
                });
            })
            ->with(['listing', 'service', 'sender', 'receiver'])
            ->get()
            ->groupBy(function ($message) use ($userId) {
                // Grupiši po kombinaciji oglas/usluga + drugi korisnik
                $otherUserId = $message->sender_id == $userId
                    ? $message->receiver_id
                    : $message->sender_id;

                // Handle null listing_id and service_id for admin contact
                $itemKey = $message->listing_id
                    ? 'listing-' . $message->listing_id
                    : ($message->service_id
                        ? 'service-' . $message->service_id
                        : 'admin_contact');

                return $itemKey . '-' . $otherUserId;
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
                    return null;
                }
                
                $unreadCount = $messages->where('receiver_id', $userId)
                                    ->where('is_read', false)
                                    ->count();

                return [
                    'listing' => $lastMessage->listing, // Can be null for admin contact
                    'service' => $lastMessage->service, // Can be null if not service-related
                    'other_user' => $otherUser,
                    'last_message' => $lastMessage,
                    'unread_count' => $unreadCount,
                    'message_count' => $messages->count(),
                    'created_at' => $lastMessage->created_at
                ];
            })
            ->filter(function ($conversation) use ($userId) {
                // Filtriraj null vrednosti
                if ($conversation === null) {
                    return false;
                }
                
                // Apply sortBy filter
                if ($this->sortBy === 'unread' && $conversation['unread_count'] == 0) {
                    return false;
                }
                
                // Apply search filter
                if (!empty($this->search)) {
                    $searchTerm = strtolower($this->search);
                    $userName = strtolower($conversation['other_user']->name ?? '');
                    $listingTitle = strtolower($conversation['listing']->title ?? '');
                    $serviceTitle = strtolower($conversation['service']->title ?? '');

                    if (strpos($userName, $searchTerm) === false
                        && strpos($listingTitle, $searchTerm) === false
                        && strpos($serviceTitle, $searchTerm) === false) {
                        return false;
                    }
                }
                
                return true;
            })
            ->sortByDesc('created_at')
            ->values();
    }

    public function selectConversation($conversationKey)
    {
        $conversation = $this->conversations[$conversationKey] ?? null;

        if ($conversation) {
            // Check if this is a service conversation
            if ($conversation['service']) {
                $url = route('service.chat', [
                    'slug' => $conversation['service']->slug,
                    'user' => $conversation['other_user']->id
                ]);

                \Log::info('Redirecting to service conversation', [
                    'url' => $url,
                    'service_slug' => $conversation['service']->slug,
                    'other_user_id' => $conversation['other_user']->id,
                    'auth_id' => Auth::id()
                ]);

                return redirect()->to($url);
            }

            // Check if this is a listing conversation
            if ($conversation['listing']) {
                $url = route('listing.chat', [
                    'slug' => $conversation['listing']->slug,
                    'user' => $conversation['other_user']->id
                ]);

                \Log::info('Redirecting to listing conversation', [
                    'url' => $url,
                    'listing_slug' => $conversation['listing']->slug,
                    'other_user_id' => $conversation['other_user']->id,
                    'auth_id' => Auth::id()
                ]);

                return redirect()->to($url);
            }

            // Admin contact (no listing or service)
            return redirect()->route('admin.contact');
        }
    }

    public function markAsRead($conversationKey)
    {
        $conversation = $this->conversations[$conversationKey] ?? null;
        
        if ($conversation) {
            Message::where('listing_id', $conversation['listing']->id)
                ->where('receiver_id', Auth::id())
                ->where('is_system_message', false) // Samo regularne poruke
                ->update(['is_read' => true]);
            
            $this->loadConversations();
        }
    }

    public function setSort($sort)
    {
        $this->sortBy = $sort;
        $this->loadConversations();
    }
    
    public function updatedSearch()
    {
        $this->loadConversations();
    }
    
    public function updatedSortBy()
    {
        $this->loadConversations();
    }
    
    public function clearSearch()
    {
        $this->search = '';
        $this->loadConversations();
    }

    public function deleteConversation($conversationKey)
    {
        $conversation = $this->conversations[$conversationKey] ?? null;
        
        if ($conversation) {
            $userId = Auth::id();
            
            // Mark all messages in this conversation as deleted by current user
            Message::where('listing_id', $conversation['listing']->id)
                ->where(function($query) use ($userId, $conversation) {
                    $query->where(function($q) use ($userId, $conversation) {
                        $q->where('sender_id', $userId)
                          ->where('receiver_id', $conversation['other_user']->id);
                    })->orWhere(function($q) use ($userId, $conversation) {
                        $q->where('sender_id', $conversation['other_user']->id)
                          ->where('receiver_id', $userId);
                    });
                })
                ->where('is_system_message', false)
                ->get()
                ->each(function($message) use ($userId) {
                    $message->deleteForUser($userId);
                });
            
            session()->flash('success', 'Konverzacija je obrisana.');
            $this->loadConversations();
        }
    }

    public function render()
    {
        $this->loadConversations(); // Ensure conversations are always fresh
        
        return view('livewire.messages-list')
            ->layout('layouts.app');
    }
}