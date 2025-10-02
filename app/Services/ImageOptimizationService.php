<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Http\UploadedFile;
use App\Models\Setting;

class ImageOptimizationService
{
    protected $manager;
    protected $sizes;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());

        // Load sizes from settings with defaults
        $this->sizes = [
            'desktop' => [
                'width' => Setting::get('image_desktop_width', 1200),
                'quality' => Setting::get('image_desktop_quality', 85),
                'suffix' => '_desktop'
            ],
            'tablet' => [
                'width' => Setting::get('image_tablet_width', 800),
                'quality' => Setting::get('image_tablet_quality', 80),
                'suffix' => '_tablet'
            ],
            'mobile' => [
                'width' => Setting::get('image_mobile_width', 400),
                'quality' => Setting::get('image_mobile_quality', 75),
                'suffix' => '_mobile'
            ],
            'thumbnail' => [
                'width' => Setting::get('image_thumbnail_width', 200),
                'quality' => Setting::get('image_thumbnail_quality', 75),
                'suffix' => '_thumb'
            ],
        ];
    }

    /**
     * Process and optimize uploaded image
     *
     * @param UploadedFile $file
     * @param string $path
     * @param string $filename
     * @return array
     */
    public function processImage(UploadedFile $file, string $path, string $filename): array
    {
        // Temporarily increase memory limit for large images from mobile devices
        $originalMemoryLimit = ini_get('memory_limit');
        ini_set('memory_limit', '256M');

        // Ensure directory exists
        $fullPath = storage_path('app/public/' . $path);
        if (!file_exists($fullPath)) {
            mkdir($fullPath, 0755, true);
        }

        $uploadedFiles = [];
        $nameWithoutExt = pathinfo($filename, PATHINFO_FILENAME);
        $extension = 'jpg'; // Convert all to JPG for optimization

        // Save original file (compressed)
        $originalPath = $path . '/' . $nameWithoutExt . '_original.' . $extension;
        $originalImage = $this->manager->read($file->getRealPath());

        // Compress original if it's larger than desktop size
        if ($originalImage->width() > $this->sizes['desktop']['width']) {
            $originalImage->scale(width: $this->sizes['desktop']['width']);
        }

        // Save original with compression (use desktop quality setting)
        $originalQuality = $this->sizes['desktop']['quality'];
        $originalImage->toJpeg(quality: $originalQuality)->save(storage_path('app/public/' . $originalPath));
        $uploadedFiles['original'] = $originalPath;

        // Create responsive versions
        foreach ($this->sizes as $sizeName => $sizeConfig) {
            $resizedPath = $path . '/' . $nameWithoutExt . $sizeConfig['suffix'] . '.' . $extension;

            // Read the original file again for each resize
            $image = $this->manager->read($file->getRealPath());

            // Only resize if image is larger than target width
            if ($image->width() > $sizeConfig['width']) {
                $image->scale(width: $sizeConfig['width']);
            }

            // Use quality from settings
            $quality = $sizeConfig['quality'] ?? 85;

            // Save optimized version
            $image->toJpeg(quality: $quality)->save(storage_path('app/public/' . $resizedPath));
            $uploadedFiles[$sizeName] = $resizedPath;
        }

        // Restore original memory limit
        ini_set('memory_limit', $originalMemoryLimit);

        return $uploadedFiles;
    }

    /**
     * Delete all versions of an image
     *
     * @param string $originalPath
     * @return void
     */
    public function deleteImageVersions(string $originalPath): void
    {
        $directory = dirname($originalPath);
        $nameWithoutExt = pathinfo($originalPath, PATHINFO_FILENAME);

        // Remove _original suffix if present
        $nameWithoutExt = str_replace('_original', '', $nameWithoutExt);

        // Delete all versions
        Storage::disk('public')->delete($originalPath);

        foreach ($this->sizes as $sizeConfig) {
            $versionPath = $directory . '/' . $nameWithoutExt . $sizeConfig['suffix'] . '.jpg';
            if (Storage::disk('public')->exists($versionPath)) {
                Storage::disk('public')->delete($versionPath);
            }
        }

        // Also delete original
        $originalVersionPath = $directory . '/' . $nameWithoutExt . '_original.jpg';
        if (Storage::disk('public')->exists($originalVersionPath)) {
            Storage::disk('public')->delete($originalVersionPath);
        }
    }

    /**
     * Get responsive image URL based on context
     *
     * @param string $originalPath
     * @param string $size
     * @return string
     */
    public function getResponsiveUrl(string $originalPath, string $size = 'desktop'): string
    {
        $directory = dirname($originalPath);
        $nameWithoutExt = pathinfo($originalPath, PATHINFO_FILENAME);

        // Remove _original suffix if present
        $nameWithoutExt = str_replace('_original', '', $nameWithoutExt);

        if (!isset($this->sizes[$size])) {
            $size = 'desktop';
        }

        $responsivePath = $directory . '/' . $nameWithoutExt . $this->sizes[$size]['suffix'] . '.jpg';

        if (Storage::disk('public')->exists($responsivePath)) {
            return Storage::url($responsivePath);
        }

        // Fallback to original
        return Storage::url($originalPath);
    }

    /**
     * Get all responsive URLs for an image
     *
     * @param string $originalPath
     * @return array
     */
    public function getAllResponsiveUrls(string $originalPath): array
    {
        $urls = [];

        foreach (array_keys($this->sizes) as $size) {
            $urls[$size] = $this->getResponsiveUrl($originalPath, $size);
        }

        $urls['original'] = Storage::url($originalPath);

        return $urls;
    }
}