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
$existing = $rstate->query("SELECT id FROM tbl_reels WHERE prop_id = " . intval($prop_id))->fetch_assoc();
if ($existing) {
    errorResponse("A reel already exists for this property. Use update instead.", 401);
}

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

$uniq = uniqid();
$ffmpeg_version = @shell_exec('ffmpeg -version 2>&1');
$has_ffmpeg = ($ffmpeg_version && strpos(strtolower($ffmpeg_version), 'ffmpeg') !== false);

if ($has_ffmpeg) {
    $temp_filename = 'images/property/temp_reel_' . $uniq . '.' . $ext;
    $abs_temp = dirname(dirname(__DIR__)) . '/' . $temp_filename;

    if (move_uploaded_file($_FILES['video']['tmp_name'], $abs_temp)) {
        // Insert pending status
        $sql = "INSERT INTO tbl_reels (prop_id, video_path, thumbnail_path, status, created_at, updated_at) VALUES (" . intval($prop_id) . ", '" . $rstate->real_escape_string($temp_filename) . "', '', 0, NOW(), NOW())";
        
        if ($rstate->query($sql)) {
            $reel_id = $rstate->insert_id;
            
            // Trigger background processing script using nohup
            $process_script = dirname(__FILE__) . '/process_reel.php';
            $cmd = "nohup php -f " . escapeshellarg($process_script) . " " . intval($reel_id) . " > /dev/null 2>&1 &";
            shell_exec($cmd);
            
            successResponse("Reel uploaded and processing started.");
        } else {
            unlink($abs_temp);
            errorResponse("Database error.", 500);
        }
    } else {
        errorResponse("Failed to save uploaded file.", 500);
    }
} else {
    // Fallback: no ffmpeg, direct upload
    $upload_dir = dirname(dirname(__DIR__)) . '/uploads/reels/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    $final_filename = 'uploads/reels/reel_' . $uniq . '.' . $ext;
    $abs_final = dirname(dirname(__DIR__)) . '/' . $final_filename;

    if (move_uploaded_file($_FILES['video']['tmp_name'], $abs_final)) {
        $sql = "INSERT INTO tbl_reels (prop_id, video_path, thumbnail_path, status, created_at, updated_at) VALUES (" . intval($prop_id) . ", '" . $rstate->real_escape_string($final_filename) . "', '', 1, NOW(), NOW())";
        if ($rstate->query($sql)) {
            successResponse("Reel uploaded successfully (no compression).");
        } else {
            unlink($abs_final);
            errorResponse("Database error.", 500);
        }
    } else {
        errorResponse("Failed to save uploaded file.", 500);
    }
}
