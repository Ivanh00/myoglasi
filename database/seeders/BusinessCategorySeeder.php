<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BusinessCategory;

class BusinessCategorySeeder extends Seeder
{
    public function run(): void
    {
        // Ugostiteljstvo
        $ugostiteljstvo = BusinessCategory::create([
            'name' => 'Ugostiteljstvo',
            'slug' => 'ugostiteljstvo',
            'icon' => 'fas fa-utensils',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $subcategories = [
            'Restorani',
            'Kafići',
            'Brza hrana',
            'Picerije',
            'Kineski restorani',
            'Azijski restorani',
            'Riblji restorani',
            'Pivnice',
            'Wine bar',
            'Diskoteke',
            'Pekare',
            'Poslastičarnice',
        ];

        foreach ($subcategories as $index => $name) {
            BusinessCategory::create([
                'parent_id' => $ugostiteljstvo->id,
                'name' => $name,
                'slug' => \Str::slug($name),
                'is_active' => true,
                'sort_order' => $index + 1,
            ]);
        }

        // Turizam i smeštaj
        $turizam = BusinessCategory::create([
            'name' => 'Turizam i smeštaj',
            'slug' => 'turizam-i-smestaj',
            'icon' => 'fas fa-hotel',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $subcategories = [
            'Hoteli',
            'Moteli',
            'Apartmani',
            'Sobe za izdavanje',
            'Seoska domaćinstva',
        ];

        foreach ($subcategories as $index => $name) {
            BusinessCategory::create([
                'parent_id' => $turizam->id,
                'name' => $name,
                'slug' => \Str::slug($name),
                'is_active' => true,
                'sort_order' => $index + 1,
            ]);
        }

        // Zanatske radnje i servisi
        $zanati = BusinessCategory::create([
            'name' => 'Zanatske radnje i servisi',
            'slug' => 'zanatske-radnje-i-servisi',
            'icon' => 'fas fa-hammer',
            'is_active' => true,
            'sort_order' => 3,
        ]);

        $subcategories = [
            'Automehaničari',
            'Vulkanizeri',
            'Električarske radnje',
            'Keramika',
            'Voda i grejanje',
            'Frizeri',
            'Krojači',
            'Perionice',
        ];

        foreach ($subcategories as $index => $name) {
            BusinessCategory::create([
                'parent_id' => $zanati->id,
                'name' => $name,
                'slug' => \Str::slug($name),
                'is_active' => true,
                'sort_order' => $index + 1,
            ]);
        }

        // Trgovina
        $trgovina = BusinessCategory::create([
            'name' => 'Trgovina',
            'slug' => 'trgovina',
            'icon' => 'fas fa-shopping-cart',
            'is_active' => true,
            'sort_order' => 4,
        ]);

        $subcategories = [
            'Marketi i prodavnice',
            'Butici i obuća',
            'Tehnika i elektronika',
            'Knjižare i papirnice',
            'Apoteke',
            'Poljoprivredne apoteke',
            'Cvećare',
        ];

        foreach ($subcategories as $index => $name) {
            BusinessCategory::create([
                'parent_id' => $trgovina->id,
                'name' => $name,
                'slug' => \Str::slug($name),
                'is_active' => true,
                'sort_order' => $index + 1,
            ]);
        }

        // Automobili i transport
        $transport = BusinessCategory::create([
            'name' => 'Automobili i transport',
            'slug' => 'automobili-i-transport',
            'icon' => 'fas fa-car',
            'is_active' => true,
            'sort_order' => 5,
        ]);

        $subcategories = [
            'Auto servisi',
            'Prodaja automobila',
            'Rent-a-car',
            'Prevoz putnika',
            'Prevoz robe i materijala',
        ];

        foreach ($subcategories as $index => $name) {
            BusinessCategory::create([
                'parent_id' => $transport->id,
                'name' => $name,
                'slug' => \Str::slug($name),
                'is_active' => true,
                'sort_order' => $index + 1,
            ]);
        }

        // Zdravstvo i lepota
        $zdravstvo = BusinessCategory::create([
            'name' => 'Zdravstvo i lepota',
            'slug' => 'zdravstvo-i-lepota',
            'icon' => 'fas fa-heartbeat',
            'is_active' => true,
            'sort_order' => 6,
        ]);

        $subcategories = [
            'Ordinacije lekarska',
            'Ordinacija stomatološka',
            'Optika',
            'Fitnes centri',
            'Teretane',
            'Saloni lepote',
            'Frizerski saloni',
        ];

        foreach ($subcategories as $index => $name) {
            BusinessCategory::create([
                'parent_id' => $zdravstvo->id,
                'name' => $name,
                'slug' => \Str::slug($name),
                'is_active' => true,
                'sort_order' => $index + 1,
            ]);
        }

        // Građevinarstvo i nekretnine
        $gradjevinarstvo = BusinessCategory::create([
            'name' => 'Građevinarstvo i nekretnine',
            'slug' => 'gradjevinarstvo-i-nekretnine',
            'icon' => 'fas fa-building',
            'is_active' => true,
            'sort_order' => 7,
        ]);

        $subcategories = [
            'Građevinske firme',
            'Arhitektonski biro',
            'Projektanti',
            'Agencija za nekretnine',
            'Stolarija PVC/ALU',
        ];

        foreach ($subcategories as $index => $name) {
            BusinessCategory::create([
                'parent_id' => $gradjevinarstvo->id,
                'name' => $name,
                'slug' => \Str::slug($name),
                'is_active' => true,
                'sort_order' => $index + 1,
            ]);
        }

        // Poljoprivreda i prehrana
        $poljoprivreda = BusinessCategory::create([
            'name' => 'Poljoprivreda i prehrana',
            'slug' => 'poljoprivreda-i-prehrana',
            'icon' => 'fas fa-tractor',
            'is_active' => true,
            'sort_order' => 8,
        ]);

        $subcategories = [
            'Pčelari',
            'Proizvođači vina',
        ];

        foreach ($subcategories as $index => $name) {
            BusinessCategory::create([
                'parent_id' => $poljoprivreda->id,
                'name' => $name,
                'slug' => \Str::slug($name),
                'is_active' => true,
                'sort_order' => $index + 1,
            ]);
        }

        // IT i biznis usluge
        $it = BusinessCategory::create([
            'name' => 'IT i biznis usluge',
            'slug' => 'it-i-biznis-usluge',
            'icon' => 'fas fa-laptop-code',
            'is_active' => true,
            'sort_order' => 9,
        ]);

        $subcategories = [
            'IT firme',
            'Programeri',
            'Digitalni marketing',
            'Dizajn i štampa',
            'Advokati',
            'Knjigovodstvo',
        ];

        foreach ($subcategories as $index => $name) {
            BusinessCategory::create([
                'parent_id' => $it->id,
                'name' => $name,
                'slug' => \Str::slug($name),
                'is_active' => true,
                'sort_order' => $index + 1,
            ]);
        }
    }
}
