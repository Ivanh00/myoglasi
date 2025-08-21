<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function images()
    {
        return $this->hasMany(ListingImage::class);
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