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
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();

            // Foreign key naar users tabel
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            // Foreign key naar vehicles tabel (Sally maakt deze)
            $table->foreignId('vehicle_id')
                ->constrained()
                ->onDelete('cascade');

            // Rental details
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('total_price', 10, 2);

            $table->timestamps();

            // Index voor snellere queries
            $table->index(['vehicle_id', 'start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
