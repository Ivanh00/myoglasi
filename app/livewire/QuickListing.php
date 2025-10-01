<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\ListingCondition;
use App\Models\ServiceCategory;
use App\Models\Listing;
use App\Models\Service;
use App\Models\Auction;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
    public $starting_price = '';
    public $reserve_price = '';
    public $auction_end_date = '';
    public $auction_end_time = '';

    // Step 4: Description
    public $description = '';

    // Step 5: Images
    public $images = [];

    public $categories = [];
    public $subcategories = [];
    public $conditions = [];

    protected function rules()
    {
        return [
            'listingType' => 'required|in:standard,auction,service,giveaway',
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'price' => 'required_unless:listingType,giveaway|numeric|min:0',
            'images.*' => 'nullable|image|max:5120',
        ];
    }

    public function mount()
    {
        $this->loadCategories();
        $this->conditions = ListingCondition::orderBy('id')->get();
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
        $this->reset(['listingType', 'title', 'category_id', 'subcategory_id', 'condition_id', 'price', 'starting_price', 'reserve_price', 'auction_end_date', 'auction_end_time', 'description', 'images']);
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
                'title' => 'required|string|max:255',
                'category_id' => 'required',
            ]);
        }

        if ($this->step === 3) {
            if ($this->listingType === 'auction') {
                $this->validate([
                    'starting_price' => 'required|numeric|min:0',
                    'auction_end_date' => 'required|date|after:today',
                    'auction_end_time' => 'required',
                ]);
            } elseif ($this->listingType !== 'giveaway') {
                $this->validate([
                    'price' => 'required|numeric|min:0',
                ]);
            }
        }

        if ($this->step === 4) {
            $this->validate([
                'description' => 'required|string|min:10',
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
                // Create Service
                $service = Service::create([
                    'user_id' => auth()->id(),
                    'title' => $this->title,
                    'service_category_id' => $this->category_id,
                    'subcategory_id' => $this->subcategory_id ?: null,
                    'description' => $this->description,
                    'price' => $this->price,
                    'price_type' => 'fixed',
                    'status' => 'active',
                ]);

                // Handle images for service if needed
                if (!empty($this->images)) {
                    foreach ($this->images as $image) {
                        $path = $image->store('services', 'public');
                        $service->images()->create(['image_path' => $path]);
                    }
                }
            } else {
                // Create Listing
                $listing = Listing::create([
                    'user_id' => auth()->id(),
                    'title' => $this->title,
                    'slug' => Str::slug($this->title) . '-' . time(),
                    'description' => $this->description,
                    'category_id' => $this->category_id,
                    'subcategory_id' => $this->subcategory_id ?: null,
                    'condition_id' => $this->condition_id ?: null,
                    'price' => $this->listingType === 'giveaway' ? 0 : $this->price,
                    'location' => auth()->user()->location ?? 'Srbija',
                    'listing_type' => $this->listingType,
                    'status' => 'active',
                ]);

                // Handle images
                if (!empty($this->images)) {
                    foreach ($this->images as $image) {
                        $path = $image->store('listings', 'public');
                        $listing->images()->create(['image_path' => $path]);
                    }
                }

                // Create auction if type is auction
                if ($this->listingType === 'auction') {
                    $endDateTime = $this->auction_end_date . ' ' . $this->auction_end_time;

                    Auction::create([
                        'listing_id' => $listing->id,
                        'starting_price' => $this->starting_price,
                        'reserve_price' => $this->reserve_price ?: null,
                        'current_price' => $this->starting_price,
                        'end_time' => $endDateTime,
                        'status' => 'active',
                    ]);
                }
            }

            DB::commit();

            session()->flash('success', 'Oglas je uspešno kreiran!');
            $this->closeModal();

            if ($this->listingType === 'service') {
                return redirect()->route('services.my');
            } else {
                return redirect()->route('listings.my');
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
