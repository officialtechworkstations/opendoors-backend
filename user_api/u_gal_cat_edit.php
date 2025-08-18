<?php
require dirname(dirname(__FILE__)) . '/include/reconfig.php';
require dirname(dirname(__FILE__)) . '/include/estate.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
     
	 $status = $data["status"];
        $prop_id = $rstate->real_escape_string($data["prop_id"]);
		$user_id = $data['uid'];
		$title = $rstate->real_escape_string($data["title"]);
		$record_id = $data['record_id'];
if ($user_id == '' or $status == '' or $prop_id == '' or $record_id == '' or $title == '') {
    $returnArr = array(
        "ResponseCode" => "401",
        "Result" => "false",
        "ResponseMsg" => "Something Went Wrong!"
    );
} else {
	$check_owner = $rstate->query("select * from tbl_property where id=" . $prop_id . " and add_user_id=".$user_id."")->num_rows;
if($check_owner !=0)
{
	$check_record = $rstate->query("select * from tbl_gal_cat where pid=" . $prop_id . " and add_user_id=".$user_id." and id=" . $record_id . "")->num_rows;
	if($check_record !=0)
{
	
		
 $table="tbl_gal_cat";
  $field = array('pid'=>$prop_id,'status'=>$status,'title'=>$title);
  $where = "where id=".$record_id."";
$h = new Estate();
	  $check = $h->restateupdateData_Api($field,$table,$where);
	  
			$returnArr    = array(
                "ResponseCode" => "200",
                "Result" => "true",
                "ResponseMsg" => "Gallery Category Update Successfully"
            );
	
			}

else 
{
	$returnArr = array(
        "ResponseCode" => "401",
        "Result" => "false",
        "ResponseMsg" => "Gallery Category Update On Your Own Property!"
    );
}
}
else 
{
	$returnArr = array(
        "ResponseCode" => "401",
        "Result" => "false",
        "ResponseMsg" => "Gallery Category Update On Your Own Property!"
    );
}
	}

echo json_encode($returnArr);
?>