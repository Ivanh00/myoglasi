<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'listing_id',
        'message',
        'subject',
        'is_read',
        'is_system_message', // Dodajte ovo
        'deleted_by_sender',
        'deleted_by_receiver',
        'deleted_by_sender_at',
        'deleted_by_receiver_at'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_system_message' => 'boolean',
        'deleted_by_sender' => 'boolean',
        'deleted_by_receiver' => 'boolean',
        'deleted_by_sender_at' => 'datetime',
        'deleted_by_receiver_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Ostale relacije i metode ostaju iste
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public function deleteForUser($userId)
    {
        if ($this->sender_id == $userId) {
            $this->update([
                'deleted_by_sender' => true,
                'deleted_by_sender_at' => now()
            ]);
        } elseif ($this->receiver_id == $userId) {
            $this->update([
                'deleted_by_receiver' => true,
                'deleted_by_receiver_at' => now()
            ]);
        }
    }

    public function isDeletedBy($userId)
    {
        if ($this->sender_id == $userId) {
            return $this->deleted_by_sender;
        } elseif ($this->receiver_id == $userId) {
            return $this->deleted_by_receiver;
        }
        return false;
    }

    public function isVisibleTo($userId)
    {
        return !$this->isDeletedBy($userId);
    }
}