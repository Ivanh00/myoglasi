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
                'icon' => 'M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z',
                'children' => [
                    'Modeli',
                    'Delovi i oprema',
                    'Gume i felne',
                    'Tuning i styling'
                ]
            ],
            [
                'name' => 'Mobilni telefoni',
                'icon' => 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z',
                'children' => [
                    'Telefoni',
                    'Oprema i dodaci',
                    'Servis i popravka'
                ]
            ],
            [
                'name' => 'Kompjuteri',
                'icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
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
                'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3',
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
                'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
                'children' => [
                    'Električni alati',
                    'Alati na baterije',
                    'Ručni alati',
                    'Merne sprave',
                    'Radioničko oprema'
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