<?php
$dbFile = __DIR__ . '/../database/database.sqlite';
if (!file_exists($dbFile)) {
    echo "Database file not found: $dbFile\n";
    exit(1);
}
try {
    $db = new PDO('sqlite:' . $dbFile);
} catch (Exception $e) {
    echo "PDO error: " . $e->getMessage() . "\n";
    exit(1);
}
function dumpTable($db, $name) {
    $res = $db->query("PRAGMA table_info('$name')");
    $rows = $res ? $res->fetchAll(PDO::FETCH_ASSOC) : [];
    echo "=== $name ===\n";
    echo json_encode($rows, JSON_PRETTY_PRINT) . "\n\n";
}
dumpTable($db, 'transactions');
dumpTable($db, 'payment_attempts');
echo "Done\n";
