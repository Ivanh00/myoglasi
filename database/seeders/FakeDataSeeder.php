<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Listing;
use App\Models\ListingImage;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FakeDataSeeder extends Seeder
{
    public function run(): void
    {
        // Kreiraj test korisnika
        $testUser = User::create([
            'name' => 'Test Korisnik',
            'email' => 'test@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'balance' => 5000.00,
            'phone' => '+381 60 123 4567',
            'city' => 'Beograd'
        ]);

        // Kreiraj još 20 korisnika
        $users = User::factory(20)->create([
            'balance' => fake()->numberBetween(500, 10000)
        ]);

        // Dodaj test korisnika u kolekciju
        $allUsers = $users->push($testUser);

        $this->command->info('Kreiranje 100 oglasa sa slikama...');
        
        // Progress bar
        $bar = $this->command->getOutput()->createProgressBar(100);
        $bar->start();

        // Kreiraj 100 oglasa
        for ($i = 0; $i < 100; $i++) {
            $user = $allUsers->random();
            
            $listing = Listing::factory()->create([
                'user_id' => $user->id,
            ]);

            // Dodaj 1-5 slika po oglasu
            $imageCount = fake()->numberBetween(1, 5);
            for ($j = 1; $j <= $imageCount; $j++) {
                ListingImage::factory()->create([
                    'listing_id' => $listing->id,
                    'order' => $j,
                    'image_path' => $this->getRandomImageForCategory($listing->category->parent->name ?? $listing->category->name),
                ]);
            }

            // Kreiraj transakciju za objavljeni oglas
            Transaction::create([
                'user_id' => $user->id,
                'amount' => -10.00,
                'type' => 'fee',
                'description' => 'Naplaćena taxa za oglas: ' . $listing->title
            ]);

            // Smanji balans korisniku
            $user->decrement('balance', 10);

            $bar->advance();
        }

        $bar->finish();
        $this->command->newLine();

        // Kreiraj još neke transakcije (dopune računa)
        foreach ($allUsers->take(15) as $user) {
            Transaction::create([
                'user_id' => $user->id,
                'amount' => fake()->numberBetween(1000, 5000),
                'type' => 'deposit',
                'description' => 'Dopuna računa - ' . fake()->randomElement(['PayPal', 'Kartica', 'Banka'])
            ]);
        }

        $this->command->info('✅ Kreiranje fake podataka završeno!');
        $this->command->info('📊 Kreirano:');
        $this->command->info('   - ' . ($allUsers->count()) . ' korisnika');
        $this->command->info('   - 100 oglasa');
        $this->command->info('   - ~300 slika');
        $this->command->info('   - ~115 transakcija');
        $this->command->newLine();
        $this->command->info('🔐 Test nalog:');
        $this->command->info('   Email: test@example.com');
        $this->command->info('   Password: password');
    }

    private function getRandomImageForCategory($categoryName): string
    {
        $categoryImages = [
            'Automobili' => [
                'https://images.unsplash.com/photo-1494976147631-f21f4d7b7ba2?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1493238792000-8113da705763?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1511919884226-fd3cad34687c?w=800&h=600&fit=crop',
            ],
            'Mobilni telefoni' => [
                'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1592286286632-bdf3a7b4efe4?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1574944985070-8f3ebc6b79d2?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1556656793-08538906a9f8?w=800&h=600&fit=crop',
            ],
            'Kompjuteri' => [
                'https://images.unsplash.com/photo-1547082299-de196ea013d6?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1587831990711-23ca6441447b?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1484788984921-03950022c9ef?w=800&h=600&fit=crop',
            ],
            'Nekretnine' => [
                'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1448630360428-65456885c650?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&h=600&fit=crop',
            ],
            'Alati' => [
                'https://images.unsplash.com/photo-1530124566582-a618bc2615dc?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1504148455328-c376907d081c?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1581092918056-0c4c3acd3789?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1519312082522-b6b9c4fb0fdf?w=800&h=600&fit=crop',
            ],
        ];

        $images = $categoryImages[$categoryName] ?? $categoryImages['Automobili'];
        return fake()->randomElement($images);
    }
}