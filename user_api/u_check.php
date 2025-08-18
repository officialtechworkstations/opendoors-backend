<?php 
require dirname( dirname(__FILE__) ).'/include/reconfig.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
$pol = array();
$c = array();
$pro_id  = $data['pro_id'];
$check_in = $data['check_in'];
$check_out = $data['check_out'];
if($pro_id == '' or $check_in == '' or $check_out == '')
{
	$returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!");
}
else 
{
	
 $check_date = $rstate->query("SELECT * FROM tbl_book WHERE (check_in BETWEEN '".$check_in."' and '".$check_out."' OR check_out BETWEEN '".$check_in."' and '".$check_out."') and prop_id=".$pro_id." and book_status!='Cancelled'");
 
 if ($check_date->num_rows != 0) {
        $returnArr = array(
            "ResponseCode" => "401",
            "Result" => "false",
            "ResponseMsg" => "That Date Range Already Booked!"
        );
    }
else 
{
	$returnArr = array(
            "ResponseCode" => "200",
            "Result" => "true",
            "ResponseMsg" => "That Date Range Available!"
        );
}	
}
echo json_encode($returnArr);
?>