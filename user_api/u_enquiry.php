<?php 
require dirname( dirname(__FILE__) ).'/include/reconfig.php';
require dirname(dirname(__FILE__)) . '/include/estate.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
if($data['uid'] == '' or $data['prop_id'] == '')
{
    $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!");
}
else
{
    $uid = strip_tags(mysqli_real_escape_string($rstate,$data['uid']));
	$prop_id = strip_tags(mysqli_real_escape_string($rstate,$data['prop_id']));
	
    $count = $rstate->query("select * from tbl_enquiry where prop_id=".$prop_id." and uid=".$uid."")->num_rows;
	if($count != 0)
	{
		$returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Enquiry Already Send!!");
	}
	else 
	{
    $get_owner = $rstate->query("select * from tbl_property where id=".$prop_id."")->fetch_assoc();
	$user_id = $get_owner['add_user_id'];
$table        = "tbl_enquiry";
                $field_values = array(
                    "uid",
                    "prop_id",
                    "add_user_id"
                );
                $data_values  = array(
                    "$uid",
                    "$prop_id",
                    "$user_id"
                );
                
                $h     = new Estate();
                $check = $h->restateinsertdata_Api_Id($field_values, $data_values, $table);
				
$returnArr = array("ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Enquiry Sent Successfully!!");
	}
}
echo json_encode($returnArr);