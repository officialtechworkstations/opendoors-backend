<?php 
require dirname( dirname(__FILE__) ).'/include/reconfig.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
$pol = array();
$c = array();
$uid  = $data['uid'];
$status = $data['status'];
if($uid == '' or $status == '')
{
	$returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!");
}
else 
{
	$fp = array();
	$wow = array();
	if($status == 'active')
	{
		$bd = $rstate->query("select * from tbl_book where add_user_id=".$uid." and book_status!='Completed' and book_status!='Cancelled' order by id desc");
		
	}
	else 
	{
		$bd = $rstate->query("select * from tbl_book where add_user_id=".$uid." and (book_status='Completed' or book_status='Cancelled') order by id desc");
	}
	while($row = $bd->fetch_assoc())
	{
	$fp['book_id'] = $row['id'];
	$fp['prop_id'] = $row['prop_id'];
$fp['prop_img'] = $row['prop_img'];
$fp['prop_title'] = $row['prop_title'];	
$fp['p_method_id'] = $row['p_method_id'];
$fp['prop_price'] = $row['prop_price'];
		$fp['total_day'] = $row['total_day'];
		$checkrate = $rstate->query("SELECT *  FROM tbl_book where prop_id=".$row['prop_id']." and book_status='Completed' and total_rate !=0")->num_rows;
		if($checkrate !=0)
		{
			$rdata_rest = $rstate->query("SELECT sum(total_rate)/count(*) as rate_rest FROM tbl_book where prop_id=".$row['prop_id']." and book_status='Completed' and total_rate !=0")->fetch_assoc();
			$fp['rate'] = number_format((float)$rdata_rest['rate_rest'], 0, '.', '');
		}
		else 
		{
		$fp['rate'] = "5";
		}
		$fp['book_status'] = $row['book_status'];
		
		
		$wow[] = $fp;
	}
	$returnArr = array("statuswise"=>$wow,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Status Wise Property Details Founded!");
}
echo json_encode($returnArr);
?>