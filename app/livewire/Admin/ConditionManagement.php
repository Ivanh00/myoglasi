<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ListingCondition;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ConditionManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 15;
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $selectedCondition = null;
    public $showEditModal = false;
    public $showDeleteModal = false;

    public $editState = [
        'name' => '',
        'slug' => '',
        'is_active' => true
    ];

    protected $listeners = ['refreshConditions' => '$refresh'];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function generateSlug()
    {
        if (!empty($this->editState['name']) && empty($this->editState['slug'])) {
            $this->editState['slug'] = Str::slug($this->editState['name']);
        }
    }

    public function createCondition()
    {
        $this->selectedCondition = null;
        $this->editState = [
            'name' => '',
            'slug' => '',
            'is_active' => true
        ];
        $this->showEditModal = true;
    }

    public function editCondition($conditionId)
    {
        $this->selectedCondition = ListingCondition::find($conditionId);
        
        $this->editState = [
            'name' => $this->selectedCondition->name,
            'slug' => $this->selectedCondition->slug,
            'is_active' => $this->selectedCondition->is_active
        ];

        $this->showEditModal = true;
    }

    public function saveCondition()
    {
        $rules = [
            'editState.name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('listing_conditions', 'name')->ignore($this->selectedCondition?->id)
            ],
            'editState.slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('listing_conditions', 'slug')->ignore($this->selectedCondition?->id)
            ],
            'editState.is_active' => 'boolean'
        ];

        $validated = $this->validate($rules);

        if ($this->selectedCondition) {
            // Ažuriranje postojećeg stanja
            $this->selectedCondition->update($validated['editState']);
            $message = 'Stanje oglasa uspešno ažurirano!';
        } else {
            // Kreiranje novog stanja
            ListingCondition::create($validated['editState']);
            $message = 'Stanje oglasa uspešno kreirano!';
        }

        $this->showEditModal = false;
        $this->dispatch('notify', type: 'success', message: $message);
    }

    public function confirmDelete($conditionId)
    {
        $this->selectedCondition = ListingCondition::withCount('listings')->find($conditionId);
        
        // Provera da li stanje ima povezane oglase
        if ($this->selectedCondition->listings_count > 0) {
            $this->dispatch('notify', type: 'error', message: 'Ne možete obrisati stanje koje ima povezane oglase!');
            return;
        }

        $this->showDeleteModal = true;
    }

    public function deleteCondition()
    {
        if ($this->selectedCondition) {
            $this->selectedCondition->delete();
            $this->showDeleteModal = false;
            $this->dispatch('notify', type: 'success', message: 'Stanje oglasa uspešno obrisano!');
        }
    }

    public function toggleActive($conditionId)
    {
        $condition = ListingCondition::find($conditionId);
        $condition->is_active = !$condition->is_active;
        $condition->save();

        $this->dispatch('notify', type: 'success', message: 'Status stanja ažuriran!');
    }

    public function render()
    {
        $conditions = ListingCondition::withCount('listings')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('slug', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.condition-management', compact('conditions'))
            ->layout('layouts.admin');
    }
}