<?php
require dirname(dirname(__FILE__)) . '/include/reconfig.php';
require dirname(dirname(__FILE__)) . '/include/estate.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
     
	 $status = $data["status"];
        $prop_id = $rstate->real_escape_string($data["prop_id"]);
		$user_id = $data['uid'];
		$is_panorama = $data['is_panorama'];
if ($user_id == '' or $status == '' or $prop_id == '' or $is_panorama == '') {
    $returnArr = array(
        "ResponseCode" => "401",
        "Result" => "false",
        "ResponseMsg" => "Something Went Wrong!"
    );
} else {
	$check_owner = $rstate->query("select * from tbl_property where id=" . $prop_id . " and add_user_id=".$user_id."")->num_rows;
if($check_owner !=0)
{
	$img = $data['img'];
 $img = str_replace('data:image/png;base64,', '', $img);
$img = str_replace(' ', '+', $img);
$datavb = base64_decode($img);
$path = 'images/property/'.uniqid().'.png';
$fname = dirname( dirname(__FILE__) ).'/'.$path;
file_put_contents($fname, $datavb);
$table = "tbl_extra";
            $field_values = ["img", "status","pid","add_user_id","pano"];
            $data_values = ["$path", "$status","$prop_id","$user_id","$is_panorama"];

            $h = new Estate();
            $check = $h->restateinsertdata_Api($field_values, $data_values, $table);
			$returnArr    = array(
                "ResponseCode" => "200",
                "Result" => "true",
                "ResponseMsg" => "Extra Image Add Successfully"
            );
			}
else 
{
	$returnArr = array(
        "ResponseCode" => "401",
        "Result" => "false",
        "ResponseMsg" => "Extra Image Add On Your Own Property!"
    );
}
	}

echo json_encode($returnArr);
?>