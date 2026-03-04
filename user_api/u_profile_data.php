<?php
require dirname(dirname(__FILE__)) . '/include/reconfig.php';
require dirname(dirname(__FILE__)) . '/include/estate.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
if ($data['uid'] == '') {
    $returnArr = [
        "ResponseCode" => "401",
        "Result" => "false",
        "ResponseMsg" => "User data not found",
    ];
} else {
    $uid = $data['uid'];
    $user_query = $rstate->query("SELECT * FROM `tbl_user` WHERE `id`= {$uid}");
    if ($user_query->num_rows == 0) {
        $returnArr = [
            "ResponseCode" => "401",
            "Result" => "false",
            "ResponseMsg" => "User data not found",
        ];
    } else {
        $user_data = $user_query->fetch_assoc();
        $returnArr = [
            "ResponseCode" => "200",
            "Result" => "true",
            "ResponseMsg" => "User data found",
            "data" => [
                "name" => $user_data['name'],
                "ccode" => $user_data['ccode'],
                "mobile" => $user_data['mobile'],
                "email" => $user_data['email'],
                "refercode" => $user_data['refercode'],
                "pro_pic" => $user_data['pro_pic'],
                "is_subscribe" => $user_data["is_subscribe"],
                "accept_newsletter" => $user_data["accept_newsletter"],
            ],
        ];
    }
}

echo json_encode($returnArr);
exit;