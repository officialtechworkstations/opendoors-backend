<?php 
require dirname( dirname(__FILE__) ).'/include/reconfig.php';

header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);

function getDatesFromRange($start, $end) {
    $dates = [];
    $current = strtotime($start);
    $end = strtotime($end);
    
    while ($current <= $end) {
        $dates[] = date('Y-m-d', $current);
        $current = strtotime('+1 day', $current);
    }
    
    return $dates;
}

$uid = $data['uid'];
$property_id = $data['property_id'];
if ($uid == '' || $property_id == '') {
    $returnArr = array(
        "ResponseCode" => "401",
        "Result" => "false",
        "ResponseMsg" => "Something Went Wrong!"
    );
} else {
$sql = "SELECT check_in, check_out FROM tbl_book where prop_id=".$property_id." and book_status != 'Cancelled'";
    $result = $rstate->query($sql);

    if ($result->num_rows > 0) {
        $date_list = [];

        // Output data of each row
        while($row = $result->fetch_assoc()) {
            $date_list = array_merge($date_list, getDatesFromRange($row['check_in'], $row['check_out']));
        }

        // Remove duplicate dates
        $date_list = array_unique($date_list);
        // Sort the dates
        sort($date_list);
		$returnArr = array("datelist"=>$date_list,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Book Date List Founded!");
		} else {
        $returnArr = array("datelist"=>[],"ResponseCode"=>"200","Result"=>"false","ResponseMsg"=>"Book Date List Not Founded!");
    }
}
	echo json_encode($returnArr);
?>