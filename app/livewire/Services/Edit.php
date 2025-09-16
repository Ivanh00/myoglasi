<?php

namespace App\Livewire\Services;

use App\Models\Service;
use App\Models\ServiceCategory;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class Edit extends Component
{
    use WithFileUploads;

    public Service $service;
    public $categories;
    public $title;
    public $description;
    public $price;
    public $location;
    public $contact_phone;
    public $images = [];
    public $tempImages = [];
    public $service_category_id;
    public $subcategory_id;
    public $subcategories;

    public function updatedTempImages()
    {
        $maxImages = \App\Models\Setting::get('max_images_per_service', 10);

        if (!empty($this->tempImages)) {
            foreach ($this->tempImages as $tempImage) {
                if (count($this->images) < $maxImages) {
                    $this->images[] = $tempImage;
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
        $image = $this->service->images()->find($imageId);
        if ($image) {
            // Delete file from storage
            \Storage::disk('public')->delete($image->image_path);
            // Delete from database
            $image->delete();
            // Refresh the service
            $this->service->refresh();
        }
    }

    public function mount($service)
    {
        // Ako je prosleđen slug, nađi service po slug-u
        if (is_string($service)) {
            $this->service = Service::where('slug', $service)->firstOrFail();
        }
        // Ako je prosleđen Service model
        else if ($service instanceof Service) {
            $this->service = $service;
        }

        // Check if user owns this service
        if ($this->service && $this->service->user_id !== auth()->id()) {
            abort(403);
        }

        $this->categories = ServiceCategory::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get() ?? collect();

        // Load subcategories if service has category
        if ($this->service->service_category_id) {
            $this->subcategories = ServiceCategory::where('parent_id', $this->service->service_category_id)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        } else {
            $this->subcategories = collect();
        }

        // Populate form with existing data
        $this->title = $this->service->title;
        $this->description = $this->service->description;
        $this->price = $this->service->price;
        $this->service_category_id = $this->service->service_category_id;
        $this->subcategory_id = $this->service->subcategory_id;
        $this->location = $this->service->location;
        $this->contact_phone = $this->service->contact_phone;

        // Load existing images as URLs (not file uploads)
        $this->images = $this->service->images->map(function ($image) {
            return $image->url;
        })->toArray();
    }

    public function updatedServiceCategoryId($value)
    {
        $this->subcategory_id = null;

        if ($value) {
            $this->subcategories = ServiceCategory::where('parent_id', $value)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        } else {
            $this->subcategories = collect();
        }
    }

    public function save()
    {
        $maxImages = \App\Models\Setting::get('max_images_per_service', 10);

        $rules = [
            'title' => 'required|string|min:5|max:100',
            'description' => 'required|string|min:10|max:2000',
            'price' => 'required|numeric|min:1',
            'service_category_id' => 'required|exists:service_categories,id',
            'location' => 'required|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'tempImages.*' => 'nullable|image|max:5120',
        ];

        $this->validate($rules);

        // Update service
        $this->service->update([
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'service_category_id' => $this->service_category_id,
            'subcategory_id' => $this->subcategory_id,
            'location' => $this->location,
            'contact_phone' => $this->contact_phone,
            'slug' => Str::slug($this->title) . '-' . Str::random(6),
        ]);

        // Handle new images if any
        if (!empty($this->tempImages)) {
            foreach ($this->tempImages as $image) {
                $path = $image->store('services', 'public');
                $this->service->images()->create([
                    'image_path' => $path,
                    'order' => $this->service->images()->count()
                ]);
            }
        }

        session()->flash('success', 'Usluga je uspešno ažurirana!');
        return redirect()->route('services.show', $this->service->slug);
    }

    public function render()
    {
        return view('livewire.services.edit')
            ->layout('layouts.app');
    }
}