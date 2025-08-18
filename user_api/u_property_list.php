<?php 
require dirname( dirname(__FILE__) ).'/include/reconfig.php';

header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
$uid = $data['uid'];
if ($uid == '') {
    $returnArr = array(
        "ResponseCode" => "401",
        "Result" => "false",
        "ResponseMsg" => "Something Went Wrong!"
    );
} else {
$pol = array();
$c = array();
$sel = $rstate->query("SELECT tbl_property.*,(SELECT GROUP_CONCAT(`title`) from `tbl_facility` WHERE find_in_set(tbl_facility.id,tbl_property.facility)) as facility_select FROM tbl_property where tbl_property.add_user_id=".$uid."");
while($row = $sel->fetch_assoc())
{
   
		$pol['id'] = $row['id'];
		$pol['title'] = $row['title'];
		$type = $rstate->query("select * from tbl_category where id=".$row['ptype']."")->fetch_assoc();
		$pol['property_type'] = $type['title'];
		$pol['property_type_id'] = $row['ptype'];
		$pol['image'] = $row['image'];
		$pol['price'] = $row['price'];
		$pol['beds'] = $row['beds'];
		$pol['plimit'] = $row['plimit'];
		$pol['bathroom'] = $row['bathroom'];
		$pol['sqrft'] = $row['sqrft'];
		$pol['is_sell'] = $row['is_sell'];
		$pol['facility_select'] = $row['facility_select'];
		$pol['status'] = $row['status'];
		$pol['latitude'] = $row['latitude'];
		$pol['longtitude'] = $row['longtitude'];
		$pol['mobile'] = $row['mobile'];
		$pol['buyorrent'] = $row['pbuysell'];
		$pol['city'] = $row['city'];
		$checkrate = $rstate->query("SELECT *  FROM tbl_book where prop_id=".$row['id']." and book_status='Completed' and total_rate !=0")->num_rows;
		if($checkrate !=0)
		{
			$rdata_rest = $rstate->query("SELECT sum(total_rate)/count(*) as rate_rest FROM tbl_book where prop_id=".$row['id']." and book_status='Completed' and total_rate !=0")->fetch_assoc();
			$pol['rate'] = number_format((float)$rdata_rest['rate_rest'], 0, '.', '');
		}
		else 
		{
		$pol['rate'] = $row['rate'];
		}
		$pol['description'] = $row['description'];
		$pol['address'] = $row['address'];
		$c[] = $pol;
	
	
}
if(empty($c))
{
	$returnArr = array("proplist"=>$c,"ResponseCode"=>"200","Result"=>"false","ResponseMsg"=>"Property List Not Founded!");
}
else 
{
$returnArr = array("proplist"=>$c,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Property  List Founded!");
}
}
echo json_encode($returnArr);
?>