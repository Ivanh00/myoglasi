<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class BusinessImage extends Model
{
    protected $fillable = [
        'business_id',
        'filename',
        'path',
        'order'
    ];

    // Relationships
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    // Accessor for full URL
    public function getUrlAttribute()
    {
        return Storage::url($this->path);
    }

    // Delete file when model is deleted
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($image) {
            if (Storage::exists($image->path)) {
                Storage::delete($image->path);
            }
        });
    }
}
