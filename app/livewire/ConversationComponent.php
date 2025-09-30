<?php

namespace App\Livewire;

use Log;
use Validator;
use App\Models\User;
use App\Models\Listing;
use App\Models\Service;
use App\Models\Message;
use Livewire\Component;
use App\Events\MessageRead;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class ConversationComponent extends Component
{
    public $listing;
    public $service;
    public $otherUser;
    public $messages;
    public $newMessage = '';
    public $conversationId;
    public $showAddressList = false;
    public $addresses = [];

    protected $listeners = ['messageSent' => 'loadMessages'];

    public function mount($slug = null, $user = null)
    {
        \Log::info('CONVERSATION MOUNT START', [
            'slug' => $slug,
            'user_param' => $user,
            'full_url' => request()->fullUrl(),
            'route_name' => request()->route()->getName()
        ]);

        if (!$slug) {
            abort(404, 'Sadržaj nije pronađen');
        }

        // Determine if this is a listing or service conversation based on route
        $routeName = request()->route()->getName();

        if ($routeName === 'service.chat') {
            $this->service = Service::where('slug', $slug)->with('user')->first();

            if (!$this->service) {
                abort(404, 'Usluga nije pronađena');
            }

            $owner = $this->service->user;
        } else {
            $this->listing = Listing::where('slug', $slug)->with('user')->first();

            if (!$this->listing) {
                abort(404, 'Oglas nije pronađen');
            }

            $owner = $this->listing->user;
        }
        
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Morate se prijaviti da biste slali poruke.'); 
        }

        // Određivanje drugog korisnika u konverzaciji
        if ($user) {
            // Ako je prosleđen ID drugog korisnika (iz URL parametra)
            $this->otherUser = User::find($user);
        } else {
            // Ako nije prosleđen ID, to znači da kupac otvara konverzaciju sa vlasnikom
            $this->otherUser = $owner;
        }

        if (!$this->otherUser) {
            abort(404, 'Korisnik nije pronađen');
        }

        \Log::info('ConversationComponent mount parameters', [
            'slug' => $slug,
            'user_param' => $user,
            'listing_id' => $this->listing ? $this->listing->id : null,
            'service_id' => $this->service ? $this->service->id : null,
            'auth_id' => Auth::id(),
            'other_user_id' => $this->otherUser->id,
            'are_auth_and_other_equal' => Auth::id() == $this->otherUser->id
        ]);
    
        // Ako je otherUser isti kao trenutni korisnik, to je greška
        if (Auth::id() == $this->otherUser->id) {
            \Log::error('Conversation with self detected', [
                'auth_id' => Auth::id(),
                'other_user_id' => $this->otherUser->id,
                'user_parameter' => $user
            ]);
            
            session()->flash('error', 'Ne možete poslati poruku samom sebi.');
            return redirect()->route('messages.inbox');
        }

        $itemType = $this->listing ? 'listing' : 'service';
        $itemId = $this->listing ? $this->listing->id : $this->service->id;
        $this->conversationId = "conversation_{$itemType}_{$itemId}_{$this->otherUser->id}";
        
        $this->messages = collect([]);
        $this->loadMessages();
        $this->markMessagesAsRead();
    }

    public function loadMessages()
    {
        try {
            \Log::info('Loading messages for conversation', [
                'listing_id' => $this->listing ? $this->listing->id : null,
                'service_id' => $this->service ? $this->service->id : null,
                'auth_id' => Auth::id(),
                'other_user_id' => $this->otherUser->id
            ]);

            $query = Message::query();

            if ($this->listing) {
                $query->where('listing_id', $this->listing->id);
            } elseif ($this->service) {
                $query->where('service_id', $this->service->id);
            }

            $this->messages = $query
                ->where(function($query) {
                    $query->where(function($q) {
                        $q->where('sender_id', Auth::id())
                        ->where('receiver_id', $this->otherUser->id);
                    })->orWhere(function($q) {
                        $q->where('sender_id', $this->otherUser->id)
                        ->where('receiver_id', Auth::id());
                    });
                })
                ->where('is_system_message', false) // 👈 Samo regularne poruke
                ->with(['sender', 'receiver'])
                ->orderBy('created_at', 'asc')
                ->get();

            \Log::info('Filtered messages for conversation', [
                'auth_id' => Auth::id(),
                'other_user_id' => $this->otherUser->id,
                'count' => $this->messages->count()
            ]);
                
            $this->dispatch('scrollToBottom');
        } catch (\Exception $e) {
            Log::error('Error loading messages: ' . $e->getMessage());
            $this->messages = collect([]);
        }
    }

    public function markMessagesAsRead()
    {
        try {
            $query = Message::query();

            if ($this->listing) {
                $query->where('listing_id', $this->listing->id);
            } elseif ($this->service) {
                $query->where('service_id', $this->service->id);
            }

            $query->where('sender_id', $this->otherUser->id)
                ->where('receiver_id', Auth::id())
                ->where('is_system_message', false) // Only mark regular messages as read, NOT notifications
                ->where('is_read', false)
                ->update(['is_read' => true]);
        } catch (\Exception $e) {
            Log::error('Error marking messages as read: ' . $e->getMessage());
        }
    }

    public function sendMessage()
    {
        // Proverite da li korisnik pokušava da pošalje poruku samom sebi
        if (Auth::id() === $this->otherUser->id) {
            $this->addError('newMessage', 'Ne možete slati poruke samom sebi.');
            return;
        }

        // Manualna validacija
        $validator = Validator::make(
            ['newMessage' => $this->newMessage],
            ['newMessage' => 'required|string|max:1000|min:1']
        );

        if ($validator->fails()) {
            $this->addError('newMessage', $validator->errors()->first('newMessage'));
            return;
        }

        if (empty(trim($this->newMessage))) {
            return;
        }

        try {
            $message = new Message();
            $message->sender_id = Auth::id();
            $message->receiver_id = $this->otherUser->id;
            $message->listing_id = $this->listing ? $this->listing->id : null;
            $message->service_id = $this->service ? $this->service->id : null;
            $message->message = trim($this->newMessage);
            $message->is_read = false;
            $message->is_system_message = false; // 👈 Uvek false za regularne poruke
            $message->save();

            $this->newMessage = '';
            $this->loadMessages();
            
        } catch (\Exception $e) {
            \Log::error('Error sending message: ' . $e->getMessage());
            session()->flash('error', 'Došlo je do greške pri slanju poruke.');
        }
    }

    #[On('messageRead')]
    public function handleMessageRead($messageId)
    {
        $message = Message::find($messageId);
        if ($message && $message->receiver_id === Auth::id()) {
            $message->is_read = true;
            $message->save();
            $this->loadMessages();
        }
    }

    public function markMessageAsRead($messageId)
    {
        $message = Message::find($messageId);
        
        if ($message && $message->receiver_id === Auth::id() && !$message->is_read) {
            $message->is_read = true;
            $message->save();
            
            // Emituj event da je poruka pročitana
            broadcast(new MessageRead($message, $message->receiver_id));
            
            $this->loadMessages();
        }
    }
    
    public function markAllMessagesAsRead()
    {
        $query = Message::query();

        if ($this->listing) {
            $query->where('listing_id', $this->listing->id);
        } elseif ($this->service) {
            $query->where('service_id', $this->service->id);
        }

        $unreadMessages = $query->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->where('is_system_message', false) // 👈 Samo regularne poruke
            ->get();
    
        foreach ($unreadMessages as $message) {
            $message->is_read = true;
            $message->save();
            
            // Emituj event za svaku pročitanu poruku
            broadcast(new MessageRead($message, $message->receiver_id));
        }
        
        $this->loadMessages();
    }

    public function loadAddresses()
    {
        $this->addresses = [];
    }

    public function toggleAddressList()
    {
        $this->showAddressList = !$this->showAddressList;
    }
    
    public function checkMessagesReadStatus()
    {
        if (Auth::id() !== $this->otherUser->id) {
            $query = Message::query();

            if ($this->listing) {
                $query->where('listing_id', $this->listing->id);
            } elseif ($this->service) {
                $query->where('service_id', $this->service->id);
            }

            $query->where('sender_id', $this->otherUser->id)
                ->where('receiver_id', Auth::id())
                ->where('is_system_message', false) // 👈 Samo regularne poruke
                ->update(['is_read' => true]);
        }

        $this->loadMessages();
    }

    public function render()
    {
        $this->checkMessagesReadStatus();
        
        return view('livewire.conversation-component')
            ->layout('layouts.app');
    }
}