<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get the ID of "Polovno - zadovoljavajuće" before deleting
        $satisfactoryCondition = \DB::table('listing_conditions')
            ->where('name', 'Polovno - zadovoljavajuće')
            ->first();

        if ($satisfactoryCondition) {
            // Get the "Polovno - dobro" condition ID
            $goodCondition = \DB::table('listing_conditions')
                ->where('name', 'Polovno - dobro')
                ->first();

            if ($goodCondition) {
                // Update any listings that have "Polovno - zadovoljavajuće" to "Polovno - dobro"
                \DB::table('listings')
                    ->where('condition_id', $satisfactoryCondition->id)
                    ->update(['condition_id' => $goodCondition->id]);
            }
        }

        // Delete the "Polovno - zadovoljavajuće" condition
        \DB::table('listing_conditions')
            ->where('name', 'Polovno - zadovoljavajuće')
            ->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate the "Polovno - zadovoljavajuće" condition
        \DB::table('listing_conditions')->insert([
            'name' => 'Polovno - zadovoljavajuće',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
};
