<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone')) {
                if (Schema::hasColumn('users', 'no_hp')) {
                    DB::statement('ALTER TABLE `users` CHANGE COLUMN `no_hp` `phone` VARCHAR(20) NULL');
                } else {
                    $table->string('phone')->nullable()->after('password');
                }
            }

            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('role');
            }
        });

        if (Schema::hasColumn('users', 'role')) {
            DB::statement("ALTER TABLE `users` MODIFY COLUMN `role` VARCHAR(50) NOT NULL DEFAULT 'customer'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'address')) {
                $table->dropColumn('address');
            }

            if (Schema::hasColumn('users', 'phone') && !Schema::hasColumn('users', 'no_hp')) {
                DB::statement('ALTER TABLE `users` CHANGE COLUMN `phone` `no_hp` VARCHAR(20) NULL');
            }
        });

        if (Schema::hasColumn('users', 'role')) {
            DB::statement("ALTER TABLE `users` MODIFY COLUMN `role` VARCHAR(50) NOT NULL DEFAULT 'customer'");
        }
    }
};
