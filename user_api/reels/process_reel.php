<?php
// user_api/reels/process_reel.php
// Expected to run via CLI: php process_reel.php <reel_id>
if (php_sapi_name() !== 'cli') {
    die("This script must be run from the command line.");
}

require dirname(dirname(__DIR__)) . '/include/reconfig.php';

$reel_id = $argv[1] ?? null;
if (!$reel_id) {
    die("Reel ID is required.\n");
}

$reel = $rstate->query("SELECT * FROM tbl_reels WHERE id = " . intval($reel_id))->fetch_assoc();
if (!$reel) {
    die("Reel not found.\n");
}

if ($reel['status'] != 0) {
    die("Reel is already processed or not pending.\n");
}

$temp_path = $reel['video_path'];
if (!file_exists(dirname(dirname(__DIR__)) . '/' . $temp_path)) {
    die("Temp video file not found.\n");
}

$uniq = uniqid();
$final_video_path = 'uploads/reels/videos/reel_' . $uniq . '.mp4';
$abs_temp = dirname(dirname(__DIR__)) . '/' . $temp_path;
$abs_final = dirname(dirname(__DIR__)) . '/' . $final_video_path;

// 1. Generate Thumbnail (if not provided)
$thumbnail_path = $reel['thumbnail_path'];
if (empty($thumbnail_path)) {
    $thumbnail_path = 'uploads/reels/thumbnails/reel_' . $uniq . '.png';
    $abs_thumb = dirname(dirname(__DIR__)) . '/' . $thumbnail_path;
    
    $thumb_cmd = "ffmpeg -y -i " . escapeshellarg($abs_temp) . " -ss 00:00:01.000 -vframes 1 " . escapeshellarg($abs_thumb) . " 2>&1";
    shell_exec($thumb_cmd);
    
    if (!file_exists($abs_thumb)) {
        // If it's a very short video (< 1s), try at 0s
        $thumb_cmd2 = "ffmpeg -y -i " . escapeshellarg($abs_temp) . " -ss 00:00:00.000 -vframes 1 " . escapeshellarg($abs_thumb) . " 2>&1";
        shell_exec($thumb_cmd2);
    }
}

// 2. Compress Video
// -preset veryfast and -crf 23 for fast, visually pleasing, small size
// -threads 1 to not kill the shared server CPU
$encode_cmd = "ffmpeg -y -i " . escapeshellarg($abs_temp) . " -threads 1 -c:v libx264 -preset veryfast -crf 23 -c:a aac -b:a 128k -movflags +faststart " . escapeshellarg($abs_final) . " 2>&1";
shell_exec($encode_cmd);

if (file_exists($abs_final)) {
    // Update DB
    $update = "UPDATE tbl_reels SET video_path = '" . $rstate->real_escape_string($final_video_path) . "', thumbnail_path = '" . $rstate->real_escape_string($thumbnail_path) . "', status = 1, updated_at = NOW() WHERE id = " . intval($reel_id);
    $rstate->query($update);
    echo "Processing complete.\n";
} else {
    echo "Processing failed.\n";
}

// Remove temp file always to clean up
if (file_exists($abs_temp)) {
    @unlink($abs_temp);
}
