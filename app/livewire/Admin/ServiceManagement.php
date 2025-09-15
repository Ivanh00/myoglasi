<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Service;
use App\Models\ServiceCategory;

class ServiceManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $selectedService = null;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $categories = [];

    public $statusOptions = [
        'active' => 'Aktivan',
        'inactive' => 'Neaktivan',
        'expired' => 'Istekao'
    ];

    public $editState = [
        'title' => '',
        'description' => '',
        'price' => '',
        'service_category_id' => '',
        'subcategory_id' => '',
        'status' => 'active',
        'location' => '',
        'contact_phone' => ''
    ];

    public $filters = [
        'status' => '',
        'service_category_id' => '',
    ];

    public function mount()
    {
        $this->categories = ServiceCategory::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function updatingSearch()
    {
        $this->resetPage();
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

    public function editService($serviceId)
    {
        $this->selectedService = Service::find($serviceId);
        $this->editState = [
            'title' => $this->selectedService->title,
            'description' => $this->selectedService->description,
            'price' => $this->selectedService->price,
            'service_category_id' => $this->selectedService->service_category_id,
            'subcategory_id' => $this->selectedService->subcategory_id,
            'status' => $this->selectedService->status,
            'location' => $this->selectedService->location,
            'contact_phone' => $this->selectedService->contact_phone,
        ];
        $this->showEditModal = true;
    }

    public function updateService()
    {
        $validated = $this->validate([
            'editState.title' => 'required|string|max:100',
            'editState.description' => 'required|string|max:2000',
            'editState.price' => 'required|numeric|min:0',
            'editState.service_category_id' => 'required|exists:service_categories,id',
            'editState.status' => 'required|in:active,inactive,expired',
            'editState.location' => 'required|string|max:255',
            'editState.contact_phone' => 'nullable|string|max:20',
        ]);

        $this->selectedService->update($validated['editState']);
        $this->showEditModal = false;
        $this->dispatch('notify', type: 'success', message: 'Usluga uspešno ažurirana!');
    }

    public function confirmDeleteService($serviceId)
    {
        $this->selectedService = Service::find($serviceId);
        $this->showDeleteModal = true;
    }

    public function deleteService()
    {
        // Delete images
        foreach ($this->selectedService->images as $image) {
            \Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        // Delete service
        $this->selectedService->delete();
        $this->showDeleteModal = false;
        $this->dispatch('notify', type: 'success', message: 'Usluga uspešno obrisana!');
    }

    public function activateService($serviceId)
    {
        $service = Service::find($serviceId);
        $service->update(['status' => 'active']);
        $this->dispatch('notify', type: 'success', message: 'Usluga aktivirana!');
    }

    public function deactivateService($serviceId)
    {
        $service = Service::find($serviceId);
        $service->update(['status' => 'inactive']);
        $this->dispatch('notify', type: 'success', message: 'Usluga deaktivirana!');
    }

    public function closeModals()
    {
        $this->showEditModal = false;
        $this->showDeleteModal = false;
        $this->selectedService = null;
    }

    public function render()
    {
        $services = Service::with(['user', 'category', 'subcategory', 'images'])
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%')
                      ->orWhereHas('user', function ($userQuery) {
                          $userQuery->where('name', 'like', '%' . $this->search . '%');
                      });
            })
            ->when($this->filters['status'], function ($query) {
                $query->where('status', $this->filters['status']);
            })
            ->when($this->filters['service_category_id'], function ($query) {
                $query->where('service_category_id', $this->filters['service_category_id']);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.service-management', compact('services'))
            ->layout('layouts.admin');
    }
}