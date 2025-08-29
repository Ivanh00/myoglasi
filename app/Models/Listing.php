<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'description', 'price', 'location', 'contact_phone',
        'user_id', 'category_id', 'subcategory_id', 'condition_id', 'status', 'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'price' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relacija za subkategoriju
    public function subcategory()
    {
        return $this->belongsTo(Category::class, 'subcategory_id');
    }

    public function condition()
    {
        return $this->belongsTo(ListingCondition::class);
    }

    public function images()
    {
        return $this->hasMany(ListingImage::class)->orderBy('order');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
    
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    
    public function primaryImage()
    {
        return $this->hasOne(ListingImage::class)->where('is_primary', true);
    }

    public function favorites()
{
    return $this->hasMany(Favorite::class);
}

public function favoritedByUsers()
{
    return $this->belongsToMany(User::class, 'favorites')
                ->withTimestamps();
}

public function getFavoritesCountAttribute()
{
    return $this->favorites()->count();
}

public function isFavoritedBy($user)
{
    if (!$user) {
        return false;
    }
    
    return $this->favorites()
                ->where('user_id', $user->id)
                ->exists();
}
}