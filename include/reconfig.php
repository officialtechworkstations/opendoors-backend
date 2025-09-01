<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once ('functions.php');

$db_server = getConfig('DB_SERVER');
$db_user = getConfig('DB_USER');
$db_pass = getConfig('DB_PASS');
$db_name = getConfig('DB_NAME');
$db_connection = getConfig('DB_MYSQL_CONNECT');

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

$set = $rstate->query("SELECT * FROM `tbl_setting`")
    ->fetch_assoc();
date_default_timezone_set($set['timezone']);

$main = $rstate->query("SELECT * FROM `tbl_prop`")->fetch_assoc();

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