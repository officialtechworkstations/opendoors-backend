<?php
require dirname(dirname(__FILE__)) . '/include/reconfig.php';
require dirname(dirname(__FILE__)) . '/include/estate.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);

if ($data['uid'] == '' or $data['cid'] == '' or $data['country_id'] == '') {
    $returnArr = array(
        "ResponseCode" => "401",
        "Result" => "false",
        "ResponseMsg" => "Something Went Wrong!"
    );
} else {
	$cid = $data['cid'];
	$country_id = $data['country_id'];
	$uid = $data['uid'];
	$fpv = array();
	$fps = array();
	if($uid == 0)
	{
		if($cid == 0)
	{
		$props = $rstate->query("select * from tbl_property where country_id=".$country_id."");
	}
	else 
	{
	$props = $rstate->query("select * from tbl_property where ptype=".$cid." and country_id=".$country_id."");
	}
	}
	else 
	{
	if($cid == 0)
	{
		$props = $rstate->query("select * from tbl_property where country_id=".$country_id." and add_user_id!=".$uid."");
	}
	else 
	{
	$props = $rstate->query("select * from tbl_property where ptype=".$cid." and country_id=".$country_id." and add_user_id!=".$uid."");
	}
	}
	while($rows = $props->fetch_assoc())
	{
		$fps['id'] = $rows['id'];
		$fps['title'] = $rows['title'];
		$fps['buyorrent'] = $rows['pbuysell'];
		$fps['plimit'] = $rows['plimit'];
		$checkrate = $rstate->query("SELECT *  FROM tbl_book where prop_id=".$rows['id']." and book_status='Completed' and total_rate !=0")->num_rows;
		if($checkrate !=0)
		{
			$rdata_rest = $rstate->query("SELECT sum(total_rate)/count(*) as rate_rest FROM tbl_book where prop_id=".$rows['id']." and book_status='Completed' and total_rate !=0")->fetch_assoc();
			$fps['rate'] = number_format((float)$rdata_rest['rate_rest'], 0, '.', '');
		}
		else 
		{
		$fps['rate'] = $rows['rate'];
		}
		$fps['city'] = $rows['city'];
		$fps['property_type'] = $rows['ptype'];
		$fps['beds'] = $rows['beds'];
		$fps['bathroom'] = $rows['bathroom'];
		$fps['sqrft'] = $rows['sqrft'];
		$fps['image'] = $rows['image'];
		$fps['price'] = $rows['price'];
		if($uid == 0)
		{
			$fps['IS_FAVOURITE'] = 0;
		}
		else 
		{
		$fps['IS_FAVOURITE'] = $rstate->query("select * from tbl_fav where uid=".$uid." and property_id=".$rows['id']."")->num_rows;
		}
		$fpv[] = $fps;
	}
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Category Data Get Successfully!","Property_cat"=>$fpv);
}
echo json_encode($returnArr);
?>