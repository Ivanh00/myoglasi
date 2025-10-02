<?php

namespace App\Livewire\Businesses;

use App\Models\Business;
use App\Models\BusinessCategory;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use App\Services\ImageOptimizationService;

class Create extends Component
{
    use WithFileUploads;

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
    public $established_year;
    public $images = [];
    public $tempImages = [];
    public $business_category_id;
    public $subcategory_id;
    public $subcategories;

    public function updatedTempImages()
    {
        $maxImages = \App\Models\Setting::get('max_images_per_business', 10);

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
        $this->categories = BusinessCategory::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get() ?? collect();

        $this->subcategories = collect();

        $user = auth()->user();
        $this->location = $user->city;
        $this->contact_phone = $user->phone;
        $this->contact_email = $user->email;
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

    public function save()
    {
        $maxImages = \App\Models\Setting::get('max_images_per_business', 10);

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

        $user = auth()->user();

        // Calculate business fee
        $fee = 0;
        if (\App\Models\Setting::get('business_fee_enabled', true)) {
            $fee = \App\Models\Setting::get('business_fee_amount', 200);
        }

        // Check balance if fee is required
        if ($fee > 0 && $user->balance < $fee) {
            session()->flash('error', 'Nemate dovoljno kredita za postavljanje business-a. Potrebno: ' . number_format($fee, 0, ',', '.') . ' RSD, a imate: ' . number_format($user->balance, 0, ',', '.') . ' RSD');
            return redirect()->route('balance.payment-options');
        }

        // Charge fee if required
        if ($fee > 0) {
            $user->decrement('balance', $fee);

            \App\Models\Transaction::create([
                'user_id' => $user->id,
                'type' => 'business_fee',
                'amount' => $fee,
                'status' => 'completed',
                'description' => 'Naplaćivanje za objavljivanje business-a: ' . $this->name,
                'reference_number' => 'BUSINESS-FEE-' . now()->timestamp,
            ]);
        }

        // Save and optimize logo
        $logoPath = null;
        if ($this->logo) {
            $imageService = new ImageOptimizationService();
            $filename = Str::random(40) . '.jpg';
            $optimizedPaths = $imageService->processImage(
                $this->logo,
                'businesses/logos',
                $filename
            );
            $logoPath = $optimizedPaths['original'];
        }

        // Save and optimize images
        $imagePaths = [];
        $imageService = new ImageOptimizationService();

        if (!empty($this->images)) {
            foreach ($this->images as $image) {
                $filename = Str::random(40) . '.jpg';
                $optimizedPaths = $imageService->processImage(
                    $image,
                    'businesses',
                    $filename
                );
                $imagePaths[] = $optimizedPaths['original'];
            }
        }

        $expiryDays = \App\Models\Setting::get('business_auto_expire_days', 365);

        $business = Business::create([
            'user_id' => auth()->id(),
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
            'slug' => Str::slug($this->name) . '-' . Str::random(6),
            'status' => 'active',
            'expires_at' => now()->addDays($expiryDays),
        ]);

        // Save images to database
        foreach ($imagePaths as $order => $path) {
            $business->images()->create([
                'path' => $path,
                'filename' => basename($path),
                'order' => $order
            ]);
        }

        session()->flash('success', 'Business je uspešno kreiran!');
        return redirect()->route('businesses.show', $business->slug);
    }

    public function render()
    {
        return view('livewire.businesses.create')
            ->layout('layouts.app');
    }
}
