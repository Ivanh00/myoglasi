<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Message;
use App\Models\User;
use App\Models\Listing;

class MessageManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 20;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $selectedMessage = null;
    public $showViewModal = false;
    public $showDeleteModal = false;

    public $filters = [
        'is_read' => '',
        'sender_id' => '',
        'receiver_id' => '',
        'listing_id' => ''
    ];

    public $users = [];
    public $listings = [];

    protected $listeners = ['refreshMessages' => '$refresh'];

    public function mount()
    {
        $this->loadUsersAndListings();
    }

    public function loadUsersAndListings()
    {
        $this->users = User::orderBy('name')->get();
        $this->listings = Listing::orderBy('title')->get();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function applyFilters($query)
    {
        return $query
            ->when($this->filters['is_read'] !== '', function ($query) {
                return $query->where('is_read', $this->filters['is_read']);
            })
            ->when($this->filters['sender_id'], function ($query, $senderId) {
                return $query->where('sender_id', $senderId);
            })
            ->when($this->filters['receiver_id'], function ($query, $receiverId) {
                return $query->where('receiver_id', $receiverId);
            })
            ->when($this->filters['listing_id'], function ($query, $listingId) {
                return $query->where('listing_id', $listingId);
            });
    }

    public function viewMessage($messageId)
    {
        $this->selectedMessage = Message::with(['sender', 'receiver', 'listing'])->find($messageId);
        
        // Označi poruku kao pročitanu
        if (!$this->selectedMessage->is_read) {
            $this->selectedMessage->update(['is_read' => true]);
        }

        $this->showViewModal = true;
    }

    public function markAsRead($messageId)
    {
        $message = Message::find($messageId);
        $message->update(['is_read' => true]);
        
        $this->dispatch('notify', type: 'success', message: 'Poruka označena kao pročitana!');
    }

    public function markAsUnread($messageId)
    {
        $message = Message::find($messageId);
        $message->update(['is_read' => false]);
        
        $this->dispatch('notify', type: 'success', message: 'Poruka označena kao nepročitana!');
    }

    public function confirmDelete($messageId)
    {
        $this->selectedMessage = Message::find($messageId);
        $this->showDeleteModal = true;
    }

    public function deleteMessage()
    {
        if ($this->selectedMessage) {
            $this->selectedMessage->delete();
            $this->showDeleteModal = false;
            $this->dispatch('notify', type: 'success', message: 'Poruka uspešno obrisana!');
        }
    }

    public function deleteAllRead()
    {
        $deletedCount = Message::where('is_read', true)->delete();
        
        $this->dispatch('notify', type: 'success', message: "Obrisano {$deletedCount} pročitanih poruka!");
    }

    public function resetFilters()
    {
        $this->filters = [
            'is_read' => '',
            'sender_id' => '',
            'receiver_id' => '',
            'listing_id' => ''
        ];
    }

    public function render()
    {
        $messages = Message::with(['sender', 'receiver', 'listing'])
            ->when($this->search, function ($query) {
                $query->where('message', 'like', '%' . $this->search . '%')
                      ->orWhereHas('sender', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%');
                      })
                      ->orWhereHas('receiver', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%');
                      })
                      ->orWhereHas('listing', function ($q) {
                          $q->where('title', 'like', '%' . $this->search . '%');
                      });
            })
            ->when($this->filters, function ($query) {
                return $this->applyFilters($query);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        // Statistike
        $totalMessages = Message::count();
        $unreadMessages = Message::where('is_read', false)->count();
        $readMessages = $totalMessages - $unreadMessages;

        return view('livewire.admin.message-management', compact('messages', 'totalMessages', 'unreadMessages', 'readMessages'))
            ->layout('layouts.admin');
    }
}