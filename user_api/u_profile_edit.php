<?php
require dirname(dirname(__FILE__)) . '/include/reconfig.php';
require dirname(dirname(__FILE__)) . '/include/estate.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
if ($data['name'] == '' or $data['uid'] == '') {
    $returnArr = array("ResponseCode" => "401", "Result" => "false", "ResponseMsg" => "Something Went Wrong!");
} else {
    $password = '';
    $name = strip_tags(mysqli_real_escape_string($rstate, $data['name']));
    $lname = strip_tags(mysqli_real_escape_string($rstate, $data['lname']));
    if ($data['password'] != '') {
        $password = password_hash(strip_tags($data['password']), PASSWORD_BCRYPT);
    }

    $uid =  strip_tags(mysqli_real_escape_string($rstate, $data['uid']));

    $checkimei = $rstate->query("SELECT * FROM `tbl_user` WHERE `id`= {$uid}")->num_rows;

    if ($checkimei == 0) {
        $returnArr = array("ResponseCode" => "401", "Result" => "false", "ResponseMsg" => "User Not Exist!!!!");
    } else {
        $table = "tbl_user";
        $field = array(
            'name' => $name,
            'lname' => $lname,
        );

        if ($password != '') {
            $field['password'] = $password;
        }
        $where = "where id=" . $uid . "";
        $h = new Estate();
        $check = $h->restateupdateData_Api($field, $table, $where);

        $c = $rstate->query("select * from tbl_user where  `id`=" . $uid . "")->fetch_assoc();
        $returnArr = array("UserLogin" => $c, "ResponseCode" => "200", "Result" => "true", "ResponseMsg" => "Profile Update successfully!");
    }
}

echo json_encode($returnArr);
exit;