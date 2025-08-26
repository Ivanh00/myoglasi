<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Proverite da li admin već postoji
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'password' => Hash::make('admin123'), // Promenite ovo u jači password
                'is_admin' => true,
                'email_verified_at' => now(),
                'city' => 'Beograd',
                'phone' => '+381631234567',
            ]);
            
            $this->command->info('Admin korisnik je uspešno kreiran!');
            $this->command->info('Email: admin@example.com');
            $this->command->info('Password: admin123');
        } else {
            $this->command->info('Admin korisnik već postoji.');
        }
    }
}