<?php
require dirname(dirname(__DIR__)) . '/include/reconfig.php';
header('Content-type: text/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    errorResponse("Method Not Allowed. Expected POST.", 405);
}

$data = json_decode(file_get_contents('php://input'), true);
$uid = $data['uid'] ?? ($_POST['uid'] ?? '');
$reel_id = $data['reel_id'] ?? ($_POST['reel_id'] ?? '');

if (empty($uid) || empty($reel_id)) {
    errorResponse("Missing required parameters.", 401);
}

$reel = $rstate->query("SELECT * FROM tbl_reels WHERE id = " . intval($reel_id))->fetch_assoc();
if (!$reel) {
    errorResponse("Reel not found.", 401);
}

// Verify property ownership
$prop = $rstate->query("SELECT id, add_user_id FROM tbl_property WHERE id = " . intval($reel['prop_id']))->fetch_assoc();
if (!$prop || $prop['add_user_id'] != $uid) {
    errorResponse("Property not found or access denied.", 401);
}

// Delete old files
$old_video = dirname(dirname(__DIR__)) . '/' . $reel['video_path'];
$old_thumb = dirname(dirname(__DIR__)) . '/' . $reel['thumbnail_path'];
if (file_exists($old_video) && !empty($reel['video_path'])) @unlink($old_video);
if (file_exists($old_thumb) && !empty($reel['thumbnail_path'])) @unlink($old_thumb);

$sql = "DELETE FROM tbl_reels WHERE id = " . intval($reel_id);
if ($rstate->query($sql)) {
    successResponse("Reel deleted successfully.");
} else {
    errorResponse("Database error.", 500);
}
