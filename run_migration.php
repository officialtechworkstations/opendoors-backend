<?php
require 'include/reconfig.php';

$sql = file_get_contents('database/migrations/20260908120000__create_tbl_reels_table.txt');
if ($rstate->query($sql) === TRUE) {
    echo "Table tbl_reels created successfully";
} else {
    echo "Error creating table: " . $rstate->error;
}
