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
        Schema::table('reservations', function (Blueprint $table) {
            if (! Schema::hasColumn('reservations', 'cancellation_reason')) {
                $table->text('cancellation_reason')->nullable()->after('notes');
            }
            if (! Schema::hasColumn('reservations', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable()->after('cancellation_reason');
            }
        });

        Schema::table('transactions', function (Blueprint $table) {
            if (! Schema::hasColumn('transactions', 'is_deposit')) {
                $table->boolean('is_deposit')->default(false)->after('payment_method');
            }
            if (! Schema::hasColumn('transactions', 'deposit_amount')) {
                $table->decimal('deposit_amount', 12, 2)->nullable()->after('is_deposit');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            if (Schema::hasColumn('reservations', 'cancellation_reason')) {
                $table->dropColumn('cancellation_reason');
            }
            if (Schema::hasColumn('reservations', 'cancelled_at')) {
                $table->dropColumn('cancelled_at');
            }
        });

        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'deposit_amount')) {
                $table->dropColumn('deposit_amount');
            }
            if (Schema::hasColumn('transactions', 'is_deposit')) {
                $table->dropColumn('is_deposit');
            }
        });
    }
};
