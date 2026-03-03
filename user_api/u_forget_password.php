<?php
require dirname(dirname(__FILE__)) . '/include/reconfig.php';
require dirname(dirname(__FILE__)) . '/include/estate.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);

$ccode   = $data['ccode'];
$mobile   = $data['mobile'];
$password = $data['password'];
if ($ccode == '' or $mobile == '' or $password == '') {
    $returnArr = ["ResponseCode" => "401", "Result" => "false", "ResponseMsg" => "The country code, mobile, password and code fields are required. Please check and try again!"];
} else {

    $ccode   = strip_tags(mysqli_real_escape_string($rstate, $ccode));
    $mobile   = strip_tags(mysqli_real_escape_string($rstate, $mobile));
    $password = strip_tags(mysqli_real_escape_string($rstate, $password));

    $counter = $rstate->query("SELECT * FROM `tbl_user` WHERE `mobile` = '{$mobile}' AND `ccode` = '{$ccode}' ORDER BY `id` DESC LIMIT 1");

    if ($counter->num_rows != 0) {
        $table = "tbl_user";
        $field = ['password' => $password];
        $where = "WHERE `mobile` = '{$mobile}' AND `ccode` = '{$ccode}' ORDER BY `id` DESC LIMIT 1";
        $h     = new Estate();
        $check = $h->restateupdateData_Api($field, $table, $where);

        $returnArr = ["ResponseCode" => "200", "Result" => "true", "ResponseMsg" => "Password Changed Successfully!!!!!"];
    } else {
        $returnArr = ["ResponseCode" => "401", "Result" => "false", "ResponseMsg" => "mobile Not Matched!!!!"];
    }
}

echo json_encode($returnArr);
