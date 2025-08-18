<?php 
require dirname( dirname(__FILE__) ).'/include/reconfig.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
$pol = array();
$c = array();
$uid  = $data['uid'];
$property_type = $data['property_type'];
$country_id = $data['country_id'];
if($uid == '' or $property_type == '' or $country_id == '')
{
	$returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!");
}
else 
{
$fp = array();
$f = array();
if($property_type == 0)
{
$sel = $rstate->query("select * from tbl_fav where uid=".$uid."");
}
else 
{
	$sel = $rstate->query("select * from tbl_fav where uid=".$uid." and property_type=".$property_type."");
}
while($row = $sel->fetch_assoc())
	{
		$props = $rstate->query("select * from tbl_property where id=".$row['property_id']." and country_id=".$country_id."")->fetch_assoc();
		if(!empty($props['id']))
		{
		$fp['id'] = $props['id'];
		$fp['title'] = $props['title'];
		
		$checkrate = $rstate->query("SELECT *  FROM tbl_book where prop_id=".$props['id']." and book_status='Completed' and total_rate !=0")->num_rows;
		if($checkrate !=0)
		{
			$rdata_rest = $rstate->query("SELECT sum(total_rate)/count(*) as rate_rest FROM tbl_book where prop_id=".$props['id']." and book_status='Completed' and total_rate !=0")->fetch_assoc();
			$fp['rate'] = number_format((float)$rdata_rest['rate_rest'], 2, '.', '');
		}
		else 
		{
		$fp['rate'] = $props['rate'];
		}
		$fp['city'] = $props['city'];
		$fp['buyorrent'] = $props['pbuysell'];
		$fp['plimit'] = $props['plimit'];
		$fp['property_type'] = $props['ptype'];
		$fp['image'] = $props['image'];
		$fp['price'] = $props['price'];
		$fp['IS_FAVOURITE'] = $rstate->query("select * from tbl_fav where uid=".$uid." and property_id=".$props['id']."")->num_rows;
		$f[] = $fp;
		}
	}
if(empty($f))
{
	$returnArr = array("propetylist"=>$f,"ResponseCode"=>"200","Result"=>"false","ResponseMsg"=>"Faviourite Property Not Founded!");
}
else 
{
$returnArr = array("propetylist"=>$f,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Faviourite Property List Founded!");
}
}
echo json_encode($returnArr);
?>