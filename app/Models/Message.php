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
        'is_system_message' // Dodajte ovo
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_system_message' => 'boolean', // Dodajte ovo
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
}