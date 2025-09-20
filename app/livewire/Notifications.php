<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class Notifications extends Component
{
    use WithPagination;
    
    public $filter = 'all'; // all, unread
    public $selectedNotification = null;
    public $autoMarkAsRead = false;

    protected $listeners = ['closeModal' => 'closeModal'];

    public function mount()
    {
        // Opciono: automatski označi sva obaveštenja kao pročitana
        // kada se stranica učita
        if ($this->autoMarkAsRead) {
            $this->markAllAsRead();
        }
    }

    public function loadNotifications()
    {
        $query = Message::where('receiver_id', Auth::id())
            ->where('is_system_message', true)
            ->where('deleted_by_receiver', false)
            ->with(['listing', 'sender', 'giveawayReservation']);

        if ($this->filter === 'unread') {
            $query->where('is_read', false);
        }

        return $query->orderBy('created_at', 'desc')
                    ->paginate(20);
    }

    public function markAsRead($notificationId)
    {
        $notification = Message::where('id', $notificationId)
            ->where('receiver_id', Auth::id())
            ->where('is_system_message', true)
            ->first();

        if ($notification && !$notification->is_read) {
            $notification->is_read = true;
            $notification->save();

            // Emit event da se osveži brojač
            $this->dispatch('notificationsUpdated');
        }
    }

    public function markAllAsRead()
    {
        Message::where('receiver_id', Auth::id())
            ->where('is_system_message', true)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Emit event da se osveži brojač
        $this->dispatch('notificationsUpdated');
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function deleteNotification($notificationId)
    {
        $notification = Message::where('id', $notificationId)
            ->where('receiver_id', Auth::id())
            ->where('is_system_message', true)
            ->first();
            
        if ($notification) {
            $notification->deleteForUser(Auth::id());
            session()->flash('success', 'Obaveštenje je obrisano.');
        }
    }

    public function selectNotification($notificationId)
    {
        $notification = Message::where('id', $notificationId)
            ->where('receiver_id', Auth::id())
            ->where('is_system_message', true)
            ->with('giveawayReservation')
            ->first();

        if ($notification) {
            $this->selectedNotification = $notification;
            // Označi kao pročitano
            if (!$notification->is_read) {
                $notification->is_read = true;
                $notification->save();
                $this->dispatch('notificationsUpdated');
            }
        }
    }

    public function closeModal()
    {
        $this->selectedNotification = null;
    }

    public function getUnreadCountProperty()
    {
        return Message::where('receiver_id', Auth::id())
            ->where('is_system_message', true)
            ->where('is_read', false)
            ->count();
    }

    public function render()
    {
        return view('livewire.notifications', [
            'notifications' => $this->loadNotifications()
        ])->layout('layouts.app');
    }
}