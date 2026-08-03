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
$sel = $rstate->query("SELECT tbl_property.*, c.title AS property_type_title,
	COALESCE(ROUND(r.avg_rate, 0), tbl_property.rate) AS effective_rate, (
	SELECT GROUP_CONCAT(`title`) 
	FROM `tbl_facility` 
	WHERE find_in_set(tbl_facility.id,tbl_property.facility)) as facility_select 
		FROM tbl_property
		LEFT JOIN tbl_category c ON c.id = tbl_property.ptype
		LEFT JOIN (
			SELECT prop_id, AVG(total_rate) AS avg_rate
			FROM tbl_book
			WHERE book_status='Completed' AND total_rate != 0
			GROUP BY prop_id
		) r ON r.prop_id = tbl_property.id
		WHERE tbl_property.add_user_id = ".$uid."
	ORDER BY tbl_property.is_featured DESC, tbl_property.rate DESC, tbl_property.price DESC");
while($row = $sel->fetch_assoc()) {
		$pol['id'] = $row['id'];
		$pol['title'] = $row['title'];
		$pol['property_type'] = $row['property_type_title'];
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
		$pol['party_allowed'] = $row['party_allowed'];
		$pol['party_cost'] = $row['party_cost'];
		$pol['caution_fee'] = $row['caution_fee'];
		$pol['rate'] = $row['effective_rate'];
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
exit;