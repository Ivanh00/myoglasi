<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id', 
        'amount', 
        'type', 
        'description', 
        'status', 
        'payment_method', 
        'payment_reference', 
        'payment_details',
        'failure_reason',
        'completed_at',
        'cancelled_at'
    ];
    
    protected $casts = [
        'amount' => 'decimal:2',
        'payment_details' => 'json',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
