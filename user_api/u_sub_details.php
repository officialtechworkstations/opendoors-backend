<?php
require dirname(dirname(__FILE__)) . '/include/reconfig.php';
require dirname(dirname(__FILE__)) . '/include/estate.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);

if ($data['uid'] == '' ) {
    $returnArr = array(
        "ResponseCode" => "401",
        "Result" => "false",
        "ResponseMsg" => "Something Went Wrong!"
    );
} else {
	
	$uid = $data['uid'];
	$check = $rstate->query("select * from plan_purchase_history where uid=".$uid."");
$op = array();
while($row = $check->fetch_assoc())
{
		$op[] = $row;
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
	
$returnArr = array("Subscribedetails"=>$op,"is_subscribe"=>$getstatus,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Subscibe Information Get Successfully!!");
}

echo json_encode($returnArr);
?>