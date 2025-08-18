<?php 
require dirname(dirname(__FILE__)) . '/include/reconfig.php';
require dirname(dirname(__FILE__)) . '/include/estate.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
if($data['uid'] == '' or $data['book_id'] == '')
{
 $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!");    
}
else
{
	 $book_id = $rstate->real_escape_string($data['book_id']);
 $uid =  $rstate->real_escape_string($data['uid']);
 $cancle_reason =  $rstate->real_escape_string($data['cancle_reason']);

 $table="tbl_book";
  $field = array('book_status'=>'Cancelled','cancle_reason'=>$cancle_reason);
  $where = "where uid=".$uid." and id=".$book_id." and book_status='Booked'";
$h = new Estate();
	  $check = $h->restateupdateData_Api($field,$table,$where);
 $returnArr = array("ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Booking  Cancelled Successfully!");
}
echo json_encode($returnArr);
?>