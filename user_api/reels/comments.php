<?php
require dirname(dirname(__DIR__)) . '/include/reconfig.php';
require dirname(dirname(__DIR__)) . '/include/auth.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // List comments
    $reel_id = intval($_GET['reel_id'] ?? 0);
    $last_id = max(0, intval($_GET['last_id'] ?? 0));
    $limit = max(1, min(50, intval($_GET['limit'] ?? 20)));

    if ($reel_id <= 0) {
        errorResponse('Missing or invalid reel_id.', 400);
    }

    $cursor_clause = $last_id > 0 ? "AND c.id < $last_id" : '';

    $query = "
        SELECT 
            c.id, c.reel_id, c.user_id, c.comment, c.created_at, c.updated_at,
            u.name as user_name, u.pro_pic as user_pic
        FROM tbl_reel_comments c
        JOIN tbl_user u ON c.user_id = u.id
        WHERE c.reel_id = $reel_id
        $cursor_clause
        ORDER BY c.id DESC
        LIMIT $limit
    ";

    $sel = $rstate->query($query);
    $comments = [];
    $min_id = PHP_INT_MAX;

    if ($sel && $sel->num_rows > 0) {
        while ($row = $sel->fetch_assoc()) {
            $row['user_pic'] = absoluteMediaUrl($row['user_pic'] ?? '');
            $comments[] = $row;
            if ((int)$row['id'] < $min_id) {
                $min_id = (int)$row['id'];
            }
        }
    }

    $has_more = false;
    $next_cursor = 0;

    if (!empty($comments)) {
        $check = $rstate->query("SELECT id FROM tbl_reel_comments c WHERE c.reel_id = $reel_id AND c.id < $min_id LIMIT 1");
        $has_more = ($check && $check->num_rows > 0);
        $next_cursor = $has_more ? $min_id : 0;
    }

    successResponse('Comments fetched successfully.', [
        'comments' => $comments,
        'pagination' => [
            'next_cursor' => $next_cursor,
            'has_more' => $has_more,
            'limit' => $limit
        ]
    ]);
} 
elseif ($method === 'POST') {
    // Create or Update comment
    $legacy_uid = $_POST['uid'] ?? null;
    $auth_uid = requireAuth($legacy_uid);

    $comment_id = intval($_POST['comment_id'] ?? 0);
    $reel_id = intval($_POST['reel_id'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');

    if ($comment === '') {
        errorResponse('Comment cannot be empty.', 400);
    }

    if ($comment_id > 0) {
        // Update
        $check = $rstate->query("SELECT id, user_id FROM tbl_reel_comments WHERE id = $comment_id");
        if (!$check || $check->num_rows === 0) {
            errorResponse('Comment not found.', 404);
        }
        $row = $check->fetch_assoc();
        if ((int)$row['user_id'] !== $auth_uid) {
            errorResponse('You can only edit your own comments.', 403);
        }

        $stmt = $rstate->prepare("UPDATE tbl_reel_comments SET comment = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("si", $comment, $comment_id);
        $stmt->execute();
        
        successResponse('Comment updated successfully.', [
            'comment_id' => $comment_id,
            'comment' => $comment
        ]);
    } else {
        // Create
        if ($reel_id <= 0) {
            errorResponse('Missing or invalid reel_id.', 400);
        }
        $check = $rstate->query("SELECT id FROM tbl_reels WHERE id = $reel_id AND status = 1");
        if (!$check || $check->num_rows === 0) {
            errorResponse('Reel not found.', 404);
        }

        $stmt = $rstate->prepare("INSERT INTO tbl_reel_comments (reel_id, user_id, comment, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
        $stmt->bind_param("iis", $reel_id, $auth_uid, $comment);
        $stmt->execute();
        $new_id = $stmt->insert_id;
        
        // Return full comment details to allow appending to list
        $uCheck = $rstate->query("SELECT name, pro_pic FROM tbl_user WHERE id = $auth_uid");
        $uRow = $uCheck->fetch_assoc();

        successResponse('Comment added successfully.', [
            'comment' => [
                'id' => $new_id,
                'reel_id' => $reel_id,
                'user_id' => $auth_uid,
                'comment' => $comment,
                'user_name' => $uRow['name'] ?? '',
                'user_pic' => absoluteMediaUrl($uRow['pro_pic'] ?? ''),
            ]
        ]);
    }
}
elseif ($method === 'DELETE') {
    // Delete comment
    parse_str(file_get_contents("php://input"), $_DELETE);
    
    // Check headers for token if not in DELETE body
    $legacy_uid = $_DELETE['uid'] ?? null;
    $auth_uid = requireAuth($legacy_uid);

    $comment_id = intval($_DELETE['comment_id'] ?? $_GET['comment_id'] ?? 0);
    if ($comment_id <= 0) {
        errorResponse('Missing comment_id.', 400);
    }

    $check = $rstate->query("
        SELECT c.id, c.user_id, c.reel_id, p.add_user_id as host_id
        FROM tbl_reel_comments c
        JOIN tbl_reels r ON c.reel_id = r.id
        JOIN tbl_property p ON r.prop_id = p.id
        WHERE c.id = $comment_id
    ");

    if (!$check || $check->num_rows === 0) {
        errorResponse('Comment not found.', 404);
    }
    
    $row = $check->fetch_assoc();
    
    // Allow delete if: user is comment author OR user is the property host (admin of the reel)
    if ((int)$row['user_id'] !== $auth_uid && (int)$row['host_id'] !== $auth_uid) {
        // We also check if user is in 'admin' table, just in case a global admin is using the API
        $adminCheck = $rstate->query("SELECT id FROM `admin` WHERE id = $auth_uid LIMIT 1");
        if (!$adminCheck || $adminCheck->num_rows === 0) {
            errorResponse('You do not have permission to delete this comment.', 403);
        }
    }

    $rstate->query("DELETE FROM tbl_reel_comments WHERE id = $comment_id");
    
    successResponse('Comment deleted successfully.');
}
else {
    http_response_code(405);
    errorResponse('Method Not Allowed.', 405);
}
