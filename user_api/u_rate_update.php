<?php 
require dirname(dirname(__FILE__)) . '/include/reconfig.php';
require dirname(dirname(__FILE__)) . '/include/estate.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
if($data['uid'] == '' or $data['book_id'] == ''   or $data['total_rate']==''  or $data['rate_text'] == '')
{
    $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!");
}
else
{
	$uid = $data['uid'];
	$book_id = $data['book_id'];
	$total_rate = $data['total_rate'];
	$rate_text = $rstate->real_escape_string($data['rate_text']);
	
	$table="tbl_book";
  $field = array('total_rate'=>$total_rate,'rate_text'=>$rate_text,'is_rate'=>"1");
  $where = "where uid=".$uid." and id=".$book_id."";
$h = new Estate();
	  $check = $h->restateupdateData_Api($field,$table,$where);
	  $returnArr = array("ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Rate Updated Successfully!!!");
}
echo json_encode($returnArr);