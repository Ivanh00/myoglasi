<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Message;

class AdminContact extends Component
{
    public $message = '';
    public $subject = '';
    public $admin;
    public $conversation = [];
    
    protected $rules = [
        'subject' => 'required|string|max:200',
        'message' => 'required|string|min:10|max:2000'
    ];

    protected function messages()
    {
        return [
            'subject.required' => 'Naslov je obavezan.',
            'subject.max' => 'Naslov može imati maksimalno 200 karaktera.',
            'message.required' => 'Poruka je obavezna.',
            'message.min' => 'Poruka mora imati najmanje 10 karaktera.',
            'message.max' => 'Poruka može imati maksimalno 2000 karaktera.'
        ];
    }

    public function mount()
    {
        if (auth()->user()->is_admin) {
            // If current user is admin, find the other user from request or conversation
            $otherUserId = request('user');
            
            if ($otherUserId) {
                $this->admin = User::find($otherUserId); // Actually the "other user"
            } else {
                // Find the most recent user who contacted admin
                $recentMessage = Message::where(function($query) {
                        $query->where('receiver_id', auth()->id())
                              ->orWhere('sender_id', auth()->id());
                    })
                    ->whereNull('listing_id')
                    ->where('is_system_message', false)
                    ->latest()
                    ->with(['sender', 'receiver'])
                    ->first();
                    
                if ($recentMessage) {
                    $this->admin = $recentMessage->sender_id == auth()->id() 
                        ? $recentMessage->receiver 
                        : $recentMessage->sender;
                } else {
                    session()->flash('error', 'Nema aktivnih konverzacija.');
                    return redirect()->route('admin.messages.index');
                }
            }
        } else {
            // Regular user - find admin
            $this->admin = User::where('is_admin', true)->first();
            
            if (!$this->admin) {
                session()->flash('error', 'Greška: Admin nije pronađen. Molimo kontaktirajte podršku.');
                return redirect()->route('home');
            }
        }
        
        $this->loadMessages();
    }

    public function loadMessages()
    {
        $this->conversation = Message::where(function($query) {
                $query->where(function($q) {
                    $q->where('sender_id', auth()->id())
                      ->where('receiver_id', $this->admin->id);
                })->orWhere(function($q) {
                    $q->where('sender_id', $this->admin->id)
                      ->where('receiver_id', auth()->id());
                });
            })
            ->whereNull('listing_id') // General admin communication, not listing-specific
            ->where('is_system_message', false)
            ->orderBy('created_at', 'asc')
            ->get();
            
        // Mark messages from admin as read
        Message::where('sender_id', $this->admin->id)
            ->where('receiver_id', auth()->id())
            ->whereNull('listing_id')
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    public function sendMessage()
    {
        \Log::info('AdminContact sendMessage called', [
            'user_id' => auth()->id(),
            'admin_id' => $this->admin->id ?? 'null',
            'message' => $this->message,
            'subject' => $this->subject
        ]);
        
        // Validate required fields
        if (empty($this->message)) {
            session()->flash('error', 'Poruka ne može biti prazna.');
            return;
        }

        if (count($this->conversation) == 0 && empty($this->subject)) {
            session()->flash('error', 'Naslov je obavezan za prvu poruku.');
            return;
        }

        try {
            Message::create([
                'sender_id' => auth()->id(),
                'receiver_id' => $this->admin->id, // This is now the "other user" not always admin
                'listing_id' => null, // General communication
                'subject' => $this->subject ?: null,
                'message' => $this->message,
                'is_system_message' => false,
                'is_read' => false
            ]);

            // Clear form
            $this->message = '';
            if (count($this->conversation) == 0) {
                $this->subject = '';
            }
            
            // Reload messages
            $this->loadMessages();
            
            $successMessage = auth()->user()->is_admin 
                ? 'Poruka je poslata korisniku.' 
                : 'Poruka je poslata administratoru.';
            session()->flash('success', $successMessage);
            
            // Dispatch event to scroll to bottom
            $this->dispatch('message-sent');
            
        } catch (\Exception $e) {
            \Log::error('Error sending admin message: ' . $e->getMessage());
            session()->flash('error', 'Greška pri slanju poruke: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin-contact')
            ->layout('layouts.app');
    }
}