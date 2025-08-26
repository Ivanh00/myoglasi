<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ListingImage;
use App\Models\Listing;
use Illuminate\Support\Facades\Storage;

class ImageManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 24;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $selectedImage = null;
    public $showViewModal = false;
    public $showDeleteModal = false;

    public $filters = [
        'listing_id' => '',
        'is_primary' => ''
    ];

    public $listings = [];

    protected $listeners = ['refreshImages' => '$refresh'];

    public function mount()
    {
        $this->loadListings();
    }

    public function loadListings()
    {
        $this->listings = Listing::orderBy('title')->get();
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
            ->when($this->filters['listing_id'], function ($query, $listingId) {
                return $query->where('listing_id', $listingId);
            })
            ->when($this->filters['is_primary'] !== '', function ($query) {
                return $query->where('is_primary', $this->filters['is_primary']);
            });
    }

    public function viewImage($imageId)
    {
        $this->selectedImage = ListingImage::with('listing')->find($imageId);
        $this->showViewModal = true;
    }

    public function confirmDelete($imageId)
    {
        $this->selectedImage = ListingImage::with('listing')->find($imageId);
        $this->showDeleteModal = true;
    }

    public function deleteImage()
    {
        if ($this->selectedImage) {
            // Obriši fizički fajl sa storage-a
            if (Storage::disk('public')->exists($this->selectedImage->image_path)) {
                Storage::disk('public')->delete($this->selectedImage->image_path);
            }

            // Obriši zapis iz baze
            $this->selectedImage->delete();

            $this->showDeleteModal = false;
            $this->dispatch('notify', type: 'success', message: 'Slika uspešno obrisana!');
        }
    }

    public function setAsPrimary($imageId)
    {
        $image = ListingImage::find($imageId);
        
        // Resetuj sve primarne slike za ovaj oglas
        ListingImage::where('listing_id', $image->listing_id)
            ->update(['is_primary' => false]);

        // Postavi ovu sliku kao primarnu
        $image->update(['is_primary' => true]);

        $this->dispatch('notify', type: 'success', message: 'Slika postavljena kao primarna!');
    }

    public function resetFilters()
    {
        $this->filters = [
            'listing_id' => '',
            'is_primary' => ''
        ];
    }

    public function getStats()
    {
        $totalImages = ListingImage::count();
        $primaryImages = ListingImage::where('is_primary', true)->count();
        $totalSize = ListingImage::sum('file_size'); // Ovo bi zahtevalo dodavanje file_size kolone

        return [
            'total' => $totalImages,
            'primary' => $primaryImages,
            'totalSize' => $totalSize
        ];
    }

    public function render()
    {
        $images = ListingImage::with('listing')
            ->when($this->search, function ($query) {
                $query->whereHas('listing', function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filters, function ($query) {
                return $this->applyFilters($query);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $stats = $this->getStats();

        return view('livewire.admin.image-management', compact('images', 'stats'))
            ->layout('layouts.admin');
    }
}