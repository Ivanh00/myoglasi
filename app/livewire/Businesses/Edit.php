<?php

namespace App\Livewire\Businesses;

use App\Models\Business;
use App\Models\BusinessCategory;
use App\Models\BusinessImage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use App\Services\ImageOptimizationService;
use Illuminate\Support\Facades\Storage;

class Edit extends Component
{
    use WithFileUploads;

    public Business $business;
    public $categories;
    public $name;
    public $description;
    public $slogan;
    public $location;
    public $contact_phone;
    public $contact_email;
    public $contact_phone_2;
    public $contact_name_2;
    public $contact_phone_3;
    public $contact_name_3;
    public $website_url;
    public $facebook_url;
    public $youtube_url;
    public $instagram_url;
    public $logo;
    public $existing_logo;
    public $established_year;
    public $images = [];
    public $tempImages = [];
    public $existing_images = [];
    public $business_category_id;
    public $subcategory_id;
    public $subcategories;

    public function updatedTempImages()
    {
        $maxImages = \App\Models\Setting::get('max_images_per_business', 10);
        $totalImages = count($this->existing_images) + count($this->images);

        if (!empty($this->tempImages)) {
            foreach ($this->tempImages as $tempImage) {
                if ($totalImages < $maxImages) {
                    $this->images[] = $tempImage;
                    $totalImages++;
                } else {
                    session()->flash('error', "Možete dodati maksimalno {$maxImages} slika.");
                    break;
                }
            }

            $this->tempImages = [];
        }
    }

    public function removeImage($index)
    {
        unset($this->images[$index]);
        $this->images = array_values($this->images);
    }

    public function removeExistingImage($imageId)
    {
        $image = BusinessImage::where('id', $imageId)
            ->where('business_id', $this->business->id)
            ->first();

        if ($image) {
            // Delete from storage
            if (Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
            }

            // Delete from database
            $image->delete();

            // Reload existing images
            $this->existing_images = $this->business->fresh()->images;

            session()->flash('success', 'Slika je obrisana.');
        }
    }

    public function removeLogo()
    {
        if ($this->business->logo) {
            // Delete from storage
            if (Storage::disk('public')->exists($this->business->logo)) {
                Storage::disk('public')->delete($this->business->logo);
            }

            // Update database
            $this->business->update(['logo' => null]);
            $this->existing_logo = null;

            session()->flash('success', 'Logo je obrisan.');
        }
    }

    public function mount(Business $business)
    {
        // Check authorization
        if ($business->user_id !== auth()->id()) {
            abort(403, 'Nemate dozvolu da izmenite ovaj business.');
        }

        $this->business = $business;
        $this->name = $business->name;
        $this->description = $business->description;
        $this->slogan = $business->slogan;
        $this->location = $business->location;
        $this->contact_phone = $business->contact_phone;
        $this->contact_email = $business->contact_email;
        $this->contact_phone_2 = $business->contact_phone_2;
        $this->contact_name_2 = $business->contact_name_2;
        $this->contact_phone_3 = $business->contact_phone_3;
        $this->contact_name_3 = $business->contact_name_3;
        $this->website_url = $business->website_url;
        $this->facebook_url = $business->facebook_url;
        $this->youtube_url = $business->youtube_url;
        $this->instagram_url = $business->instagram_url;
        $this->existing_logo = $business->logo;
        $this->established_year = $business->established_year;
        $this->business_category_id = $business->business_category_id;
        $this->subcategory_id = $business->subcategory_id;

        $this->categories = BusinessCategory::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get() ?? collect();

        if ($this->business_category_id) {
            $this->subcategories = BusinessCategory::where('parent_id', $this->business_category_id)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        } else {
            $this->subcategories = collect();
        }

        $this->existing_images = $business->images;
    }

    public function updatedBusinessCategoryId($value)
    {
        $this->subcategory_id = null;

        if ($value) {
            $this->subcategories = BusinessCategory::where('parent_id', $value)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        } else {
            $this->subcategories = collect();
        }
    }

    public function update()
    {
        $maxImages = \App\Models\Setting::get('max_images_per_business', 10);
        $totalImages = count($this->existing_images) + count($this->images);

        $rules = [
            'name' => 'required|string|min:3|max:100',
            'description' => 'required|string|min:10|max:2000',
            'slogan' => 'nullable|string|max:200',
            'business_category_id' => 'required|exists:business_categories,id',
            'location' => 'required|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone_2' => 'nullable|string|max:20',
            'contact_name_2' => 'nullable|string|max:100',
            'contact_phone_3' => 'nullable|string|max:20',
            'contact_name_3' => 'nullable|string|max:100',
            'website_url' => 'nullable|url|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'youtube_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'established_year' => 'nullable|integer|min:1800|max:' . date('Y'),
            'logo' => 'nullable|image|max:2048',
            'images' => "nullable|array|max:{$maxImages}",
            'images.*' => 'nullable|image|max:5120',
            'tempImages.*' => 'nullable|image|max:5120',
        ];

        $this->validate($rules);

        // Update logo if new one uploaded
        $logoPath = $this->existing_logo;
        if ($this->logo) {
            // Delete old logo
            if ($this->existing_logo && Storage::disk('public')->exists($this->existing_logo)) {
                Storage::disk('public')->delete($this->existing_logo);
            }

            // Save and optimize new logo
            $imageService = new ImageOptimizationService();
            $filename = Str::random(40) . '.jpg';
            $optimizedPaths = $imageService->processImage(
                $this->logo,
                'businesses/logos',
                $filename
            );
            $logoPath = $optimizedPaths['original'];
        }

        // Save and optimize new images
        $imageService = new ImageOptimizationService();

        if (!empty($this->images)) {
            $currentOrder = $this->existing_images->count();

            foreach ($this->images as $image) {
                $filename = Str::random(40) . '.jpg';
                $optimizedPaths = $imageService->processImage(
                    $image,
                    'businesses',
                    $filename
                );

                $this->business->images()->create([
                    'path' => $optimizedPaths['original'],
                    'filename' => basename($optimizedPaths['original']),
                    'order' => $currentOrder
                ]);

                $currentOrder++;
            }
        }

        // Update business
        $this->business->update([
            'name' => $this->name,
            'description' => $this->description,
            'slogan' => $this->slogan,
            'business_category_id' => $this->business_category_id,
            'subcategory_id' => $this->subcategory_id,
            'location' => $this->location,
            'contact_phone' => $this->contact_phone,
            'contact_email' => $this->contact_email,
            'contact_phone_2' => $this->contact_phone_2,
            'contact_name_2' => $this->contact_name_2,
            'contact_phone_3' => $this->contact_phone_3,
            'contact_name_3' => $this->contact_name_3,
            'website_url' => $this->website_url,
            'facebook_url' => $this->facebook_url,
            'youtube_url' => $this->youtube_url,
            'instagram_url' => $this->instagram_url,
            'logo' => $logoPath,
            'established_year' => $this->established_year,
        ]);

        session()->flash('success', 'Business je uspešno ažuriran!');
        return redirect()->route('businesses.show', $this->business->slug);
    }

    public function render()
    {
        return view('livewire.businesses.edit')
            ->layout('layouts.app');
    }
}
