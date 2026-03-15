<?php
require dirname(dirname(__FILE__)) . '/include/reconfig.php';
header('Content-type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$uid = isset($data['uid']) ? (int) $data['uid'] : 0;

if ($uid <= 0) {
    $returnArr = [
        "ResponseCode" => "401",
        "Result"       => "false",
        "ResponseMsg"  => "Something went wrong. Try again!",
    ];
} else {
    $user_query = $rstate->query("SELECT * FROM tbl_user WHERE id = {$uid} LIMIT 1");

    if ($user_query && $user_query->num_rows != 0) {

        $user      = $user_query->fetch_assoc();
        $new_value = $user['accept_newsletter'] == 1 ? 0 : 1;

        $update_query = $rstate->query("UPDATE tbl_user SET accept_newsletter = {$new_value} WHERE id = {$uid}");

        if ($update_query) {
            oneSignalNewsLetterSubscription($uid, $new_value == 1, ['email' => trim($user['email'])]);

            $returnArr = [
                "ResponseCode"      => "200",
                "Result"            => "true",
                "ResponseMsg"       => "Newsletter preference updated successfully!",
                "accept_newsletter" => $new_value
            ];
        } else {
            $returnArr = [
                "ResponseCode" => "401",
                "Result"       => "false",
                "ResponseMsg"  => "Failed to update newsletter preference!",
            ];
        }
    } else {
        $returnArr = [
            "ResponseCode" => "401",
            "Result"       => "false",
            "ResponseMsg"  => "User does not exist!",
        ];
    }
}
echo json_encode($returnArr);
exit;
