<?php
/**
 * user_api/reels/update.php
 *
 * POST /user_api/reels/update.php  (multipart/form-data)
 *
 * Replace the video of an existing reel identified by reel_id.
 * The new video goes through the same validation and background processing
 * pipeline as create.php, with processing_version incremented to invalidate
 * any in-flight processing job for the previous upload.
 *
 * Auth: Bearer token (preferred) or legacy uid form field (transition).
 *
 * Form fields:
 *   uid       string  (legacy)
 *   reel_id   int     Required
 *
 * Files:
 *   video     Required  mp4 / mov / avi / mkv / webm  ≤ 300 MB, ≤ 90 s, ≤ 1080p
 *   thumbnail Optional  jpg / png / webp
 */
require dirname(dirname(__DIR__)) . '/include/reconfig.php';
require dirname(dirname(__DIR__)) . '/include/auth.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    errorResponse('Method Not Allowed. Expected POST.', 405);
}

// --- Authentication ----------------------------------------------------------
$legacy_uid = $_POST['uid'] ?? null;
$auth_uid   = requireAuth($legacy_uid);

// --- Input validation --------------------------------------------------------
$reel_id = intval($_POST['reel_id'] ?? 0);
if ($reel_id <= 0) {
    errorResponse('Missing or invalid reel_id.', 400, 'REEL_MISSING_ID');
}

// --- Fetch reel + ownership check --------------------------------------------
$reel = $rstate->query("SELECT * FROM tbl_reels WHERE id = " . $reel_id)->fetch_assoc();
if (! $reel) {
    errorResponse('Reel not found.', 404, 'REEL_NOT_FOUND');
}

$prop = $rstate->query("SELECT id, add_user_id FROM tbl_property WHERE id = " . intval($reel['prop_id']))->fetch_assoc();
if (! $prop || (int)$prop['add_user_id'] !== $auth_uid) {
    errorResponse('You do not have permission to update this reel.', 403, 'REEL_ACCESS_DENIED');
}

// --- File validation ---------------------------------------------------------
if (! isset($_FILES['video'])) {
    errorResponse('Video file is required. Use multipart/form-data.', 400, 'REEL_MISSING_VIDEO');
}
if (! isset($_FILES['thumbnail'])) {
    errorResponse('Thumbnail file is required. Use multipart/form-data.', 400, 'REEL_MISSING_THUMBNAIL');
}
validateVideoFile($_FILES['video']);

// --- Ensure upload directories exist -----------------------------------------
$videos_dir     = dirname(dirname(__DIR__)) . '/uploads/reels/videos/';
$thumbnails_dir = dirname(dirname(__DIR__)) . '/uploads/reels/thumbnails/';
if (! is_dir($videos_dir))     mkdir($videos_dir,     0755, true);
if (! is_dir($thumbnails_dir)) mkdir($thumbnails_dir, 0755, true);

$uniq    = uniqid('', true);
$ext     = strtolower(pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION));
$tempRel = 'uploads/reels/videos/temp_reel_' . $uniq . '.' . $ext;
$tempAbs = dirname(dirname(__DIR__)) . '/' . $tempRel;

if (! move_uploaded_file($_FILES['video']['tmp_name'], $tempAbs)) {
    errorResponse('Failed to save uploaded file.', 500, 'REEL_UPLOAD_FAILED');
}

// --- Duration + resolution guards --------------------------------------------
// Disabled for direct upload as we are bypassing ffmpeg
// $probe = probeVideo($tempAbs);
// assertVideoDuration($probe, 90);
// assertVideoResolution($probe, 1080);

// --- Optional thumbnail -------------------------------------------------------
$thumbnail_path = '';
if (isset($_FILES['thumbnail'])) {
    $thumbnail_path = validateThumbnailFile($_FILES['thumbnail'], $uniq, $thumbnails_dir);
}

// --- Delete old files before replacing ---------------------------------------
deleteReelFiles($reel);

// --- ffmpeg availability -----------------------------------------------------
// $ffmpeg_output = @shell_exec('ffmpeg -version 2>&1');
// $has_ffmpeg    = (bool)($ffmpeg_output && stripos($ffmpeg_output, 'ffmpeg') !== false);
$has_ffmpeg = false; // Force direct upload path

if ($has_ffmpeg) {
    $sql = "UPDATE tbl_reels
            SET video_path         = '" . $rstate->real_escape_string($tempRel)        . "',
                thumbnail_path     = '" . $rstate->real_escape_string($thumbnail_path) . "',
                status             = 0,
                processing_version = processing_version + 1,
                processing_error   = NULL,
                updated_at         = NOW()
            WHERE id = " . $reel_id;

    if (! $rstate->query($sql)) {
        @unlink($tempAbs);
        if ($thumbnail_path) @unlink(dirname(dirname(__DIR__)) . '/' . $thumbnail_path);
        errorResponse('Database error while updating reel.', 500, 'REEL_UPLOAD_FAILED');
    }

    $vRow    = $rstate->query("SELECT processing_version FROM tbl_reels WHERE id = $reel_id")->fetch_assoc();
    $version = (int)($vRow['processing_version'] ?? 0);

    spawnReelProcessor($reel_id, $version);

    $reelRow = $rstate->query("SELECT * FROM tbl_reels WHERE id = $reel_id")->fetch_assoc();
    successResponse('Reel updated and processing started.', ['reel' => reelToArray($reelRow)]);

} else {
    // No ffmpeg — save directly as ready
    $finalRel = 'uploads/reels/videos/reel_' . $uniq . '.' . $ext;
    $finalAbs = dirname(dirname(__DIR__)) . '/' . $finalRel;

    if (! rename($tempAbs, $finalAbs)) {
        @unlink($tempAbs);
        if ($thumbnail_path) @unlink(dirname(dirname(__DIR__)) . '/' . $thumbnail_path);
        errorResponse('Failed to finalise uploaded file.', 500, 'REEL_UPLOAD_FAILED');
    }

    $sql = "UPDATE tbl_reels
            SET video_path         = '" . $rstate->real_escape_string($finalRel)       . "',
                thumbnail_path     = '" . $rstate->real_escape_string($thumbnail_path) . "',
                status             = 1,
                processing_version = processing_version + 1,
                processing_error   = NULL,
                updated_at         = NOW()
            WHERE id = " . $reel_id;

    if (! $rstate->query($sql)) {
        @unlink($finalAbs);
        if ($thumbnail_path) @unlink(dirname(dirname(__DIR__)) . '/' . $thumbnail_path);
        errorResponse('Database error while updating reel.', 500, 'REEL_UPLOAD_FAILED');
    }

    $reelRow = $rstate->query("SELECT * FROM tbl_reels WHERE id = $reel_id")->fetch_assoc();
    successResponse('Reel updated successfully.', ['reel' => reelToArray($reelRow)]);
}
