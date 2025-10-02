<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\BusinessCategory;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BusinessCategoryManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 15;
    public $sortField = 'sort_order';
    public $sortDirection = 'asc';
    public $selectedCategory = null;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $showWriteSeederModal = false;
    public $showChildren = false;
    public $forceDelete = false;

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
        $this->selectedCategory = BusinessCategory::find($categoryId);

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
        $nextSortOrder = BusinessCategory::whereNull('parent_id')->max('sort_order') + 1;

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
        $nextSortOrder = BusinessCategory::where('parent_id', $parentId)->max('sort_order') + 1;

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
                Rule::unique('business_categories', 'slug')->ignore($this->selectedCategory?->id)
            ],
            'editState.description' => 'nullable|string',
            'editState.icon' => 'nullable|string|max:1000',
            'editState.parent_id' => 'nullable|exists:business_categories,id',
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
            $message = 'Business kategorija uspešno ažurirana!';
        } else {
            // Kreiranje nove kategorije
            BusinessCategory::create($validated['editState']);
            $message = 'Business kategorija uspešno kreirana!';
        }

        $this->showEditModal = false;
        session()->flash('success', $message);
    }

    public function confirmDelete($categoryId)
    {
        $this->selectedCategory = BusinessCategory::with(['children', 'businesses'])->find($categoryId);
        $this->forceDelete = false;

        // Provera da li kategorija ima podkategorije
        if ($this->selectedCategory->children->count() > 0) {
            $this->forceDelete = true;
        }

        // Provera da li kategorija ima businesses
        $totalBusinesses = $this->selectedCategory->businesses->count();
        if ($totalBusinesses > 0) {
            session()->flash('error', 'Ne možete obrisati kategoriju koja ima ' . $totalBusinesses . ' business-a! Prvo premestite ili obrišite business-e.');
            return;
        }

        $this->showDeleteModal = true;
    }

    public function deleteCategory($force = false)
    {
        if ($this->selectedCategory) {
            if ($force || $this->forceDelete) {
                // Prvo obriši sve podkategorije
                $this->selectedCategory->children()->delete();
                session()->flash('success', 'Obrisane sve podkategorije za kategoriju: ' . $this->selectedCategory->name);
            }

            $this->selectedCategory->delete();
            $this->showDeleteModal = false;
            session()->flash('success', 'Business kategorija uspešno obrisana!');
        }
    }

    public function toggleActive($categoryId)
    {
        $category = BusinessCategory::find($categoryId);
        $category->is_active = !$category->is_active;
        $category->save();

        // Deaktiviraj sve podkategorije ako se glavna kategorija deaktivira
        if (!$category->is_active && $category->children->count() > 0) {
            $category->children()->update(['is_active' => false]);
        }

        session()->flash('success', 'Status kategorije ažuriran!');
    }

    public function runCategorySeeder()
    {
        try {
            // Disable foreign key checks temporarily
            \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // Clear existing business categories
            BusinessCategory::truncate();

            // Re-enable foreign key checks
            \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            // Run the seeder
            $seeder = new \Database\Seeders\BusinessCategorySeeder();
            $seeder->run();

            session()->flash('success', 'Business kategorije uspešno učitane iz seedera!');
            $this->dispatch('refreshCategories');
        } catch (\Exception $e) {
            session()->flash('error', 'Greška pri učitavanju kategorija: ' . $e->getMessage());
        }
    }

    public function exportCategories()
    {
        try {
            $categories = BusinessCategory::with('children')
                ->whereNull('parent_id')
                ->orderBy('sort_order')
                ->get()
                ->map(function ($category) {
                    return [
                        'name' => $category->name,
                        'slug' => $category->slug,
                        'icon' => $category->icon,
                        'description' => $category->description,
                        'sort_order' => $category->sort_order,
                        'is_active' => $category->is_active,
                        'children' => $category->children->map(function ($child) {
                            return [
                                'name' => $child->name,
                                'slug' => $child->slug,
                                'icon' => $child->icon,
                                'description' => $child->description,
                                'sort_order' => $child->sort_order,
                                'is_active' => $child->is_active,
                            ];
                        })->toArray()
                    ];
                });

            $json = json_encode($categories, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            $filename = 'business-categories-' . date('Y-m-d-His') . '.json';

            return response()->streamDownload(function() use ($json) {
                echo $json;
            }, $filename);

        } catch (\Exception $e) {
            session()->flash('error', 'Greška pri eksportovanju kategorija: ' . $e->getMessage());
        }
    }

    public function confirmWriteToSeeder()
    {
        $this->showWriteSeederModal = true;
    }

    public function writeToSeeder()
    {
        try {
            $categories = BusinessCategory::with('children')
                ->whereNull('parent_id')
                ->orderBy('sort_order')
                ->get();

            $categoriesArray = [];

            foreach ($categories as $category) {
                $categoryData = [
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'icon' => $category->icon ?: 'fas fa-briefcase',
                    'sort_order' => $category->sort_order,
                    'subcategories' => []
                ];

                foreach ($category->children->sortBy('sort_order') as $child) {
                    $categoryData['subcategories'][] = [
                        'name' => $child->name,
                        'slug' => $child->slug,
                        'icon' => $child->icon ?: 'fas fa-circle'
                    ];
                }

                $categoriesArray[] = $categoryData;
            }

            // Generate the seeder content
            $seederContent = $this->generateSeederContent($categoriesArray);

            // Write to seeder file
            $seederPath = database_path('seeders/BusinessCategorySeeder.php');
            file_put_contents($seederPath, $seederContent);

            session()->flash('success', 'Kategorije uspešno upisane u seeder fajl! (' . count($categories) . ' glavnih kategorija sa ' . BusinessCategory::whereNotNull('parent_id')->count() . ' podkategorija)');
            $this->showWriteSeederModal = false;

        } catch (\Exception $e) {
            session()->flash('error', 'Greška pri upisivanju u seeder: ' . $e->getMessage());
            $this->showWriteSeederModal = false;
        }
    }

    private function generateSeederContent($categories)
    {
        $categoriesString = var_export($categories, true);

        // Format the array output for better readability
        $categoriesString = preg_replace('/array \(/', '[', $categoriesString);
        $categoriesString = preg_replace('/\)/', ']', $categoriesString);
        $categoriesString = preg_replace('/=> \n\s+\[/', '=> [', $categoriesString);
        $categoriesString = str_replace('  ', '    ', $categoriesString);

        return <<<PHP
<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BusinessCategory;

class BusinessCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \$categories = $categoriesString;

        foreach (\$categories as \$categoryData) {
            \$subcategories = \$categoryData['subcategories'];
            unset(\$categoryData['subcategories']);

            \$category = BusinessCategory::create([
                'name' => \$categoryData['name'],
                'slug' => \$categoryData['slug'],
                'icon' => \$categoryData['icon'],
                'sort_order' => \$categoryData['sort_order'],
                'is_active' => true,
            ]);

            foreach (\$subcategories as \$index => \$subcategoryData) {
                BusinessCategory::create([
                    'parent_id' => \$category->id,
                    'name' => \$subcategoryData['name'],
                    'slug' => \$subcategoryData['slug'],
                    'icon' => \$subcategoryData['icon'],
                    'sort_order' => \$index + 1,
                    'is_active' => true,
                ]);
            }
        }

        // Only show info if running from command line
        if (\$this->command) {
            \$this->command->info('Business categories seeded successfully!');
        }
    }
}
PHP;
    }

    public function render()
    {
        $query = BusinessCategory::with(['parent', 'children'])
            ->withCount('businesses')
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
        $parentCategories = BusinessCategory::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('livewire.admin.business-category-management', compact('categories', 'parentCategories'))
            ->layout('layouts.admin');
    }
}
