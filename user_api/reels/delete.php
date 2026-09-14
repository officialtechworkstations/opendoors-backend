<?php
/**
 * user_api/reels/delete.php
 *
 * POST /user_api/reels/delete.php
 *
 * Delete a reel and all its associated media files.
 * After the DB record is deleted, any in-flight process_reel.php worker will
 * detect the missing record (or version mismatch) and abort without writing.
 *
 * Auth: Bearer token (preferred) or legacy uid body param (transition).
 *
 * Request body (JSON or form):
 *   uid      string  (legacy)
 *   reel_id  int     Required
 */
require dirname(dirname(__DIR__)) . '/include/reconfig.php';
require dirname(dirname(__DIR__)) . '/include/auth.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    errorResponse('Method Not Allowed. Expected POST.', 405);
}

// --- Authentication ----------------------------------------------------------
$data       = json_decode(file_get_contents('php://input'), true) ?? [];
$legacy_uid = $data['uid'] ?? ($_POST['uid'] ?? null);
$auth_uid   = requireAuth($legacy_uid);

// --- Input validation --------------------------------------------------------
$reel_id = intval($data['reel_id'] ?? ($_POST['reel_id'] ?? 0));
if ($reel_id <= 0) {
    errorResponse('Missing or invalid reel_id.', 400, 'REEL_MISSING_ID');
}

// --- Fetch reel --------------------------------------------------------------
$reel = $rstate->query("SELECT * FROM tbl_reels WHERE id = " . $reel_id)->fetch_assoc();
if (! $reel) {
    errorResponse('Reel not found.', 404, 'REEL_NOT_FOUND');
}

// --- Ownership check ---------------------------------------------------------
$prop = $rstate->query(
    "SELECT id, add_user_id FROM tbl_property WHERE id = " . intval($reel['prop_id'])
)->fetch_assoc();

if (! $prop || (int)$prop['add_user_id'] !== $auth_uid) {
    errorResponse('You do not have permission to delete this reel.', 403, 'REEL_ACCESS_DENIED');
}

// --- Delete DB record first (worker checks existence before writing) ----------
if (! $rstate->query("DELETE FROM tbl_reels WHERE id = " . $reel_id)) {
    errorResponse('Database error while deleting reel.', 500);
}

// --- Delete media files (safe — @unlink, file_exists checked inside) ---------
deleteReelFiles($reel);

successResponse('Reel deleted successfully.');
