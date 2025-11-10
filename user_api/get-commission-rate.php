<?php
require dirname(dirname(__FILE__)) . '/include/reconfig.php';
$data = json_decode(file_get_contents('php://input'), true);
header('Content-type: text/json');
$user_type = $data['user_type'];

$comission_rate = $rstate->query("SELECT * FROM `tbl_booking_commission` WHERE `user_type` = '$user_type' AND `is_active` = 1");
$commision_response = [
    "ResponseCode" => "404", 
    "Result" => "false", 
    "ResponseMsg" => "No commission found for this user type", 
    "commisionRate" => []
];

if ($comission_rate->num_rows) {
    $row = $comission_rate->fetch_assoc();
    $commisionRate = [];

    do {
        $commisionRate[] = [
            'amount' => $row['amount'],
            'type' => $row['amount_type'],
            'max_amount' => $row['max_amount'],
            'range_from' => $row['range_from'],
            'range_to' => $row['range_to'],
        ];
    } while ($row = $comission_rate->fetch_assoc());

    $commision_response = [
        "ResponseCode" => "200", 
        "Result" => "true", 
        "ResponseMsg" => "Payout List Get Successfully!!!", 
        "commisionRate" => $commisionRate,
    ];
}

echo json_encode($commision_response);
http_response_code($commision_response['ResponseCode']);
exit;