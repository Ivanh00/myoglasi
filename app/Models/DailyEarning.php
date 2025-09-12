<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyEarning extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'type',
        'amount',
        'description',
        'details'
    ];

    protected $casts = [
        'date' => 'date',
        'details' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function canEarnToday($userId, $type)
    {
        return !self::where('user_id', $userId)
            ->where('date', today())
            ->where('type', $type)
            ->exists();
    }

    public static function getTodaysEarnings($userId)
    {
        return self::where('user_id', $userId)
            ->where('date', today())
            ->sum('amount');
    }
}
