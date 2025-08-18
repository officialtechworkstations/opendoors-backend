<?php
require dirname(dirname(__FILE__)) . '/include/reconfig.php';
require dirname(dirname(__FILE__)) . '/include/estate.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);

if ($data['uid'] == '' or $data['country_id'] == '') {
    $returnArr = array(
        "ResponseCode" => "401",
        "Result" => "false",
        "ResponseMsg" => "Something Went Wrong!"
    );
} else {
	
	$uid = $data['uid'];
	if($uid == 0)
	{
	    
	}
	else 
	{
	$udata = $rstate->query("select * from tbl_user where id=".$uid."")->fetch_assoc();
	$timestamp = date("Y-m-d");
	if($udata['end_date'] < $timestamp)
	{
	$table = "tbl_user";
                $field = ["start_date" => NULL, "end_date" => NULL,"pack_id"=>"0","is_subscribe"=>"0"];
  $where = "where id=".$uid."";
$h = new Estate();
	 $check = $h->restateupdateDatanull_Api($field,$table,$where);
	 
	 $table = "plan_purchase_history";
        $where = "where uid=" . $uid . "";
        $h = new Estate();
        $check = $h->restateDeleteData_Api($where, $table);
		
		$table="tbl_property";
   $field = "status=0";
  $where = "where add_user_id=".$uid."";
$h = new Estate();
	  $check = $h->restateupdateData_single($field,$table,$where);
	}
	}
	$country_id = $data['country_id'];
	$f = array();
	$fp = array();
	$vop =array();
	$fpv = array();
	$fps = array();
	$cat  = array();
	
	$wo = array();
	$wo['id'] = "0";
		$wo['title'] = "All";
		$wo['img'] = "images/category/grid-circle.png";
		$wo['status'] = "1";
	$sql = $rstate->query("select * from tbl_category where status=1");
	while($rp = $sql->fetch_assoc())
	{
		$vop['id'] = $rp['id'];
		$vop['title'] = $rp['title'];
		$vop['img'] = $rp['img'];
		$vop['status'] = $rp['status'];
		$cat[] = $vop;
	}
	array_unshift($cat , $wo);
	if($uid == 0)
{
	$prop = $rstate->query("select * from tbl_property where country_id=".$country_id." and status=1 and is_sell=0  order by id desc limit 5");
}
else 
{
	$prop = $rstate->query("select * from tbl_property where country_id=".$country_id." and status=1 and is_sell=0 and add_user_id!=".$uid." order by id desc limit 5");
}
	while($row = $prop->fetch_assoc())
	{
		$fp['id'] = $row['id'];
		$fp['title'] = $row['title'];
		$fp['buyorrent'] = $row['pbuysell'];
		$fp['latitude'] = $row['latitude'];
		$fp['longtitude'] = $row['longtitude'];
		$fp['plimit'] = $row['plimit'];
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
		$fp['city'] = $row['city'];
		$fp['property_type'] = $row['ptype'];
		$fp['beds'] = $row['beds'];
		$fp['bathroom'] = $row['bathroom'];
		$fp['sqrft'] = $row['sqrft'];
		$fp['image'] = $row['image'];
		$fp['price'] = $row['price'];
		$fp['IS_FAVOURITE'] = $rstate->query("select * from tbl_fav where uid=".$uid." and property_id=".$row['id']."")->num_rows;
		$f[] = $fp;
	}
	
	if($uid == 0)
{
	$props = $rstate->query("select * from tbl_property where status=1 and is_sell=0  and country_id=".$country_id."");
}
else 
{
		$props = $rstate->query("select * from tbl_property where status=1 and is_sell=0  and country_id=".$country_id." and add_user_id!=".$uid."");
}
	
	while($rows = $props->fetch_assoc())
	{
		$fps['id'] = $rows['id'];
		$fps['title'] = $rows['title'];
		$fps['buyorrent'] = $rows['pbuysell'];
		$fp['latitude'] = $row['latitude'];
		$fp['longtitude'] = $row['longtitude'];
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
		$fps['beds'] = $rows['beds'];
		$fps['bathroom'] = $rows['bathroom'];
		$fps['sqrft'] = $rows['sqrft'];
		$fps['property_type'] = $rows['ptype'];
		$fps['image'] = $rows['image'];
		$fps['price'] = $rows['price'];
		$fps['IS_FAVOURITE'] = $rstate->query("select * from tbl_fav where uid=".$uid." and property_id=".$rows['id']."")->num_rows;
		$fpv[] = $fps;
	}
	
	$tbwallet = $rstate->query("select wallet from tbl_user where id=".$uid."")->fetch_assoc();
if($uid == 0)
{
	$wallet = "0";
}
else 
{
	$wallet = $tbwallet['wallet'];
}

	$kp = array('Catlist'=>$cat,"currency"=>$set['currency'],"wallet"=>$wallet,"Featured_Property"=>$f,"cate_wise_property"=>$fpv,"show_add_property"=>$set['show_property']);
	
	
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Home Data Get Successfully!","HomeData"=>$kp);

}
echo json_encode($returnArr);
?>