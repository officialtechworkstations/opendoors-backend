<?php 
require dirname( dirname(__FILE__) ).'/include/reconfig.php';

$data = json_decode(file_get_contents('php://input'), true);
if($data['mobile'] == '' or  $data['ccode'] == '')
{
    $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!");
}
else
{
    $mobile = strip_tags($data['mobile']);
    $ccode = strip_tags($data['ccode']);
    
$chek = findUserByPhone($rstate, $mobile, $ccode) !== null;

if($chek)
{
	$returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Already Exist Mobile Number!");
}
else 
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"New Number!");
}
}
echo json_encode($returnArr);
