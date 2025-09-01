<?php
require dirname(dirname(__FILE__)) . '/include/reconfig.php';

header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input') , true);
$facilities = [];
$sel = $rstate->query("SELECT `title`,`id`,`img` FROM `tbl_facility` where `status` = 1");

if ($sel->num_rows > 0) {
	$row = $sel->fetch_assoc();
	do {
		$facilities[] = [
			'id' => $row['id'],
			'title' => $row['title'],
			'img' => $row['img'],
		];
	} while ($row = $sel->fetch_assoc());
}

if (empty($facilities)) {
    $returnArr = array(
        "facilitylist" => $facilities,
        "ResponseCode" => "200",
        "Result" => "false",
        "ResponseMsg" => "Property Type Not Founded!"
    );
} else {
    $returnArr = array(
        "facilitylist" => $facilities,
        "ResponseCode" => "200",
        "Result" => "true",
        "ResponseMsg" => "Property Type List Founded!"
    );
}
echo json_encode($returnArr);
exit;