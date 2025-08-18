 <?php 
require dirname( dirname(__FILE__) ).'/include/reconfig.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
 
$uid = $data['uid'];
if($uid == '')
{
	$returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went wrong  try again !");
}
else 
{ 
$count = $rstate->query("select * from tbl_user where id=".$uid."")->num_rows;
if($count != 0)
{
$wallet = $rstate->query("select * from tbl_user where id=".$uid."")->fetch_assoc();
$curr = $rstate->query("select scredit,rcredit from tbl_setting")->fetch_assoc();
$returnArr = array("ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Wallet Balance Get Successfully!","code"=>$wallet['refercode'],"signupcredit"=>$curr['scredit'],"refercredit"=>$curr['rcredit'],"tax"=>$set['tax']);
}
else 
{
	$returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Not Exist User!");
}
}
echo json_encode($returnArr);

