<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

try {
  $rstate = new mysqli("localhost", "rapirkqk_property", "rapirkqk_property", "rapirkqk_property");
  $rstate->set_charset("utf8mb4");
} catch(Exception $e) {
  error_log($e->getMessage());
  //Should be a message a typical user could understand
}
    
	$set = $rstate->query("SELECT * FROM `tbl_setting`")->fetch_assoc();
	date_default_timezone_set($set['timezone']);
	
	$main = $rstate->query("SELECT * FROM `tbl_prop`")->fetch_assoc();
	
	if (isset($_SESSION["stype"]) && $_SESSION["stype"] == 'Staff') {
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
	
?>