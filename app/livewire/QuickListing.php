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

    // Step 4: Description
    public $description = '';

    // User info
    public $location = '';
    public $contact_phone = '';

    // Step 5: Images
    public $images = [];

    public $categories = [];
    public $subcategories = [];
    public $conditions = [];

    protected function rules()
    {
        return [
            'listingType' => 'required|in:listing,auction,service,giveaway',
            'title' => 'required|string|max:255',
            'category_id' => 'required',
            'description' => 'required|string|min:10',
            'price' => 'required_unless:listingType,giveaway,auction|numeric|min:0',
            'images.*' => 'nullable|image|max:5120',
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
        } else {
            $this->subcategories = Category::where('parent_id', $this->category_id)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        }
        $this->subcategory_id = '';
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
            } elseif ($this->listingType !== 'giveaway') {
                $this->validate([
                    'price' => 'required|numeric|min:1',
                ]);
            }
        }

        if ($this->step === 4) {
            $this->validate([
                'description' => 'required|string|min:10|max:2000',
            ]);
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

            if ($this->listingType === 'service') {
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
