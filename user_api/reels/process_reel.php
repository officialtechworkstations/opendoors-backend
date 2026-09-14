<?php
/**
 * user_api/reels/process_reel.php
 *
 * CLI script — must be invoked via the command line (spawned by spawnReelProcessor()).
 * Direct HTTP access is rejected.
 *
 * Usage:
 *   php process_reel.php <reel_id> <processing_version>
 *
 * Race-condition guard:
 *   Before writing any results, the worker compares its $expected_version against
 *   the current tbl_reels.processing_version. If they differ (a newer upload has
 *   replaced this job), the worker exits without touching the database so it
 *   cannot overwrite the newer upload's record.
 *
 * Status transitions:
 *   0 (queued)  →  1 (ready)   on FFmpeg success
 *   0 (queued)  →  2 (failed)  on FFmpeg failure
 *
 * FFmpeg output spec (Req 15):
 *   Container : MP4
 *   Video     : H.264 baseline/3.1, yuv420p, CRF 23, preset veryfast
 *   Audio     : AAC 128k
 *   Max dur   : 90 s (hard-cap via -t 90)
 *   Streaming : -movflags +faststart
 *   Threads   : 1  (shared-server CPU courtesy)
 */

if (php_sapi_name() !== 'cli') {
    http_response_code(403);
    die('This script must be run from the command line.');
}

require dirname(dirname(__DIR__)) . '/include/reconfig.php';

// ---------------------------------------------------------------------------
// Arguments
// ---------------------------------------------------------------------------
$reel_id          = isset($argv[1]) ? intval($argv[1]) : 0;
$expected_version = isset($argv[2]) ? intval($argv[2]) : -1;

if ($reel_id <= 0) {
    die("Error: reel_id argument is required.\n");
}

if ($expected_version < 0) {
    die("Error: processing_version argument is required.\n");
}

// ---------------------------------------------------------------------------
// Fetch reel from DB
// ---------------------------------------------------------------------------
$reel = $rstate->query("SELECT * FROM tbl_reels WHERE id = " . $reel_id)->fetch_assoc();

if (! $reel) {
    // Reel was deleted while we were queued — clean up nothing (files already gone)
    die("Reel $reel_id not found. It may have been deleted. Aborting.\n");
}

// ---------------------------------------------------------------------------
// Version guard — were we superseded by a newer upload?
// ---------------------------------------------------------------------------
if ((int)$reel['processing_version'] !== $expected_version) {
    die("Version mismatch for reel $reel_id: expected $expected_version, "
        . "got {$reel['processing_version']}. A newer upload supersedes this job. Aborting.\n");
}

// ---------------------------------------------------------------------------
// Locate temp input file
// ---------------------------------------------------------------------------
$temp_rel = $reel['video_path'];
$temp_abs = dirname(dirname(__DIR__)) . '/' . ltrim($temp_rel, '/');

if (! file_exists($temp_abs)) {
    // File gone — mark as failed
    $rstate->query(
        "UPDATE tbl_reels
         SET status = 2, processing_error = 'Source file not found on disk.'
         WHERE id = $reel_id AND processing_version = $expected_version"
    );
    die("Source file not found: $temp_abs\n");
}

// ---------------------------------------------------------------------------
// Output paths
// ---------------------------------------------------------------------------
$uniq            = uniqid('', true);
$final_video_rel = 'uploads/reels/videos/reel_' . $uniq . '.mp4';
$final_video_abs = dirname(dirname(__DIR__)) . '/' . $final_video_rel;

// ---------------------------------------------------------------------------
// Step 1: Generate thumbnail (if not already provided)
// ---------------------------------------------------------------------------
$thumbnail_rel = $reel['thumbnail_path'];

if (empty($thumbnail_rel)) {
    $thumbnail_rel = 'uploads/reels/thumbnails/reel_' . $uniq . '.jpg';
    $thumbnail_abs = dirname(dirname(__DIR__)) . '/' . $thumbnail_rel;

    // Try at 1 second, then 0 seconds for very short clips
    $thumb_cmd = 'ffmpeg -y -i ' . escapeshellarg($temp_abs)
               . ' -ss 00:00:01.000 -vframes 1 '
               . escapeshellarg($thumbnail_abs) . ' 2>&1';
    @shell_exec($thumb_cmd);

    if (! file_exists($thumbnail_abs)) {
        $thumb_cmd2 = 'ffmpeg -y -i ' . escapeshellarg($temp_abs)
                    . ' -ss 00:00:00.000 -vframes 1 '
                    . escapeshellarg($thumbnail_abs) . ' 2>&1';
        @shell_exec($thumb_cmd2);
    }

    if (! file_exists($thumbnail_abs)) {
        $thumbnail_rel = ''; // thumbnail generation optional — continue without it
    }
}

// ---------------------------------------------------------------------------
// Step 2: Transcode video
//
// Flags explained:
//   -t 90               Hard-cap at 90 seconds (Req 15 duration limit)
//   -c:v libx264        H.264 codec (broad mobile compatibility)
//   -preset veryfast    Fast encode, good quality/size ratio
//   -crf 23             Constant Rate Factor — visually lossless at typical sizes
//   -vf scale=...       Ensure even pixel dimensions required by yuv420p
//   -pix_fmt yuv420p    Required for H.264 baseline compatibility on iOS/Android
//   -profile:v baseline -level 3.1   Widest device support
//   -c:a aac -b:a 128k  AAC audio at 128 kbps
//   -movflags +faststart Move MP4 metadata to front for progressive streaming
//   -threads 1          Limit CPU usage on shared servers
// ---------------------------------------------------------------------------
$encode_cmd = 'ffmpeg -y'
    . ' -i '          . escapeshellarg($temp_abs)
    . ' -t 90'
    . ' -threads 1'
    . ' -c:v libx264'
    . ' -preset veryfast'
    . ' -crf 23'
    . ' -vf "scale=trunc(iw/2)*2:trunc(ih/2)*2"'
    . ' -pix_fmt yuv420p'
    . ' -profile:v baseline -level 3.1'
    . ' -c:a aac -b:a 128k'
    . ' -movflags +faststart'
    . ' '             . escapeshellarg($final_video_abs)
    . ' 2>&1';

$encode_output = shell_exec($encode_cmd);

// ---------------------------------------------------------------------------
// Step 3: Check output and update DB (version-safe WHERE clause)
// ---------------------------------------------------------------------------
if (file_exists($final_video_abs)) {
    $update = "UPDATE tbl_reels
               SET video_path       = '" . $rstate->real_escape_string($final_video_rel) . "',
                   thumbnail_path   = '" . $rstate->real_escape_string($thumbnail_rel)   . "',
                   status           = 1,
                   processing_error = NULL,
                   updated_at       = NOW()
               WHERE id = $reel_id
                 AND processing_version = $expected_version";

    if ($rstate->query($update) && $rstate->affected_rows > 0) {
        echo "Processing complete for reel $reel_id (version $expected_version).\n";
    } else {
        // Version changed between our file write and this DB update — clean up output
        @unlink($final_video_abs);
        if ($thumbnail_rel && file_exists(dirname(dirname(__DIR__)) . '/' . $thumbnail_rel)) {
            @unlink(dirname(dirname(__DIR__)) . '/' . $thumbnail_rel);
        }
        echo "Version changed during processing for reel $reel_id. Output discarded.\n";
    }
} else {
    // FFmpeg failed — mark as status 2
    $errorMsg  = 'Video transcoding failed.';
    $logSample = substr((string)$encode_output, -500); // last 500 chars for diagnosis
    logger("process_reel reel_id=$reel_id version=$expected_version ffmpeg_tail=" . $logSample);

    $rstate->query(
        "UPDATE tbl_reels
         SET status = 2, processing_error = '" . $rstate->real_escape_string($errorMsg) . "', updated_at = NOW()
         WHERE id = $reel_id AND processing_version = $expected_version"
    );
    echo "Processing failed for reel $reel_id. FFmpeg output logged.\n";
}

// ---------------------------------------------------------------------------
// Step 4: Always remove the temp file
// ---------------------------------------------------------------------------
if (file_exists($temp_abs)) {
    @unlink($temp_abs);
}
