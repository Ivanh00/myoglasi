<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'description', 'price', 'location', 'contact_phone',
        'user_id', 'category_id', 'condition_id', 'status', 'expires_at'
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

}