<?php
$envFile = __DIR__ . '/.env';
if (!file_exists($envFile)) die('.env file not found');

$lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$env = [];
foreach ($lines as $line) {
    $line = trim($line);
    if ($line === '' || str_starts_with($line, '#')) continue;
    if (str_contains($line, '=')) {
        [$key, $val] = explode('=', $line, 2);
        $key = trim($key);
        $val = trim($val);
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

if (empty($dbname) || empty($user)) die('DB credentials not found');

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) die('Connection failed: ' . $conn->connect_error);

echo "<pre>Connected to $dbname\n\n";

$success = 0;
$total = 0;

// Add slug column
$q = "ALTER TABLE prompts ADD COLUMN IF NOT EXISTS slug VARCHAR(255) DEFAULT NULL AFTER title";
if ($conn->query($q)) { echo "OK: $q\n"; $success++; }
else { echo "ERROR: $q\n -> " . $conn->error . "\n"; }
$total++;

// Backfill slugs for existing prompts
$result = $conn->query("SELECT id, title, slug FROM prompts WHERE slug IS NULL OR slug = ''");
if ($result && $result->num_rows > 0) {
    $updated = 0;
    while ($row = $result->fetch_assoc()) {
        $slug = strtolower(trim(preg_replace('/[^a-z0-9-]+/', '-', $row['title']), '-'));
        $stmt = $conn->prepare("UPDATE prompts SET slug = ? WHERE id = ?");
        $stmt->bind_param('si', $slug, $row['id']);
        if ($stmt->execute()) $updated++;
        $stmt->close();
    }
    echo "OK: Backfilled $updated prompts with slugs\n";
    $success++;
} else {
    echo "OK: No prompts need slug backfill\n";
    $success++;
}
$total++;

echo "\n$success / $total steps executed successfully.\n";
echo "Migration complete.</pre>";
$conn->close();
