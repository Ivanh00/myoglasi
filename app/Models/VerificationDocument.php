<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerificationDocument extends Model
{
    protected $fillable = [
        'user_id',
        'id_front_path',
        'id_back_path',
        'street_address',
        'street_number',
        'city',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
