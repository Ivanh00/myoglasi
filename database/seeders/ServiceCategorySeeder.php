<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ServiceCategory;

class ServiceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Građevinarstvo',
                'slug' => 'gradjevinarstvo',
                'icon' => 'fas fa-hard-hat',
                'sort_order' => 1,
                'subcategories' => [
                    ['name' => 'Projektovanje', 'slug' => 'projektovanje', 'icon' => 'fas fa-drafting-compass'],
                    ['name' => 'Zidanje i fasada', 'slug' => 'zidanje-fasada', 'icon' => 'fas fa-building'],
                    ['name' => 'Moleraj i krečenje', 'slug' => 'moleraj-krecenje', 'icon' => 'fas fa-paint-roller'],
                    ['name' => 'Keramičarski radovi', 'slug' => 'keramicarski-radovi', 'icon' => 'fas fa-th'],
                    ['name' => 'Voda i grejanje', 'slug' => 'voda-grejanje', 'icon' => 'fas fa-tint'],
                    ['name' => 'Struja', 'slug' => 'struja', 'icon' => 'fas fa-bolt'],
                    ['name' => 'Bravarija', 'slug' => 'bravarija', 'icon' => 'fas fa-door-open'],
                    ['name' => 'Limarski radovi', 'slug' => 'limarski-radovi', 'icon' => 'fas fa-tools'],
                    ['name' => 'Stolari', 'slug' => 'stolari', 'icon' => 'fas fa-hammer'],
                    ['name' => 'Transport materijala', 'slug' => 'transport-materijala', 'icon' => 'fas fa-truck'],
                    ['name' => 'Ostalo', 'slug' => 'gradjevinarstvo-ostalo', 'icon' => 'fas fa-ellipsis-h'],
                ]
            ],
            [
                'name' => 'Kućne usluge',
                'slug' => 'kucne-usluge',
                'icon' => 'fas fa-home',
                'sort_order' => 2,
                'subcategories' => [
                    ['name' => 'Čišćenje i održavanje', 'slug' => 'ciscenje-odrzavanje', 'icon' => 'fas fa-broom'],
                    ['name' => 'Čuvanje dece', 'slug' => 'cuvanje-dece', 'icon' => 'fas fa-baby'],
                    ['name' => 'Čuvanje ljubimaca', 'slug' => 'cuvanje-ljubimaca', 'icon' => 'fas fa-paw'],
                    ['name' => 'Selidbe i transport', 'slug' => 'selidbe-transport', 'icon' => 'fas fa-dolly'],
                    ['name' => 'Ostalo', 'slug' => 'kucne-usluge-ostalo', 'icon' => 'fas fa-ellipsis-h'],
                ]
            ],
            [
                'name' => 'IT i digitalne usluge',
                'slug' => 'it-digitalne-usluge',
                'icon' => 'fas fa-laptop-code',
                'sort_order' => 3,
                'subcategories' => [
                    ['name' => 'Izrada sajtova', 'slug' => 'izrada-sajtova', 'icon' => 'fas fa-globe'],
                    ['name' => 'Grafički dizajn', 'slug' => 'graficki-dizajn', 'icon' => 'fas fa-palette'],
                    ['name' => 'Digitalni marketing', 'slug' => 'digitalni-marketing', 'icon' => 'fas fa-chart-line'],
                    ['name' => 'Programiranje', 'slug' => 'programiranje', 'icon' => 'fas fa-code'],
                    ['name' => 'Online kursevi', 'slug' => 'it-online-kursevi', 'icon' => 'fas fa-graduation-cap'],
                    ['name' => 'Ostalo', 'slug' => 'it-digitalne-ostalo', 'icon' => 'fas fa-ellipsis-h'],
                ]
            ],
            [
                'name' => 'Umetničke usluge',
                'slug' => 'umetnicke-usluge',
                'icon' => 'fas fa-paint-brush',
                'sort_order' => 4,
                'subcategories' => [
                    ['name' => 'Fotografija', 'slug' => 'fotografija', 'icon' => 'fas fa-camera'],
                    ['name' => 'Video montaža', 'slug' => 'video-montaza', 'icon' => 'fas fa-film'],
                    ['name' => 'Audio produkcija', 'slug' => 'audio-produkcija', 'icon' => 'fas fa-microphone'],
                    ['name' => 'Likovna umetnost', 'slug' => 'likovna-umetnost', 'icon' => 'fas fa-palette'],
                    ['name' => 'Dekoracije', 'slug' => 'dekoracije', 'icon' => 'fas fa-holly-berry'],
                    ['name' => 'Ostalo', 'slug' => 'umetnicke-ostalo', 'icon' => 'fas fa-ellipsis-h'],
                ]
            ],
            [
                'name' => 'Auto i transport',
                'slug' => 'auto-transport',
                'icon' => 'fas fa-car',
                'sort_order' => 5,
                'subcategories' => [
                    ['name' => 'Automehaničari', 'slug' => 'automehanicari', 'icon' => 'fas fa-wrench'],
                    ['name' => 'Vulkanizeri', 'slug' => 'vulkanizeri', 'icon' => 'fas fa-ring'],
                    ['name' => 'Limari i lakiranje', 'slug' => 'limari-lakiranje', 'icon' => 'fas fa-spray-can'],
                    ['name' => 'Rent-a-car', 'slug' => 'rent-a-car', 'icon' => 'fas fa-key'],
                    ['name' => 'Prevoz putnika', 'slug' => 'prevoz-putnika', 'icon' => 'fas fa-taxi'],
                    ['name' => 'Ostalo', 'slug' => 'auto-transport-ostalo', 'icon' => 'fas fa-ellipsis-h'],
                ]
            ],
            [
                'name' => 'Zdravlje i lepota',
                'slug' => 'zdravlje-lepota',
                'icon' => 'fas fa-spa',
                'sort_order' => 6,
                'subcategories' => [
                    ['name' => 'Frizeri', 'slug' => 'frizeri', 'icon' => 'fas fa-cut'],
                    ['name' => 'Kozmetika', 'slug' => 'kozmetika', 'icon' => 'fas fa-magic'],
                    ['name' => 'Fitness', 'slug' => 'fitness', 'icon' => 'fas fa-dumbbell'],
                    ['name' => 'Masaža', 'slug' => 'masaza', 'icon' => 'fas fa-hand-sparkles'],
                    ['name' => 'Ostalo', 'slug' => 'zdravlje-lepota-ostalo', 'icon' => 'fas fa-ellipsis-h'],
                ]
            ],
            [
                'name' => 'Edukacija i kursevi',
                'slug' => 'edukacija-kursevi',
                'icon' => 'fas fa-user-graduate',
                'sort_order' => 7,
                'subcategories' => [
                    ['name' => 'Strani jezici', 'slug' => 'strani-jezici', 'icon' => 'fas fa-language'],
                    ['name' => 'Matematika i fizika', 'slug' => 'matematika-fizika', 'icon' => 'fas fa-calculator'],
                    ['name' => 'Muzika i pevanje', 'slug' => 'muzika-pevanje', 'icon' => 'fas fa-music'],
                    ['name' => 'Online kursevi', 'slug' => 'edukacija-online-kursevi', 'icon' => 'fas fa-laptop'],
                    ['name' => 'Ostalo', 'slug' => 'edukacija-ostalo', 'icon' => 'fas fa-ellipsis-h'],
                ]
            ],
            [
                'name' => 'Poljoprivreda i baštovanstvo',
                'slug' => 'poljoprivreda-bastovanstvo',
                'icon' => 'fas fa-tractor',
                'sort_order' => 8,
                'subcategories' => [
                    ['name' => 'Orezivanje i održavanje voćnjaka', 'slug' => 'orezivanje-vocnjaka', 'icon' => 'fas fa-apple-alt'],
                    ['name' => 'Košenje trave', 'slug' => 'kosenje-trave', 'icon' => 'fas fa-seedling'],
                    ['name' => 'Mašinske usluge', 'slug' => 'masinske-usluge', 'icon' => 'fas fa-cogs'],
                    ['name' => 'Ostalo', 'slug' => 'poljoprivreda-ostalo', 'icon' => 'fas fa-ellipsis-h'],
                ]
            ],
            [
                'name' => 'Događaji i organizacija',
                'slug' => 'dogadjaji-organizacija',
                'icon' => 'fas fa-calendar-alt',
                'sort_order' => 9,
                'subcategories' => [
                    ['name' => 'Dekoracija i organizacija', 'slug' => 'dekoracija-organizacija', 'icon' => 'fas fa-gift'],
                    ['name' => 'Iznajmljivanje opreme', 'slug' => 'iznajmljivanje-opreme', 'icon' => 'fas fa-chair'],
                    ['name' => 'Ketering', 'slug' => 'ketering', 'icon' => 'fas fa-utensils'],
                    ['name' => 'Animatori za decu', 'slug' => 'animatori-za-decu', 'icon' => 'fas fa-theater-masks'],
                    ['name' => 'Ostalo', 'slug' => 'dogadjaji-ostalo', 'icon' => 'fas fa-ellipsis-h'],
                ]
            ],
            [
                'name' => 'Poslovne usluge',
                'slug' => 'poslovne-usluge',
                'icon' => 'fas fa-briefcase',
                'sort_order' => 10,
                'subcategories' => [
                    ['name' => 'Knjigovodstvo', 'slug' => 'knjigovodstvo', 'icon' => 'fas fa-calculator'],
                    ['name' => 'Pravne usluge', 'slug' => 'pravne-usluge', 'icon' => 'fas fa-gavel'],
                    ['name' => 'Prevodioci', 'slug' => 'prevodioci', 'icon' => 'fas fa-globe-europe'],
                    ['name' => 'Konsalting', 'slug' => 'konsalting', 'icon' => 'fas fa-chart-bar'],
                    ['name' => 'Ostalo', 'slug' => 'poslovne-ostalo', 'icon' => 'fas fa-ellipsis-h'],
                ]
            ],
        ];

        foreach ($categories as $categoryData) {
            $subcategories = $categoryData['subcategories'];
            unset($categoryData['subcategories']);

            $category = ServiceCategory::create([
                'name' => $categoryData['name'],
                'slug' => $categoryData['slug'],
                'icon' => $categoryData['icon'],
                'sort_order' => $categoryData['sort_order'],
                'is_active' => true,
            ]);

            foreach ($subcategories as $index => $subcategoryData) {
                ServiceCategory::create([
                    'parent_id' => $category->id,
                    'name' => $subcategoryData['name'],
                    'slug' => $subcategoryData['slug'],
                    'icon' => $subcategoryData['icon'],
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]);
            }
        }

        $this->command->info('Service categories seeded successfully!');
    }
}