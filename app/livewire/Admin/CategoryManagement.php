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
        $this->selectedCategory = Category::with(['allChildren', 'listings', 'subListings'])->find($categoryId);
        $this->forceDelete = false;
        
        // Provera da li kategorija ima podkategorije (sve, ne samo aktivne)
        if ($this->selectedCategory->allChildren->count() > 0) {
            $this->forceDelete = true;
        }

        // Provera da li kategorija ima oglase (kao glavna kategorija ili podkategorija)
        $totalListings = $this->selectedCategory->listings->count() + $this->selectedCategory->subListings->count();
        if ($totalListings > 0) {
            $this->dispatch('notify', type: 'error', message: 'Ne možete obrisati kategoriju koja ima ' . $totalListings . ' oglasa! Prvo premestite ili obrišite oglase.');
            return;
        }

        $this->showDeleteModal = true;
    }

    public function deleteCategory($force = false)
    {
        if ($this->selectedCategory) {
            if ($force || $this->forceDelete) {
                // Prvo obriši sve podkategorije
                $this->selectedCategory->allChildren()->delete();
                $this->dispatch('notify', type: 'success', message: 'Obrisane sve podkategorije za kategoriju: ' . $this->selectedCategory->name);
            }
            
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
            // Disable foreign key checks temporarily
            \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // Clear existing categories
            Category::truncate();

            // Re-enable foreign key checks
            \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            // Run the seeder
            $seeder = new \Database\Seeders\CategorySeeder();
            $seeder->run();

            session()->flash('success', 'Kategorije uspešno učitane iz seedera!');
            $this->dispatch('refreshCategories');
        } catch (\Exception $e) {
            session()->flash('error', 'Greška pri učitavanju kategorija: ' . $e->getMessage());
        }
    }

    public function writeToSeeder()
    {
        try {
            $categories = Category::with('children')
                ->whereNull('parent_id')
                ->orderBy('sort_order')
                ->get();

            $categoriesArray = [];

            foreach ($categories as $category) {
                $categoryData = [
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'icon' => $category->icon ?: 'fas fa-folder',
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
            $seederPath = database_path('seeders/CategorySeeder.php');
            file_put_contents($seederPath, $seederContent);

            session()->flash('success', 'Kategorije uspešno upisane u seeder fajl! (' . count($categories) . ' glavnih kategorija sa ' . Category::whereNotNull('parent_id')->count() . ' podkategorija)');

        } catch (\Exception $e) {
            session()->flash('error', 'Greška pri upisivanju u seeder: ' . $e->getMessage());
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
use App\Models\Category;

class CategorySeeder extends Seeder
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

            \$category = Category::create([
                'name' => \$categoryData['name'],
                'slug' => \$categoryData['slug'],
                'icon' => \$categoryData['icon'],
                'sort_order' => \$categoryData['sort_order'],
                'is_active' => true,
            ]);

            foreach (\$subcategories as \$index => \$subcategoryData) {
                Category::create([
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
            \$this->command->info('Categories seeded successfully!');
        }
    }
}
PHP;
    }

    public function exportCategories()
    {
        try {
            $categories = Category::with('children')
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
            $filename = 'categories-' . date('Y-m-d-His') . '.json';

            return response()->streamDownload(function() use ($json) {
                echo $json;
            }, $filename);

        } catch (\Exception $e) {
            session()->flash('error', 'Greška pri eksportovanju kategorija: ' . $e->getMessage());
        }
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