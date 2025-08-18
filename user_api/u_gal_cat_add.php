<?php
require dirname(dirname(__FILE__)) . '/include/reconfig.php';
require dirname(dirname(__FILE__)) . '/include/estate.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
     
	 $status = $data["status"];
        $prop_id = $rstate->real_escape_string($data["prop_id"]);
		$user_id = $data['uid'];
		$title = $rstate->real_escape_string($data["title"]);
		
if ($user_id == '' or $status == '' or $prop_id == '' or $title == '') {
    $returnArr = array(
        "ResponseCode" => "401",
        "Result" => "false",
        "ResponseMsg" => "Something Went Wrong!"
    );
} else {
	$check_owner = $rstate->query("select * from tbl_property where id=" . $prop_id . " and add_user_id=".$user_id."")->num_rows;
if($check_owner !=0)
{
	
$table="tbl_gal_cat";
  $field_values=array("pid","title","status","add_user_id");
  $data_values=array("$prop_id","$title","$status","$user_id");

            $h = new Estate();
            $check = $h->restateinsertdata_Api($field_values, $data_values, $table);
			$returnArr    = array(
                "ResponseCode" => "200",
                "Result" => "true",
                "ResponseMsg" => "Gallery Category Add Successfully"
            );
			}
else 
{
	$returnArr = array(
        "ResponseCode" => "401",
        "Result" => "false",
        "ResponseMsg" => "Gallery Category Add On Your Own Property!"
    );
}
	}

echo json_encode($returnArr);
?>