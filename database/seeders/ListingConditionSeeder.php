<?php

namespace Database\Seeders;

use App\Models\ListingCondition;
use Illuminate\Database\Seeder;

class ListingConditionSeeder extends Seeder
{
    public function run()
    {
        $conditions = [
            ['name' => 'Novo'],
            ['name' => 'Polovno - kao novo'],
            ['name' => 'Polovno - dobro'],
            ['name' => 'Polovno - zadovoljavajuće'],
            ['name' => 'Oštećeno'],
            ['name' => 'Neispravno']
        ];

        foreach ($conditions as $condition) {
            ListingCondition::create($condition);
        }
    }
}