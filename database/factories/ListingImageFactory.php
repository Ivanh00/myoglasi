<?php

namespace Database\Factories;

use App\Models\Listing;
use Illuminate\Database\Eloquent\Factories\Factory;

class ListingImageFactory extends Factory
{
    protected $model = \App\Models\ListingImage::class;
    public function definition(): array
    {
        // Lista placeholder slika različitih kategorija
        $images = [
            // Automobili
            'https://via.placeholder.com/800x600/3B82F6/FFFFFF?text=BMW+320d',
            'https://via.placeholder.com/800x600/EF4444/FFFFFF?text=Audi+A4',
            'https://via.placeholder.com/800x600/10B981/FFFFFF?text=VW+Golf',
            'https://via.placeholder.com/800x600/8B5CF6/FFFFFF?text=Mercedes',
            'https://via.placeholder.com/800x600/F59E0B/FFFFFF?text=Automobil',
            
            // Telefoni
            'https://via.placeholder.com/800x600/1F2937/FFFFFF?text=iPhone+14',
            'https://via.placeholder.com/800x600/6366F1/FFFFFF?text=Samsung+S23',
            'https://via.placeholder.com/800x600/EC4899/FFFFFF?text=Xiaomi+13',
            'https://via.placeholder.com/800x600/14B8A6/FFFFFF?text=Telefon',
            
            // Kompjuteri
            'https://via.placeholder.com/800x600/DC2626/FFFFFF?text=Gaming+PC',
            'https://via.placeholder.com/800x600/059669/FFFFFF?text=MacBook+Pro',
            'https://via.placeholder.com/800x600/7C3AED/FFFFFF?text=Dell+XPS',
            'https://via.placeholder.com/800x600/DB2777/FFFFFF?text=Laptop',
            
            // Nekretnine
            'https://via.placeholder.com/800x600/0EA5E9/FFFFFF?text=Stan+65m2',
            'https://via.placeholder.com/800x600/84CC16/FFFFFF?text=Kuca+120m2',
            'https://via.placeholder.com/800x600/F97316/FFFFFF?text=Nekretnina',
            
            // Alati
            'https://via.placeholder.com/800x600/64748B/FFFFFF?text=Bosch+Alati',
            'https://via.placeholder.com/800x600/92400E/FFFFFF?text=Makita+Set',
            'https://via.placeholder.com/800x600/1E40AF/FFFFFF?text=Alat',
        ];

        return [
            'listing_id' => Listing::factory(),
            'image_path' => $this->faker->randomElement($images),
            'order' => $this->faker->numberBetween(1, 10),
        ];
    }
}