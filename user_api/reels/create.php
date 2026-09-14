<?php
/**
 * user_api/reels/create.php
 *
 * POST /user_api/reels/create.php  (multipart/form-data)
 *
 * Upload a reel video for a property. If a reel already exists for the
 * property it is replaced (upsert). Processing is handled in the background
 * by process_reel.php via nohup.
 *
 * Auth: Bearer token (preferred) or legacy uid form field (transition).
 *
 * Form fields:
 *   uid       string  (legacy — ignored when Bearer token is present)
 *   prop_id   int     Required
 *
 * Files:
 *   video     Required  mp4 / mov / avi / mkv / webm  ≤ 300 MB, ≤ 90 s, ≤ 1080p
 *   thumbnail Optional  jpg / png / webp
 *
 * Success response (reel_status 0 = queued for processing):
 *   {
 *     "ResponseCode": "200",
 *     "Result": "true",
 *     "ResponseMsg": "Reel uploaded and processing started.",
 *     "reel": { "reel_id": 15, "prop_id": 42, "reel_status": 0, ... }
 *   }
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
$prop_id = intval($_POST['prop_id'] ?? 0);
if ($prop_id <= 0) {
    errorResponse('Missing or invalid prop_id.', 400, 'REEL_MISSING_PROP_ID');
}

// --- Property ownership check ------------------------------------------------
$prop = $rstate->query("SELECT id, add_user_id FROM tbl_property WHERE id = " . $prop_id)->fetch_assoc();
if (! $prop) {
    errorResponse('Property not found.', 404, 'REEL_PROPERTY_NOT_FOUND');
}
if ((int)$prop['add_user_id'] !== $auth_uid) {
    errorResponse('You do not have permission to add a reel to this property.', 403, 'REEL_ACCESS_DENIED');
}

// --- File validation (delegates to utils.php) --------------------------------
if (! isset($_FILES['video'])) {
    errorResponse('Video file is required. Use multipart/form-data.', 400, 'REEL_MISSING_VIDEO');
}
validateVideoFile($_FILES['video']);

// --- Save temp file so ffprobe can inspect it --------------------------------
$videos_dir     = dirname(dirname(__DIR__)) . '/uploads/reels/videos/';
$thumbnails_dir = dirname(dirname(__DIR__)) . '/uploads/reels/thumbnails/';
if (! is_dir($videos_dir))     mkdir($videos_dir,     0755, true);
if (! is_dir($thumbnails_dir)) mkdir($thumbnails_dir, 0755, true);

$uniq     = uniqid('', true);
$ext      = strtolower(pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION));
$tempRel  = 'uploads/reels/videos/temp_reel_' . $uniq . '.' . $ext;
$tempAbs  = dirname(dirname(__DIR__)) . '/' . $tempRel;

if (! move_uploaded_file($_FILES['video']['tmp_name'], $tempAbs)) {
    errorResponse('Failed to save uploaded file.', 500, 'REEL_UPLOAD_FAILED');
}

// --- Duration + resolution guards (ffprobe, graceful degradation) ------------
$probe = probeVideo($tempAbs);
assertVideoDuration($probe, 90);
assertVideoResolution($probe, 1080);

// --- Optional thumbnail -------------------------------------------------------
$thumbnail_path = '';
if (isset($_FILES['thumbnail'])) {
    $thumbnail_path = validateThumbnailFile($_FILES['thumbnail'], $uniq, $thumbnails_dir);
}

// --- Fetch ffmpeg availability ------------------------------------------------
$ffmpeg_output = @shell_exec('ffmpeg -version 2>&1');
$has_ffmpeg    = (bool)($ffmpeg_output && stripos($ffmpeg_output, 'ffmpeg') !== false);

// --- Check for existing reel (upsert) ----------------------------------------
$existing = $rstate->query("SELECT * FROM tbl_reels WHERE prop_id = " . $prop_id)->fetch_assoc();

// --- DB write ----------------------------------------------------------------
if ($has_ffmpeg) {
    // Store temp file path; process_reel.php will transcode and update the row
    if ($existing) {
        // Delete old processed files before replacing
        deleteReelFiles($existing);
        $sql = "UPDATE tbl_reels
                SET video_path         = '" . $rstate->real_escape_string($tempRel)        . "',
                    thumbnail_path     = '" . $rstate->real_escape_string($thumbnail_path) . "',
                    status             = 0,
                    processing_version = processing_version + 1,
                    processing_error   = NULL,
                    updated_at         = NOW()
                WHERE id = " . intval($existing['id']);
    } else {
        $sql = "INSERT INTO tbl_reels
                    (prop_id, video_path, thumbnail_path, status, processing_version, created_at, updated_at)
                VALUES
                    ($prop_id,
                     '" . $rstate->real_escape_string($tempRel)        . "',
                     '" . $rstate->real_escape_string($thumbnail_path) . "',
                     0, 0, NOW(), NOW())";
    }

    if (! $rstate->query($sql)) {
        @unlink($tempAbs);
        if ($thumbnail_path) @unlink(dirname(dirname(__DIR__)) . '/' . $thumbnail_path);
        // Duplicate key = another reel is already active for this property
        if ($rstate->errno === 1062) {
            errorResponse('A reel already exists for this property. Delete it first or use the update endpoint.', 409, 'REEL_ALREADY_EXISTS');
        }
        errorResponse('Database error while saving reel.', 500, 'REEL_UPLOAD_FAILED');
    }

    $reel_id = $existing ? intval($existing['id']) : intval($rstate->insert_id);

    // Fetch the current processing_version for the worker
    $vRow    = $rstate->query("SELECT processing_version FROM tbl_reels WHERE id = $reel_id")->fetch_assoc();
    $version = (int)($vRow['processing_version'] ?? 0);

    spawnReelProcessor($reel_id, $version);

    // Return queued reel object
    $reelRow = $rstate->query("SELECT * FROM tbl_reels WHERE id = $reel_id")->fetch_assoc();
    successResponse(
        $existing ? 'Reel updated and processing started.' : 'Reel uploaded and processing started.',
        ['reel' => reelToArray($reelRow)]
    );

} else {
    // No ffmpeg — store directly with status 1
    $finalRel = 'uploads/reels/videos/reel_' . $uniq . '.' . $ext;
    $finalAbs = dirname(dirname(__DIR__)) . '/' . $finalRel;

    if (! rename($tempAbs, $finalAbs)) {
        @unlink($tempAbs);
        if ($thumbnail_path) @unlink(dirname(dirname(__DIR__)) . '/' . $thumbnail_path);
        errorResponse('Failed to finalise uploaded file.', 500, 'REEL_UPLOAD_FAILED');
    }

    if ($existing) {
        deleteReelFiles($existing);
        $sql = "UPDATE tbl_reels
                SET video_path     = '" . $rstate->real_escape_string($finalRel)       . "',
                    thumbnail_path = '" . $rstate->real_escape_string($thumbnail_path) . "',
                    status         = 1,
                    processing_version = processing_version + 1,
                    processing_error   = NULL,
                    updated_at     = NOW()
                WHERE id = " . intval($existing['id']);
    } else {
        $sql = "INSERT INTO tbl_reels
                    (prop_id, video_path, thumbnail_path, status, processing_version, created_at, updated_at)
                VALUES
                    ($prop_id,
                     '" . $rstate->real_escape_string($finalRel)       . "',
                     '" . $rstate->real_escape_string($thumbnail_path) . "',
                     1, 0, NOW(), NOW())";
    }

    if (! $rstate->query($sql)) {
        @unlink($finalAbs);
        if ($thumbnail_path) @unlink(dirname(dirname(__DIR__)) . '/' . $thumbnail_path);
        if ($rstate->errno === 1062) {
            errorResponse('A reel already exists for this property.', 409, 'REEL_ALREADY_EXISTS');
        }
        errorResponse('Database error while saving reel.', 500, 'REEL_UPLOAD_FAILED');
    }

    $reel_id = $existing ? intval($existing['id']) : intval($rstate->insert_id);
    $reelRow = $rstate->query("SELECT * FROM tbl_reels WHERE id = $reel_id")->fetch_assoc();

    successResponse(
        $existing ? 'Reel updated successfully.' : 'Reel uploaded successfully.',
        ['reel' => reelToArray($reelRow)]
    );
}
