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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('reservation_id')->nullable()->constrained('reservations')->onDelete('set null');
            $table->string('transaction_code')->unique();
            $table->decimal('amount', 10, 2);
            // Use string for payment_method to allow extensible providers (QRIS, GOPAY, etc.)
            $table->string('payment_method')->nullable()->default('cash');
            // Status as string to keep compatibility across DB drivers
            $table->string('status')->default('pending');
            $table->text('description')->nullable();
            $table->timestamp('payment_date')->nullable();
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['user_id']);
            $table->index(['reservation_id']);
            $table->index(['payment_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
