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
	$city = $rstate->query("select * from tbl_enquiry where add_user_id=".$uid."");
										$lop = array();
										$vop = array();
										while($row = $city->fetch_assoc())
										{
											$prop_data = $rstate->query("select title,image,is_sell from tbl_property where id=".$row['prop_id']."")->fetch_assoc();
											$udata = $rstate->query("select ccode,mobile,name from tbl_user where id=".$row['uid']."")->fetch_assoc();
											$lop['title'] = $prop_data['title'];
											$lop['image'] = $prop_data['image'];
											$lop['name'] = $udata['name'];
											$lop['mobile'] = $udata['ccode'].$udata['mobile'];
											$lop['is_sell'] = $prop_data['is_sell'];
											$vop[] = $lop;
										}
										$returnArr = array("ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Enquiry List Get Successfully!","EnquiryData"=>$vop);
}
echo json_encode($returnArr);
?>