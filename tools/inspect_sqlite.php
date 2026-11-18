<?php
$db = __DIR__ . '/../database/database.sqlite';
if (!file_exists($db)) {
    echo "MISSING_DB\n";
    exit(1);
}
try {
    $pdo = new PDO('sqlite:' . $db);
    $tables = ['users','transactions','migrations','payment_attempts','reservations'];
    foreach ($tables as $t) {
        echo "=== TABLE: $t ===\n";
        $stmt = $pdo->query("PRAGMA table_info('$t')");
        if ($stmt === false) { echo "(not found)\n"; continue; }
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $r) {
            echo sprintf("%s | %s | %s | %s\n", $r['cid'], $r['name'], $r['type'], $r['dflt_value']);
        }
        echo "\n";
        // show create statement
        $stmt2 = $pdo->query("SELECT sql FROM sqlite_master WHERE type='table' AND name='".$t."'");
        if ($stmt2) {
            $row = $stmt2->fetch(PDO::FETCH_ASSOC);
            if ($row && isset($row['sql'])) {
                echo $row['sql'] . "\n\n";
            }
        }
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(2);
}
