<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Listing;
use App\Models\User;
use App\Models\Message;

class ListingFavorited extends Notification implements ShouldQueue
{
    use Queueable;

    public $listing;
    public $favoritedBy;

    public function __construct(Listing $listing, User $favoritedBy)
    {
        $this->listing = $listing;
        $this->favoritedBy = $favoritedBy;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        // Kreiraj poruku u sistemu poruka
        $message = Message::create([
            'sender_id' => $this->favoritedBy->id,
            'receiver_id' => $this->listing->user_id,
            'listing_id' => $this->listing->id,
            'message' => "Korisnik {$this->favoritedBy->name} je dodao vaš oglas '{$this->listing->title}' u omiljene.",
            'is_system_message' => true, // Dodajte ovo polje u migraciju ako već ne postoji
            'is_read' => false,
        ]);

        return [
            'message_id' => $message->id,
            'type' => 'favorite_notification',
            'listing_id' => $this->listing->id,
            'favorited_by_id' => $this->favoritedBy->id,
            'favorited_by_name' => $this->favoritedBy->name,
        ];
    }
}