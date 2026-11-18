<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tables = ['users', 'fields', 'reservations', 'transactions', 'payment_attempts', 'activity_logs', 'migrations'];
foreach ($tables as $table) {
    try {
        $count = Illuminate\Support\Facades\DB::table($table)->count();
        echo "$table: $count\n";
    } catch (Throwable $e) {
        echo "$table: ERR {$e->getMessage()}\n";
    }
}
