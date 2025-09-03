<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Automobili',
                'description' => 'Automobili, motori, delovi i oprema',
                'icon' => 'fas fa-car',
                'children' => [
                    ['name' => 'Modeli', 'icon' => 'fas fa-car'],
                    ['name' => 'Delovi i oprema', 'icon' => 'fas fa-cog'],
                    ['name' => 'Gume i felne', 'icon' => 'fas fa-circle'],
                    ['name' => 'Tuning i styling', 'icon' => 'fas fa-paint-brush']
                ]
            ],
            [
                'name' => 'Mobilni telefoni',
                'description' => 'Mobilni telefoni i oprema',
                'icon' => 'fas fa-mobile-alt',
                'children' => [
                    ['name' => 'Telefoni', 'icon' => 'fas fa-mobile'],
                    ['name' => 'Oprema i dodaci', 'icon' => 'fas fa-headphones'],
                    ['name' => 'Servis i popravka', 'icon' => 'fas fa-wrench']
                ]
            ],
            [
                'name' => 'Kompjuteri',
                'description' => 'Računari, laptopovi i IT oprema',
                'icon' => 'fas fa-laptop',
                'children' => [
                    ['name' => 'Desktop računari', 'icon' => 'fas fa-desktop'],
                    ['name' => 'Laptopovi', 'icon' => 'fas fa-laptop'],
                    ['name' => 'Komponente', 'icon' => 'fas fa-microchip'],
                    ['name' => 'Gejmerska oprema', 'icon' => 'fas fa-gamepad'],
                    ['name' => 'Periferije', 'icon' => 'fas fa-mouse']
                ]
            ],
            [
                'name' => 'Nekretnine',
                'description' => 'Stanovi, kuće, poslovni prostori',
                'icon' => 'fas fa-home',
                'children' => [
                    ['name' => 'Stanovi - prodaja', 'icon' => 'fas fa-building'],
                    ['name' => 'Stanovi - izdavanje', 'icon' => 'fas fa-key'],
                    ['name' => 'Kuće - prodaja', 'icon' => 'fas fa-home'],
                    ['name' => 'Kuće - izdavanje', 'icon' => 'fas fa-home'],
                    ['name' => 'Placevi', 'icon' => 'fas fa-map'],
                    ['name' => 'Vikendice', 'icon' => 'fas fa-mountain'],
                    ['name' => 'Poslovni prostori', 'icon' => 'fas fa-store']
                ]
            ],
            [
                'name' => 'Alati',
                'description' => 'Alati za rad i konstrukciju',
                'icon' => 'fas fa-tools',
                'children' => [
                    ['name' => 'Električni alati', 'icon' => 'fas fa-plug'],
                    ['name' => 'Alati na baterije', 'icon' => 'fas fa-battery-full'],
                    ['name' => 'Ručni alati', 'icon' => 'fas fa-hammer'],
                    ['name' => 'Merne sprave', 'icon' => 'fas fa-ruler'],
                    ['name' => 'Radioničko oprema', 'icon' => 'fas fa-industry']
                ]
            ],
            [
                'name' => 'Sport i rekreacija',
                'description' => 'Sportska oprema i rekreativne aktivnosti',
                'icon' => 'fas fa-futbol',
                'children' => [
                    ['name' => 'Fitness oprema', 'icon' => 'fas fa-dumbbell'],
                    ['name' => 'Fudbal', 'icon' => 'fas fa-futbol'],
                    ['name' => 'Košarka', 'icon' => 'fas fa-basketball-ball'],
                    ['name' => 'Tenis', 'icon' => 'fas fa-table-tennis'],
                    ['name' => 'Bicikli', 'icon' => 'fas fa-bicycle'],
                    ['name' => 'Kampovanje', 'icon' => 'fas fa-campground']
                ]
            ],
            [
                'name' => 'Moda',
                'description' => 'Odeća, obuća i modni dodaci',
                'icon' => 'fas fa-tshirt',
                'children' => [
                    ['name' => 'Ženska odeća', 'icon' => 'fas fa-female'],
                    ['name' => 'Muška odeća', 'icon' => 'fas fa-male'],
                    ['name' => 'Dečja odeća', 'icon' => 'fas fa-child'],
                    ['name' => 'Obuća', 'icon' => 'fas fa-shoe-prints'],
                    ['name' => 'Torbe i tašne', 'icon' => 'fas fa-shopping-bag'],
                    ['name' => 'Nakit i satovi', 'icon' => 'fas fa-gem']
                ]
            ]
        ];

        foreach ($categories as $categoryData) {
            $children = $categoryData['children'] ?? [];
            unset($categoryData['children']);
        
            // Kreiraj glavnu kategoriju samo ako ne postoji
            $parent = Category::firstOrCreate(
                ['slug' => Str::slug($categoryData['name'])],
                $categoryData
            );
        
            // Dodaj podkategorije sa ikonama
            foreach ($children as $index => $childData) {
                if (is_string($childData)) {
                    $childData = ['name' => $childData];
                }
                
                Category::firstOrCreate(
                    ['slug' => Str::slug($childData['name']) . '-' . $parent->id],
                    [
                        'name' => $childData['name'],
                        'slug' => Str::slug($childData['name']) . '-' . $parent->id,
                        'parent_id' => $parent->id,
                        'sort_order' => $index + 1,
                        'icon' => $childData['icon'] ?? 'fas fa-tag',
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}