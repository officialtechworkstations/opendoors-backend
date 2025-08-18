<?php 
require dirname( dirname(__FILE__) ).'/include/reconfig.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
$pol = array();
$c = array();
$pro_id  = $data['pro_id'];
$uid  = $data['uid'];
if($pro_id == '' or $uid == '')
{
	$returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!");
}
else 
{
$fp = array();
$f = array();
$v = array();
$vr=array();
$po = array();
$sel = $rstate->query("select * from tbl_property where id=".$pro_id."")->fetch_assoc();

$vr[] = array('image' => $sel['image'], 'is_panorama' => "0");
$get_extra = $rstate->query("select img,pano from tbl_extra where pid=".$sel['id']."");
while($rk = $get_extra->fetch_assoc())
{
    array_push($vr,array('image' => $rk['img'], 'is_panorama' => $rk['pano']));
}
		$fp['id'] = $sel['id'];
		$fp['user_id'] = $sel['add_user_id'];
		$fp['title'] = $sel['title'];
		$checkrate = $rstate->query("SELECT *  FROM tbl_book where prop_id=".$sel['id']." and book_status='Completed' and total_rate !=0")->num_rows;
		if($checkrate !=0)
		{
			$rdata_rest = $rstate->query("SELECT sum(total_rate)/count(*) as rate_rest FROM tbl_book where prop_id=".$sel['id']." and book_status='Completed' and total_rate !=0")->fetch_assoc();
			$fp['rate'] = number_format((float)$rdata_rest['rate_rest'], 2, '.', '');
		}
		else 
		{
		$fp['rate'] = $sel['rate'];
		}
		
		$fp['city'] = $sel['city'];
		$fp['image'] = $vr;
		$fp['property_type'] = $sel['ptype'];
		$prop = $rstate->query("select title from tbl_category where id=".$sel['ptype']."")->fetch_assoc();
		$fp['property_title'] = $prop['title'];
		$fp['price'] = $sel['price'];
		$fp['buyorrent'] = $sel['pbuysell'];
		$fp['is_enquiry'] = $rstate->query("select * from tbl_enquiry where prop_id=".$sel['id']." and uid=".$uid."")->num_rows;
		$fp['address'] = $sel['address'];
		$fp['beds'] = $sel['beds'];
		if($sel['add_user_id'] == 0)
		{
			$fp['owner_image'] = 'images/property/owner.jpg';
		$fp['owner_name'] = 'Host';
		}
		else 
		{
		$udata = $rstate->query("select pro_pic,name from tbl_user where id=".$sel['add_user_id']."")->fetch_assoc();
		$fp['owner_image'] = (empty($udata['pro_pic'])) ? 'images/property/owner.jpg' : $udata['pro_pic'];
		$fp['owner_name'] = $udata['name'];
		}
		
		$fp['bathroom'] = $sel['bathroom'];
		$fp['sqrft'] = $sel['sqrft'];
		$fp['description'] = $sel['description'];
		$fp['latitude'] = $sel['latitude'];
		$fp['mobile'] = $sel['mobile'];
		$fp['plimit'] = $sel['plimit'];
		$fp['longtitude'] = $sel['longtitude'];
		$fp['IS_FAVOURITE'] = $rstate->query("select * from tbl_fav where uid=".$uid." and property_id=".$sel['id']."")->num_rows;
		
	
	$fac = $rstate->query("select img,title from tbl_facility where id IN(".$sel['facility'].")");

while($row = $fac->fetch_assoc())
	{
		
		$f[] = $row;
	}
	
	$gal = $rstate->query("select img from tbl_gallery where pid=".$sel['id']." limit 5");

while($rows = $gal->fetch_assoc())
	{
		
		$v[] = $rows['img'];
	}
	$count_review =  $rstate->query("select * from tbl_book where prop_id=".$pro_id." and book_status='Completed' and is_rate=1 order by id desc")->num_rows;
	$bov = array();
	$kol = array();
	$rev = $rstate->query("select * from tbl_book where prop_id=".$pro_id." and book_status='Completed' and is_rate=1 order by id desc limit 3");
	while($k = $rev->fetch_assoc())
	{
		$udata = $rstate->query("select * from tbl_user where id=".$k['uid']."")->fetch_assoc();
		$bov['user_img'] = $udata['pro_pic'];
		$bov['user_title'] = $udata['name'];
		$bov['user_rate'] = $k['total_rate'];
		$bov['user_desc'] = $k['rate_text'];
		$kol[] = $bov;
		
	}
$returnArr = array("propetydetails"=>$fp,"facility"=>$f,"gallery"=>$v,"reviewlist"=>$kol,"total_review"=>$count_review,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Property Details Founded!");
}
echo json_encode($returnArr);
?>