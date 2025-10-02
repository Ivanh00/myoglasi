<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\ListingCondition;
use App\Models\ServiceCategory;
use App\Models\Listing;
use App\Models\Service;
use App\Models\Auction;
use App\Services\ImageOptimizationService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class QuickListing extends Component
{
    use WithFileUploads;

    public $show = false;
    public $step = 1;

    protected $listeners = ['openQuickListing'];

    // Step 1: Type selection
    public $listingType = '';

    // Step 2: Basic info
    public $title = '';
    public $category_id = '';
    public $subcategory_id = '';

    // Step 3: Condition and Price
    public $condition_id = '';
    public $price = '';
    public $price_type = 'fixed'; // For services
    public $starting_price = '';
    public $buy_now_price = ''; // For auctions
    public $duration = '7'; // For auctions (string to match form input)
    public $startType = 'immediately'; // For auctions
    public $startDate = ''; // For scheduled auctions
    public $startTime = ''; // For scheduled auctions

    // Business specific fields
    public $slogan = '';
    public $established_year = '';
    public $address_1 = '';
    public $address_2 = '';
    public $contact_email = '';
    public $contact_phone_2 = '';
    public $contact_name_2 = '';
    public $contact_phone_3 = '';
    public $contact_name_3 = '';
    public $website_url = '';
    public $facebook_url = '';
    public $youtube_url = '';
    public $instagram_url = '';
    public $logo;

    // Step 4: Description
    public $description = '';

    // User info
    public $location = '';
    public $contact_phone = '';

    // Step 5: Images
    public $images = [];
    public $tempImages = [];

    public $categories = [];
    public $subcategories = [];
    public $conditions = [];

    protected function rules()
    {
        return [
            'listingType' => 'required|in:listing,auction,service,giveaway,business',
            'title' => 'required|string|max:255',
            'category_id' => 'required',
            'description' => 'required|string|min:10',
            'price' => 'required_unless:listingType,giveaway,auction,business|numeric|min:0',
        ];
    }

    public function mount()
    {
        $this->conditions = ListingCondition::orderBy('id')->get();

        // Load user location and phone
        $user = auth()->user();
        $this->location = $user->city ?? 'Srbija';
        $this->contact_phone = $user->phone ?? '';
    }

    public function loadCategories()
    {
        if ($this->listingType === 'service') {
            $this->categories = ServiceCategory::whereNull('parent_id')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        } elseif ($this->listingType === 'business') {
            $this->categories = \App\Models\BusinessCategory::whereNull('parent_id')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        } else {
            $this->categories = Category::whereNull('parent_id')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        }
    }

    public function updatedListingType()
    {
        $this->loadCategories();
        $this->category_id = '';
        $this->subcategory_id = '';
    }

    public function updatedCategoryId()
    {
        if ($this->listingType === 'service') {
            $this->subcategories = ServiceCategory::where('parent_id', $this->category_id)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        } elseif ($this->listingType === 'business') {
            $this->subcategories = \App\Models\BusinessCategory::where('parent_id', $this->category_id)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        } else {
            $this->subcategories = Category::where('parent_id', $this->category_id)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        }
        $this->subcategory_id = '';
    }

    public function updatedTempImages()
    {
        $maxImages = \App\Models\Setting::get('max_images_per_listing', 10);

        // Validate file size (15MB max before compression)
        $this->validate([
            'tempImages.*' => 'image|max:15360', // 15MB max before compression
        ]);

        if (!empty($this->tempImages)) {
            // Add new images to existing images array
            foreach ($this->tempImages as $tempImage) {
                if (count($this->images) < $maxImages) {
                    $this->images[] = $tempImage;
                } else {
                    session()->flash('error', "Možete dodati maksimalno {$maxImages} slika.");
                    break;
                }
            }

            // Clear temp images
            $this->tempImages = [];
        }
    }

    public function removeImage($index)
    {
        unset($this->images[$index]);
        $this->images = array_values($this->images); // Re-index array
    }

    public function openQuickListing()
    {
        $this->openModal();
    }

    public function openModal()
    {
        $this->show = true;
        $this->step = 1;
        $this->loadCategories();
        $this->reset(['listingType', 'title', 'category_id', 'subcategory_id', 'condition_id', 'price', 'starting_price', 'buy_now_price', 'description', 'images', 'startDate', 'startTime']);
        $this->duration = '7'; // Reset to default
        $this->startType = 'immediately'; // Reset to default
    }

    public function closeModal()
    {
        $this->show = false;
        $this->step = 1;
    }

    public function nextStep()
    {
        if ($this->step === 1 && !$this->listingType) {
            session()->flash('error', 'Izaberite tip oglasa');
            return;
        }

        if ($this->step === 2) {
            $this->validate([
                'title' => 'required|string|min:5|max:100',
                'category_id' => 'required',
            ]);
        }

        if ($this->step === 3) {
            if ($this->listingType === 'auction') {
                $rules = [
                    'starting_price' => 'required|numeric|min:1',
                    'duration' => 'required|integer|min:1|max:30',
                ];

                if ($this->startType === 'scheduled') {
                    $rules['startDate'] = 'required|date|after_or_equal:today';
                    $rules['startTime'] = 'required';
                }

                $this->validate($rules);
            } elseif ($this->listingType === 'business') {
                // Validate description for business in Step 3
                $this->validate([
                    'description' => 'required|string|min:10|max:2000',
                ]);
            } elseif ($this->listingType !== 'giveaway') {
                $this->validate([
                    'price' => 'required|numeric|min:1',
                ]);
            }
        }

        if ($this->step === 4) {
            // Description validation only for non-business types (business validates in Step 3)
            if ($this->listingType !== 'business') {
                $this->validate([
                    'description' => 'required|string|min:10|max:2000',
                ]);
            }
        }

        $this->step++;
    }

    public function previousStep()
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function createListing()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            if ($this->listingType === 'business') {
                $user = auth()->user();

                // Check if user has active business plan
                $hasActiveBusinessPlan = $user->payment_plan === 'business'
                    && $user->plan_expires_at
                    && $user->plan_expires_at->isFuture()
                    && $user->business_plan_total > 0;

                $fee = 0;
                $isFromBusinessPlan = false;
                $paidUntil = null;

                if ($hasActiveBusinessPlan) {
                    // Count only businesses from business plan
                    $activeBusinessCount = $user->businesses()->where('status', 'active')->where('is_from_business_plan', true)->count();
                    $businessLimit = $user->business_plan_total;

                    if ($activeBusinessCount < $businessLimit) {
                        // User has business plan and hasn't reached limit - no fee
                        $isFromBusinessPlan = true;

                        \App\Models\Transaction::create([
                            'user_id' => $user->id,
                            'type' => 'business_plan_usage',
                            'amount' => 0,
                            'status' => 'completed',
                            'description' => 'Korišćenje biznis plana za business: ' . $this->title . ' (' . ($activeBusinessCount + 1) . '/' . $businessLimit . ' aktivnih)',
                            'reference_number' => 'BUSINESS-PLAN-' . now()->timestamp,
                        ]);
                    } else {
                        // User reached business plan limit - charge per-business fee
                        if (\App\Models\Setting::get('business_fee_enabled', false)) {
                            $fee = \App\Models\Setting::get('business_fee_amount', 2000);

                            // Check balance if fee is required
                            if ($fee > 0 && $user->balance < $fee) {
                                session()->flash('error', 'Dostigli ste limit biznis plana (' . $businessLimit . ' aktivnih). Za dodatne biznise potrebno je: ' . number_format($fee, 0, ',', '.') . ' RSD, a imate: ' . number_format($user->balance, 0, ',', '.') . ' RSD');
                                DB::rollBack();
                                $this->closeModal();
                                return redirect()->route('balance.payment-options');
                            }

                            // Charge fee
                            if ($fee > 0) {
                                $user->decrement('balance', $fee);

                                // Set paid_until for paid businesses
                                $businessDuration = \App\Models\Setting::get('business_auto_expire_days', 365);
                                $paidUntil = now()->addDays($businessDuration);

                                \App\Models\Transaction::create([
                                    'user_id' => $user->id,
                                    'type' => 'business_fee',
                                    'amount' => $fee,
                                    'status' => 'completed',
                                    'description' => 'Naplaćivanje za objavljivanje business-a (preko limita plana): ' . $this->title,
                                    'reference_number' => 'BUSINESS-FEE-' . now()->timestamp,
                                ]);
                            }
                        } else {
                            // Business fee is disabled - can't post more
                            session()->flash('error', 'Dostigli ste limit od ' . $businessLimit . ' aktivnih biznisa. Obrišite postojeći biznis da biste dodali novi.');
                            DB::rollBack();
                            $this->closeModal();
                            return redirect()->route('businesses.index');
                        }
                    }
                } else {
                    // No business plan - calculate business fee
                    if (\App\Models\Setting::get('business_fee_enabled', false)) {
                        $fee = \App\Models\Setting::get('business_fee_amount', 2000);
                    }

                    // Check balance if fee is required
                    if ($fee > 0 && $user->balance < $fee) {
                        session()->flash('error', 'Nemate dovoljno kredita za postavljanje business-a. Potrebno: ' . number_format($fee, 0, ',', '.') . ' RSD, a imate: ' . number_format($user->balance, 0, ',', '.') . ' RSD');
                        DB::rollBack();
                        $this->closeModal();
                        return redirect()->route('balance.payment-options');
                    }

                    // Charge fee if required
                    if ($fee > 0) {
                        $user->decrement('balance', $fee);

                        // Set paid_until for paid businesses
                        $businessDuration = \App\Models\Setting::get('business_auto_expire_days', 365);
                        $paidUntil = now()->addDays($businessDuration);

                        \App\Models\Transaction::create([
                            'user_id' => $user->id,
                            'type' => 'business_fee',
                            'amount' => $fee,
                            'status' => 'completed',
                            'description' => 'Naplaćivanje za objavljivanje business-a: ' . $this->title,
                            'reference_number' => 'BUSINESS-FEE-' . now()->timestamp,
                        ]);
                    }
                }

                // Save and optimize logo
                $logoPath = null;
                $imageService = new ImageOptimizationService();
                if ($this->logo) {
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

                // Determine expiry date based on how business was created
                if ($isFromBusinessPlan) {
                    // Business from plan expires when the plan expires
                    $expiresAt = $user->plan_expires_at;
                } else {
                    // Paid business expires after the configured days
                    $expiryDays = \App\Models\Setting::get('business_auto_expire_days', 365);
                    $expiresAt = now()->addDays($expiryDays);
                }

                $business = \App\Models\Business::create([
                    'user_id' => auth()->id(),
                    'name' => $this->title,
                    'description' => $this->description,
                    'slogan' => $this->slogan,
                    'business_category_id' => $this->category_id,
                    'subcategory_id' => $this->subcategory_id,
                    'location' => $this->location,
                    'address_1' => $this->address_1,
                    'address_2' => $this->address_2,
                    'contact_phone' => $this->contact_phone,
                    'contact_email' => $user->email, // Use email from user profile
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
                    'slug' => Str::slug($this->title) . '-' . Str::random(6),
                    'status' => 'active',
                    'expires_at' => $expiresAt,
                    'is_from_business_plan' => $isFromBusinessPlan,
                    'paid_until' => $paidUntil,
                ]);

                // Save images to database
                foreach ($imagePaths as $order => $path) {
                    $business->images()->create([
                        'path' => $path,
                        'filename' => basename($path),
                        'order' => $order
                    ]);
                }

                DB::commit();
                $this->closeModal();

                session()->flash('success', 'Business je uspešno kreiran!');
                return redirect()->route('businesses.show', $business->slug);

            } elseif ($this->listingType === 'service') {
                // Handle images for service
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
                        $imagePaths[] = $optimizedPaths['original'];
                    }
                }

                // Create Service
                $service = Service::create([
                    'user_id' => auth()->id(),
                    'title' => $this->title,
                    'service_category_id' => $this->category_id,
                    'subcategory_id' => $this->subcategory_id ?: null,
                    'description' => $this->description,
                    'price' => $this->price,
                    'price_type' => $this->price_type ?? 'fixed',
                    'location' => $this->location,
                    'contact_phone' => $this->contact_phone,
                    'status' => 'active',
                ]);

                // Save images to database
                foreach ($imagePaths as $path) {
                    $service->images()->create([
                        'image_path' => $path,
                        'order' => 0
                    ]);
                }

                DB::commit();
                $this->closeModal();

                session()->flash('success', 'Usluga je uspešno kreirana!');
                return redirect()->route('services.show', $service);

            } else {
                // Handle images for listing
                $imagePaths = [];
                $imageService = new ImageOptimizationService();

                if (!empty($this->images)) {
                    foreach ($this->images as $image) {
                        $filename = Str::random(40) . '.jpg';
                        $optimizedPaths = $imageService->processImage(
                            $image,
                            'listings',
                            $filename
                        );
                        $imagePaths[] = $optimizedPaths['original'];
                    }
                }

                $expiryDays = \App\Models\Setting::get('listing_auto_expire_days', 60);

                // Create Listing (listing, giveaway, or auction)
                $listing = Listing::create([
                    'user_id' => auth()->id(),
                    'title' => $this->title,
                    'slug' => Str::slug($this->title) . '-' . Str::random(6),
                    'description' => $this->description,
                    'category_id' => $this->category_id,
                    'subcategory_id' => $this->subcategory_id ?: null,
                    'condition_id' => $this->condition_id ?: null,
                    'price' => $this->listingType === 'giveaway' ? null : ($this->listingType === 'auction' ? $this->starting_price : $this->price),
                    'location' => $this->location,
                    'contact_phone' => $this->contact_phone,
                    'listing_type' => $this->listingType === 'auction' ? 'listing' : $this->listingType,
                    'status' => 'active',
                    'expires_at' => now()->addDays($expiryDays),
                ]);

                // Save images to database
                foreach ($imagePaths as $path) {
                    $listing->images()->create([
                        'image_path' => $path,
                        'order' => 0
                    ]);
                }

                // Create auction if type is auction
                if ($this->listingType === 'auction') {
                    $startsAt = $this->startType === 'immediately'
                        ? now()
                        : Carbon::createFromFormat('Y-m-d H:i', $this->startDate . ' ' . $this->startTime);

                    $endsAt = $startsAt->copy()->addDays((int)$this->duration);

                    Auction::create([
                        'listing_id' => $listing->id,
                        'user_id' => $listing->user_id,
                        'starting_price' => $this->starting_price,
                        'buy_now_price' => $this->buy_now_price ?: null,
                        'current_price' => $this->starting_price,
                        'starts_at' => $startsAt,
                        'ends_at' => $endsAt,
                        'status' => 'active',
                    ]);
                }

                DB::commit();
                $this->closeModal();

                session()->flash('success', $this->listingType === 'auction' ? 'Aukcija je uspešno kreirana!' : ($this->listingType === 'giveaway' ? 'Poklon je uspešno kreiran!' : 'Oglas je uspešno kreiran!'));
                return redirect()->route('listings.show', $listing);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Došlo je do greške: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.quick-listing');
    }
}
