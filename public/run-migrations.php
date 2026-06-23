<?php
header('Content-Type: text/plain');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Read .env from project root
$envFile = dirname(__DIR__) . '/.env';
echo "Looking for .env at: $envFile\n";
if (!file_exists($envFile)) {
    die("ERROR: .env not found at $envFile\n");
}

$env = parse_ini_file($envFile);
if (!$env) {
    die("ERROR: Could not parse .env file\n");
}

$host = $env['database.default.hostname'] ?? 'localhost';
$user = $env['database.default.username'] ?? '';
$pass = $env['database.default.password'] ?? '';
$dbname = $env['database.default.database'] ?? '';

echo "Host: $host\n";
echo "DB: $dbname\n";
echo "User: " . substr($user, 0, 8) . "...\n\n";

$mysqli = new mysqli($host, $user, $pass, $dbname);
if ($mysqli->connect_error) {
    die("Connection FAILED: " . $mysqli->connect_error . "\n");
}
echo "Connected OK!\n\n";

echo "=== Running Migrations ===\n\n";

// 1. Add columns
echo "1. Adding category_id and min_subscription_level to prompts...\n";
$r = $mysqli->query("ALTER TABLE `prompts` ADD COLUMN IF NOT EXISTS `category_id` INT(11) UNSIGNED NULL AFTER `id`");
echo "   category_id: " . ($r ? "OK" : $mysqli->error) . "\n";
$r = $mysqli->query("ALTER TABLE `prompts` ADD COLUMN IF NOT EXISTS `min_subscription_level` TINYINT(1) DEFAULT 0 AFTER `status`");
echo "   min_subscription_level: " . ($r ? "OK" : $mysqli->error) . "\n";

// 2. subscription_plans
echo "\n2. Creating subscription_plans table...\n";
$chk = $mysqli->query("SHOW TABLES LIKE 'subscription_plans'");
if ($chk->num_rows == 0) {
    $r = $mysqli->query("CREATE TABLE `subscription_plans` (
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "   Table: " . ($r ? "OK" : $mysqli->error) . "\n";
    if ($r) {
        $now = date('Y-m-d H:i:s');
        $r = $mysqli->query("INSERT INTO `subscription_plans` (`name`,`slug`,`description`,`price`,`validity_days`,`level`,`status`,`created_at`,`updated_at`) VALUES
            ('Free','free','Basic access to free prompts',0,0,0,'active','$now','$now'),
            ('Pro','pro','Unlock professional-grade prompts. Valid for 30 days.',499,30,1,'active','$now','$now'),
            ('Premium','premium','Full access to all prompts including premium tier. Valid for 30 days.',999,30,2,'active','$now','$now')");
        echo "   Default plans: " . ($r ? "OK" : $mysqli->error) . "\n";
    }
} else {
    echo "   Already exists, skipped\n";
}

// 3. user_subscriptions
echo "\n3. Creating user_subscriptions table...\n";
$chk = $mysqli->query("SHOW TABLES LIKE 'user_subscriptions'");
if ($chk->num_rows == 0) {
    $r = $mysqli->query("CREATE TABLE `user_subscriptions` (
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "   Table: " . ($r ? "OK" : $mysqli->error) . "\n";
} else {
    echo "   Already exists, skipped\n";
}

$mysqli->close();
echo "\n=== All done! ===\n";
echo "IMPORTANT: Delete run-migrations.php after verification!\n";
