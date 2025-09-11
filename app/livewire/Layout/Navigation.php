<?php

namespace App\Livewire\Layout;

use Livewire\Component;
use App\Models\Category;
use App\Models\ListingCondition;
use App\Models\Message;

class Navigation extends Component
{
    protected $listeners = [
        'notificationsUpdated' => '$refresh',
    ];

    public function getListeners()
    {
        $listeners = [
            'notificationsUpdated' => '$refresh',
        ];

        // Add dynamic listener for authenticated users
        if (auth()->check()) {
            $listeners['echo-private:notifications.' . auth()->id() . ',credit.received'] = 'handleCreditReceived';
        }

        return $listeners;
    }

    public function handleCreditReceived($event)
    {
        // Refresh the component to update badge counts
        $this->dispatch('$refresh');
        
        // Show a toast notification
        session()->flash('credit_received', [
            'sender_name' => $event['sender_name'],
            'amount' => $event['formatted_amount'],
            'message' => $event['message']
        ]);
    }

    public function getUnreadMessagesCountProperty()
    {
        if (!auth()->check()) {
            return 0;
        }
        
        return Message::where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->where('is_system_message', false)
            ->count();
    }

    public function getUnreadNotificationsCountProperty()
    {
        if (!auth()->check()) {
            return 0;
        }
        
        return Message::where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->where('is_system_message', true)
            ->count();
    }

    public function render()
    {
        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'name']);

        $conditions = ListingCondition::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('livewire.layout.navigation', compact('categories', 'conditions'))
            ->layout('layouts.app');
    }
}
