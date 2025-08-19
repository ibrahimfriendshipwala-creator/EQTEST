<?php
// Database connection using PDO. Edit if you move DB somewhere else.
$DB_HOST = 'localhost';
$DB_NAME = 'db5g9mad0nggk9';
$DB_USER = 'up0ghncfmfakv';
$DB_PASS = 'vznwqmh2glra';

try {
    $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4", $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (Exception $e) {
    // Friendly error for development. In production hide details.
    echo "<h2>Database connection error</h2><p>" . htmlspecialchars($e->getMessage()) . "</p>";
    exit;
}

?>
