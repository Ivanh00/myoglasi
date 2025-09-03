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
                    'Modeli',
                    'Delovi i oprema',
                    'Gume i felne',
                    'Tuning i styling'
                ]
            ],
            [
                'name' => 'Mobilni telefoni',
                'description' => 'Mobilni telefoni i oprema',
                'icon' => 'fas fa-mobile-alt',
                'children' => [
                    'Telefoni',
                    'Oprema i dodaci',
                    'Servis i popravka'
                ]
            ],
            [
                'name' => 'Kompjuteri',
                'description' => 'Računari, laptopovi i IT oprema',
                'icon' => 'fas fa-laptop',
                'children' => [
                    'Desktop računari',
                    'Laptopovi',
                    'Komponente',
                    'Gejmerska oprema',
                    'Periferije'
                ]
            ],
            [
                'name' => 'Nekretnine',
                'description' => 'Stanovi, kuće, poslovni prostori',
                'icon' => 'fas fa-home',
                'children' => [
                    'Stanovi - prodaja',
                    'Stanovi - izdavanje',
                    'Kuće - prodaja',
                    'Kuće - izdavanje',
                    'Placevi',
                    'Vikendice',
                    'Poslovni prostori'
                ]
            ],
            [
                'name' => 'Alati',
                'description' => 'Alati za rad i konstrukciju',
                'icon' => 'fas fa-tools',
                'children' => [
                    'Električni alati',
                    'Alati na baterije',
                    'Ručni alati',
                    'Merne sprave',
                    'Radioničko oprema'
                ]
            ],
            [
                'name' => 'Sport i rekreacija',
                'description' => 'Sportska oprema i rekreativne aktivnosti',
                'icon' => 'fas fa-futbol',
                'children' => [
                    'Fitness oprema',
                    'Fudbal',
                    'Košarka',
                    'Tenis',
                    'Bicikli',
                    'Kampovanje'
                ]
            ],
            [
                'name' => 'Moda',
                'description' => 'Odeća, obuća i modni dodaci',
                'icon' => 'fas fa-tshirt',
                'children' => [
                    'Ženska odeća',
                    'Muška odeća',
                    'Dečja odeća',
                    'Obuća',
                    'Torbe i tašne',
                    'Nakit i satovi'
                ]
            ]
        ];

        foreach ($categories as $categoryData) {
            $children = $categoryData['children'] ?? [];
            unset($categoryData['children']);
        
            // Kreiraj glavnu kategoriju
            $parent = Category::create($categoryData);
        
            // Dodaj podkategorije
            foreach ($children as $index => $childName) {
                Category::create([
                    'name' => $childName,
                    'slug' => Str::slug($childName) . '-' . $parent->id . '-' . ($index + 1),
                    'parent_id' => $parent->id,
                    'sort_order' => $index + 1,
                ]);
            }
        }
    }
}