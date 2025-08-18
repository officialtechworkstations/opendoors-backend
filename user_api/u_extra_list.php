<?php 
require dirname( dirname(__FILE__) ).'/include/reconfig.php';

header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
$uid = $data['uid'];
if ($uid == '') {
    $returnArr = array(
        "ResponseCode" => "401",
        "Result" => "false",
        "ResponseMsg" => "Something Went Wrong!"
    );
} else {
$pol = array();
$c = array();
$sel = $rstate->query("SELECT * FROM `tbl_extra` where add_user_id=".$uid."");
while($row = $sel->fetch_assoc())
{
   
		$pol['id'] = $row['id'];
		$type = $rstate->query("select * from tbl_property where id=".$row['pid']."")->fetch_assoc();
		$pol['property_title'] = $type['title'];
		$pol['property_id'] = $row['pid'];
		$pol['image'] = $row['img'];
		$pol['pano'] = $row['pano'];
		$pol['status'] = $row['status'];
		
		$c[] = $pol;
	
	
}
if(empty($c))
{
	$returnArr = array("extralist"=>$c,"ResponseCode"=>"200","Result"=>"false","ResponseMsg"=>"Extra Image List Not Founded!");
}
else 
{
$returnArr = array("extralist"=>$c,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Extra Image  List Founded!");
}
}
echo json_encode($returnArr);
?>