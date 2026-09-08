<?php
require dirname(dirname(__DIR__)) . '/include/reconfig.php';
header('Content-type: text/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    errorResponse("Method Not Allowed. Expected GET.", 405);
}

$last_id = isset($_GET['last_id']) ? intval($_GET['last_id']) : 0;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;

// Cursor condition
$where = "WHERE r.status = 1"; // only fully processed reels
if ($last_id > 0) {
    $where .= " AND r.id < " . $last_id; // assuming descending order (newest first)
}

$query = "
    SELECT 
        r.id as reel_id,
        r.prop_id,
        r.thumbnail_path,
        p.title as property_title,
        p.address as property_address,
        p.image as property_image
    FROM tbl_reels r
    JOIN tbl_property p ON r.prop_id = p.id
    $where
    ORDER BY r.id DESC
    LIMIT $limit
";

$sel = $rstate->query($query);
$reels = array();
$new_last_id = 0;

if ($sel->num_rows > 0) {
    while($row = $sel->fetch_assoc()) {
        $reels[] = $row;
        $new_last_id = $row['reel_id']; // will be the last one in the loop
    }
}

if(empty($reels)) {
    successResponse("No reels found.", ["reels" => $reels, "next_cursor" => 0, "Result" => "false"]);
} else {
    // check if there's more data
    $check_more = $rstate->query("SELECT id FROM tbl_reels r WHERE r.status = 1 AND r.id < " . $new_last_id . " LIMIT 1")->num_rows;
    $next_cursor = ($check_more > 0) ? $new_last_id : 0;
    
    successResponse("Reels fetched successfully.", ["reels" => $reels, "next_cursor" => $next_cursor]);
}
