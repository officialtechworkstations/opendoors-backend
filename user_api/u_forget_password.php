<?php
require dirname(dirname(__FILE__)) . '/include/reconfig.php';
require dirname(dirname(__FILE__)) . '/include/estate.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);

$email   = $data['email'];
$password = $data['password'];
if ($email == '' or $password == '') {
    $returnArr = ["ResponseCode" => "401", "Result" => "false", "ResponseMsg" => "The email, password and code fields are required. Please check and try again!"];
} else {

    $email   = strip_tags(mysqli_real_escape_string($rstate, $email));
    $password = strip_tags(mysqli_real_escape_string($rstate, $password));

    $counter = $rstate->query("SELECT * FROM `tbl_user` WHERE `email` = '" . $email . "'");

    if ($counter->num_rows != 0) {
        $table = "tbl_user";
        $field = ['password' => $password];
        $where = "where email='" . $email . "'";
        $h     = new Estate();
        $check = $h->restateupdateData_Api($field, $table, $where);

        $returnArr = ["ResponseCode" => "200", "Result" => "true", "ResponseMsg" => "Password Changed Successfully!!!!!"];
    } else {
        $returnArr = ["ResponseCode" => "401", "Result" => "false", "ResponseMsg" => "email Not Matched!!!!"];
    }
}

echo json_encode($returnArr);
