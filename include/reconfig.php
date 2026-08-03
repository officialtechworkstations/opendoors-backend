<?php
$requestStartedAt = microtime(true);

// API endpoints are stateless. Starting a file-backed PHP session here makes
// concurrent mobile requests wait for one another on the same session lock.
$isApiRequest = strpos(str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME'] ?? ''), '/user_api/') !== false;
if (! $isApiRequest && session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once ('functions.php');

$db_server = getConfig('DB_SERVER');
$db_user = getConfig('DB_USER');
$db_pass = getConfig('DB_PASS');
$db_name = getConfig('DB_NAME');
// Connection details
define("DB_SERVER", $db_server); // Azure database server address
define("DB_USER", $db_user); // Your username
define("DB_PASS", $db_pass); // Your password
define("DB_NAME", $db_name); // Your database name
try {
    $rstate = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    $rstate->set_charset("utf8mb4");
} catch(Exception $e) {
    error_log($e->getMessage());
    //Should be a message a typical user could understand
}

$set = $rstate->query("SELECT * FROM `tbl_setting` LIMIT 1")
    ->fetch_assoc();
date_default_timezone_set($set['timezone']);

$main = $rstate->query("SELECT * FROM `tbl_prop` LIMIT 1")->fetch_assoc();

// Leave actionable evidence for production-only latency without logging bodies
// or credentials. Requests faster than two seconds do not touch the filesystem.
register_shutdown_function(static function () use ($requestStartedAt) {
    $elapsed = microtime(true) - $requestStartedAt;
    if ($elapsed < 2.0) {
        return;
    }

    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: 'unknown';
    logger(sprintf(
        'slow_request duration_ms=%d method=%s path=%s peak_memory_mb=%.1f',
        (int) round($elapsed * 1000),
        $_SERVER['REQUEST_METHOD'] ?? 'unknown',
        $uri,
        memory_get_peak_usage(true) / 1048576
    ));
});

// echo '<pre>'.htmlspecialchars($main['data']).'</pre>'; exit;
if (isset($_SESSION["stype"]) && $_SESSION["stype"] == 'Staff'){
    // Your database query and session data processing
    $sdata = $rstate->query("SELECT * FROM `tbl_staff` where email='" . $_SESSION['restatename'] . "'")->fetch_assoc();
    $country_per = explode(',', $sdata['country']);
    $page_per = explode(',', $sdata['page']);
    $faq_per = explode(',', $sdata['faq']);
    $category_per = explode(',', $sdata['category']);
    $coupon_per = explode(',', $sdata['coupon']);
    $payout_per = explode(',', $sdata['payout']);
    $enquiry_per = explode(',', $sdata['enquiry']);
    $property_per = explode(',', $sdata['property']);
    $eimg_per = explode(',', $sdata['eimg']);
    $facility_per = explode(',', $sdata['facility']);
    $package_per = explode(',', $sdata['package']);
    $ulist_per = explode(',', $sdata['ulist']);
    $gcat_per = explode(',', $sdata['gcat']);
    $gal_per = explode(',', $sdata['gal']);
    $booking_per = explode(',', $sdata['booking']);
    $payment_per = explode(',', $sdata['payment']);
}
