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
        // First, check for duplicate names and handle them
        $duplicates = \DB::table('users')
            ->select('name')
            ->groupBy('name')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('name');

        foreach ($duplicates as $duplicateName) {
            $users = \DB::table('users')
                ->where('name', $duplicateName)
                ->orderBy('id')
                ->get();

            foreach ($users as $index => $user) {
                if ($index > 0) { // Keep first user, modify others
                    \DB::table('users')
                        ->where('id', $user->id)
                        ->update(['name' => $duplicateName . '_' . $user->id]);
                }
            }
        }

        // Now add unique constraint
        Schema::table('users', function (Blueprint $table) {
            $table->unique('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });
    }
};
