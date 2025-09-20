<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\GiveawayReservation;

class GiveawayRequestRejected extends Notification
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
            'type' => 'giveaway_rejected',
            'reservation_id' => $this->reservation->id,
            'listing_id' => $this->reservation->listing_id,
            'listing_title' => $this->reservation->listing->title,
            'owner_id' => $this->reservation->listing->user_id,
            'owner_name' => $this->reservation->listing->user->name,
            'response' => $this->reservation->response,
            'title' => 'Vaš zahtev je odbijen',
            'body' => 'Nažalost, vaš zahtev za poklon "' . $this->reservation->listing->title . '" je odbijen.',
            'icon' => 'fas fa-times-circle',
            'color' => 'red'
        ];
    }
}