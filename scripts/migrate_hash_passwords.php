<?php
/**
 * One-time Password Hash Migration Script
 * ----------------------------------------
 * Purpose : Backs up each user's plaintext password into `old_password`,
 *           then replaces `password` with a bcrypt hash.
 *
 * Tables  : tbl_user, admin
 *
 * Prerequisites :
 *   1. Run migration:  202603041430__add_old_password_column.txt
 *      to add the `old_password` column to both tables first.
 *   2. Execute this script ONCE via CLI or browser.
 *
 * Cleanup :
 *   After successful tests, run 202603041430__drop_old_password_column.txt
 *   (or manually: ALTER TABLE tbl_user DROP COLUMN old_password;)
 *
 * Usage (CLI):
 *   php scripts/migrate_hash_passwords.php
 */

require dirname(__DIR__) . '/include/reconfig.php';

// ---------------------------------------------------------------------------
// Helper: migrate a single table
// ---------------------------------------------------------------------------
function migrate_table(mysqli $db, string $table, string $id_col = 'id'): array
{
    $results = [
        'table'   => $table,
        'total'   => 0,
        'updated' => 0,
        'skipped' => 0,
        'errors'  => [],
    ];

    // Fetch all rows where old_password is not yet set (safe to re-run)
    $rows = $db->query(
        "SELECT `{$id_col}`, `password`
         FROM `{$table}`
         WHERE `old_password` IS NULL
           AND `password` != ''"
    );

    if (!$rows) {
        $results['errors'][] = "Query failed: " . $db->error;
        return $results;
    }

    while ($row = $rows->fetch_assoc()) {
        $results['total']++;
        $id        = (int) $row[$id_col];
        $plaintext = $row['password'];

        // Skip rows that look like they're already hashed (bcrypt starts with $2y$)
        if (strpos($plaintext, '$2y$') === 0) {
            $results['skipped']++;
            continue;
        }

        $hash           = password_hash($plaintext, PASSWORD_BCRYPT);
        $escaped_plain  = $db->real_escape_string($plaintext);
        $escaped_hash   = $db->real_escape_string($hash);

        $ok = $db->query(
            "UPDATE `{$table}`
             SET `old_password` = '{$escaped_plain}',
                 `password`     = '{$escaped_hash}'
             WHERE `{$id_col}` = {$id}
             LIMIT 1"
        );

        if ($ok) {
            $results['updated']++;
        } else {
            $results['errors'][] = "Failed to update {$id_col}={$id}: " . $db->error;
        }
    }

    return $results;
}

// ---------------------------------------------------------------------------
// Run migrations
// ---------------------------------------------------------------------------
$tables = [
    ['table' => 'tbl_user', 'id_col' => 'id'],
    ['table' => 'admin',    'id_col' => 'id'],
];

$all_results = [];
foreach ($tables as $cfg) {
    $all_results[] = migrate_table($rstate, $cfg['table'], $cfg['id_col']);
}

// ---------------------------------------------------------------------------
// Output results
// ---------------------------------------------------------------------------
header('Content-Type: text/plain; charset=utf-8');

echo "=======================================================\n";
echo "  Password Hash Migration — " . date('Y-m-d H:i:s') . "\n";
echo "=======================================================\n\n";

foreach ($all_results as $r) {
    echo "Table    : {$r['table']}\n";
    echo "Total    : {$r['total']}\n";
    echo "Updated  : {$r['updated']}\n";
    echo "Skipped  : {$r['skipped']} (already hashed)\n";

    if (!empty($r['errors'])) {
        echo "ERRORS   :\n";
        foreach ($r['errors'] as $err) {
            echo "  - {$err}\n";
        }
    } else {
        echo "Errors   : none\n";
    }

    echo "-------------------------------------------------------\n";
}

echo "\nDone.\n";
echo "When tests pass, drop the `old_password` columns:\n";
echo "  ALTER TABLE tbl_user DROP COLUMN old_password;\n";
echo "  ALTER TABLE admin    DROP COLUMN old_password;\n";
