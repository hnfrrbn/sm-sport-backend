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
        if (Schema::hasTable('users')) {
            if (Schema::hasColumn('users', 'no_hp') && !Schema::hasColumn('users', 'phone')) {
                DB::statement('ALTER TABLE `users` CHANGE COLUMN `no_hp` `phone` VARCHAR(20) NULL');
            }

            if (!Schema::hasColumn('users', 'phone')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->string('phone')->nullable()->after('password');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users')) {
            if (Schema::hasColumn('users', 'phone') && !Schema::hasColumn('users', 'no_hp')) {
                DB::statement('ALTER TABLE `users` CHANGE COLUMN `phone` `no_hp` VARCHAR(20) NULL');
            }

            if (!Schema::hasColumn('users', 'no_hp')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->string('no_hp')->nullable()->after('password');
                });
            }
        }
    }
};
