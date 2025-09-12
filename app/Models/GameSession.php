<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameSession extends Model
{
    protected $fillable = [
        'user_id',
        'game_type',
        'score',
        'credits_earned',
        'started_at',
        'completed_at',
        'game_data'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'game_data' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isCompleted()
    {
        return !is_null($this->completed_at);
    }

    public static function getTodaysGames($userId)
    {
        return self::where('user_id', $userId)
            ->whereDate('created_at', today())
            ->sum('credits_earned');
    }
}
