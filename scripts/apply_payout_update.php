<?php
/**
 * One-time migrator for the payout (auto + manual disbursement) update.
 *
 * Run with XAMPP's PHP so it can reach the MySQL socket:
 *     /Applications/XAMPP/xamppfiles/bin/php scripts/apply_payout_update.php
 *
 * Applies the payout_setting column migration against the active environment's
 * database. Safe to re-run: "already applied" errors are reported and skipped.
 */

if (php_sapi_name() !== 'cli') {
    exit("This script must be run from the command line.\n");
}

$root = dirname(__DIR__);
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
    '20260807120000__add_payout_disbursement_columns.txt',
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
