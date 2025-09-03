<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 15;
    public $sortField = 'sort_order';
    public $sortDirection = 'asc';
    public $selectedCategory = null;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $showChildren = false;

    public $editState = [
        'name' => '',
        'slug' => '',
        'description' => '',
        'icon' => '',
        'parent_id' => null,
        'sort_order' => 0,
        'is_active' => true
    ];

    protected $listeners = ['refreshCategories' => '$refresh'];

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

    public function editCategory($categoryId)
    {
        $this->selectedCategory = Category::find($categoryId);
        
        $this->editState = [
            'name' => $this->selectedCategory->name,
            'slug' => $this->selectedCategory->slug,
            'description' => $this->selectedCategory->description,
            'icon' => $this->selectedCategory->icon,
            'parent_id' => $this->selectedCategory->parent_id,
            'sort_order' => $this->selectedCategory->sort_order,
            'is_active' => $this->selectedCategory->is_active
        ];

        $this->showEditModal = true;
    }

    public function createCategory()
    {
        $this->selectedCategory = null;
        
        // Auto-generate sort_order for new category
        $nextSortOrder = Category::whereNull('parent_id')->max('sort_order') + 1;
        
        $this->editState = [
            'name' => '',
            'slug' => '',
            'description' => '',
            'icon' => '',
            'parent_id' => null,
            'sort_order' => $nextSortOrder,
            'is_active' => true
        ];
        $this->showEditModal = true;
    }

    public function createSubcategory($parentId)
    {
        $this->selectedCategory = null;
        
        // Auto-generate sort_order for new subcategory
        $nextSortOrder = Category::where('parent_id', $parentId)->max('sort_order') + 1;
        
        $this->editState = [
            'name' => '',
            'slug' => '',
            'description' => '',
            'icon' => '',
            'parent_id' => $parentId,
            'sort_order' => $nextSortOrder,
            'is_active' => true
        ];
        $this->showEditModal = true;
    }

    public function saveCategory()
    {
        $rules = [
            'editState.name' => 'required|string|max:255',
            'editState.slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'slug')->ignore($this->selectedCategory?->id)
            ],
            'editState.description' => 'nullable|string',
            'editState.icon' => 'nullable|string|max:1000',
            'editState.parent_id' => 'nullable|exists:categories,id',
            'editState.sort_order' => 'required|integer|min:0',
            'editState.is_active' => 'boolean'
        ];

        // Provera da roditeljska kategorija nije ista kao trenutna
        if ($this->selectedCategory && $this->editState['parent_id'] == $this->selectedCategory->id) {
            $this->addError('editState.parent_id', 'Kategorija ne može biti roditelj sama sebi.');
            return;
        }

        $validated = $this->validate($rules);

        if ($this->selectedCategory) {
            // Ažuriranje postojeće kategorije
            $this->selectedCategory->update($validated['editState']);
            $message = 'Kategorija uspešno ažurirana!';
        } else {
            // Kreiranje nove kategorije
            Category::create($validated['editState']);
            $message = 'Kategorija uspešno kreirana!';
        }

        $this->showEditModal = false;
        $this->dispatch('notify', type: 'success', message: $message);
    }

    public function confirmDelete($categoryId)
    {
        $this->selectedCategory = Category::with(['children', 'listings'])->find($categoryId);
        
        // Provera da li kategorija ima podkategorije ili oglase
        if ($this->selectedCategory->children->count() > 0) {
            $this->dispatch('notify', type: 'error', message: 'Ne možete obrisati kategoriju koja ima podkategorije!');
            return;
        }

        if ($this->selectedCategory->listings->count() > 0) {
            $this->dispatch('notify', type: 'error', message: 'Ne možete obrisati kategoriju koja ima oglase!');
            return;
        }

        $this->showDeleteModal = true;
    }

    public function deleteCategory()
    {
        if ($this->selectedCategory) {
            $this->selectedCategory->delete();
            $this->showDeleteModal = false;
            $this->dispatch('notify', type: 'success', message: 'Kategorija uspešno obrisana!');
        }
    }

    public function toggleActive($categoryId)
    {
        $category = Category::find($categoryId);
        $category->is_active = !$category->is_active;
        $category->save();

        // Deaktiviraj sve podkategorije ako se glavna kategorija deaktivira
        if (!$category->is_active && $category->children->count() > 0) {
            $category->children()->update(['is_active' => false]);
        }

        $this->dispatch('notify', type: 'success', message: 'Status kategorije ažuriran!');
    }

    public function updateSortOrder($categoryId, $newOrder)
    {
        $category = Category::find($categoryId);
        $category->sort_order = $newOrder;
        $category->save();

        $this->dispatch('notify', type: 'success', message: 'Redosled kategorije ažuriran!');
    }

    public function runCategorySeeder()
    {
        try {
            \Artisan::call('db:seed', ['--class' => 'CategorySeeder', '--force' => true]);
            $this->dispatch('notify', type: 'success', message: 'Kategorije iz seeder-a uspešno učitane!');
        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Greška pri učitavanju kategorija: ' . $e->getMessage());
        }
    }

    public function runConditionSeeder()
    {
        try {
            \Artisan::call('db:seed', ['--class' => 'ListingConditionSeeder', '--force' => true]);
            $this->dispatch('notify', type: 'success', message: 'Uslovi oglasa iz seeder-a uspešno učitani!');
        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Greška pri učitavanju uslova: ' . $e->getMessage());
        }
    }

    public function exportCategories()
    {
        $categories = Category::with(['children'])->whereNull('parent_id')->get();
        $exportData = [];

        foreach ($categories as $category) {
            $categoryData = [
                'name' => $category->name,
                'description' => $category->description,
                'icon' => $category->icon,
                'sort_order' => $category->sort_order,
                'children' => []
            ];

            foreach ($category->children as $child) {
                $categoryData['children'][] = [
                    'name' => $child->name,
                    'description' => $child->description,
                    'icon' => $child->icon,
                    'sort_order' => $child->sort_order,
                ];
            }

            $exportData[] = $categoryData;
        }

        $this->dispatch('downloadCategories', json_encode($exportData, JSON_PRETTY_PRINT));
    }

    public function render()
    {
        $query = Category::with(['parent', 'children'])
            ->withCount('listings')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%')
                      ->orWhere('slug', 'like', '%' . $this->search . '%');
            });

        // Prikaz samo glavnih kategorija ili svih u zavisnosti od postavke
        if (!$this->showChildren) {
            $query->whereNull('parent_id');
        }

        $categories = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        // Dodaj parent kategorije za modal dropdown
        $parentCategories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('livewire.admin.category-management', compact('categories', 'parentCategories'))
            ->layout('layouts.admin');
    }
}