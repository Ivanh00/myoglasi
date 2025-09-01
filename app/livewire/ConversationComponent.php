<?php

namespace App\Livewire;

use Log;
use Validator;
use App\Models\User;
use App\Models\Listing;
use App\Models\Message;
use Livewire\Component;
use App\Events\MessageRead;
use Livewire\Attributes\On;
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

    public function mount($slug = null, $user = null)
    {
        \Log::info('CONVERSATION MOUNT START', [
            'slug' => $slug,
            'user_param' => $user,
            'full_url' => request()->fullUrl()
        ]);

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

        // Određivanje drugog korisnika u konverzaciji
        if ($user) {
            // Ako je prosleđen ID drugog korisnika (iz URL parametra)
            $this->otherUser = User::find($user);
        } else {
            // Ako nije prosleđen ID, to znači da kupac otvara konverzaciju sa vlasnikom oglasa
            $this->otherUser = $this->listing->user;
        }

        if (!$this->otherUser) {
            abort(404, 'Korisnik nije pronađen');
        }

        \Log::info('ConversationComponent mount parameters', [
            'slug' => $slug,
            'user_param' => $user,
            'listing_id' => $this->listing->id,
            'listing_user_id' => $this->listing->user_id,
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

        $this->conversationId = "conversation_{$this->listing->id}_{$this->otherUser->id}";
        
        $this->messages = collect([]);
        $this->loadMessages();
        $this->markMessagesAsRead();
    }

    public function loadMessages()
    {
        try {
            \Log::info('Loading messages for conversation', [
                'listing_id' => $this->listing->id,
                'auth_id' => Auth::id(),
                'other_user_id' => $this->otherUser->id
            ]);

            $this->messages = Message::where('listing_id', $this->listing->id)
                ->where(function($query) {
                    $query->where(function($q) {
                        $q->where('sender_id', Auth::id())
                        ->where('receiver_id', $this->otherUser->id);
                    })->orWhere(function($q) {
                        $q->where('sender_id', $this->otherUser->id)
                        ->where('receiver_id', Auth::id());
                    });
                })
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
            Message::where('listing_id', $this->listing->id)
                ->where('sender_id', $this->otherUser->id)
                ->where('receiver_id', Auth::id())
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
            $message->listing_id = $this->listing->id;
            $message->message = trim($this->newMessage);
            $message->is_read = false;
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
        $unreadMessages = Message::where('listing_id', $this->listing->id)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
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
    if (!$this->isSystemConversation && Auth::id() !== $this->otherUser->id) {
        Message::where('listing_id', $this->listing->id)
            ->where('sender_id', $this->otherUser->id)
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