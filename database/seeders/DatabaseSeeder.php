<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Listing;
use App\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call([
            AdminSeeder::class,
            CategorySeeder::class,
            ListingConditionSeeder::class,
            FakeDataSeeder::class,
        ]);

        // // Kreiraj kategorije
        // $categories = [
        //     ['name' => 'Automobili', 'slug' => 'automobili', 'icon' => '🚗'],
        //     ['name' => 'Nekretnine', 'slug' => 'nekretnine', 'icon' => '🏠'], 
        //     ['name' => 'Elektronika', 'slug' => 'elektronika', 'icon' => '📱'],
        //     ['name' => 'Odeća', 'slug' => 'odeca', 'icon' => '👕'],
        //     ['name' => 'Sport', 'slug' => 'sport', 'icon' => '⚽'],
        //     ['name' => 'Knjige', 'slug' => 'knjige', 'icon' => '📚'],
        // ];
        
        // foreach ($categories as $category) {
        //     Category::create($category);
        // }
        
        // Kreiraj test korisnike
        User::factory(10)->create()->each(function ($user) {
            // Neki korisnici imaju vidljiv telefon, neki ne
            $user->update([
                'phone' => '064/' . rand(100, 999) . '-' . rand(100, 999),
                'phone_visible' => rand(0, 1),
                'balance' => rand(0, 5000)
            ]);
            
            // Kreiraj oglase za korisnika
            Listing::factory(rand(1, 5))->create([
                'user_id' => $user->id,
                'category_id' => Category::inRandomOrder()->first()->id
            ]);
        });
    }
}
