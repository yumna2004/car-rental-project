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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();

            // Basis informatie
            $table->string('title');                          // Bijv: "Toyota Yaris 2023"
            $table->text('description');                      // Uitgebreide beschrijving
            $table->string('category');                       // personenauto, aanhangwagen, etc.
            $table->decimal('price_per_day', 8, 2);          // Prijs per dag (bijv: 49.99)
            $table->string('region');                         // Amsterdam, Rotterdam, etc.
            $table->string('transmission');                   // automaat of schakel

            // Afbeelding
            $table->string('image')->nullable();              // Pad naar foto (optioneel)

            // Beschikbaarheid
            $table->date('availability_start')->nullable();   // Vanaf wanneer beschikbaar
            $table->date('availability_end')->nullable();     // Tot wanneer beschikbaar

            // Timestamps (created_at, updated_at)
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
