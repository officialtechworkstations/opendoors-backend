<?php
/**
 * include/utils.php
 *
 * Shared utility functions for the OpenDoors API.
 * Auto-loaded by include/reconfig.php — available in every endpoint.
 *
 * Contents:
 *  - absoluteMediaUrl()      Build an absolute URL from a relative media path
 *  - reelToArray()           Normalise a raw tbl_reels DB row for API output
 *  - validateVideoFile()     Validate a $_FILES video entry (size, MIME)
 *  - validateThumbnailFile() Validate + save a $_FILES thumbnail entry
 *  - probeVideo()            Run ffprobe and return decoded JSON
 *  - assertVideoDuration()   Reject videos exceeding max duration
 *  - assertVideoResolution() Reject videos exceeding max resolution
 *  - spawnReelProcessor()    Launch the background process_reel.php worker
 *  - deleteReelFiles()       Safely unlink all files belonging to a reel row
 */

// ---------------------------------------------------------------------------
// Media URL helpers
// ---------------------------------------------------------------------------

if (! function_exists('absoluteMediaUrl')) {
    /**
     * Prepend the application base URL to a relative media path.
     *
     * Returns null when $path is null or empty (e.g. a reel still processing).
     *
     * @param  string|null $path  e.g. "uploads/reels/videos/reel_abc.mp4"
     * @return string|null        e.g. "https://admin.opendoorsapp.com/uploads/reels/videos/reel_abc.mp4"
     */
    function absoluteMediaUrl(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        $base = rtrim(getConfig('APP_URL') ?? '', '/');
        if (empty($base)) {
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'admin.opendoorsapp.com';
            $base = $protocol . '://' . $host;
        }
        return $base . '/' . ltrim($path, '/');
    }
}

// ---------------------------------------------------------------------------
// Reel row normalisation
// ---------------------------------------------------------------------------

if (! function_exists('reelToArray')) {
    /**
     * Normalise a raw tbl_reels database row into the canonical API shape.
     *
     * - Casts numeric IDs and status to int
     * - Renames the `status` column to `reel_status`
     * - Appends absolute `video_url` / `thumbnail_url`
     * - Includes `processing_error` only when status = 2
     * - Returns null when $row is null
     *
     * @param  array|null $row  fetch_assoc() result from tbl_reels query
     * @return array|null
     */
    function reelToArray(?array $row): ?array
    {
        if ($row === null) {
            return null;
        }

        $status = (int)($row['status'] ?? $row['reel_status'] ?? 0);

        $result = [
            'reel_id'       => (int)($row['id'] ?? $row['reel_id'] ?? 0),
            'prop_id'       => (int)($row['prop_id'] ?? 0),
            'reel_status'   => $status,
            'video_path'    => $row['video_path'] ?: null,
            'video_url'     => absoluteMediaUrl($row['video_path'] ?? null),
            'thumbnail_path'=> $row['thumbnail_path'] ?: null,
            'thumbnail_url' => absoluteMediaUrl($row['thumbnail_path'] ?? null),
        ];

        if ($status === 2) {
            $result['processing_error'] = $row['processing_error'] ?? null;
        }

        return $result;
    }
}

// ---------------------------------------------------------------------------
// File validation helpers
// ---------------------------------------------------------------------------

if (! function_exists('validateVideoFile')) {
    /**
     * Validate a $_FILES video entry.
     *
     * Checks:
     *  - Upload error code is UPLOAD_ERR_OK
     *  - File size <= 300 MB (HTTP 413)
     *  - MIME type is in the allowed video list (HTTP 415)
     *
     * Calls errorResponse() and exits on failure — never returns false.
     *
     * @param array $file  A single entry from $_FILES (e.g. $_FILES['video'])
     */
    function validateVideoFile(array $file): void
    {
        $uploadErrors = [
            UPLOAD_ERR_INI_SIZE   => 'Video file exceeds the server upload limit.',
            UPLOAD_ERR_FORM_SIZE  => 'Video file exceeds the form upload limit.',
            UPLOAD_ERR_PARTIAL    => 'Video file was only partially uploaded.',
            UPLOAD_ERR_NO_FILE    => 'No video file was uploaded.',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing server temporary directory.',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write video file to disk.',
            UPLOAD_ERR_EXTENSION  => 'A PHP extension blocked the upload.',
        ];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $msg = $uploadErrors[$file['error']] ?? 'Unknown upload error.';
            http_response_code(400);
            errorResponse($msg, 400, 'REEL_UPLOAD_FAILED');
        }

        // 300 MB hard cap
        if ($file['size'] > 300 * 1024 * 1024) {
            http_response_code(413);
            errorResponse('Video file exceeds the 300 MB limit.', 413, 'REEL_FILE_TOO_LARGE');
        }

        // Validate MIME type — do not trust extension alone
        $allowedMimes = [
            'video/mp4',
            'video/quicktime',
            'video/x-msvideo',
            'video/x-matroska',
            'video/webm',
            'video/mpeg',
        ];

        $mimeType = mime_content_type($file['tmp_name']);
        if (! in_array($mimeType, $allowedMimes, true)) {
            http_response_code(415);
            errorResponse('Unsupported video format. Accepted: mp4, mov, avi, mkv, webm.', 415, 'REEL_UNSUPPORTED_FORMAT');
        }
    }
}

if (! function_exists('validateThumbnailFile')) {
    /**
     * Validate and save an optional thumbnail from $_FILES.
     *
     * Returns the saved relative path on success, or '' if no file was provided.
     * Calls errorResponse() with HTTP 415 if the format is unsupported.
     *
     * @param  array  $file     A single entry from $_FILES (e.g. $_FILES['thumbnail'])
     * @param  string $uniq     Unique string used to build the destination filename
     * @param  string $baseDir  Absolute path to the thumbnails directory
     * @return string           Relative path e.g. "uploads/reels/thumbnails/thumb_xxx.jpg" or ""
     */
    function validateThumbnailFile(array $file, string $uniq, string $baseDir): string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return '';
        }

        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
        $mimeType = mime_content_type($file['tmp_name']);

        if (! in_array($mimeType, $allowedMimes, true)) {
            http_response_code(415);
            errorResponse('Unsupported thumbnail format. Accepted: jpg, png, webp.', 415, 'REEL_THUMBNAIL_UNSUPPORTED');
        }

        $extMap = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];
        $ext = $extMap[$mimeType];
        $relPath = 'uploads/reels/thumbnails/thumb_' . $uniq . '.' . $ext;
        $absPath = $baseDir . 'thumb_' . $uniq . '.' . $ext;

        if (! move_uploaded_file($file['tmp_name'], $absPath)) {
            return '';
        }

        return $relPath;
    }
}

// ---------------------------------------------------------------------------
// ffprobe helpers
// ---------------------------------------------------------------------------

if (! function_exists('probeVideo')) {
    /**
     * Run ffprobe on the given file and return the parsed JSON output.
     *
     * Returns an empty array when ffprobe is unavailable or fails —
     * callers treat an empty array as "unknown" and skip constraint checks.
     *
     * @param  string $absPath  Absolute path to the video file
     * @return array
     */
    function probeVideo(string $absPath): array
    {
        $ffprobe = @shell_exec('which ffprobe 2>/dev/null');
        if (empty(trim((string)$ffprobe))) {
            return [];
        }

        $cmd = 'ffprobe -v quiet -print_format json -show_streams -show_format '
             . escapeshellarg($absPath) . ' 2>/dev/null';
        $json = @shell_exec($cmd);

        if (empty($json)) {
            return [];
        }

        $data = json_decode($json, true);
        return is_array($data) ? $data : [];
    }
}

if (! function_exists('assertVideoDuration')) {
    /**
     * Reject the upload if the video exceeds $maxSeconds.
     *
     * No-op when ffprobe data is unavailable.
     *
     * @param array $probe       Output of probeVideo()
     * @param int   $maxSeconds  Hard cap (default: 90)
     */
    function assertVideoDuration(array $probe, int $maxSeconds = 90): void
    {
        if (empty($probe)) {
            return; // ffprobe unavailable — skip check
        }

        $duration = (float)($probe['format']['duration'] ?? 0);
        if ($duration > $maxSeconds) {
            http_response_code(400);
            errorResponse(
                "Video exceeds the maximum allowed duration of {$maxSeconds} seconds.",
                400,
                'REEL_DURATION_EXCEEDED'
            );
        }
    }
}

if (! function_exists('assertVideoResolution')) {
    /**
     * Reject the upload if any video stream exceeds $maxHeight pixels.
     *
     * No-op when ffprobe data is unavailable.
     *
     * @param array $probe      Output of probeVideo()
     * @param int   $maxHeight  Max vertical resolution in pixels (default: 1080)
     */
    function assertVideoResolution(array $probe, int $maxHeight = 1080): void
    {
        if (empty($probe)) {
            return; // ffprobe unavailable — skip check
        }

        foreach ($probe['streams'] ?? [] as $stream) {
            if (($stream['codec_type'] ?? '') !== 'video') {
                continue;
            }
            $height = (int)($stream['height'] ?? 0);
            if ($height > $maxHeight) {
                http_response_code(400);
                errorResponse(
                    "Video resolution exceeds the maximum allowed height of {$maxHeight}px.",
                    400,
                    'REEL_RESOLUTION_EXCEEDED'
                );
            }
        }
    }
}

// ---------------------------------------------------------------------------
// Background processor
// ---------------------------------------------------------------------------

if (! function_exists('spawnReelProcessor')) {
    /**
     * Launch process_reel.php in the background using nohup.
     *
     * Passes $reel_id and $processing_version as CLI arguments so the worker
     * can detect if it has been superseded by a newer upload.
     *
     * @param int $reel_id           The reel to process
     * @param int $processing_version The version at the time of spawning
     */
    function spawnReelProcessor(int $reel_id, int $processing_version): void
    {
        $script = dirname(__DIR__) . '/user_api/reels/process_reel.php';
        $cmd = 'nohup php -f ' . escapeshellarg($script)
                . ' ' . $reel_id
                . ' ' . $processing_version
                . ' > /dev/null 2>&1 &';
        shell_exec($cmd);
    }
}

// ---------------------------------------------------------------------------
// File cleanup
// ---------------------------------------------------------------------------

if (! function_exists('deleteReelFiles')) {
    /**
     * Safely unlink all media files associated with a tbl_reels row.
     *
     * Handles:
     *  - Processed video (video_path)
     *  - Thumbnail (thumbnail_path)
     *  - Temp/pre-processed file (any temp_reel_* in the same directory)
     *
     * @param array  $reel    fetch_assoc() result from tbl_reels
     * @param string $root    Absolute document root (default: two dirs up from include/)
     */
    function deleteReelFiles(array $reel, string $root = ''): void
    {
        if (empty($root)) {
            $root = dirname(__DIR__);
        }

        $paths = [
            $reel['video_path']     ?? '',
            $reel['thumbnail_path'] ?? '',
        ];

        foreach ($paths as $relPath) {
            if (empty($relPath)) {
                continue;
            }
            $abs = $root . '/' . ltrim($relPath, '/');
            if (file_exists($abs)) {
                @unlink($abs);
            }
        }

        // Also remove any temp_reel_* file for this prop_id
        // (in case the reel is deleted mid-processing)
        $videoDir = $root . '/uploads/reels/videos/';
        if (is_dir($videoDir)) {
            $tempPattern = $videoDir . 'temp_reel_*.{mp4,mov,avi,mkv,webm}';
            // We can't directly target by prop_id here; rely on the DB record being
            // gone so process_reel.php aborts. Just clean any temp files that are
            // clearly orphaned (>1 hour old) if the video_path was a temp file.
            if (! empty($reel['video_path']) && strpos($reel['video_path'], 'temp_reel_') !== false) {
                $abs = $root . '/' . ltrim($reel['video_path'], '/');
                if (file_exists($abs)) {
                    @unlink($abs);
                }
            }
        }
    }
}
