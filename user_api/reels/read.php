<?php
require dirname(dirname(__DIR__)) . '/include/reconfig.php';
header('Content-type: text/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    errorResponse("Method Not Allowed. Expected GET.", 405);
}

$reel_id = isset($_GET['reel_id']) ? intval($_GET['reel_id']) : 0;
$prop_id = isset($_GET['prop_id']) ? intval($_GET['prop_id']) : 0;

if ($reel_id == 0 && $prop_id == 0) {
    errorResponse("Missing reel_id or prop_id parameter.", 401);
}

$where = $reel_id > 0 ? "r.id = $reel_id" : "r.prop_id = $prop_id";

$query = "
    SELECT 
        r.id as reel_id,
        r.prop_id,
        r.video_path,
        r.thumbnail_path,
        r.status as reel_status,
        p.title as property_title,
        p.address as property_address,
        p.image as property_image,
        p.price as property_price,
        p.beds,
        p.bathroom
    FROM tbl_reels r
    JOIN tbl_property p ON r.prop_id = p.id
    WHERE $where
";

$reel = $rstate->query($query)->fetch_assoc();

if(!$reel) {
    errorResponse("Reel not found.", 404);
} else {
    successResponse("Reel fetched successfully.", ["reel" => $reel]);
}
