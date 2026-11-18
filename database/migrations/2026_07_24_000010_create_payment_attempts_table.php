<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
            $table->string('provider')->nullable();
            $table->string('method')->nullable();
            $table->string('status')->default('pending');
            $table->string('deeplink')->nullable();
            $table->text('payload')->nullable();
            $table->string('attempt_token')->nullable();
            $table->timestamps();

            // foreignId above already creates the foreign key constraint
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_attempts');
    }
};
