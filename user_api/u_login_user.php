<?php
require dirname(dirname(__FILE__)) . '/include/reconfig.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$data = json_decode(file_get_contents('php://input'), true);
header('Content-type: text/json');
if ($data['mobile'] == '' or $data['password'] == '' or $data['ccode'] == '') {
    $returnArr = array(
        "ResponseCode" => "401",
        "Result" => "false",
        "ResponseMsg" => "Something Went Wrong!"
    );
} else {
    $mobile   = strip_tags(mysqli_real_escape_string($rstate, $data['mobile']));
    $ccode    = strip_tags(mysqli_real_escape_string($rstate, $data['ccode']));
    $password = strip_tags(mysqli_real_escape_string($rstate, $data['password']));
    
    $chek   = $rstate->query("select * from tbl_user where  (mobile='" . $mobile . "' or email='" . $mobile . "') and ccode='" . $ccode . "' and status = 1 and password='" . $password . "'");
	$chek_admin   = $rstate->query("select * from admin where  mobile='".$ccode.$mobile . "' and password='" . $password . "'");
    
        if ($chek->num_rows != 0) {
            $c = $rstate->query("select * from tbl_user where  (mobile='" . $mobile . "' or email='" . $mobile . "')  and ccode='" . $ccode . "' and status = 1 and password='" . $password . "'")->fetch_assoc();
            
            $returnArr = array(
                "UserLogin" => $c,
                "currency" => $set['currency'],
                "ResponseCode" => "200",
                "Result" => "true",
				"type"=>"user",
                "ResponseMsg" => "Login successfully!"
            );
        } 
    
   else if ($chek_admin->num_rows != 0) {
	   $c = $rstate->query("select * from admin where  mobile='" . $ccode.$mobile . "' and password='" . $password . "'")->fetch_assoc();
$p = array();
$p["id"] = "0";
$p["name"] = $c['username'];
$p["mobile"] = $c['mobile'];            
            $returnArr = array(
                "UserLogin" => $p,
                "currency" => $set['currency'],
                "ResponseCode" => "200",
                "Result" => "true",
				"type"=>"admin",
                "ResponseMsg" => "Login successfully!"
            );
   }
	else {
            $returnArr = array(
                "ResponseCode" => "401",
                "Result" => "false",
                "ResponseMsg" => "Invalid Mobile Number Or Email Addresss or Password!!!"
            );
        }
}

echo json_encode($returnArr);