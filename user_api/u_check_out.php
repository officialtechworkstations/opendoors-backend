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
$timestamp    = date("Y-m-d H:i:s");
$check_status = $rstate->query("select * from tbl_book where add_user_id=".$uid." and id=".$book_id." and book_status='Check_in'")->num_rows;
if($check_status != 0)
{
 $table="tbl_book";
  $field = array('book_status'=>'Completed','check_outtime'=>$timestamp);
  $where = "where add_user_id=".$uid." and id=".$book_id."";
$h = new Estate();
	  $check = $h->restateupdateData_Api($field,$table,$where);
	  $returnArr = array("ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Property Check Out Successfully!");
	  
	  $bdata = $rstate->query("select uid from tbl_book where id=".$book_id."")->fetch_assoc();
			$udata = $rstate->query("select name from tbl_user where id=".$bdata['uid']."")->fetch_assoc();
$name = $udata['name'];
$uid = $bdata['uid'];
	   


$content = array(
       "en" => $name.', Your Booking #'.$book_id.' Has Been Check Out Successfully.'
   );
$heading = array(
   "en" => "Check Out Successfully!!"
);

$fields = array(
'app_id' => $set['one_key'],
'included_segments' =>  array("Active Users"),
'data' => array("order_id" =>$book_id,"type"=>'normal'),
'filters' => array(array('field' => 'tag', 'key' => 'user_id', 'relation' => '=', 'value' => $uid)),
'contents' => $content,
'headings' => $heading
);
$fields = json_encode($fields);

 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
curl_setopt($ch, CURLOPT_HTTPHEADER, 
array('Content-Type: application/json; charset=utf-8',
'Authorization: Basic '.$set['one_hash']));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
 
$response = curl_exec($ch);
curl_close($ch);

$timestamp = date("Y-m-d H:i:s");

$title_mains = "Check Out Successfully!!";
$descriptions = 'Booking #'.$book_id.' Has Been Check Out Successfully.';

	   $table="tbl_notification";
  $field_values=array("uid","datetime","title","description");
  $data_values=array("$uid","$timestamp","$title_mains","$descriptions");
  
    $h = new Estate();
	   $h->restateinsertdata_Api($field_values,$data_values,$table);
}
else 
{
	 $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Property Check In First Required!!");    
}
 
}
echo json_encode($returnArr);
?>