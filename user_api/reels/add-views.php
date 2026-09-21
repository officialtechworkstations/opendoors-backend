<?php
require dirname(dirname(__DIR__)) . '/include/reconfig.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    errorResponse('Method Not Allowed. Expected POST.', 405);
}

$reel_id = intval($_POST['reel_id'] ?? 0);
if ($reel_id <= 0) {
    errorResponse('Missing or invalid reel_id.', 400);
}

// check if reel exists
$check = $rstate->query("SELECT id FROM tbl_reels WHERE id = $reel_id AND status = 1");
if (!$check || $check->num_rows === 0) {
    errorResponse('Reel not found.', 404);
}

// increment views
$rstate->query("UPDATE tbl_reels SET views = views + 1 WHERE id = $reel_id");

// get updated views
$countRow = $rstate->query("SELECT views FROM tbl_reels WHERE id = $reel_id")->fetch_assoc();
$viewsCount = (int)$countRow['views'];

successResponse('Views updated successfully.', [
    'views_count' => $viewsCount
]);
