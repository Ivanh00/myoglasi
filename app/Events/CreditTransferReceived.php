<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreditTransferReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $receiver;
    public $sender;
    public $amount;
    public $note;

    public function __construct(User $receiver, User $sender, $amount, $note = null)
    {
        $this->receiver = $receiver;
        $this->sender = $sender;
        $this->amount = $amount;
        $this->note = $note;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('notifications.' . $this->receiver->id);
    }

    public function broadcastWith()
    {
        return [
            'type' => 'credit_transfer_received',
            'sender_name' => $this->sender->name,
            'sender_id' => $this->sender->id,
            'amount' => $this->amount,
            'formatted_amount' => number_format($this->amount, 0, ',', '.') . ' RSD',
            'note' => $this->note,
            'timestamp' => now()->toISOString(),
            'message' => "Primili ste kredit od korisnika {$this->sender->name}! Iznos: " . number_format($this->amount, 0, ',', '.') . " RSD" . ($this->note ? "\nNapomena: {$this->note}" : ''),
            'unread_notification_count' => \App\Models\Message::where('receiver_id', $this->receiver->id)
                ->where('is_system_message', true)
                ->where('is_read', false)
                ->count()
        ];
    }

    public function broadcastAs()
    {
        return 'credit.received';
    }
}