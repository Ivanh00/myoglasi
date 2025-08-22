<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ListingCondition extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($condition) {
            if (!$condition->slug) {
                $condition->slug = Str::slug($condition->name);
            }
        });
    }

    public function listings()
    {
        return $this->hasMany(Listing::class, 'condition_id');
    }
}