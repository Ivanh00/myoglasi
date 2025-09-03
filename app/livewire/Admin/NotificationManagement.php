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
        \Log::info('SendNotification function called', ['admin_id' => auth()->id(), 'data' => $this->notificationData]);
        
        // Debug notification - let's see if this function is even being called
        $this->dispatch('notify', type: 'info', message: 'sendNotification funkcija pozvana!');
        
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

        try {
            $this->validate($rules);
            $this->dispatch('notify', type: 'info', message: 'Validation prošla!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = collect($e->validator->errors()->all())->join(', ');
            $this->dispatch('notify', type: 'error', message: 'Validation greška: ' . $errors);
            return;
        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Validation error: ' . $e->getMessage());
            return;
        }

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
            \Log::error('Notification sending error: ' . $e->getMessage(), [
                'recipient_type' => $this->notificationData['recipient_type'],
                'recipients_count' => isset($recipients) ? $recipients->count() : 0,
                'admin_id' => auth()->id(),
                'notification_data' => $this->notificationData,
                'trace' => $e->getTraceAsString()
            ]);
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

    public function testRecipients()
    {
        $this->dispatch('notify', type: 'info', message: 'Test funkcija je pozvana! Livewire komunikacija radi.');
        
        try {
            $userCount = User::where('is_banned', false)->count();
            $this->dispatch('notify', type: 'info', message: "Ukupno aktivnih korisnika u bazi: {$userCount}");
            
            // Test direktni query za "all" tip
            if ($this->notificationData['recipient_type'] === 'all') {
                $directCount = User::where('is_banned', false)->count();
                $this->dispatch('notify', type: 'info', message: "Direktni query za 'all' tip: {$directCount} korisnika");
            }
            
            $recipients = $this->getRecipients();
            $debugInfo = [
                'Tip' => $this->notificationData['recipient_type'],
                'Broj korisnika iz getRecipients' => $recipients->count(),
                'listing_id' => $this->notificationData['listing_id'] === '' ? 'prazan string' : ($this->notificationData['listing_id'] ?? 'null'),
                'Naslov prazan' => empty($this->notificationData['title']) ? 'da' : 'ne',
                'Poruka prazna' => empty($this->notificationData['message']) ? 'da' : 'ne',
            ];
            
            if ($this->notificationData['recipient_type'] === 'single') {
                $debugInfo['Odabran korisnik ID'] = $this->notificationData['recipient_id'] ?? 'Nije odabran';
            }
            
            if ($this->notificationData['recipient_type'] === 'filtered') {
                $debugInfo['Filter tip'] = $this->notificationData['filter_criteria']['user_type'];
                if ($this->notificationData['filter_criteria']['user_type'] === 'recent') {
                    $debugInfo['Dani'] = $this->notificationData['filter_criteria']['days'];
                }
            }

            // Test creating one message to see what happens
            if ($recipients->count() > 0) {
                $testRecipient = $recipients->first();
                $debugInfo['Prvi recipient'] = $testRecipient->name;
                $debugInfo['Prvi recipient banned'] = $testRecipient->is_banned ? 'da' : 'ne';
            } else {
                $debugInfo['Problem'] = 'Nema recipijenata pronađeno!';
            }
            
            $message = 'Debug: ' . json_encode($debugInfo, JSON_UNESCAPED_UNICODE);
            $this->dispatch('notify', type: 'info', message: $message);
            
        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Debug error: ' . $e->getMessage() . ' | Line: ' . $e->getLine());
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
