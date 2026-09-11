<?php
require dirname(dirname(__DIR__)) . '/include/reconfig.php';
header('Content-type: text/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    errorResponse("Method Not Allowed. Expected POST.", 405);
}

$uid = $_POST['uid'] ?? '';
$prop_id = $_POST['prop_id'] ?? '';

if (empty($uid) || empty($prop_id)) {
    errorResponse("Missing required parameters.", 401);
}

// Verify property ownership
$prop = $rstate->query("SELECT id, add_user_id FROM tbl_property WHERE id = " . intval($prop_id))->fetch_assoc();
if (!$prop || $prop['add_user_id'] != $uid) {
    errorResponse("Property not found or access denied.", 401);
}

// Check if reel already exists
$existing = $rstate->query("SELECT * FROM tbl_reels WHERE prop_id = " . intval($prop_id))->fetch_assoc();

if (!isset($_FILES['video']) || $_FILES['video']['error'] !== UPLOAD_ERR_OK) {
    errorResponse("Video file missing or upload error. Make sure to use multipart/form-data.", 401);
}

$fileSize = $_FILES['video']['size'];
if ($fileSize > 300 * 1024 * 1024) {
    errorResponse("Video file exceeds 300MB limit.", 401);
}

$ext = pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION);
$allowed = ['mp4', 'mov', 'avi', 'mkv', 'webm'];
if (!in_array(strtolower($ext), $allowed)) {
    errorResponse("Invalid video format.", 401);
}

// Ensure directories exist
$videos_dir = dirname(dirname(__DIR__)) . '/uploads/reels/videos/';
$thumbnails_dir = dirname(dirname(__DIR__)) . '/uploads/reels/thumbnails/';
if (!is_dir($videos_dir)) mkdir($videos_dir, 0777, true);
if (!is_dir($thumbnails_dir)) mkdir($thumbnails_dir, 0777, true);

$uniq = uniqid();

// Handle optional thumbnail upload
$thumbnail_path = '';
if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
    $thumb_ext = pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION);
    $allowed_thumb = ['jpg', 'jpeg', 'png', 'webp'];
    if (in_array(strtolower($thumb_ext), $allowed_thumb)) {
        $thumb_filename = 'uploads/reels/thumbnails/thumb_' . $uniq . '.' . $thumb_ext;
        $abs_thumb = dirname(dirname(__DIR__)) . '/' . $thumb_filename;
        if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $abs_thumb)) {
            $thumbnail_path = $thumb_filename;
        }
    }
}

$ffmpeg_version = @shell_exec('ffmpeg -version 2>&1');
$has_ffmpeg = ($ffmpeg_version && strpos(strtolower($ffmpeg_version), 'ffmpeg') !== false);

if ($has_ffmpeg) {
    // Save as temp_reel in videos folder to be processed
    $temp_filename = 'uploads/reels/videos/temp_reel_' . $uniq . '.' . $ext;
    $abs_temp = dirname(dirname(__DIR__)) . '/' . $temp_filename;

    if (move_uploaded_file($_FILES['video']['tmp_name'], $abs_temp)) {
        if ($existing) {
            // Delete old files
            $old_video = dirname(dirname(__DIR__)) . '/' . $existing['video_path'];
            $old_thumb = dirname(dirname(__DIR__)) . '/' . $existing['thumbnail_path'];
            if (file_exists($old_video) && !empty($existing['video_path'])) @unlink($old_video);
            if (file_exists($old_thumb) && !empty($existing['thumbnail_path'])) @unlink($old_thumb);
            
            $sql = "UPDATE tbl_reels SET video_path = '" . $rstate->real_escape_string($temp_filename) . "', thumbnail_path = '" . $rstate->real_escape_string($thumbnail_path) . "', status = 0, updated_at = NOW() WHERE id = " . intval($existing['id']);
        } else {
            // Insert pending status
            $sql = "INSERT INTO tbl_reels (prop_id, video_path, thumbnail_path, status, created_at, updated_at) VALUES (" . intval($prop_id) . ", '" . $rstate->real_escape_string($temp_filename) . "', '" . $rstate->real_escape_string($thumbnail_path) . "', 0, NOW(), NOW())";
        }
        
        if ($rstate->query($sql)) {
            $reel_id = $existing ? $existing['id'] : $rstate->insert_id;
            
            // Trigger background processing script using nohup
            $process_script = dirname(__FILE__) . '/process_reel.php';
            $cmd = "nohup php -f " . escapeshellarg($process_script) . " " . intval($reel_id) . " > /dev/null 2>&1 &";
            shell_exec($cmd);
            
            successResponse($existing ? "Reel updated and processing started." : "Reel uploaded and processing started.");
        } else {
            unlink($abs_temp);
            if ($thumbnail_path) unlink(dirname(dirname(__DIR__)) . '/' . $thumbnail_path);
            errorResponse("Database error.", 500);
        }
    } else {
        if ($thumbnail_path) unlink(dirname(dirname(__DIR__)) . '/' . $thumbnail_path);
        errorResponse("Failed to save uploaded file.", 500);
    }
} else {
    // Fallback: no ffmpeg, direct upload
    $final_filename = 'uploads/reels/videos/reel_' . $uniq . '.' . $ext;
    $abs_final = dirname(dirname(__DIR__)) . '/' . $final_filename;

    if (move_uploaded_file($_FILES['video']['tmp_name'], $abs_final)) {
        if ($existing) {
            // Delete old files
            $old_video = dirname(dirname(__DIR__)) . '/' . $existing['video_path'];
            $old_thumb = dirname(dirname(__DIR__)) . '/' . $existing['thumbnail_path'];
            if (file_exists($old_video) && !empty($existing['video_path'])) @unlink($old_video);
            if (file_exists($old_thumb) && !empty($existing['thumbnail_path'])) @unlink($old_thumb);
            
            $sql = "UPDATE tbl_reels SET video_path = '" . $rstate->real_escape_string($final_filename) . "', thumbnail_path = '" . $rstate->real_escape_string($thumbnail_path) . "', status = 1, updated_at = NOW() WHERE id = " . intval($existing['id']);
        } else {
            $sql = "INSERT INTO tbl_reels (prop_id, video_path, thumbnail_path, status, created_at, updated_at) VALUES (" . intval($prop_id) . ", '" . $rstate->real_escape_string($final_filename) . "', '" . $rstate->real_escape_string($thumbnail_path) . "', 1, NOW(), NOW())";
        }
        if ($rstate->query($sql)) {
            successResponse($existing ? "Reel updated successfully ." : "Reel uploaded successfully .");
        } else {
            unlink($abs_final);
            if ($thumbnail_path) unlink(dirname(dirname(__DIR__)) . '/' . $thumbnail_path);
            errorResponse("Database error.", 500);
        }
    } else {
        if ($thumbnail_path) unlink(dirname(dirname(__DIR__)) . '/' . $thumbnail_path);
        errorResponse("Failed to save uploaded file.", 500);
    }
}
