<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Prvo proverite da li tabela postoji
        if (!Schema::hasTable('favorites')) {
            // Ako ne postoji, kreirajte je
            Schema::create('favorites', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('listing_id');
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('listing_id')->references('id')->on('listings')->onDelete('cascade');
                $table->unique(['user_id', 'listing_id']);
            });
        } else {
            // Ako postoji, dodajte kolone koje nedostaju
            Schema::table('favorites', function (Blueprint $table) {
                if (!Schema::hasColumn('favorites', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->after('id');
                }
                
                if (!Schema::hasColumn('favorites', 'listing_id')) {
                    $table->unsignedBigInteger('listing_id')->after('user_id');
                }

                // Dodajte foreign keys ako ne postoje
                try {
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                } catch (\Exception $e) {
                    // Foreign key već postoji
                }

                try {
                    $table->foreign('listing_id')->references('id')->on('listings')->onDelete('cascade');
                } catch (\Exception $e) {
                    // Foreign key već postoji
                }

                // Dodajte unique constraint ako ne postoji
                try {
                    $table->unique(['user_id', 'listing_id']);
                } catch (\Exception $e) {
                    // Unique constraint već postoji
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};