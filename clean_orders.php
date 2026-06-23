<?php
// DELETE THIS FILE AFTER RUNNING!
error_reporting(E_ALL);
ini_set('display_errors', 1);

$envFile = __DIR__ . '/.env';
$lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$env = [];
foreach ($lines as $line) {
    $line = trim($line);
    if ($line === '' || str_starts_with($line, '#')) continue;
    if (str_contains($line, '=')) {
        [$key, $value] = explode('=', $line, 2);
        $env[trim($key)] = trim($value);
    }
}

$host = $env['database.default.hostname'] ?? 'localhost';
$user = $env['database.default.username'] ?? 'root';
$pass = $env['database.default.password'] ?? '';
$db   = $env['database.default.database'] ?? '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "\n");
}

echo "Connected. Cleaning all orders...\n";

$conn->query("SET FOREIGN_KEY_CHECKS = 0");

$conn->query("UPDATE user_subscriptions SET order_id = NULL WHERE order_id IS NOT NULL");
echo "Cleared order_id from user_subscriptions.\n";

$tables = ['order_items', 'downloads', 'invoices', 'orders'];
foreach ($tables as $t) {
    $conn->query("DELETE FROM $t");
    echo "Cleared $t.\n";
}

$conn->query("SET FOREIGN_KEY_CHECKS = 1");
$conn->close();
echo "All orders cleaned successfully!\n";
