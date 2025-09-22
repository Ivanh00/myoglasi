<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageOptimizationService;

class ServiceImage extends Model
{
    protected $fillable = [
        'service_id',
        'image_path',
        'order'
    ];

    protected $appends = ['url', 'responsive_urls'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function getUrlAttribute()
    {
        return Storage::url($this->image_path);
    }

    public function getResponsiveUrlsAttribute()
    {
        $service = new ImageOptimizationService();
        return [
            'thumbnail' => $service->getResponsiveUrl($this->image_path, 'thumbnail'),
            'mobile' => $service->getResponsiveUrl($this->image_path, 'mobile'),
            'tablet' => $service->getResponsiveUrl($this->image_path, 'tablet'),
            'desktop' => $service->getResponsiveUrl($this->image_path, 'desktop'),
            'original' => $this->url
        ];
    }

    public function getResponsiveUrl($size = 'desktop')
    {
        $service = new ImageOptimizationService();
        return $service->getResponsiveUrl($this->image_path, $size);
    }
}
