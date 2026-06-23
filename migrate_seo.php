<?php
/**
 * Migration script to add SEO columns to prompts table.
 * Run: visit https://aiartstore.in/migrate_seo.php
 */

$envFile = __DIR__ . '/.env';
if (!file_exists($envFile)) {
    die('.env file not found');
}

$lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$env = [];
foreach ($lines as $line) {
    $line = trim($line);
    if ($line === '' || str_starts_with($line, '#')) continue;
    if (str_contains($line, '=')) {
        [$key, $val] = explode('=', $line, 2);
        $key = trim($key);
        $val = trim($val);
        // Remove surrounding quotes
        if ((str_starts_with($val, "'") && str_ends_with($val, "'")) ||
            (str_starts_with($val, '"') && str_ends_with($val, '"'))) {
            $val = substr($val, 1, -1);
        }
        $env[$key] = $val;
    }
}

$host = $env['database.default.hostname'] ?? 'localhost';
$dbname = $env['database.default.database'] ?? '';
$user = $env['database.default.username'] ?? '';
$pass = $env['database.default.password'] ?? '';

if (empty($dbname) || empty($user)) {
    die('Database credentials not found in .env');
}

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

echo "<pre>Connected to $dbname\n\n";

$sql = [
    "ALTER TABLE prompts ADD COLUMN IF NOT EXISTS seo_title VARCHAR(255) DEFAULT NULL AFTER min_subscription_level",
    "ALTER TABLE prompts ADD COLUMN IF NOT EXISTS seo_description TEXT DEFAULT NULL AFTER seo_title",
    "ALTER TABLE prompts ADD COLUMN IF NOT EXISTS seo_keywords VARCHAR(255) DEFAULT NULL AFTER seo_description",
    "ALTER TABLE prompts ADD COLUMN IF NOT EXISTS seo_thumbnail VARCHAR(255) DEFAULT NULL AFTER seo_keywords",
];

$success = 0;
foreach ($sql as $q) {
    if ($conn->query($q)) {
        echo "OK: $q\n";
        $success++;
    } else {
        echo "ERROR: $q\n -> " . $conn->error . "\n";
    }
}

echo "\n$success / " . count($sql) . " statements executed successfully.\n";
echo "Migration complete.</pre>";

$conn->close();
