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

echo "Connecting to $host / $db ...\n";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "\n");
}

$conn->query("ALTER TABLE custom_requests CHANGE plan plan VARCHAR(20) NOT NULL DEFAULT 'free'");
echo "Alter ran.\n";

$conn->query("UPDATE custom_requests SET plan = '499' WHERE plan = 'paid'");
echo "Rows updated: " . $conn->affected_rows . ".\n";

$conn->close();
echo "Migration complete!\n";
