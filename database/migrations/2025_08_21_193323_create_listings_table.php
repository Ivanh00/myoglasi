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
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('contact_phone')->nullable();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            // $table->foreignId('subcategory_id')->nullable()->constrained('categories')->onDelete('cascade'); // Dodato
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->decimal('price', 12, 2)->nullable();
            $table->unsignedBigInteger('condition_id')->nullable();
            $table->foreign('condition_id')->references('id')->on('listing_conditions');
            $table->enum('status', ['active', 'inactive', 'sold', 'expired'])->default('active');
            $table->string('location')->nullable();
            // $table->unsignedBigInteger('views')->default(0); // Dodato
            $table->boolean('is_featured')->default(false);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'expires_at']);
            $table->index('category_id');
            // $table->index('subcategory_id'); // Dodato
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
