<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Rating;
use App\Models\User;

class RatingManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 20;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $selectedRating = null;
    public $showEditModal = false;
    public $showDeleteModal = false;

    public $filters = [
        'rating_type' => '', // all, positive, neutral, negative
        'date_from' => '',
        'date_to' => ''
    ];

    public $editState = [
        'rating' => '',
        'comment' => ''
    ];

    public $ratingOptions = [
        'positive' => 'Pozitivna',
        'neutral' => 'Neutralna',
        'negative' => 'Negativna'
    ];

    protected $listeners = ['refreshRatings' => '$refresh'];

    public function mount()
    {
        // Initialize if needed
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

    public function setRatingFilter($type)
    {
        $this->filters['rating_type'] = $type;
        $this->resetPage();
    }

    public function applyFilters($query)
    {
        return $query
            ->when($this->filters['rating_type'], function ($query, $type) {
                return $query->where('rating', $type);
            })
            ->when($this->filters['date_from'], function ($query, $dateFrom) {
                return $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($this->filters['date_to'], function ($query, $dateTo) {
                return $query->whereDate('created_at', '<=', $dateTo);
            });
    }

    public function editRating($ratingId)
    {
        $this->selectedRating = Rating::with(['rater', 'ratedUser', 'listing'])->find($ratingId);
        
        $this->editState = [
            'rating' => $this->selectedRating->rating,
            'comment' => $this->selectedRating->comment
        ];

        $this->showEditModal = true;
    }

    public function updateRating()
    {
        $validated = $this->validate([
            'editState.rating' => 'required|in:positive,neutral,negative',
            'editState.comment' => 'nullable|string|max:1000'
        ]);

        $this->selectedRating->update([
            'rating' => $validated['editState']['rating'],
            'comment' => $validated['editState']['comment']
        ]);

        $this->showEditModal = false;
        $this->dispatch('notify', type: 'success', message: 'Ocena je uspešno ažurirana!');
    }

    public function confirmDelete($ratingId)
    {
        $this->selectedRating = Rating::with(['rater', 'ratedUser', 'listing'])->find($ratingId);
        $this->showDeleteModal = true;
    }

    public function deleteRating()
    {
        $this->selectedRating->delete();
        $this->showDeleteModal = false;
        $this->dispatch('notify', type: 'success', message: 'Ocena je uspešno obrisana!');
    }

    public function closeModals()
    {
        $this->showEditModal = false;
        $this->showDeleteModal = false;
        $this->selectedRating = null;
    }

    public function resetFilters()
    {
        $this->filters = [
            'rating_type' => '',
            'date_from' => '',
            'date_to' => ''
        ];
        $this->search = '';
        $this->resetPage();
    }

    public function getStats()
    {
        $total = Rating::count();
        $positive = Rating::where('rating', 'positive')->count();
        $neutral = Rating::where('rating', 'neutral')->count();
        $negative = Rating::where('rating', 'negative')->count();

        return [
            'total' => $total,
            'positive' => $positive,
            'neutral' => $neutral,
            'negative' => $negative,
            'positive_percentage' => $total > 0 ? round(($positive / $total) * 100, 1) : 0,
            'negative_percentage' => $total > 0 ? round(($negative / $total) * 100, 1) : 0
        ];
    }

    public function render()
    {
        $ratings = Rating::with(['rater', 'ratedUser', 'listing'])
            ->when($this->search, function ($query) {
                $query->where('comment', 'like', '%' . $this->search . '%')
                      ->orWhereHas('rater', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      })
                      ->orWhereHas('ratedUser', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      })
                      ->orWhereHas('listing', function ($q) {
                          $q->where('title', 'like', '%' . $this->search . '%');
                      });
            })
            ->when($this->filters, function ($query) {
                return $this->applyFilters($query);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $stats = $this->getStats();

        return view('livewire.admin.rating-management', compact('ratings', 'stats'))
            ->layout('layouts.admin');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}