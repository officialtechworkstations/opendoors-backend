<?php
/**
 * One-time migrator for the social sign-in + host-phone-visibility feature.
 *
 * Run AFTER installing the new Composer dependency:
 *     composer install          # or: composer require firebase/php-jwt:^7.0
 *     php scripts/apply_social_signin.php
 *
 * Applies the two SQL migrations against the active environment's database
 * (read from .env via getConfig). Safe to re-run: "already applied" errors
 * are reported and skipped.
 */

if (php_sapi_name() !== 'cli') {
    exit("This script must be run from the command line.\n");
}

$root = dirname(__DIR__);

// getConfig() resolves .env via $_SERVER['DOCUMENT_ROOT']; set it for CLI.
$_SERVER['DOCUMENT_ROOT'] = $root;
require $root . '/include/functions.php';

echo "Environment: " . getEnvironment() . "\n";

$mysqli = new mysqli(
    getConfig('DB_SERVER'),
    getConfig('DB_USER'),
    getConfig('DB_PASS'),
    getConfig('DB_NAME')
);

if ($mysqli->connect_errno) {
    exit("DB connection failed: {$mysqli->connect_error}\n");
}
$mysqli->set_charset('utf8mb4');

$migrations = [
    '20260619100000__add_social_and_consent_columns_to_tbl_user.txt',
    '20260619100100__add_social_and_host_mobile_columns_to_tbl_setting.txt',
];

$ignorable = ['Duplicate column', 'Duplicate key name', 'already exists', 'Duplicate'];

foreach ($migrations as $file) {
    $path = $root . '/database/migrations/' . $file;
    echo "Applying {$file} ... ";

    $sql = @file_get_contents($path);
    if ($sql === false) {
        echo "SKIP (file not found)\n";
        continue;
    }

    if ($mysqli->query($sql)) {
        echo "OK\n";
        continue;
    }

    $skip = false;
    foreach ($ignorable as $needle) {
        if (stripos($mysqli->error, $needle) !== false) {
            $skip = true;
            break;
        }
    }

    echo ($skip ? "ALREADY APPLIED" : "FAILED") . " ({$mysqli->error})\n";
}

$mysqli->close();
echo "Done.\n";
