<?php 
require dirname( dirname(__FILE__) ).'/include/reconfig.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
$orag_id = $data['orag_id'];
if ($orag_id == '') {
    $returnArr = array(
        "ResponseCode" => "401",
        "Result" => "false",
        "ResponseMsg" => "Something Went Wrong!"
    );
} else {
$pol = array();
$c = array();
$sel = $rstate->query("select * from tbl_book where add_user_id=".$orag_id." and is_rate=1 ");
while($row = $sel->fetch_assoc())
{
   
		
		$pol['total_rate'] = $row['total_rate'];
		
		$pol['rate_text'] = $row['rate_text'];
		
		
		$c[] = $pol;
	
	
}
if(empty($c))
{
	$returnArr = array("reviewlist"=>$c,"ResponseCode"=>"200","Result"=>"false","ResponseMsg"=>"Reviews Not Founded!");
}
else 
{
$returnArr = array("reviewlist"=>$c,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Reviews List Founded!");
}
}
echo json_encode($returnArr);
?>