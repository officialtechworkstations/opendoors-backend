<?php
/**
 * user_api/reels/read.php
 *
 * GET /user_api/reels/read.php?reel_id=15
 *   or
 * GET /user_api/reels/read.php?prop_id=42
 *
 * Fetch a single reel by reel_id or prop_id.
 *
 * Visibility rules:
 *   - Public (no uid / token)  : only status=1 (ready) reels are returned.
 *   - Owner (uid or Bearer)    : reels at any status (0, 1, 2) are returned,
 *                                including processing_error when status=2.
 *
 * Auth: optional — pass uid query param or Authorization: Bearer header
 *       for owner-level access.
 */
require dirname(dirname(__DIR__)) . '/include/reconfig.php';
require dirname(dirname(__DIR__)) . '/include/auth.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    errorResponse('Method Not Allowed. Expected GET.', 405);
}

// --- Optional auth (determine if the requester is the property owner) --------
$request_uid = 0;

// Try Bearer first
$authHeader = $_SERVER['HTTP_AUTHORIZATION']
    ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
    ?? '';
if (empty($authHeader) && function_exists('getallheaders')) {
    $h          = getallheaders();
    $authHeader = $h['Authorization'] ?? $h['authorization'] ?? '';
}
if (! empty($authHeader) && stripos($authHeader, 'Bearer ') === 0) {
    // Attempt to decode — fail silently (read endpoint is public by default)
    $rawToken = substr($authHeader, 7);
    $secret   = getConfig('JWT_SECRET');
    try {
        $decoded     = (array) \Firebase\JWT\JWT::decode($rawToken, new \Firebase\JWT\Key($secret, 'HS256'));
        $request_uid = (int)($decoded['sub'] ?? 0);
    } catch (\Throwable $e) {
        $request_uid = 0;
    }
} elseif (isset($_GET['uid']) && is_numeric($_GET['uid'])) {
    $request_uid = (int)$_GET['uid'];
}

// --- Input params ------------------------------------------------------------
$reel_id = isset($_GET['reel_id']) ? intval($_GET['reel_id']) : 0;
$prop_id = isset($_GET['prop_id']) ? intval($_GET['prop_id']) : 0;

if ($reel_id <= 0 && $prop_id <= 0) {
    errorResponse('Provide reel_id or prop_id as a query parameter.', 400, 'REEL_MISSING_PARAM');
}

$where = $reel_id > 0 ? "r.id = $reel_id" : "r.prop_id = $prop_id";

// --- Query -------------------------------------------------------------------
$row = $rstate->query("
    SELECT
        r.id              AS id,
        r.prop_id,
        r.video_path,
        r.thumbnail_path,
        r.status,
        r.processing_error,
        p.title           AS property_title,
        p.address         AS property_address,
        p.image           AS property_image,
        p.price           AS property_price,
        p.beds,
        p.bathroom,
        p.add_user_id
    FROM tbl_reels r
    JOIN tbl_property p ON r.prop_id = p.id
    WHERE $where
    LIMIT 1
")->fetch_assoc();

if (! $row) {
    errorResponse('Reel not found.', 404, 'REEL_NOT_FOUND');
}

$status    = (int)$row['status'];
$is_owner  = ($request_uid > 0 && $request_uid === (int)$row['add_user_id']);

// Public users only see ready reels
if (! $is_owner && $status !== 1) {
    errorResponse('Reel not found.', 404, 'REEL_NOT_FOUND');
}

// Build response — reelToArray handles processing_error inclusion for status=2
$reel = reelToArray($row);

// Remove internal add_user_id from output
unset($reel['add_user_id']);

successResponse('Reel fetched successfully.', ['reel' => $reel]);
