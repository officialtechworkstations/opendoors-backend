<?php 
require dirname( dirname(__FILE__) ).'/include/reconfig.php';

header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
$pol = array();
$c = array();
$sel = $rstate->query("select title,id from tbl_category where status=1");
while($row = $sel->fetch_assoc())
{
   
		$pol['id'] = $row['id'];
		$pol['title'] = $row['title'];
		
		
		
		
		$c[] = $pol;
	
	
}
if(empty($c))
{
	$returnArr = array("typelist"=>$c,"ResponseCode"=>"200","Result"=>"false","ResponseMsg"=>"Property Type Not Founded!");
}
else 
{
$returnArr = array("typelist"=>$c,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Property Type List Founded!");
}
echo json_encode($returnArr);
?>