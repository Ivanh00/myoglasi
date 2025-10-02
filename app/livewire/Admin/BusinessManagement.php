<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Business;
use App\Models\BusinessCategory;

class BusinessManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    public $filters = [
        'status' => '',
        'category' => '',
    ];

    public $showEditModal = false;
    public $selectedBusiness = null;
    public $editState = [];
    public $subcategories = [];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function deleteBusiness($id)
    {
        $business = Business::find($id);
        if ($business) {
            $business->delete();
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Business je uspešno obrisan.'
            ]);
        }
    }

    public function toggleStatus($id)
    {
        $business = Business::find($id);
        if ($business) {
            $business->status = $business->status === 'active' ? 'inactive' : 'active';
            $business->save();
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Status business-a je ažuriran.'
            ]);
        }
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filters = [
            'status' => '',
            'category' => '',
        ];
        $this->resetPage();
    }

    public function editBusiness($id)
    {
        $this->selectedBusiness = Business::with(['category', 'subcategory'])->find($id);

        if ($this->selectedBusiness) {
            $this->editState = [
                'name' => $this->selectedBusiness->name,
                'slogan' => $this->selectedBusiness->slogan,
                'description' => $this->selectedBusiness->description,
                'business_category_id' => $this->selectedBusiness->business_category_id,
                'subcategory_id' => $this->selectedBusiness->subcategory_id,
                'contact_name_2' => $this->selectedBusiness->contact_name_2,
                'contact_phone_2' => $this->selectedBusiness->contact_phone_2,
                'contact_name_3' => $this->selectedBusiness->contact_name_3,
                'contact_phone_3' => $this->selectedBusiness->contact_phone_3,
                'website_url' => $this->selectedBusiness->website_url,
                'facebook_url' => $this->selectedBusiness->facebook_url,
                'instagram_url' => $this->selectedBusiness->instagram_url,
                'established_year' => $this->selectedBusiness->established_year,
                'status' => $this->selectedBusiness->status,
            ];

            // Load subcategories if category is selected
            if ($this->editState['business_category_id']) {
                $this->subcategories = BusinessCategory::where('parent_id', $this->editState['business_category_id'])
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->get();
            }

            $this->showEditModal = true;
        }
    }

    public function updatedEditState($value, $key)
    {
        if ($key === 'business_category_id') {
            $this->editState['subcategory_id'] = null;
            if ($value) {
                $this->subcategories = BusinessCategory::where('parent_id', $value)
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->get();
            } else {
                $this->subcategories = [];
            }
        }
    }

    public function updateBusiness()
    {
        $this->validate([
            'editState.name' => 'required|string|max:255',
            'editState.description' => 'required|string',
            'editState.business_category_id' => 'required|exists:business_categories,id',
            'editState.subcategory_id' => 'nullable|exists:business_categories,id',
            'editState.contact_name_2' => 'nullable|string|max:255',
            'editState.contact_phone_2' => 'nullable|string|max:50',
            'editState.contact_name_3' => 'nullable|string|max:255',
            'editState.contact_phone_3' => 'nullable|string|max:50',
            'editState.status' => 'required|in:active,inactive,expired',
            'editState.established_year' => 'nullable|integer|min:1900|max:' . date('Y'),
        ]);

        $this->selectedBusiness->update([
            'name' => $this->editState['name'],
            'slogan' => $this->editState['slogan'],
            'description' => $this->editState['description'],
            'business_category_id' => $this->editState['business_category_id'],
            'subcategory_id' => $this->editState['subcategory_id'],
            'contact_name_2' => $this->editState['contact_name_2'],
            'contact_phone_2' => $this->editState['contact_phone_2'],
            'contact_name_3' => $this->editState['contact_name_3'],
            'contact_phone_3' => $this->editState['contact_phone_3'],
            'website_url' => $this->editState['website_url'],
            'facebook_url' => $this->editState['facebook_url'],
            'instagram_url' => $this->editState['instagram_url'],
            'established_year' => $this->editState['established_year'],
            'status' => $this->editState['status'],
        ]);

        $this->showEditModal = false;
        $this->selectedBusiness = null;
        $this->editState = [];

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Business je uspešno ažuriran.'
        ]);
    }

    public function render()
    {
        $query = Business::with(['category', 'subcategory', 'user', 'images'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%')
                      ->orWhere('location', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filters['status'], function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($this->filters['category'], function ($query, $categoryId) {
                $query->where('business_category_id', $categoryId);
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $businesses = $query->paginate($this->perPage);

        $categories = BusinessCategory::whereNull('parent_id')->get();

        return view('livewire.admin.business-management', [
            'businesses' => $businesses,
            'categories' => $categories,
        ])->layout('layouts.admin');
    }
}
