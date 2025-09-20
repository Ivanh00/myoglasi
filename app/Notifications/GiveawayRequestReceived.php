<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\GiveawayReservation;

class GiveawayRequestReceived extends Notification
{
    use Queueable;

    protected $reservation;

    /**
     * Create a new notification instance.
     */
    public function __construct(GiveawayReservation $reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'giveaway_request',
            'reservation_id' => $this->reservation->id,
            'listing_id' => $this->reservation->listing_id,
            'listing_title' => $this->reservation->listing->title,
            'requester_id' => $this->reservation->requester_id,
            'requester_name' => $this->reservation->requester->name,
            'message' => $this->reservation->message,
            'title' => 'Novi zahtev za poklon',
            'body' => $this->reservation->requester->name . ' želi vaš poklon "' . $this->reservation->listing->title . '"',
            'icon' => 'fas fa-gift',
            'color' => 'green'
        ];
    }
}