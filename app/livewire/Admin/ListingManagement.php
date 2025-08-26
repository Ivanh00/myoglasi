<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Listing;
use App\Models\Category;
use App\Models\ListingCondition;
use Illuminate\Support\Str;

class ListingManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $selectedListing = null;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $categories = [];
    public $conditions = [];
    public $statusOptions = [
        'active' => 'Aktivan',
        'inactive' => 'Neaktivan', 
        'sold' => 'Prodat',
        'expired' => 'Istekao'
    ];

    public $editState = [
        'title' => '',
        'description' => '',
        'price' => '',
        'category_id' => '',
        'subcategory_id' => '',
        'condition_id' => '',
        'status' => 'active',
        'location' => '',
        'is_featured' => false,
        'contact_phone' => ''
    ];

    public $filters = [
        'status' => '',
        'category_id' => '',
        'is_featured' => ''
    ];

    protected $listeners = ['refreshListings' => '$refresh'];

    public function mount()
    {
        $this->categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $this->conditions = ListingCondition::where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function applyFilters($query)
    {
        return $query
            ->when($this->filters['status'], function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($this->filters['category_id'], function ($query, $categoryId) {
                return $query->where('category_id', $categoryId);
            })
            ->when($this->filters['is_featured'] !== '', function ($query) {
                return $query->where('is_featured', $this->filters['is_featured']);
            });
    }

    public function editListing($listingId)
    {
        $this->selectedListing = Listing::with(['category', 'subcategory', 'condition', 'user'])->find($listingId);
        
        $this->editState = [
            'title' => $this->selectedListing->title,
            'description' => $this->selectedListing->description,
            'price' => $this->selectedListing->price,
            'category_id' => $this->selectedListing->category_id,
            'subcategory_id' => $this->selectedListing->subcategory_id,
            'condition_id' => $this->selectedListing->condition_id,
            'status' => $this->selectedListing->status,
            'location' => $this->selectedListing->location,
            'is_featured' => $this->selectedListing->is_featured,
            'contact_phone' => $this->selectedListing->contact_phone
        ];

        $this->showEditModal = true;
    }

    public function updateListing()
    {
        $validated = $this->validate([
            'editState.title' => 'required|string|min:5|max:100',
            'editState.description' => 'required|string|min:10|max:2000',
            'editState.price' => 'required|numeric|min:1',
            'editState.category_id' => 'required|exists:categories,id',
            'editState.subcategory_id' => 'nullable|exists:categories,id',
            'editState.condition_id' => 'required|exists:listing_conditions,id',
            'editState.status' => 'required|in:active,inactive,sold,expired',
            'editState.location' => 'required|string|max:255',
            'editState.is_featured' => 'boolean',
            'editState.contact_phone' => 'nullable|string|max:20'
        ]);

        $this->selectedListing->update($validated['editState']);
        
        $this->showEditModal = false;
        $this->dispatch('notify', type: 'success', message: 'Oglas uspešno ažuriran!');
    }

    public function confirmDelete($listingId)
    {
        $this->selectedListing = Listing::find($listingId);
        $this->showDeleteModal = true;
    }

    public function deleteListing()
    {
        if ($this->selectedListing) {
            // Obriši sve slike prvo
            $this->selectedListing->images()->delete();
            
            // Obriši sve poruke vezane za oglas
            $this->selectedListing->messages()->delete();
            
            // Obriši oglas
            $this->selectedListing->delete();
            
            $this->showDeleteModal = false;
            $this->dispatch('notify', type: 'success', message: 'Oglas uspešno obrisan!');
        }
    }

    public function toggleFeatured($listingId)
    {
        $listing = Listing::find($listingId);
        $listing->is_featured = !$listing->is_featured;
        $listing->save();

        $this->dispatch('notify', type: 'success', message: 'Oglas ' . ($listing->is_featured ? 'istaknut' : 'uklonjen iz isticanja'));
    }

    public function updateStatus($listingId, $status)
    {
        $listing = Listing::find($listingId);
        $listing->status = $status;
        $listing->save();

        $this->dispatch('notify', type: 'success', message: 'Status oglasa ažuriran na: ' . $this->statusOptions[$status]);
    }

    public function resetFilters()
    {
        $this->filters = [
            'status' => '',
            'category_id' => '',
            'is_featured' => ''
        ];
    }

    public function render()
    {
        $listings = Listing::with(['category', 'subcategory', 'condition', 'user', 'images'])
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%')
                      ->orWhere('location', 'like', '%' . $this->search . '%')
                      ->orWhereHas('user', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      });
            })
            ->when($this->filters, function ($query) {
                return $this->applyFilters($query);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $subcategories = $this->editState['category_id'] 
            ? Category::where('parent_id', $this->editState['category_id'])->get()
            : collect();

        return view('livewire.admin.listing-management', compact('listings', 'subcategories'))
            ->layout('layouts.admin');
    }
}