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

    public function mount($slug = null)
    {
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

        $this->otherUser = $this->listing->user;
        $this->conversationId = "conversation_{$this->listing->id}_{$this->otherUser->id}";
        
        $this->messages = collect([]);
        $this->loadMessages();
        $this->markMessagesAsRead();
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
                ->get();
                
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
                ->where('receiver_id', Auth::id())
                ->where('is_read', false)
                ->update(['is_read' => true]);
                
            // Ažuriraj i poruke koje je trenutni korisnik poslao a koje je primalac pročitao
            if (Auth::id() !== $this->listing->user_id) {
                // Ako je kupac, označi poruke vlasnika kao pročitane
                Message::where('listing_id', $this->listing->id)
                    ->where('sender_id', $this->listing->user_id)
                    ->where('receiver_id', Auth::id())
                    ->update(['is_read' => true]);
            }
        } catch (\Exception $e) {
            Log::error('Error marking messages as read: ' . $e->getMessage());
        }
    }


    // Dodajte ovaj event listener za Livewire
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

    public function sendMessage()
    {
        // Proverite da li korisnik pokušava da pošalje poruku samom sebi
        if (Auth::id() === $this->listing->user_id) {
            // Vlasnik oglasa želi da odgovori - pronađite originalnog pošioca
            $originalSender = $this->getOriginalSender();
            
            if (!$originalSender) {
                $this->addError('newMessage', 'Nema sa kim da razgovarate.');
                return;
            }
            
            $receiverId = $originalSender->id;
        } else {
            // Kupac šalje poruku vlasniku oglasa
            $receiverId = $this->listing->user_id;
        }

        // Manualna validacija umesto $this->validate()
        $validator = Validator::make(
            ['newMessage' => $this->newMessage],
            ['newMessage' => 'required|string|max:1000|min:1']
        );

        if ($validator->fails()) {
            $this->addError('newMessage', $validator->errors()->first('newMessage'));
            return;
        }

        // Sprečite dupli submit
        if (empty(trim($this->newMessage))) {
            return;
        }

        try {
            $message = new Message();
            $message->sender_id = Auth::id();
            $message->receiver_id = $receiverId;
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
    
    // Dodajte ovu metodu za automatsko označavanje poruka kao pročitanih
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
    

    private function getOriginalSender()
    {
        // Pronađite prvog pošioca koji nije vlasnik oglasa
        $originalMessage = Message::where('listing_id', $this->listing->id)
            ->where('sender_id', '!=', $this->listing->user_id)
            ->first();
        
        return $originalMessage ? $originalMessage->sender : null;
    }

    public function loadAddresses()
    {
        $this->addresses = [];
    }

    public function toggleAddressList()
    {
        $this->showAddressList = !$this->showAddressList;
    }
    
    // Nova metoda za praćenje kada su poruke viđene
    public function checkMessagesReadStatus()
    {
        // Proveri da li je primalac poruka (drugi korisnik) trenutno aktivan na stranici
        // Ovo je pojednostavljen primer - u realnoj aplikaciji biste koristili WebSockete ili polling
        if (Auth::id() !== $this->listing->user_id) {
            // Ako je kupac, označi poruke vlasnika kao pročitane
            Message::where('listing_id', $this->listing->id)
                ->where('sender_id', $this->listing->user_id)
                ->where('receiver_id', Auth::id())
                ->update(['is_read' => true]);
        } else {
            // Ako je vlasnik, označi poruke kupca kao pročitane
            $originalSender = $this->getOriginalSender();
            if ($originalSender) {
                Message::where('listing_id', $this->listing->id)
                    ->where('sender_id', $originalSender->id)
                    ->where('receiver_id', Auth::id())
                    ->update(['is_read' => true]);
            }
        }
        
        $this->loadMessages();
    }

    public function render()
    {
        // Proveri status pročitanosti poruka pri renderovanju
        $this->checkMessagesReadStatus();
        
        return view('livewire.conversation-component')
            ->layout('layouts.app');
    }
}