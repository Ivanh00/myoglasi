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
        $categories = [
    0 => [
        'name' => 'Automobili',
        'slug' => 'automobili',
        'icon' => 'fas fa-car',
        'sort_order' => 0,
        'subcategories' => [
            0 => [
                'name' => 'Modeli',
                'slug' => 'modeli-1',
                'icon' => 'fas fa-car',
            ],
            1 => [
                'name' => 'Delovi i oprema',
                'slug' => 'delovi-i-oprema-1',
                'icon' => 'fas fa-cog',
            ],
            2 => [
                'name' => 'Gume i felne',
                'slug' => 'gume-i-felne-1',
                'icon' => 'fas fa-circle',
            ],
            3 => [
                'name' => 'Tuning i styling',
                'slug' => 'tuning-i-styling-1',
                'icon' => 'fas fa-paint-brush',
            ],
        ],
    ],
    1 => [
        'name' => 'Mobilni telefoni',
        'slug' => 'mobilni-telefoni',
        'icon' => 'fas fa-mobile-alt',
        'sort_order' => 0,
        'subcategories' => [
            0 => [
                'name' => 'Telefoni',
                'slug' => 'telefoni-6',
                'icon' => 'fas fa-mobile',
            ],
            1 => [
                'name' => 'Oprema i dodaci',
                'slug' => 'oprema-i-dodaci-6',
                'icon' => 'fas fa-headphones',
            ],
            2 => [
                'name' => 'Servis i popravka',
                'slug' => 'servis-i-popravka-6',
                'icon' => 'fas fa-wrench',
            ],
        ],
    ],
    2 => [
        'name' => 'Kompjuteri',
        'slug' => 'kompjuteri',
        'icon' => 'fas fa-laptop',
        'sort_order' => 0,
        'subcategories' => [
            0 => [
                'name' => 'Desktop računari',
                'slug' => 'desktop-racunari-10',
                'icon' => 'fas fa-desktop',
            ],
            1 => [
                'name' => 'Laptopovi',
                'slug' => 'laptopovi-10',
                'icon' => 'fas fa-laptop',
            ],
            2 => [
                'name' => 'Komponente',
                'slug' => 'komponente-10',
                'icon' => 'fas fa-microchip',
            ],
            3 => [
                'name' => 'Gejmerska oprema',
                'slug' => 'gejmerska-oprema-10',
                'icon' => 'fas fa-gamepad',
            ],
            4 => [
                'name' => 'Periferije',
                'slug' => 'periferije-10',
                'icon' => 'fas fa-mouse',
            ],
        ],
    ],
    3 => [
        'name' => 'Nekretnine',
        'slug' => 'nekretnine',
        'icon' => 'fas fa-home',
        'sort_order' => 0,
        'subcategories' => [
            0 => [
                'name' => 'Stanovi - prodaja',
                'slug' => 'stanovi-prodaja-16',
                'icon' => 'fas fa-building',
            ],
            1 => [
                'name' => 'Stanovi - izdavanje',
                'slug' => 'stanovi-izdavanje-16',
                'icon' => 'fas fa-key',
            ],
            2 => [
                'name' => 'Kuće - prodaja',
                'slug' => 'kuce-prodaja-16',
                'icon' => 'fas fa-home',
            ],
            3 => [
                'name' => 'Kuće - izdavanje',
                'slug' => 'kuce-izdavanje-16',
                'icon' => 'fas fa-home',
            ],
            4 => [
                'name' => 'Placevi',
                'slug' => 'placevi-16',
                'icon' => 'fas fa-map',
            ],
            5 => [
                'name' => 'Vikendice',
                'slug' => 'vikendice-16',
                'icon' => 'fas fa-mountain',
            ],
            6 => [
                'name' => 'Poslovni prostori',
                'slug' => 'poslovni-prostori-16',
                'icon' => 'fas fa-store',
            ],
        ],
    ],
    4 => [
        'name' => 'Alati',
        'slug' => 'alati',
        'icon' => 'fas fa-tools',
        'sort_order' => 0,
        'subcategories' => [
            0 => [
                'name' => 'Električni alati',
                'slug' => 'elektricni-alati-24',
                'icon' => 'fas fa-plug',
            ],
            1 => [
                'name' => 'Alati na baterije',
                'slug' => 'alati-na-baterije-24',
                'icon' => 'fas fa-battery-full',
            ],
            2 => [
                'name' => 'Ručni alati',
                'slug' => 'rucni-alati-24',
                'icon' => 'fas fa-hammer',
            ],
            3 => [
                'name' => 'Merne sprave',
                'slug' => 'merne-sprave-24',
                'icon' => 'fas fa-ruler',
            ],
            4 => [
                'name' => 'Radioničko oprema',
                'slug' => 'radionicko-oprema-24',
                'icon' => 'fas fa-industry',
            ],
        ],
    ],
    5 => [
        'name' => 'Sport i rekreacija',
        'slug' => 'sport-i-rekreacija',
        'icon' => 'fas fa-futbol',
        'sort_order' => 0,
        'subcategories' => [
            0 => [
                'name' => 'Fitness oprema',
                'slug' => 'fitness-oprema-30',
                'icon' => 'fas fa-dumbbell',
            ],
            1 => [
                'name' => 'Fudbal',
                'slug' => 'fudbal-30',
                'icon' => 'fas fa-futbol',
            ],
            2 => [
                'name' => 'Košarka',
                'slug' => 'kosarka-30',
                'icon' => 'fas fa-basketball-ball',
            ],
            3 => [
                'name' => 'Tenis',
                'slug' => 'tenis-30',
                'icon' => 'fas fa-table-tennis',
            ],
            4 => [
                'name' => 'Bicikli',
                'slug' => 'bicikli-30',
                'icon' => 'fas fa-bicycle',
            ],
            5 => [
                'name' => 'Kampovanje',
                'slug' => 'kampovanje-30',
                'icon' => 'fas fa-campground',
            ],
        ],
    ],
    6 => [
        'name' => 'Moda',
        'slug' => 'moda',
        'icon' => 'fas fa-tshirt',
        'sort_order' => 0,
        'subcategories' => [
            0 => [
                'name' => 'Ženska odeća',
                'slug' => 'zenska-odeca-37',
                'icon' => 'fas fa-female',
            ],
            1 => [
                'name' => 'Muška odeća',
                'slug' => 'muska-odeca-37',
                'icon' => 'fas fa-male',
            ],
            2 => [
                'name' => 'Dečja odeća',
                'slug' => 'decja-odeca-37',
                'icon' => 'fas fa-child',
            ],
            3 => [
                'name' => 'Obuća',
                'slug' => 'obuca-37',
                'icon' => 'fas fa-shoe-prints',
            ],
            4 => [
                'name' => 'Torbe i tašne',
                'slug' => 'torbe-i-tasne-37',
                'icon' => 'fas fa-shopping-bag',
            ],
            5 => [
                'name' => 'Nakit i satovi',
                'slug' => 'nakit-i-satovi-37',
                'icon' => 'fas fa-gem',
            ],
        ],
    ],
];

        foreach ($categories as $categoryData) {
            $subcategories = $categoryData['subcategories'];
            unset($categoryData['subcategories']);

            $category = Category::create([
                'name' => $categoryData['name'],
                'slug' => $categoryData['slug'],
                'icon' => $categoryData['icon'],
                'sort_order' => $categoryData['sort_order'],
                'is_active' => true,
            ]);

            foreach ($subcategories as $index => $subcategoryData) {
                Category::create([
                    'parent_id' => $category->id,
                    'name' => $subcategoryData['name'],
                    'slug' => $subcategoryData['slug'],
                    'icon' => $subcategoryData['icon'],
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]);
            }
        }

        // Only show info if running from command line
        if ($this->command) {
            $this->command->info('Categories seeded successfully!');
        }
    }
}