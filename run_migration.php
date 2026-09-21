<?php
if (php_sapi_name() !== 'cli') {
    http_response_code(403);
    die("Forbidden");
}

require 'include/functions.php';

$db_server = getConfig('DB_SERVER');
$db_user = getConfig('DB_USER');
$db_pass = getConfig('DB_PASS');
$db_name = getConfig('DB_NAME');

$rstate = new mysqli($db_server, $db_user, $db_pass, $db_name);
if ($rstate->connect_error) {
    die("Connection failed: " . $rstate->connect_error . "\n");
}
$rstate->set_charset("utf8mb4");

$migrationDir = 'database/migrations';
$files = glob($migrationDir . '/*.txt');

if (empty($files)) {
    echo "No migration files found.\n";
    exit;
}

sort($files);

// Move the base structure and indexes to the beginning of the list
$baseStructure = 'database/migrations/202603041421__full_table_structure.txt';
$baseIndexes = 'database/migrations/202603041422__add_indexes.txt';

foreach ([$baseIndexes, $baseStructure] as $baseFile) {
    if (($key = array_search($baseFile, $files)) !== false) {
        unset($files[$key]);
        array_unshift($files, $baseFile);
    }
}

foreach ($files as $file) {
    echo "Running migration: " . basename($file) . "...\n";
    $sql = file_get_contents($file);
    
    if (empty(trim($sql))) {
        echo "  -> Skipped (empty file)\n";
        continue;
    }

    try {
        if ($rstate->multi_query($sql)) {
            do {
                if ($result = $rstate->store_result()) {
                    $result->free();
                }
            } while ($rstate->more_results() && $rstate->next_result());
            echo "  -> Success\n";
        } else {
            echo "  -> Error: " . $rstate->error . "\n";
        }
    } catch (mysqli_sql_exception $e) {
        echo "  -> Error (Exception): " . $e->getMessage() . "\n";
        
        // Clear any pending results that might be left over from a failed multi_query
        while($rstate->more_results() && $rstate->next_result()) {
            if($res = $rstate->store_result()) {
                $res->free();
            }
        }
    }
}
