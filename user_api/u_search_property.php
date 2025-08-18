<?php 
require dirname( dirname(__FILE__) ).'/include/reconfig.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
$pol = array();
$c = array();
$keyword  = $data['keyword'];
$uid = $data['uid'];
$country_id = $data['country_id'];
if($keyword == '' or $uid == '' or $country_id == '')
{
	$returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!");
}
else 
{
$fp = array();
$f = array();
if($uid == 0)
{
	$sel = $rstate->query("select * from tbl_property where title COLLATE utf8mb4_general_ci like '%".$keyword."%'  and country_id=".$country_id."  and status = 1 and is_sell=0");
}
else 
{
$sel = $rstate->query("select * from tbl_property where title COLLATE utf8mb4_general_ci like '%".$keyword."%'  and country_id=".$country_id."  and add_user_id!=".$uid." and status = 1 and is_sell=0");
}
while($row = $sel->fetch_assoc())
	{
		
		$fp['id'] = $row['id'];
		$fp['title'] = $row['title'];
		$checkrate = $rstate->query("SELECT *  FROM tbl_book where prop_id=".$row['id']." and book_status='Completed' and total_rate !=0")->num_rows;
		if($checkrate !=0)
		{
			$rdata_rest = $rstate->query("SELECT sum(total_rate)/count(*) as rate_rest FROM tbl_book where prop_id=".$row['id']." and book_status='Completed' and total_rate !=0")->fetch_assoc();
			$fp['rate'] = number_format((float)$rdata_rest['rate_rest'], 0, '.', '');
		}
		else 
		{
		$fp['rate'] = $row['rate'];
		}
		$fp['buyorrent'] = $row['pbuysell'];
		$fp['plimit'] = $row['plimit'];
		$fp['city'] = $row['city'];
		$fp['image'] = $row['image'];
		$fp['property_type'] = $row['ptype'];
		$fp['price'] = $row['price'];
		$fp['IS_FAVOURITE'] = $rstate->query("select * from tbl_fav where uid=".$uid." and property_id=".$row['id']."")->num_rows;
		$f[] = $fp;
	}
if(empty($f))
{
	$returnArr = array("search_propety"=>$f,"ResponseCode"=>"200","Result"=>"false","ResponseMsg"=>"Search Property Not Founded!");
}
else 
{
$returnArr = array("search_propety"=>$f,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Property List Founded!");
}
}
echo json_encode($returnArr);
?>