<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $connection = DB::connection()->getDriverName();

        if ($connection === 'sqlite') {
            if (! Schema::hasTable('transactions')) {
                Log::warning(
                    'Skipping sqlite transactions migration: transactions table does not exist'
                );

                return;
            }

            DB::statement('PRAGMA foreign_keys = OFF');

            DB::statement(<<<'SQL'
                CREATE TABLE transactions_new (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    user_id INTEGER NOT NULL,
                    reservation_id INTEGER,
                    transaction_code VARCHAR(255) UNIQUE,
                    amount NUMERIC(10,2) NOT NULL,
                    payment_method VARCHAR(100) DEFAULT NULL,
                    status VARCHAR(50) DEFAULT 'pending',
                    description TEXT,
                    payment_date DATETIME,
                    created_at DATETIME,
                    updated_at DATETIME
                )
SQL
            );

            DB::statement(
                'INSERT INTO transactions_new (id, user_id, reservation_id, transaction_code, amount, payment_method, status, description, payment_date, created_at, updated_at) SELECT id, user_id, reservation_id, transaction_code, amount, payment_method, status, description, payment_date, created_at, updated_at FROM transactions'
            );

            DB::statement('DROP TABLE transactions');
            DB::statement('ALTER TABLE transactions_new RENAME TO transactions');
            DB::statement('PRAGMA foreign_keys = ON');

            return;
        }

        try {
            Schema::table('transactions', function (Blueprint $table) {
                $table->string('payment_method')->nullable()->change();
            });
        } catch (\Exception $e) {
            Log::warning(
                'Could not change transactions.payment_method column automatically: ' . $e->getMessage()
            );
        }
    }

    public function down(): void
    {
        // Cannot reliably revert enum/check change for SQLite; keep payment_method as string.
    }
};
