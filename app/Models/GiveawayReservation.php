<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiveawayReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'listing_id',
        'requester_id',
        'status',
        'message',
        'response',
        'responded_at'
    ];

    protected $casts = [
        'responded_at' => 'datetime',
    ];

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function approve($response = null)
    {
        $this->update([
            'status' => 'approved',
            'response' => $response,
            'responded_at' => now()
        ]);

        // Mark listing as completed
        $this->listing->update(['status' => 'completed']);
    }

    public function reject($response = null)
    {
        $this->update([
            'status' => 'rejected',
            'response' => $response,
            'responded_at' => now()
        ]);
    }
}