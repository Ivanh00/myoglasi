<?php

namespace App\Livewire\Services;

use App\Models\Service;
use App\Models\ServiceCategory;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use App\Services\ImageOptimizationService;

class Create extends Component
{
    use WithFileUploads;

    public $categories;
    public $title;
    public $description;
    public $price;
    public $price_type = 'fixed';
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

    public function mount()
    {
        $this->categories = ServiceCategory::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get() ?? collect();

        $this->subcategories = collect();

        $user = auth()->user();
        $this->location = $user->city;
        $this->contact_phone = $user->phone;
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
            'price' => 'nullable|numeric|min:1',
            'price_type' => 'required|in:fixed,hourly,negotiable',
            'service_category_id' => 'required|exists:service_categories,id',
            'location' => 'required|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'images' => "nullable|array|max:{$maxImages}",
            'images.*' => 'nullable|image|max:5120',
            'tempImages.*' => 'nullable|image|max:5120',
        ];

        // Price is required only if price_type is not negotiable
        if ($this->price_type !== 'negotiable') {
            $rules['price'] = 'required|numeric|min:1';
        }

        $this->validate($rules);

        $user = auth()->user();

        // Calculate service fee
        $fee = 0;
        if (\App\Models\Setting::get('service_fee_enabled', true)) {
            $fee = \App\Models\Setting::get('service_fee_amount', 100);
        }

        // Check balance if fee is required
        if ($fee > 0 && $user->balance < $fee) {
            session()->flash('error', 'Nemate dovoljno kredita za postavljanje usluge. Potrebno: ' . number_format($fee, 0, ',', '.') . ' RSD, a imate: ' . number_format($user->balance, 0, ',', '.') . ' RSD');
            return redirect()->route('balance.payment-options');
        }

        // Charge fee if required
        if ($fee > 0) {
            $user->decrement('balance', $fee);

            \App\Models\Transaction::create([
                'user_id' => $user->id,
                'type' => 'service_fee',
                'amount' => $fee,
                'status' => 'completed',
                'description' => 'Naplaćivanje za objavljivanje usluge: ' . $this->title,
                'reference_number' => 'SERVICE-FEE-' . now()->timestamp,
            ]);
        }

        // Save and optimize images
        $imagePaths = [];
        $imageService = new ImageOptimizationService();

        if (!empty($this->images)) {
            foreach ($this->images as $image) {
                $filename = Str::random(40) . '.jpg';
                $optimizedPaths = $imageService->processImage(
                    $image,
                    'services',
                    $filename
                );
                // Store the original path for database
                $imagePaths[] = $optimizedPaths['original'];
            }
        }

        $expiryDays = \App\Models\Setting::get('service_auto_expire_days', 60);

        $service = Service::create([
            'user_id' => auth()->id(),
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price_type === 'negotiable' ? 0 : $this->price,
            'price_type' => $this->price_type,
            'service_category_id' => $this->service_category_id,
            'subcategory_id' => $this->subcategory_id,
            'location' => $this->location,
            'contact_phone' => $this->contact_phone,
            'slug' => Str::slug($this->title) . '-' . Str::random(6),
            'status' => 'active',
            'expires_at' => now()->addDays($expiryDays),
        ]);

        // Save images to database
        foreach ($imagePaths as $order => $path) {
            $service->images()->create([
                'image_path' => $path,
                'order' => $order
            ]);
        }

        session()->flash('success', 'Usluga je uspešno kreirana!');
        return redirect()->route('services.show', $service->slug);
    }

    public function render()
    {
        return view('livewire.services.create')
            ->layout('layouts.app');
    }
}