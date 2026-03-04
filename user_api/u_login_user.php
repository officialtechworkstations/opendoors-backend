<?php
require dirname(dirname(__FILE__)) . '/include/reconfig.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$data = json_decode(file_get_contents('php://input'), true);
header('Content-type: text/json');
if ($data['mobile'] == '' or $data['password'] == '' or $data['ccode'] == '') {
    $returnArr = [
        "ResponseCode" => "401",
        "Result"       => "false",
        "ResponseMsg"  => "Something Went Wrong!",
    ];
} else {
    $mobile   = strip_tags(mysqli_real_escape_string($rstate, $data['mobile']));
    $ccode    = strip_tags(mysqli_real_escape_string($rstate, $data['ccode']));
    $password = strip_tags($data['password']);

    // Fetch user by identity only — password verified separately against the hash
    $chek_user  = $rstate->query("SELECT * FROM tbl_user WHERE (mobile='" . $mobile . "' OR email='" . $mobile . "') AND ccode='" . $ccode . "' AND status = 1");
    $chek_admin = $rstate->query("SELECT * FROM admin WHERE mobile='" . $ccode . $mobile . "'");

    if ($chek_user->num_rows != 0 && password_verify($password, $chek_user->fetch_assoc()['password'])) {
        $c = $rstate->query("SELECT * FROM tbl_user WHERE (mobile='" . $mobile . "' OR email='" . $mobile . "') AND ccode='" . $ccode . "' AND status = 1")->fetch_assoc();

        $returnArr = [
            "UserLogin"    => $c,
            "currency"     => $set['currency'],
            "ResponseCode" => "200",
            "Result"       => "true",
            "type"         => "user",
            "ResponseMsg"  => "Login successfully!",
        ];
    } else if ($chek_admin->num_rows != 0 && password_verify($password, $chek_admin->fetch_assoc()['password'])) {
        $c           = $rstate->query("SELECT * FROM admin WHERE mobile='" . $ccode . $mobile . "'")->fetch_assoc();
        $p           = [];
        $p["id"]     = "0";
        $p["name"]   = $c['username'];
        $p["mobile"] = $c['mobile'];
        $returnArr   = [
            "UserLogin"    => $p,
            "currency"     => $set['currency'],
            "ResponseCode" => "200",
            "Result"       => "true",
            "type"         => "admin",
            "ResponseMsg"  => "Login successfully!",
        ];
    } else {
        $returnArr = [
            "ResponseCode" => "401",
            "Result"       => "false",
            "ResponseMsg"  => "Invalid Mobile Number Or Email Address or Password!",
        ];
    }
}

echo json_encode($returnArr);
exit;