<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
use App\Models\ListingCondition;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ListingFactory extends Factory
{
    public function definition(): array
    {
        $titles = [
            // Automobili
            'BMW 320d 2020 godina', 'Audi A4 TDI automatik', 'VW Golf 7 GTI', 'Mercedes C200 CDI',
            'Opel Astra 1.6 benzin', 'Škoda Octavia Combi', 'Ford Focus ST', 'Renault Clio 1.2',
            'Peugeot 308 diesel', 'Toyota Corolla hibrid', 'Hyundai i30 N Line',
            
            // Telefoni
            'iPhone 14 Pro Max 256GB', 'Samsung Galaxy S23 Ultra', 'Xiaomi 13 Pro', 'Huawei P50 Pro',
            'OnePlus 11 5G', 'Google Pixel 7', 'iPhone 13 128GB', 'Samsung A54 5G',
            'Xiaomi Redmi Note 12', 'Nothing Phone 1',
            
            // Kompjuteri
            'Gaming PC RTX 4070', 'MacBook Pro M2 13"', 'Dell XPS 15', 'ASUS ROG Strix laptop',
            'HP Pavilion desktop', 'Lenovo ThinkPad X1', 'MSI Gaming laptop', 'iMac 24" M1',
            'Custom gaming build', 'Acer Predator desktop',
            
            // Nekretnine
            'Stan 65m² Novi Beograd', 'Kuća 120m² sa dvorištem', 'Dvosoban stan centar',
            'Trosoban stan Vračar', 'Vikendica na Kopaoniku', 'Plac 800m² Zemun',
            'Poslovni prostor 45m²', 'Stan u novogradnji', 'Kuća za renoviranje',
            
            // Alati
            'Bosch aku bušilica', 'Makita set alata', 'Dewalt cirkularka', 'Hilti bušilica SDS',
            'Stanley set ključeva', 'Black & Decker multitool', 'Metabo brusilica',
            'Parkside set alata', 'Worx aku alati', 'Einhell komplet'
        ];

        $descriptions = [
            'Prodajem zbog selidbe u inostranstvo. Vrlo dobro očuvano, redovno servisiran. Svi servisi u ovlašćenom servisu.',
            'Hitno prodajem! Kupljen prošle godine, korišćen minimalno. Ima garanciju još godinu dana.',
            'Odličan za početnike ili kao rezervni. Ispravno funkcionise, ima sve delove u kompletu.',
            'Kupljen nov, korišćen pažljivo. Prodajem jer prelazim na novi model. Cena po dogovoru.',
            'Izvozno vozilo, nikad havarisan. Kompletna dokumentacija. Registrovan do kraja godine.',
            'Prodaje se u paketu sa dodacima. Sve originalno, bez oštećenja. Možete testirati pre kupovine.',
            'Odličan odnos cene i kvaliteta. Malo korišćen, čuvan u originalnoj kutiji.',
            'Poslednji model sa odličnim karakteristikama. Brzina, pouzdanost, efikasnost.',
            'Idealno za profesionalnu upotrebu ili hobi. Proverena kvaliteta i dugotrajnost.',
            'Očuvan, funkcionalan, spreman za upotrebu. Mogućnost testiranja na licu mesta.',
            'Premium kvalitet po pristupačnoj ceni. Svi delovi originalni, dokumentacija dostupna.',
            'Kupio sam skuplji, ovaj mi više ne treba. Odličan za startovanje ili kao rezerva.',
            'Uvoz iz nemačke, servisna knjižica uredna. Sve popravke evidentirane, održavan redovno.',
            'Prodajem kompletnu kolekciju odjednom. Sve u odličnom stanju, pažljivo čuvano.',
            'Zbog manjka prostora moram da se rastanem. Odličan primer, sve radi besprekorno.'
        ];

        $locations = [
            'Beograd - Novi Beograd', 'Beograd - Vračar', 'Beograd - Zemun', 'Beograd - Voždovac',
            'Novi Sad', 'Niš', 'Kragujevac', 'Subotica', 'Pančevo', 'Čačak',
            'Beograd - Zvezdara', 'Beograd - Palilula', 'Smederevo', 'Leskovac', 'Užice'
        ];

        $title = $this->faker->randomElement($titles);
        
        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . Str::random(6),
            'description' => $this->faker->randomElement($descriptions) . ' ' . 
                           $this->faker->paragraph() . ' Kontakt putem poruka ili poziva.',
            'price' => $this->faker->numberBetween(500, 50000),
            'location' => $this->faker->randomElement($locations),
            'contact_phone' => $this->faker->phoneNumber(),
            'user_id' => User::factory(),
            'category_id' => function() {
                // Uzimamo samo podkategorije (one koje imaju parent_id)
                return Category::whereNotNull('parent_id')->inRandomOrder()->first()->id;
            },
            'condition_id' => ListingCondition::inRandomOrder()->first()->id ?? 1,
            'status' => $this->faker->randomElement(['active', 'active', 'active', 'sold', 'inactive']), // Više aktivnih
            'expires_at' => now()->addDays($this->faker->numberBetween(10, 60)),
            'created_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ];
    }
}