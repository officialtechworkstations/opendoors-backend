<?php 
require dirname(dirname(__FILE__)) . '/include/reconfig.php';
require dirname(dirname(__FILE__)) . '/include/estate.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
if($data['uid'] == '' or $data['prop_id'] == '')
{
 $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!");    
}
else
{
	$prop_id = $rstate->real_escape_string($data['prop_id']);
 $uid =  $rstate->real_escape_string($data['uid']);
	$check_record = $rstate->query("select * from tbl_property where id=" . $prop_id . " and add_user_id=".$uid."")->num_rows;
	if($check_record !=0)
{
	 

 $table="tbl_property";
   $field = "is_sell=1";
  $where = "where id=".$prop_id." and add_user_id=".$uid." and pbuysell=2";
$h = new Estate();
	  $check = $h->restateupdateData_single($field,$table,$where);
 $returnArr = array("ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Property Selled Successfully!");
}
else 
{
	$returnArr = array(
        "ResponseCode" => "401",
        "Result" => "false",
        "ResponseMsg" => "Own Property Try To Sell!"
    );
}
}
echo json_encode($returnArr);
?>