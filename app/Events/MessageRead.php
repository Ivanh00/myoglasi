<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageRead implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $userId;

    public function __construct(Message $message, $userId)
    {
        $this->message = $message;
        $this->userId = $userId;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('conversation.' . $this->message->listing_id . '.' . $this->userId);
    }

    public function broadcastWith()
    {
        return [
            'message_id' => $this->message->id,
            'is_read' => true,
            'unread_count' => Message::where('receiver_id', $this->userId)
                ->where('is_read', false)
                ->count()
        ];
    }
}