<?php

namespace App\Livewire;

use App\Models\Listing;
use Livewire\Component;

class CreateListingComponent extends Component
{
    public function render()
    {
        return view('livewire.create-listing-component');
    }

    public $title, $description, $price, $category_id;
    public $images = [];
    
    protected $rules = [
        'title' => 'required|min:10',
        'description' => 'required|min:20',
        'price' => 'required|numeric|min:1',
        'category_id' => 'required|exists:categories,id'
    ];
    
    public function mount()
    {
        // Proveri da li je korisnik autentifikovan
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Morate se registrovati da biste postavljali oglase.');
        }
    }
    
    public function createListing()
    {
        $this->validate();
        
        $user = auth()->user();
        $fee = 10; // 10 dinara
        
        if (!$user->deductBalance($fee)) {
            session()->flash('error', 'Nemate dovoljno sredstava na računu. Potrebno je najmanje 10 RSD.');
            return;
        }
        
        $listing = Listing::create([
            'user_id' => $user->id,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'category_id' => $this->category_id,
            'status' => 'active',
            'expires_at' => now()->addDays(30)
        ]);
        
        // Sačuvaj slike
        foreach ($this->images as $index => $image) {
            $path = $image->store('listings', 'public');
            ListingImage::create([
                'listing_id' => $listing->id,
                'image_path' => $path,
                'is_primary' => $index === 0
            ]);
        }
        
        // Zabeleži transakciju
        Transaction::create([
            'user_id' => $user->id,
            'type' => 'listing_fee',
            'amount' => -$fee,
            'description' => 'Naknada za oglas: ' . $this->title
        ]);
        
        session()->flash('success', 'Oglas je uspešno kreiran!');
        return redirect()->route('listings.show', $listing);
    }
}
