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
    0 => [
        'name' => 'Građevinarstvo',
        'slug' => 'gradjevinarstvo',
        'icon' => 'fas fa-hard-hat',
        'sort_order' => 1,
        'subcategories' => [
            0 => [
                'name' => 'Projektovanje',
                'slug' => 'projektovanje',
                'icon' => 'fas fa-drafting-compass',
            ],
            1 => [
                'name' => 'Zidanje i fasada',
                'slug' => 'zidanje-fasada',
                'icon' => 'fas fa-building',
            ],
            2 => [
                'name' => 'Moleraj i krečenje',
                'slug' => 'moleraj-krecenje',
                'icon' => 'fas fa-paint-roller',
            ],
            3 => [
                'name' => 'Keramičarski radovi',
                'slug' => 'keramicarski-radovi',
                'icon' => 'fas fa-th',
            ],
            4 => [
                'name' => 'Voda i grejanje',
                'slug' => 'voda-grejanje',
                'icon' => 'fas fa-tint',
            ],
            5 => [
                'name' => 'Struja',
                'slug' => 'struja',
                'icon' => 'fas fa-bolt',
            ],
            6 => [
                'name' => 'Bravarija',
                'slug' => 'bravarija',
                'icon' => 'fas fa-door-open',
            ],
            7 => [
                'name' => 'Limarski radovi',
                'slug' => 'limarski-radovi',
                'icon' => 'fas fa-tools',
            ],
            8 => [
                'name' => 'Stolarski radovi',
                'slug' => 'stolarski-radovi',
                'icon' => 'fas fa-hammer',
            ],
            9 => [
                'name' => 'Transport materijala',
                'slug' => 'transport-materijala',
                'icon' => 'fas fa-truck',
            ],
            10 => [
                'name' => 'Ostalo',
                'slug' => 'gradjevinarstvo-ostalo',
                'icon' => 'fas fa-ellipsis-h',
            ],
        ],
    ],
    1 => [
        'name' => 'Kućne usluge',
        'slug' => 'kucne-usluge',
        'icon' => 'fas fa-home',
        'sort_order' => 2,
        'subcategories' => [
            0 => [
                'name' => 'Čišćenje i održavanje',
                'slug' => 'ciscenje-odrzavanje',
                'icon' => 'fas fa-broom',
            ],
            1 => [
                'name' => 'Čuvanje dece',
                'slug' => 'cuvanje-dece',
                'icon' => 'fas fa-baby',
            ],
            2 => [
                'name' => 'Čuvanje ljubimaca',
                'slug' => 'cuvanje-ljubimaca',
                'icon' => 'fas fa-paw',
            ],
            3 => [
                'name' => 'Selidbe i transport',
                'slug' => 'selidbe-transport',
                'icon' => 'fas fa-dolly',
            ],
            4 => [
                'name' => 'Ostalo',
                'slug' => 'kucne-usluge-ostalo',
                'icon' => 'fas fa-ellipsis-h',
            ],
        ],
    ],
    2 => [
        'name' => 'IT i digitalne usluge',
        'slug' => 'it-digitalne-usluge',
        'icon' => 'fas fa-laptop-code',
        'sort_order' => 3,
        'subcategories' => [
            0 => [
                'name' => 'Izrada sajtova',
                'slug' => 'izrada-sajtova',
                'icon' => 'fas fa-globe',
            ],
            1 => [
                'name' => 'Grafički dizajn',
                'slug' => 'graficki-dizajn',
                'icon' => 'fas fa-palette',
            ],
            2 => [
                'name' => 'Digitalni marketing',
                'slug' => 'digitalni-marketing',
                'icon' => 'fas fa-chart-line',
            ],
            3 => [
                'name' => 'Programiranje',
                'slug' => 'programiranje',
                'icon' => 'fas fa-code',
            ],
            4 => [
                'name' => 'Online kursevi',
                'slug' => 'it-online-kursevi',
                'icon' => 'fas fa-graduation-cap',
            ],
            5 => [
                'name' => 'Ostalo',
                'slug' => 'it-digitalne-ostalo',
                'icon' => 'fas fa-ellipsis-h',
            ],
        ],
    ],
    3 => [
        'name' => 'Umetničke usluge',
        'slug' => 'umetnicke-usluge',
        'icon' => 'fas fa-paint-brush',
        'sort_order' => 4,
        'subcategories' => [
            0 => [
                'name' => 'Fotografija',
                'slug' => 'fotografija',
                'icon' => 'fas fa-camera',
            ],
            1 => [
                'name' => 'Video montaža',
                'slug' => 'video-montaza',
                'icon' => 'fas fa-film',
            ],
            2 => [
                'name' => 'Audio produkcija',
                'slug' => 'audio-produkcija',
                'icon' => 'fas fa-microphone',
            ],
            3 => [
                'name' => 'Likovna umetnost',
                'slug' => 'likovna-umetnost',
                'icon' => 'fas fa-palette',
            ],
            4 => [
                'name' => 'Dekoracije',
                'slug' => 'dekoracije',
                'icon' => 'fas fa-holly-berry',
            ],
            5 => [
                'name' => 'Ostalo',
                'slug' => 'umetnicke-ostalo',
                'icon' => 'fas fa-ellipsis-h',
            ],
        ],
    ],
    4 => [
        'name' => 'Auto i transport',
        'slug' => 'auto-transport',
        'icon' => 'fas fa-car',
        'sort_order' => 5,
        'subcategories' => [
            0 => [
                'name' => 'Automehaničari',
                'slug' => 'automehanicari',
                'icon' => 'fas fa-wrench',
            ],
            1 => [
                'name' => 'Vulkanizeri',
                'slug' => 'vulkanizeri',
                'icon' => 'fas fa-ring',
            ],
            2 => [
                'name' => 'Limari i lakiranje',
                'slug' => 'limari-lakiranje',
                'icon' => 'fas fa-spray-can',
            ],
            3 => [
                'name' => 'Rent-a-car',
                'slug' => 'rent-a-car',
                'icon' => 'fas fa-key',
            ],
            4 => [
                'name' => 'Prevoz putnika',
                'slug' => 'prevoz-putnika',
                'icon' => 'fas fa-taxi',
            ],
            5 => [
                'name' => 'Ostalo',
                'slug' => 'auto-transport-ostalo',
                'icon' => 'fas fa-ellipsis-h',
            ],
        ],
    ],
    5 => [
        'name' => 'Zdravlje i lepota',
        'slug' => 'zdravlje-lepota',
        'icon' => 'fas fa-spa',
        'sort_order' => 6,
        'subcategories' => [
            0 => [
                'name' => 'Frizeri',
                'slug' => 'frizeri',
                'icon' => 'fas fa-cut',
            ],
            1 => [
                'name' => 'Kozmetika',
                'slug' => 'kozmetika',
                'icon' => 'fas fa-magic',
            ],
            2 => [
                'name' => 'Fitness',
                'slug' => 'fitness',
                'icon' => 'fas fa-dumbbell',
            ],
            3 => [
                'name' => 'Masaža',
                'slug' => 'masaza',
                'icon' => 'fas fa-hand-sparkles',
            ],
            4 => [
                'name' => 'Ostalo',
                'slug' => 'zdravlje-lepota-ostalo',
                'icon' => 'fas fa-ellipsis-h',
            ],
        ],
    ],
    6 => [
        'name' => 'Edukacija i kursevi',
        'slug' => 'edukacija-kursevi',
        'icon' => 'fas fa-user-graduate',
        'sort_order' => 7,
        'subcategories' => [
            0 => [
                'name' => 'Strani jezici',
                'slug' => 'strani-jezici',
                'icon' => 'fas fa-language',
            ],
            1 => [
                'name' => 'Matematika i fizika',
                'slug' => 'matematika-fizika',
                'icon' => 'fas fa-calculator',
            ],
            2 => [
                'name' => 'Muzika i pevanje',
                'slug' => 'muzika-pevanje',
                'icon' => 'fas fa-music',
            ],
            3 => [
                'name' => 'Online kursevi',
                'slug' => 'edukacija-online-kursevi',
                'icon' => 'fas fa-laptop',
            ],
            4 => [
                'name' => 'Ostalo',
                'slug' => 'edukacija-ostalo',
                'icon' => 'fas fa-ellipsis-h',
            ],
        ],
    ],
    7 => [
        'name' => 'Poljoprivreda i baštovanstvo',
        'slug' => 'poljoprivreda-bastovanstvo',
        'icon' => 'fas fa-tractor',
        'sort_order' => 8,
        'subcategories' => [
            0 => [
                'name' => 'Orezivanje i održavanje voćnjaka',
                'slug' => 'orezivanje-vocnjaka',
                'icon' => 'fas fa-apple-alt',
            ],
            1 => [
                'name' => 'Košenje trave',
                'slug' => 'kosenje-trave',
                'icon' => 'fas fa-seedling',
            ],
            2 => [
                'name' => 'Mašinske usluge',
                'slug' => 'masinske-usluge',
                'icon' => 'fas fa-cogs',
            ],
            3 => [
                'name' => 'Ostalo',
                'slug' => 'poljoprivreda-ostalo',
                'icon' => 'fas fa-ellipsis-h',
            ],
        ],
    ],
    8 => [
        'name' => 'Događaji i organizacija',
        'slug' => 'dogadjaji-organizacija',
        'icon' => 'fas fa-calendar-alt',
        'sort_order' => 9,
        'subcategories' => [
            0 => [
                'name' => 'Dekoracija i organizacija',
                'slug' => 'dekoracija-organizacija',
                'icon' => 'fas fa-gift',
            ],
            1 => [
                'name' => 'Iznajmljivanje opreme',
                'slug' => 'iznajmljivanje-opreme',
                'icon' => 'fas fa-chair',
            ],
            2 => [
                'name' => 'Ketering',
                'slug' => 'ketering',
                'icon' => 'fas fa-utensils',
            ],
            3 => [
                'name' => 'Animatori za decu',
                'slug' => 'animatori-za-decu',
                'icon' => 'fas fa-theater-masks',
            ],
            4 => [
                'name' => 'Ostalo',
                'slug' => 'dogadjaji-ostalo',
                'icon' => 'fas fa-ellipsis-h',
            ],
        ],
    ],
    9 => [
        'name' => 'Poslovne usluge',
        'slug' => 'poslovne-usluge',
        'icon' => 'fas fa-briefcase',
        'sort_order' => 10,
        'subcategories' => [
            0 => [
                'name' => 'Knjigovodstvo',
                'slug' => 'knjigovodstvo',
                'icon' => 'fas fa-calculator',
            ],
            1 => [
                'name' => 'Pravne usluge',
                'slug' => 'pravne-usluge',
                'icon' => 'fas fa-gavel',
            ],
            2 => [
                'name' => 'Prevodioci',
                'slug' => 'prevodioci',
                'icon' => 'fas fa-globe-europe',
            ],
            3 => [
                'name' => 'Konsalting',
                'slug' => 'konsalting',
                'icon' => 'fas fa-chart-bar',
            ],
            4 => [
                'name' => 'Ostalo',
                'slug' => 'poslovne-ostalo',
                'icon' => 'fas fa-ellipsis-h',
            ],
        ],
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

        // Only show info if running from command line
        if ($this->command) {
            $this->command->info('Service categories seeded successfully!');
        }
    }
}