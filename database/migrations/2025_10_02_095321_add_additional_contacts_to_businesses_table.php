<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->string('contact_phone_2')->nullable()->after('contact_email');
            $table->string('contact_name_2')->nullable()->after('contact_phone_2');
            $table->string('contact_phone_3')->nullable()->after('contact_name_2');
            $table->string('contact_name_3')->nullable()->after('contact_phone_3');
        });
    }

    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn(['contact_phone_2', 'contact_name_2', 'contact_phone_3', 'contact_name_3']);
        });
    }
};
