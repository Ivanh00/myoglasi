<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Message;
use App\Models\Listing;

class NotificationManagement extends Component
{
    use WithPagination;

    public $showSendModal = false;
    public $showSentNotifications = false;
    
    public $notificationData = [
        'recipient_type' => 'single', // single, all, filtered
        'recipient_id' => null,
        'title' => '',
        'message' => '',
        'listing_id' => '',
        'filter_criteria' => [
            'user_type' => 'all', // all, with_listings, with_balance, recent
            'days' => 30
        ]
    ];

    public $searchUser = '';
    public $foundUsers = [];

    public function mount()
    {
        $this->foundUsers = collect();
        
        // Auto-populate user if coming from user management
        if (request()->has('user_id')) {
            $userId = request('user_id');
            $user = User::find($userId);
            if ($user) {
                $this->notificationData['recipient_type'] = 'single';
                $this->notificationData['recipient_id'] = $userId;
                $this->searchUser = $user->name . ' (' . $user->email . ')';
                $this->showSendModal = true;
            }
        }
    }

    public function updatedSearchUser()
    {
        if (strlen($this->searchUser) >= 2) {
            $this->foundUsers = User::where('name', 'like', '%' . $this->searchUser . '%')
                ->orWhere('email', 'like', '%' . $this->searchUser . '%')
                ->take(10)
                ->get(['id', 'name', 'email', 'is_banned']);
        } else {
            $this->foundUsers = collect();
        }
    }

    public function updatedNotificationDataRecipientType()
    {
        // Reset listing_id when changing recipient type
        if ($this->notificationData['recipient_type'] !== 'single') {
            $this->notificationData['listing_id'] = '';
        }
        
        // Reset recipient_id when changing from single to other types
        if ($this->notificationData['recipient_type'] !== 'single') {
            $this->notificationData['recipient_id'] = null;
            $this->searchUser = '';
            $this->foundUsers = collect();
        }
    }

    public function selectUser($userId)
    {
        $user = User::find($userId);
        $this->notificationData['recipient_id'] = $userId;
        $this->searchUser = $user->name . ' (' . $user->email . ')';
        $this->foundUsers = collect();
    }

    public function openSendModal()
    {
        $this->resetNotificationForm();
        $this->showSendModal = true;
    }

    public function resetNotificationForm()
    {
        $this->notificationData = [
            'recipient_type' => 'single',
            'recipient_id' => null,
            'title' => '',
            'message' => '',
            'listing_id' => '',
            'filter_criteria' => [
                'user_type' => 'all',
                'days' => 30
            ]
        ];
        $this->searchUser = '';
        $this->foundUsers = collect();
    }

    public function sendNotification()
    {
        // Clean up data before validation
        if (empty($this->notificationData['listing_id']) || $this->notificationData['listing_id'] === '') {
            $this->notificationData['listing_id'] = null;
        }

        $rules = [
            'notificationData.title' => 'required|string|max:255',
            'notificationData.message' => 'required|string|max:1000',
            'notificationData.recipient_type' => 'required|in:single,all,filtered',
        ];

        // Add recipient_id validation only for single type
        if ($this->notificationData['recipient_type'] === 'single') {
            $rules['notificationData.recipient_id'] = 'required|exists:users,id';
        }

        $this->validate($rules);

        try {
            $recipients = $this->getRecipients();
            
            if ($recipients->isEmpty()) {
                $this->dispatch('notify', type: 'error', message: 'Nema korisnika koji odgovaraju kriterijumima!');
                return;
            }

            $sentCount = 0;

            foreach ($recipients as $recipient) {
                // Skip banned users when sending notifications
                if ($recipient->is_banned) {
                    continue;
                }

                $messageData = [
                    'sender_id' => auth()->id(),
                    'receiver_id' => $recipient->id,
                    'listing_id' => !empty($this->notificationData['listing_id']) && $this->notificationData['listing_id'] !== '' ? $this->notificationData['listing_id'] : null,
                    'message' => $this->notificationData['message'],
                    'is_system_message' => true,
                    'is_read' => false,
                    'subject' => $this->notificationData['title'],
                ];

                Message::create($messageData);
                $sentCount++;
            }

            $this->showSendModal = false;
            $this->resetNotificationForm();
            $this->dispatch('notify', type: 'success', message: "Obaveštenje uspešno poslano na {$sentCount} korisnika!");

        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Greška pri slanju: ' . $e->getMessage());
        }
    }

    private function getRecipients()
    {
        switch ($this->notificationData['recipient_type']) {
            case 'single':
                return User::where('id', $this->notificationData['recipient_id'])->get();
                
            case 'all':
                return User::where('is_banned', false)->get();
                
            case 'filtered':
                $query = User::where('is_banned', false);
                
                switch ($this->notificationData['filter_criteria']['user_type']) {
                    case 'with_listings':
                        $query->has('listings');
                        break;
                    case 'with_balance':
                        $query->where('balance', '>', 0);
                        break;
                    case 'recent':
                        $query->where('created_at', '>=', now()->subDays($this->notificationData['filter_criteria']['days']));
                        break;
                }
                
                return $query->get();
                
            default:
                return collect();
        }
    }

    public function getRecipientsCount()
    {
        try {
            return $this->getRecipients()->count();
        } catch (\Exception $e) {
            return 0;
        }
    }


    public function render()
    {
        $sentNotifications = Message::where('sender_id', auth()->id())
            ->where('is_system_message', true)
            ->with(['receiver', 'listing'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Show listings of selected user if single notification type
        $availableListings = collect();
        if ($this->notificationData['recipient_type'] === 'single' && $this->notificationData['recipient_id']) {
            $availableListings = Listing::where('user_id', $this->notificationData['recipient_id'])
                ->latest()
                ->take(10)
                ->get(['id', 'title']);
        } else {
            // Show recent listings for general notifications
            $availableListings = Listing::latest()->take(10)->get(['id', 'title']);
        }

        return view('livewire.admin.notification-management', [
            'sentNotifications' => $sentNotifications,
            'availableListings' => $availableListings
        ])->layout('layouts.admin');
    }
}
