<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Services\ImageOptimizationService;

class ListingImage extends Model

{
    use HasFactory;

    protected $fillable = ['listing_id', 'image_path', 'order'];

    protected $appends = ['url', 'responsive_urls'];

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public function getUrlAttribute()
    {
        return asset('storage/' . $this->image_path);
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
