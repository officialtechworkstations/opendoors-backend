<?php
require dirname(dirname(__DIR__)) . '/include/reconfig.php';
require dirname(dirname(__DIR__)) . '/include/auth.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    errorResponse('Method Not Allowed. Expected POST.', 405);
}

$legacy_uid = $_POST['uid'] ?? null;
$auth_uid   = requireAuth($legacy_uid);

$reel_id = intval($_POST['reel_id'] ?? 0);
if ($reel_id <= 0) {
    errorResponse('Missing or invalid reel_id.', 400);
}

// check if reel exists
$check = $rstate->query("SELECT id FROM tbl_reels WHERE id = $reel_id AND status = 1");
if (!$check || $check->num_rows === 0) {
    errorResponse('Reel not found.', 404);
}

// check if like exists
$existing = $rstate->query("SELECT id FROM tbl_reel_likes WHERE reel_id = $reel_id AND user_id = $auth_uid");
if ($existing && $existing->num_rows > 0) {
    // unlike
    $rstate->query("DELETE FROM tbl_reel_likes WHERE reel_id = $reel_id AND user_id = $auth_uid");
    $liked = false;
} else {
    // like
    $rstate->query("INSERT INTO tbl_reel_likes (reel_id, user_id, created_at) VALUES ($reel_id, $auth_uid, NOW())");
    $liked = true;
}

// count likes
$countRow = $rstate->query("SELECT COUNT(*) as cnt FROM tbl_reel_likes WHERE reel_id = $reel_id")->fetch_assoc();
$likesCount = (int)$countRow['cnt'];

successResponse('Like status updated successfully.', [
    'liked' => $liked,
    'likes_count' => $likesCount
]);
