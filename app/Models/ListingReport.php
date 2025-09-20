<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListingReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'listing_id', 
        'reason',
        'details',
        'status',
        'admin_notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-amber-200 text-amber-800',
            'reviewed' => 'bg-sky-200 text-sky-800', 
            'resolved' => 'bg-green-200 text-green-800',
            default => 'bg-gray-200 text-gray-800'
        };
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'pending' => 'Na čekanju',
            'reviewed' => 'Pregledano',
            'resolved' => 'Rešeno',
            default => ucfirst($this->status)
        };
    }
}