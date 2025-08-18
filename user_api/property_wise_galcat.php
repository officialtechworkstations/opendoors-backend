<?php 
require dirname( dirname(__FILE__) ).'/include/reconfig.php';

header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
$uid = $data['uid'];
$prop_id = $data['prop_id'];
if ($uid == '' or $prop_id == '') {
    $returnArr = array(
        "ResponseCode" => "401",
        "Result" => "false",
        "ResponseMsg" => "Something Went Wrong!"
    );
} else {
$pol = array();
$c = array();
$sel = $rstate->query("SELECT * FROM `tbl_gal_cat` where add_user_id=".$uid." and pid=".$prop_id."");
while($row = $sel->fetch_assoc())
{
   
		$pol['id'] = $row['id'];
		$type = $rstate->query("select * from tbl_property where id=".$row['pid']."")->fetch_assoc();
		$pol['cat_title'] = $row['title'];
		$pol['property_title'] = $type['title'];
		$pol['property_id'] = $row['pid'];
		
		
		$pol['status'] = $row['status'];
		
		$c[] = $pol;
	
	
}
if(empty($c))
{
	$returnArr = array("galcatlist"=>$c,"ResponseCode"=>"200","Result"=>"false","ResponseMsg"=>"Gallery Category List Not Founded!");
}
else 
{
$returnArr = array("galcatlist"=>$c,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Gallery Category   List Founded!");
}
}
echo json_encode($returnArr);
?>