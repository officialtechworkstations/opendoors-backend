<?php
require dirname(dirname(__FILE__)) . '/include/reconfig.php';
require dirname(dirname(__FILE__)) . '/include/estate.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);

if ($data['uid'] == '') {
    $returnArr = array(
        "ResponseCode" => "401",
        "Result" => "false",
        "ResponseMsg" => "Something Went Wrong!"
    );
} else {
	
	$uid = $data['uid'];
	$vp = array();
	$pack = $rstate->query("select * from tbl_package");
	while($row = $pack->fetch_assoc())
	{
		$vp[] = $row;
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
		$table="tbl_property";
   $field = "status=0";
  $where = "where add_user_id=".$uid."";
$h = new Estate();
	  $check = $h->restateupdateData_single($field,$table,$where);
	}
	
	$getstatus = $rstate->query("select * from tbl_user where id=".$uid." and is_subscribe=1")->num_rows;
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Package Data Get Successfully!","PackageData"=>$vp,"is_subscribe"=>$getstatus);
}
echo json_encode($returnArr);
?>