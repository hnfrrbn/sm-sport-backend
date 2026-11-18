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
        Schema::create('fields', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Futsal 1, Badminton 1, etc
            $table->string('type'); // Type of field (e.g., futsal, badminton)
            $table->integer('capacity')->default(4); // Player capacity
            $table->decimal('price_per_hour', 10, 2); // Price per hour
            $table->string('status')->default('available');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fields');
    }
};
