<?php
/**
 * user_api/reels/saved.php
 *
 * GET /user_api/reels/saved.php
 *
 * Paginated feed of reels the authenticated user has saved.
 */
require dirname(dirname(__DIR__)) . '/include/reconfig.php';
require dirname(dirname(__DIR__)) . '/include/auth.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    errorResponse('Method Not Allowed. Expected GET.', 405);
}

// --- Auth --------------------------------------------------------------------
$legacy_uid = $_GET['uid'] ?? null;
$auth_uid = requireAuth($legacy_uid);

// --- Pagination params -------------------------------------------------------
$raw_last_id = $_GET['last_id'] ?? '0';
$raw_limit   = $_GET['limit']   ?? '10';

if (! is_numeric($raw_last_id) || ! is_numeric($raw_limit)) {
    errorResponse('Invalid pagination parameters. last_id and limit must be integers.', 400, 'INVALID_PAGINATION');
}

$last_id = max(0, (int)$raw_last_id);
$limit   = max(1, min(30, (int)$raw_limit));

// --- Query -------------------------------------------------------------------
$cursor_clause = $last_id > 0 ? "AND rs.id < $last_id" : '';

$query = "
    SELECT
        rs.id             AS engagement_id,
        r.id              AS id,
        r.prop_id,
        r.video_path,
        r.thumbnail_path,
        r.status,
        r.views,
        r.created_at,
        p.title           AS property_title,
        p.address         AS property_address,
        p.image           AS property_image,
        p.price           AS property_price,
        p.beds,
        p.bathroom,
        u.name            AS host_name,
        u.pro_pic         AS host_pic,
        (SELECT COUNT(*) FROM tbl_reel_likes l WHERE l.reel_id = r.id) as likes_count,
        (SELECT COUNT(*) FROM tbl_reel_saves s WHERE s.reel_id = r.id) as saves_count,
        (SELECT COUNT(*) FROM tbl_reel_comments c WHERE c.reel_id = r.id) as comments_count,
        (SELECT COUNT(*) FROM tbl_reel_likes l WHERE l.reel_id = r.id AND l.user_id = $auth_uid) as has_liked,
        1                 as has_saved
    FROM tbl_reel_saves rs
    JOIN tbl_reels r ON rs.reel_id = r.id
    JOIN tbl_property p ON r.prop_id = p.id
    JOIN tbl_user u ON p.add_user_id = u.id
    WHERE rs.user_id = $auth_uid AND r.status = 1
      $cursor_clause
    ORDER BY rs.id DESC
    LIMIT $limit
";

$sel   = $rstate->query($query);
$reels = [];
$min_id = PHP_INT_MAX;

if ($sel && $sel->num_rows > 0) {
    while ($row = $sel->fetch_assoc()) {
        $reel = reelToArray($row);
        $reel['views'] = (int)($row['views'] ?? 0);
        $reel['created_at'] = $row['created_at'];
        $reel['likes_count'] = (int)$row['likes_count'];
        $reel['saves_count'] = (int)$row['saves_count'];
        $reel['comments_count'] = (int)$row['comments_count'];
        $reel['has_liked'] = (int)$row['has_liked'] > 0;
        $reel['has_saved'] = true;
        
        $reel['host'] = [
            'name' => $row['host_name'] ?? '',
            'pic_url' => absoluteMediaUrl($row['host_pic'] ?? ''),
        ];
        
        $reel['property'] = [
            'title'     => $row['property_title'] ?? '',
            'address'   => $row['property_address'] ?? '',
            'image_url' => absoluteMediaUrl($row['property_image'] ?? ''),
            'price'     => $row['property_price'] ?? '',
            'beds'      => $row['beds'] ?? '',
            'bathroom'  => $row['bathroom'] ?? '',
        ];
        $reels[] = $reel;
        if ((int)$row['engagement_id'] < $min_id) {
            $min_id = (int)$row['engagement_id'];
        }
    }
}

// --- Determine if there are more pages ---------------------------------------
$has_more   = false;
$next_cursor = 0;

if (! empty($reels)) {
    $check = $rstate->query(
        "SELECT rs.id FROM tbl_reel_saves rs
         JOIN tbl_reels r ON rs.reel_id = r.id 
         WHERE rs.user_id = $auth_uid AND r.status = 1 AND rs.id < $min_id 
         LIMIT 1"
    );
    $has_more   = ($check && $check->num_rows > 0);
    $next_cursor = $has_more ? $min_id : 0;
}

// --- Response ----------------------------------------------------------------
successResponse('Saved reels fetched successfully.', [
    'reels'      => $reels,
    'pagination' => [
        'next_cursor' => $next_cursor,
        'has_more'    => $has_more,
        'limit'       => $limit,
    ],
]);
