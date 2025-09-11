<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ServiceImage extends Model
{
    protected $fillable = [
        'service_id',
        'image_path',
        'order'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function getUrlAttribute()
    {
        return Storage::url($this->image_path);
    }
}
