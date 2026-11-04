<?php
require dirname(dirname(__FILE__)) . '/include/reconfig.php';
require dirname(dirname(__FILE__)) . '/include/estate.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);

$ccode   = $data['ccode'] ?? '';
$mobile  = $data['mobile'] ?? '';
$password = $data['password'] ?? '';
if ($ccode == '' or $mobile == '' or $password == '') {
    $returnArr = ["ResponseCode" => "401", "Result" => "false", "ResponseMsg" => "The country code, mobile, password and code fields are required. Please check and try again!"];
} else {

    $phone = normalizePhoneIdentity($mobile, $ccode);
    $user = findUserByPhone($rstate, $mobile, $ccode);
    $password = password_hash(strip_tags($password), PASSWORD_BCRYPT);

    if ($user) {
        $hash = $rstate->real_escape_string($password);
        $canonicalMobile = $rstate->real_escape_string($phone['mobile']);
        $canonicalCode = $rstate->real_escape_string($phone['ccode']);
        $check = $rstate->query(
            "UPDATE tbl_user SET password='" . $hash . "', mobile='" . $canonicalMobile .
            "', ccode='" . $canonicalCode . "' WHERE id=" . (int) $user['id'] . " LIMIT 1"
        );

        $returnArr = $check
            ? ["ResponseCode" => "200", "Result" => "true", "ResponseMsg" => "Password Changed Successfully!"]
            : ["ResponseCode" => "500", "Result" => "false", "ResponseMsg" => "Password could not be changed. Please try again."];
    } else {
        $returnArr = ["ResponseCode" => "401", "Result" => "false", "ResponseMsg" => "mobile Not Matched!!!!"];
    }
}

echo json_encode($returnArr);
