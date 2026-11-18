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
        Schema::table('transactions', function (Blueprint $table) {
            if (! Schema::hasColumn('transactions', 'payment_proof')) {
                $table->string('payment_proof')->nullable()->after('status');
            }
            if (! Schema::hasColumn('transactions', 'payment_proof_uploaded_at')) {
                $table->timestamp('payment_proof_uploaded_at')->nullable()->after('payment_proof');
            }
        });

        Schema::table('reservations', function (Blueprint $table) {
            if (! Schema::hasColumn('reservations', 'booking_code')) {
                $table->string('booking_code')->nullable()->after('status');
            }
            if (! Schema::hasColumn('reservations', 'customer_name')) {
                $table->string('customer_name')->nullable()->after('booking_code');
            }
            if (! Schema::hasColumn('reservations', 'customer_phone')) {
                $table->string('customer_phone')->nullable()->after('customer_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'payment_proof_uploaded_at')) {
                $table->dropColumn('payment_proof_uploaded_at');
            }
            if (Schema::hasColumn('transactions', 'payment_proof')) {
                $table->dropColumn('payment_proof');
            }
        });

        Schema::table('reservations', function (Blueprint $table) {
            if (Schema::hasColumn('reservations', 'customer_phone')) {
                $table->dropColumn('customer_phone');
            }
            if (Schema::hasColumn('reservations', 'customer_name')) {
                $table->dropColumn('customer_name');
            }
            if (Schema::hasColumn('reservations', 'booking_code')) {
                $table->dropColumn('booking_code');
            }
        });
    }
};
