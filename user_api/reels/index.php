<?php
/**
 * user_api/reels/index.php
 *
 * GET /user_api/reels/index.php
 *
 * Public paginated reel feed. Returns only status=1 (ready) reels.
 * Cursor-based pagination — newest reels first.
 *
 * Query params:
 *   last_id  int  Cursor from previous page (0 or omit for first page)
 *   limit    int  Items per page (1–30, default 10)
 *
 * Success response (200 — even when empty):
 *   {
 *     "ResponseCode": "200",
 *     "Result": "true",
 *     "ResponseMsg": "Reels fetched successfully.",
 *     "reels": [ { reel object with video_url, thumbnail_url, ... }, ... ],
 *     "pagination": { "next_cursor": 14, "has_more": true, "limit": 10 }
 *   }
 */
require dirname(dirname(__DIR__)) . '/include/reconfig.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    errorResponse('Method Not Allowed. Expected GET.', 405);
}

// --- Pagination params -------------------------------------------------------
$raw_last_id = $_GET['last_id'] ?? '0';
$raw_limit   = $_GET['limit']   ?? '10';

if (! is_numeric($raw_last_id) || ! is_numeric($raw_limit)) {
    errorResponse('Invalid pagination parameters. last_id and limit must be integers.', 400, 'INVALID_PAGINATION');
}

$last_id = max(0, (int)$raw_last_id);
$limit   = max(1, min(30, (int)$raw_limit));

// --- Query -------------------------------------------------------------------
$cursor_clause = $last_id > 0 ? "AND r.id < $last_id" : '';

$query = "
    SELECT
        r.id              AS id,
        r.prop_id,
        r.video_path,
        r.thumbnail_path,
        r.status,
        p.title           AS property_title,
        p.address         AS property_address,
        p.image           AS property_image,
        p.price           AS property_price,
        p.beds,
        p.bathroom
    FROM tbl_reels r
    JOIN tbl_property p ON r.prop_id = p.id
    WHERE r.status = 1
      $cursor_clause
    ORDER BY r.id DESC
    LIMIT $limit
";

$sel   = $rstate->query($query);
$reels = [];
$min_id = PHP_INT_MAX;

if ($sel && $sel->num_rows > 0) {
    while ($row = $sel->fetch_assoc()) {
        $reels[] = reelToArray($row);
        if ((int)$row['id'] < $min_id) {
            $min_id = (int)$row['id'];
        }
    }
}

// --- Determine if there are more pages ---------------------------------------
$has_more   = false;
$next_cursor = 0;

if (! empty($reels)) {
    $check = $rstate->query(
        "SELECT id FROM tbl_reels r WHERE r.status = 1 AND r.id < $min_id LIMIT 1"
    );
    $has_more   = ($check && $check->num_rows > 0);
    $next_cursor = $has_more ? $min_id : 0;
}

// --- Response ----------------------------------------------------------------
// An empty result is still a successful request (Req 17)
successResponse('Reels fetched successfully.', [
    'reels'      => $reels,
    'pagination' => [
        'next_cursor' => $next_cursor,
        'has_more'    => $has_more,
        'limit'       => $limit,
    ],
]);
