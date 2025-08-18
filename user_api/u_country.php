<?php 
require dirname( dirname(__FILE__) ).'/include/reconfig.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
if($data['uid'] == '')
{
    $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!");
}
else
{
    
    
    
$check = $rstate->query("select * from tbl_country where status=1");
$op = array();
while($row = $check->fetch_assoc())
{
		$op[] = $row;
}
$returnArr = array("CountryData"=>$op,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Country List Get Successfully!!");
}
echo json_encode($returnArr);