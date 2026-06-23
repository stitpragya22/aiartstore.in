<?php
header('Content-Type: text/plain');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Try to find and parse .env file for DB credentials
$searchPaths = [
    __DIR__ . '/../.env',
    __DIR__ . '/../env',
    __DIR__ . '/../../.env',
    __DIR__ . '/.env',
    __DIR__ . '/env',
];

$env = [];
foreach ($searchPaths as $path) {
    if (file_exists($path)) {
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) continue;
            if (str_contains($line, '=')) {
                [$key, $val] = explode('=', $line, 2);
                $env[trim($key)] = trim($val);
            }
        }
        break;
    }
}

if (empty($env)) {
    die("ERROR: Could not find .env file (tried: " . implode(', ', $searchPaths) . ")\n");
}

$host = $env['database.default.hostname'] ?? 'localhost';
$user = $env['database.default.username'] ?? '';
$pass = $env['database.default.password'] ?? '';
$dbname = $env['database.default.database'] ?? '';

if (!$dbname) {
    die("ERROR: Database name not found in .env\n");
}

$mysqli = new mysqli($host, $user, $pass, $dbname);
if ($mysqli->connect_error) {
    die("CONNECT FAILED: " . $mysqli->connect_error . "\n");
}
echo "Connected to: $dbname\n\n";

$sql = "
ALTER TABLE `prompts` ADD COLUMN IF NOT EXISTS `category_id` INT(11) UNSIGNED NULL AFTER `id`;
ALTER TABLE `prompts` ADD COLUMN IF NOT EXISTS `min_subscription_level` TINYINT(1) DEFAULT 0 AFTER `status`;
ALTER TABLE `prompts` ADD CONSTRAINT `prompts_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS `subscription_plans` (
  `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `description` TEXT NULL,
  `price` DECIMAL(10,2) DEFAULT 0.00,
  `validity_days` INT(11) DEFAULT 0,
  `level` TINYINT(1) DEFAULT 0,
  `status` ENUM('active','inactive') DEFAULT 'active',
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `subscription_plans` (`name`,`slug`,`description`,`price`,`validity_days`,`level`,`status`,`created_at`,`updated_at`) VALUES
('Free','free','Basic access to free prompts',0,0,0,'active',NOW(),NOW()),
('Pro','pro','Unlock professional-grade prompts. Valid for 30 days.',499,30,1,'active',NOW(),NOW()),
('Premium','premium','Full access to all prompts including premium tier. Valid for 30 days.',999,30,2,'active',NOW(),NOW());

CREATE TABLE IF NOT EXISTS `user_subscriptions` (
  `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `plan_id` INT(11) UNSIGNED NOT NULL,
  `order_id` INT(11) UNSIGNED NULL,
  `start_date` DATETIME NULL,
  `end_date` DATETIME NULL,
  `status` ENUM('active','expired','cancelled') DEFAULT 'active',
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`plan_id`) REFERENCES `subscription_plans`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

$statements = explode(';', $sql);
$count = 0;
foreach ($statements as $stmt) {
    $stmt = trim($stmt);
    if (empty($stmt)) continue;
    if ($mysqli->query($stmt)) {
        $count++;
    } else {
        echo "ERROR: " . $mysqli->error . "\n  SQL: " . substr($stmt, 0, 80) . "...\n\n";
    }
}

echo "\nExecuted $count SQL statements successfully.\n";
echo "Done!\n";

$mysqli->close();
