<?php 
require dirname( dirname(__FILE__) ).'/include/reconfig.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$pol = array();
$c = array();
$book_id  = $data['book_id'];
$uid  = $data['uid'];
if($book_id == '' or $uid == '')
{
	$returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!");
}
else 
{
$fp = array();
$f = array();
$v = array();
$vr=array();
$po = array();
$sel = $rstate->query("select * from tbl_book where id=".$book_id." and add_user_id=".$uid."")->fetch_assoc();

		$fp['book_id'] = $book_id;
		$fp['prop_id'] = $sel['prop_id'];
		$fp['uid'] = $sel['uid'];
		$fp['book_date'] = $sel['book_date'];
		$fp['check_in'] = $sel['check_in'];
		$fp['check_out'] = $sel['check_out'];
		$prop = $rstate->query("select title from tbl_payment_list where id=".$sel['p_method_id']."")->fetch_assoc();
		$fp['payment_title'] = $prop['title'];
		$fp['subtotal'] = $sel['subtotal'];
		$fp['total'] = $sel['total'];
		$fp['tax'] = $sel['tax'];
		$fp['cou_amt'] = $sel['cou_amt'];
		$fp['noguest'] = $sel['noguest'];
		$fp['wall_amt'] = $sel['wall_amt'];
		$fp['transaction_id'] = $sel['transaction_id'];
		$fp['p_method_id'] = $sel['p_method_id'];
		$fp['add_note'] = $sel['add_note'];
		$fp['book_status'] = $sel['book_status'];
		$fp['check_intime'] = empty($sel['check_intime']) ? "": $sel['check_intime'];
		$fp['check_outtime'] =  empty($sel['check_outtime']) ? "": $sel['check_outtime'];
		$fp['book_for'] = $sel['book_for'];
		$fp['is_rate'] = $sel['is_rate'];
		$fp['total_rate'] = empty($sel['total_rate'])? '':$sel['total_rate'];
		$fp['rate_text'] = empty($sel['rate_text'])? '':$sel['rate_text'];
		$fp['prop_price'] = $sel['prop_price'];
		$fp['total_day'] = $sel['total_day'];
		$fp['cancle_reason'] = empty($sel['cancle_reason'])?'':$sel['cancle_reason'];
	if($sel['book_for'] == 'self')
		{
			$udata = $rstate->query("select * from tbl_user where id=".$sel['uid']."")->fetch_assoc();
			$fp['customer_name'] = $udata['name'];
			$fp['customer_mobile'] = $udata['ccode'].$udata['mobile'];
		}
		else 
		{
			$udata = $rstate->query("select * from tbl_person_record where book_id=".$sel['id']."")->fetch_assoc();
			$fp['customer_name'] = $udata['fname'].$udata['lname'];
			$fp['customer_mobile'] = $udata['ccode'].$udata['mobile'];
		}
$returnArr = array("bookdetails"=>$fp,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Book Property Details Founded!");
}
echo json_encode($returnArr);
?>