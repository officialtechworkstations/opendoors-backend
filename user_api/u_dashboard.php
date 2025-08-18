<?php 
require dirname( dirname(__FILE__) ).'/include/reconfig.php';
require dirname(dirname(__FILE__)) . '/include/estate.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
$uid = $data['uid'];
if ($uid == '') {
    $returnArr = array(
        "ResponseCode" => "401",
        "Result" => "false",
        "ResponseMsg" => "Something Went Wrong!"
    );
} else {

	$total_property = $rstate->query("select * from tbl_property where add_user_id=".$uid."")->num_rows;
	$total_extra_image = $rstate->query("select * from tbl_extra where add_user_id=".$uid."")->num_rows;
	$total_gallery_image = $rstate->query("select * from tbl_gallery where add_user_id=".$uid."")->num_rows;
	$total_gallery_category = $rstate->query("select * from tbl_gal_cat where add_user_id=".$uid."")->num_rows;
	$total_Booking = $rstate->query("select * from tbl_book where add_user_id=".$uid."")->num_rows;
	$total_enquiry = $rstate->query("select * from tbl_enquiry where add_user_id=".$uid."")->num_rows;
	$total_review = $rstate->query("select * from tbl_book where add_user_id=".$uid." and is_rate=1")->num_rows;
	$total_earn = $rstate->query("select sum(total) as total_amt from tbl_book where add_user_id=".$uid." and book_status='Completed' and p_method_id!=2")->fetch_assoc();
	$earn = empty($total_earn['total_amt']) ? "0" : $total_earn['total_amt'];
	$total_payout = $rstate->query("select sum(amt) as total_payout from payout_setting where owner_id=".$uid."")->fetch_assoc();
	$payout = empty($total_payout['total_payout']) ? "0" : $total_payout['total_payout'];
	$finalearn = floatval($earn) - floatval($payout);
	$check_plan = $rstate->query("select * from tbl_user where id=".$uid."")->fetch_assoc();
	if($check_plan['pack_id'] == 0)
	{
		$current_membership = 'no subscribed';
		$valid_till = '';
	}
	else 
	{
		
		$pack = $rstate->query("select * from tbl_package where id=".$check_plan['pack_id']."")->fetch_assoc();
		$udata = $rstate->query("select * from tbl_user where id=".$uid."")->fetch_assoc();
		$current_membership = $pack['title'];
		$valid_till = $udata['end_date'];
	}
	$udata = $rstate->query("select * from tbl_user where id=".$uid."")->fetch_assoc();
	$timestamp = date("Y-m-d");
	if($udata['end_date'] < $timestamp)
	{
	$table = "tbl_user";
                $field = ["start_date" => NULL, "end_date" => NULL,"pack_id"=>"0","is_subscribe"=>"0"];
  $where = "where id=".$uid."";
$h = new Estate();
	 $check = $h->restateupdateDatanull_Api($field,$table,$where);
	 $table = "plan_purchase_history";
        $where = "where uid=" . $uid . "";
        $h = new Estate();
        $check = $h->restateDeleteData_Api($where, $table);
	}
	
	$getstatus = $rstate->query("select * from tbl_user where id=".$uid." and is_subscribe=1")->num_rows;
	$papi = array(array("title"=>"My Property","report_data"=>$total_property,"url"=>'images/dashboard/property.png'),array("title"=>"My Extra Images","report_data"=>$total_extra_image,"url"=>'images/dashboard/extra_images.png'),array("title"=>"My Gallery Category","report_data"=>$total_gallery_category,"url"=>'images/dashboard/category.png'),array("title"=>"My Gallery Images","report_data"=>$total_gallery_image,"url"=>'images/dashboard/gallery_image.png'),array("title"=>"My Booking","report_data"=>intval($total_Booking),"url"=>'images/dashboard/my-booking.png'),array("title"=>"My Earning","report_data"=>$finalearn,"url"=>'images/dashboard/my-earning.png'),array("title"=>"My Enquiry","report_data"=>intval($total_enquiry),"url"=>'images/dashboard/my-inquiry.png'),array("title"=>"Total Review","report_data"=>$total_review,"url"=>'images/dashboard/review.png'),array("title"=>"My Payout","report_data"=>floatval($payout),"url"=>'images/dashboard/my-payout.png'));
	$member = array(array("title"=>"Current Membership","report_data"=>$current_membership),array("title"=>"Memerbship Expired Date","report_data"=>$valid_till));
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Report List Get Successfully!!!","report_data"=>$papi,"is_subscribe"=>$getstatus,"member_data"=>$member,"withdraw_limit"=>$set['wlimit']);
	
}
echo json_encode($returnArr);
?>